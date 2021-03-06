@extends('layouts.backend')
@push('head')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">
@endpush
@section('content')
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<button class="btn btn-info float-right" data-toggle="collapse" data-target="#form-filter"><i class="fas fa-filter"></i> Filter</button>
				<h4 class="card-title">{{ $page_title }}</h4>
				<h6 class="card-subtitle">{{ $page_description }}</h6>
				<div class="row collapse{{ g('year') ? ' show' : ''}}" id="form-filter">
					<div class="col-12">
						<div class="card">
							<div class="card-body">
								<h4 class="card-title">Filter</h4>
								<hr>
								<form action="" method="GET">
									<div class="row">
										<div class="col-md-3">
											<div class="form-group">
												<label for="rombels_id">Rombel</label>
												<select required="" class="select2 form-control" style="width: 100%; height:36px;" name="rombels_id" id="rombels_id">
													<option disabled="" selected="">Pilih Rombel..</option>
													@foreach($rombels as $key => $row)
													<option{{ $row->id == g("rombels_id") ? ' selected' : '' }} value="{{ $row->id }}">{{ $row->name }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label for="month">Bulan</label>
												<select required="" class="select2 form-control" style="width: 100%; height:36px;" name="month" id="month">
													<option disabled="" selected="">Pilih Bulan..</option>
													@foreach($all_month as $key => $row)
													<option{{ $key == g("month") ? ' selected' : '' }} value="{{ $key }}">{{ dt('2011-'.$key.'-01')->format('F') }}</option>
													@endforeach
												</select>
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label for="year">Tahun</label>
												<div class='input-group mb-3'>
													<select required="" class="select2 form-control" name="year" id="year" style="width: 40%;height:36px;">
														<option disabled="" selected="">Pilih Tahun..</option>
														@foreach($all_year as $key => $row)
														<option{{ $key == g("year") ? ' selected' : '' }} value="{{ $key }}">{{ $key }}</option>
														@endforeach
													</select>
													@if(!g('year'))
													<button type="button" class="btn btn-secondary waves-effect ml-3" data-toggle="collapse" data-target="#form-filter">Cancel</button>
													@else
													<a class="btn btn-secondary waves-effect ml-3" href="{{ url(request()->segment(1).'/'.request()->segment(2)) }}">Reset</a>
													@endif
													<button type="submit" class="btn btn-danger waves-effect waves-light ml-2">Submit</button>
												</div>
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				<div class="table-responsive">
					<table class="table table-sm table-bordered">
						<thead>
							<tr>
								<th style="vertical-align : middle;text-align:center;" rowspan="2">No</th>
								<th style="vertical-align : middle;text-align:center;" rowspan="2">Nama</th>
								<th colspan="{{ count($dates)+5 }}"><center>{{ now()->format('F') }}</center></th>
							</tr>
							<tr>
								@foreach($dates as $d)
								<th {!! isholiday($d) ? ' style="background-color: #F2F2F2;"' : '' !!}>{{ $d->format('d') }}</th>
								@endforeach
								<td align="center" style="font-weight: bold;background-color: #FFBC34;color: #fff;">T</th>
								<td align="center" style="font-weight: bold;background-color: #EF6E6E;color: #fff;">S</th>
								<td align="center" style="font-weight: bold;background-color: #4798E8;color: #fff;">I</th>
								<td align="center" style="font-weight: bold;background-color: #7460EE;color: #fff;">A</th>
								<td align="center" style="font-weight: bold;background-color: #6C757D;color: #fff;">B</th>
							</tr>
						</thead>
						<tbody>
							@foreach($students as $key => $row)
							<tr>
								<td style="text-align: center;">{{ $key + 1}}</td>
								<td style="min-width: 225px;">{{ $row->name }}</td>
								@foreach($dates as $d)
								<td {!! isholiday($d) ? 'style="background-color: #F2F2F2"' : (colorabsent($row->id,$d) != '' ? ' style="background-color: '.colorabsent($row->id,$d).'"' : '' ) !!}></td>
								@endforeach
								<?php
								if (g('month') && g('year')) {
									$datebetween = g('year').'-'.g('month').'-01';
								}else{
									$datebetween = now();
								}
								?>
								<td align="center" style="font-weight: bold;">{{ statindv($row->id,'Terlambat',$datebetween) }}</td>
								<td align="center" style="font-weight: bold;">{{ statindv($row->id,'Sakit',$datebetween) }}</td>
								<td align="center" style="font-weight: bold;">{{ statindv($row->id,'Izin',$datebetween) }}</td>
								<td align="center" style="font-weight: bold;">{{ statindv($row->id,'Tanpa Keterangan',$datebetween) }}</td>
								<td align="center" style="font-weight: bold;">{{ statindv($row->id,'Bolos',$datebetween) }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
				<div class="row mt-3">
					<div class="col-md-3">
						<h5>Keterangan</h5>
						<hr>
						<table class="table table-bordered table-sm">
							<tr>
								<th>Warna</th>
								<th>Keterangan</th>
							</tr>
							<tr>
								<td align="center"><span class="badge badge-success">&nbsp&nbsp&nbsp&nbsp</span></td>
								<td>Tepat Waktu</td>
							</tr>
							<tr>
								<td align="center"><span class="badge badge-warning">&nbsp&nbsp&nbsp&nbsp</span></td>
								<td>Terlambat</td>
							</tr>
							<tr>
								<td align="center"><span class="badge badge-danger">&nbsp&nbsp&nbsp&nbsp</span></td>
								<td>Sakit</td>
							</tr>
							<tr>
								<td align="center"><span class="badge badge-info">&nbsp&nbsp&nbsp&nbsp</span></td>
								<td>Izin</td>
							</tr>
							<tr>
								<td align="center"><span class="badge badge-primary">&nbsp&nbsp&nbsp&nbsp</span></td>
								<td>Tanpa Keterangan / Alpa</td>
							</tr>
							<tr>
								<td align="center"><span class="badge badge-secondary">&nbsp&nbsp&nbsp&nbsp</span></td>
								<td>Bolos</td>
							</tr>
							<tr>
								<td align="center"><span class="badge" style="background-color: #F2F2F2;">&nbsp&nbsp&nbsp&nbsp</span></td>
								<td>Libur</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('bottom')
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script type="text/javascript">
	$(".select2").select2();
</script>
@endpush