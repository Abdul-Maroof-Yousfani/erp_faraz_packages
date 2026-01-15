<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}
$parentCode = $_GET['parentCode'];
$m = $_GET['m'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\Department;

?>

@extends('layouts.default')
@section('content')

	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="well_N">
					<div class="dp_sdw">
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<span class="subHeadingLabelClass">View Salesman List</span>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
								@if(in_array('print',$operation_rights))
									<?php echo CommonHelper::displayPrintButtonInBlade('PrintSubDepartmentList','','1');?>
								@endif
								@if(in_array('export',$operation_rights))
									<?php echo CommonHelper::displayExportButton('SubDepartmentList','','1')?>
								@endif
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="panel">
							<div class="panel-body" id="PrintSubDepartmentList">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered sf-table-list table-hover" id="SubDepartmentList">
												<thead>
												<th class="text-center col-sm-1">S.No</th>
												<th class="text-center hide">Department Name</th>
												<th class="text-center">Salesman Name</th>
												<th class="text-center">Created By</th>
												<th class="text-center">Status</th>
												<th class="text-center hidden-print">Action</th>
												</thead>
												<tbody>
												<?php $counter = 1;?>
												@foreach($SubDepartments as $key => $y)
													<tr>
														<td class="text-center"><?php echo $counter++;?></td>
														<td class="hide">
															<?php
																//echo $y->department_id;
															$departmentName = CommonHelper::get_dept_name_hr($m,$y->department_id);
															echo strtoupper($departmentName->department_name);
															?>

														</td>
														<td><?php echo $y->sub_department_name;?></td>
														<td><?php echo $y->username;?></td>
														<td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
														<td class="text-center hidden-print">
															@if(in_array('edit',$operation_rights))
																<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('hr/editSubDepartmentForm','<?php echo $y->id ?>','Salesman Edit Detail Form','<?php echo $m?>')">
																	<span class="glyphicon glyphicon-edit"></span>
																</button>
															@endif
															@if(in_array('repost',$operation_rights))
																@if($y->status == 2)
																	<button class="delete-modal btn btn-xs btn-primary" onclick="repostMasterTableRecords('<?php echo $y->id ?>','sub_department')">
																		<span class="glyphicon glyphicon-refresh"></span>
																	</button>
																@endif
															@endif
															@if(in_array('delete',$operation_rights))
																@if($y->status == 1)
																	<button class="delete-modal btn btn-xs btn-danger" onclick="deleteRowMasterTable('<?php echo $y->sub_department_name ?>','<?php echo $y->id ?>','sub_department')">
																		<span class="glyphicon glyphicon-trash"></span>
																	</button>
																@endif
															@endif
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
	</div>

@endsection
