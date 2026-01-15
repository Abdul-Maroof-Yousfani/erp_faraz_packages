@extends('layouts.default')
@section('content')

<?php 
$m = Input::get('m');
use App\Helpers\HrHelper;
?>
	<style>
		td{ padding: 2px !important;}
		th{ padding: 2px !important;}
	</style>

	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well_N">
				<div class="dp_sdw">					
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<span class="subHeadingLabelClass">View Role and Permission List</span>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="panel">
						<div class="panel-body" id="PrintAllownceList">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
									<div class="table-responsive">
										<table class="table table-bordered sf-table-list table-hover" id="AllownceList">
											<thead>
											<th class="text-center col-sm-1">S.No</th>
											<th class="text-center">Emp Code.</th>
											<th class="text-center">Employee Name</th>
											<th class="text-center">Region</th>
											<th class="text-center hidden-print">Action</th>
											</thead>
											<tbody>
                                            <?php $counter=1; ?>
											@foreach($MenuPrivileges as $value)
												<tr>
													<td class="text-center">{{ $counter++ }}</td>
													<td class="text-center">{{$value->emp_code}}</td>
													<td class="">{{ HrHelper::getCompanyTableValueByIdAndColumn($m,'employee','emp_name',$value->emp_code,'emp_code') }}</td>
													<td class="">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$value->region_id)}}</td>
													<td class="text-center">
														<a href="<?= url("/users/viewEmployeePrivileges/{$value->emp_code}?pageType=viewlist&&parentCode=27&&m={$m}#SFR")?>" class="btn btn-xs btn-success">
															<span class="glyphicon glyphicon-eye-open"></span>
														</a>
													</td>
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>

@endsection