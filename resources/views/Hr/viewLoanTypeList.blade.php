<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
	<style>
		td{ padding: 2px !important;}
		th{ padding: 2px !important;}
	</style>

	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="well_N">
					<div class="dp_sdw">	
						<div class="row">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<span class="subHeadingLabelClass">View Loan Type List</span>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
								@if(in_array('print', $operation_rights))
									<?php echo CommonHelper::displayPrintButtonInBlade('PrintLeaveTypeList','','1');?>
								@endif
								@if(in_array('export', $operation_rights))
									<?php echo CommonHelper::displayExportButton('LeaveTypeList','','1')?>
								@endif
							</div>
						</div>
						<div class="panel">
							<div class="panel-body" id="PrintLeaveTypeList">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered sf-table-list table-hover table-striped" id="LeaveTypeList">
												<thead>
													<th class="text-center col-sm-1">S.No</th>
													<th class="text-center">Loan Type Name</th>
													<th class="text-center">Created By</th>
													<th class="text-center">Action</th>
												</thead>
												<tbody>
													<?php $counter = 1;?>
													@foreach($LoanTypes as $key => $y)
														<tr>
															<td class="text-center"><?php echo $counter++;?></td>
															<td><?php echo $y->loan_type_name;?></td>
															<td><?php echo $y->username;?></td>
															<td class="text-center">
																@if(in_array('edit', $operation_rights))
																	<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('hr/editLoanTypeForm','<?php echo $y->id ?>','Loan Type Edit Detail Form','<?php echo $m?>')">
																		<span class="glyphicon glyphicon-edit"></span>
																	</button>
																@endif
																@if(in_array('delete', $operation_rights))
																	<button class="delete-modal btn btn-xs btn-danger" onclick="deleteRowMasterTable('<?php echo $y->id ?>','loan_type')">
																		<span class="glyphicon glyphicon-trash"></span>
																	</button>
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