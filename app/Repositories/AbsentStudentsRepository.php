<?php
namespace App\Repositories;

use DB;
use App\Models\Students;
use App\Models\AbsentStudents;

class AbsentStudentsRepository extends AbsentStudents
{
    // TODO : Make you own query methods
	public static function stats($value,$date){
		$query = AbsentStudents::simpleQuery()
		->where('type',$value)
		->whereDate('date',$date)
		->get()
		->count();

		return $query;
	}

	public static function list($date){
		$date = dt($date);
		$query = AbsentStudents::simpleQuery()
		->join('students','students.id','=','absent_students.students_id')
		->join('rombels','students.rombels_id','=','rombels.id')
		->whereDate('absent_students.date',$date->format('Y-m-d'))
		->select('absent_students.id as id','students.nis as nis','students.name as name','rombels.name as rombel','absent_students.type as type','absent_students.photo as photo','absent_students.time_in as time_in','absent_students.is_out as is_out')
		->get();

		return $query;
	}

	public static function check($id,$date){
		$query = AbsentStudents::simpleQuery()
		->where('students_id',$id)
		->whereDate('date',dateDb($date))
		->first();

		return $query;
	}

	public static function update($id,$date){
		$query = AbsentStudents::simpleQuery()
		->where('students_id',$id)
		->whereDate('date',dateDb($date));

		return $query;
	}

	public static function set($type){
		if ($type == 'Tanpa Keterangan') {
			$not_in = AbsentStudents::simpleQuery()
			->whereDate('date',date('Y-m-d'))
			->get();

			$arr = [];
			foreach ($not_in as $key => $row) {
				$arr[] = array($row->students_id);
			}

			$for = Students::simpleQuery()
			->whereNotIn('id',$arr)
			->get();

			$count = 0;
			foreach ($for as $key => $row) {
				$count += 1;
				$new = New AbsentStudents;
				$new->setDate(date('Y-m-d'));
				$new->setTimeIn(NULL);
				$new->setStudentsId($row->id);
				$new->setType($type);
				$new->setIsOut(NULL);
				$new->save();
			}
		}else{
			$data = AbsentStudents::simpleQuery()
			// ->whereDate('date',date('Y-m-d'))
			->where('is_out',0)
			->get();
            
			$count = 0;
			foreach ($data as $key => $row) {
				$count += 1;
				$update = AbsentStudents::findBy('id',$row->id);
				$update->setType($type);
				$update->setIsOut(NULL);
				$update->save();
			}
		}

		return $count;
	}

	public static function statIndv($id,$type,$date){
		$date = dt($date);

		$data = AbsentStudents::simpleQuery()
		->where('students_id',$id)
		->where('type',$type)
		->whereBetween('date',[
			$date->startOfMonth()->format('Y-m-d'),
			$date->endOfMonth()->format('Y-m-d')
		])->get();

		return $data->count();
	}
}