<?php 
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}
	$parentCode = $_GET['parentCode'];

    use App\Helpers\HrHelper;
	use App\Helpers\CommonHelper;

	$current_date = date('Y-m-d');
	$currentMonthStartDate = date('Y-m-01');
	$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')
@section('content')
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintHiringRequestList','','1');?>
                        <?php echo CommonHelper::displayExportButton('HiringRequestList','','1')?>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						@include('Hr.'.$accType.'hrMenu')
					</div>
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="subHeadingLabelClass">View Hiring Request List</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>


							<div class="row">

								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
									<label>From Date</label>
									<input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
									<input type="text" readonly class="form-control text-center" value="Between" /></div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
									<label>To Date</label>
									<input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
								</div>

								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
									<input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
								</div>

							</div>
							<div class="lineHeight">&nbsp;</div>

							<div class="panel">
								<div class="panel-body" id="PrintHiringRequestList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
											<div class="table-responsive">
												<table class="table table-bordered sf-table-list" id="HiringRequestList">
   													<thead>
														<th class="text-center">S.No</th>
														<th class="text-center">Job Title</th>
														<th class="text-center">Job Type</th>
														<th class="text-center">Designation</th>
														<th class="text-center">Qualification</th>
														<th class="text-center">Shift Type</th>
														<th class="text-center">Approval Status</th>
														<th class="text-center">Status</th>
														<th class="text-center hidden-print">Action</th>
													</thead>
													<tbody>
														<?php $counter = 1;?>
														@foreach($RequestHiring as $key => $y)
															<tr>
																<td class="text-center"><?php echo $counter++;?></td>
																<td class="text-center"><?php echo $y['RequestHiringTitle'];?></td>
																<td class="text-center"><?php echo HrHelper::getMasterTableValueById($m,'job_type','job_type_name',$y['job_type_id']);?></td>
																<td class="text-center"><?php echo HrHelper::getMasterTableValueById($m,'designation','designation_name',$y['designation_id']);?></td>
																<td class="text-center"><?php echo HrHelper::getMasterTableValueById($m,'qualification','qualification_name',$y['qualification_id']);?></td>
																<td class="text-center"><?php echo HrHelper::getMasterTableValueById($m,'shift_type','shift_type_name',$y['shift_type_id']);?></td>
																<td class="text-center"><?php echo HrHelper::getApprovalStatusLabel($y['ApprovalStatus']);?></td>
																<td class="text-center"><?php echo HrHelper::getStatusLabel($y['status']);?></td>
																<td class="text-center hidden-print">
																<div class="dropdown">
																	<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
																	<span class="caret"></span></button>
																		<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
																			<li role="presentation">
																				<a type="button" onclick="showDetailModelTwoParamerterJson('hdc/viewHiringRequestDetail','<?php echo $y['id'];?>','View Request Hiring Detail','<?php echo $m; ?>')" class="edit-modal btn">
																					View
																				</a>
																			</li>
																		@if ($y['ApprovalStatus'] != 2 )
																				<li role="presentation">
																					<a class="edit-modal btn" onclick="approveAndRejectRequestHiring('<?php echo $m ?>','<?php echo $y['id'];?>','2')">
																						Approve
																					</a>
																				</li>
																			@endif
																			@if ($y['ApprovalStatus'] != 3)
																				<li role="presentation">
																					<a class="edit-modal btn" onclick="approveAndRejectRequestHiring('<?php echo $m ?>','<?php echo $y['id'];?>','3')">
																						Reject
																					</a>
																				</li>
																			@endif
																			<li role="presentation">
																				<a class="edit-modal btn" onclick="showMasterTableEditModel('hr/editHiringRequestForm','<?php echo $y['id'] ?>','Request Hiring Edit Detail Form','<?php echo $m?>')">
																			    	Edit
																				</a>
																			</li>
																			@if ($y['status'] == 2)
																				<li role="presentation">
																					<a class="edit-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $y['id'] ?>','requesthiring','status')" type="button">
																						Repost
																					</a>
																				</li>
																			@else
																				<li role="presentation">
																					<a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $y['id'] ?>','requesthiring')">
																						Delete
																					</a>
																				</li>
																			@endif
																		</ul>
																	</div>
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
	<script src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
@endsection