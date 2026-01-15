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
?>

@extends('layouts.default')
@section('content')
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintShiftTypeList','','1');?>
                    <?php echo CommonHelper::displayExportButton('ShiftTypeList','','1')?>
				</div>
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						@include('Hr.'.$accType.'hrMenu')
					</div>
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="subHeadingLabelClass">View Shift Type List</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<div class="panel">
								<div class="panel-body" id="PrintShiftTypeList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
											<div class="table-responsive">
												<table class="table table-bordered sf-table-list" id="ShiftTypeList">
   													<thead>
														<th class="text-center col-sm-1">S.No</th>
														<th class="text-center">Shift Type Name</th>
														<th class="text-center">Created By</th>
														<th class="text-center">Status</th>
														<th class="text-center hidden-print">Action</th>
													</thead>
													<tbody>
														<?php $counter = 1;?>
														@foreach($ShiftTypes as $key => $y)
															<tr>
																<td class="text-center"><?php echo $counter++;?></td>
																<td class="text-center"><?php echo $y->shift_type_name;?></td>
																<td class="text-center"><?php echo $y->username;?></td>
																<td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>
																<td class="text-center hidden-print">
																	<button class="edit-modal btn btn-xs btn-info" onclick="showMasterTableEditModel('hr/editShiftTypeForm','<?php echo $y->id ?>','Shift Type Edit Detail Form','<?php echo $m?>')">
                    													<span class="glyphicon glyphicon-edit"></span>
                													</button>
																	@if($y->status == 2)

																		<button class="delete-modal btn btn-xs btn-primary" onclick="repostMasterTableRecords('<?php echo $y->id ?>','shift_type')">
																			<span class="glyphicon glyphicon-refresh"></span>
																		</button>
																	@else
																		<button class="delete-modal btn btn-xs btn-danger" onclick="deleteRowMasterTable('<?php echo $y->shift_type_name ?>','<?php echo $y->id ?>','shift_type')">
																			<span class="glyphicon glyphicon-trash"></span>
																		</button>
																	@endif
																</td>
															</tr>
														@endforeach
													</tbody>
												</table>
											</div>
											<div class="pagination">{!! str_replace('/?', '?', $ShiftTypes->appends(['pageType' => 'viewlist','parentCode' => $parentCode,'m' => $m])->fragment('SFR')->render()) !!}</div>
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