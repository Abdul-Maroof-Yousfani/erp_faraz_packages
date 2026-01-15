<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>
<style>
	td{ padding: 2px !important;}
	th{ padding: 2px !important;}
	.panel{
		margin-top: 8px;
		padding: 0px 30px 0px 30px;
		height: 556px;
		overflow-y: scroll;
	}

	.pointer:hover {
		cursor: pointer;
	}
</style>
@extends('layouts.default')
@section('content')
	@include('select2')
	<div class="panel-body">
		<div class="row">
			<div class="lineHeight">&nbsp;</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well_N">
				<div class="dp_sdw">    
					<div class="row">
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
							<span class="subHeadingLabelClass">View Employee List</span>
						</div>
						<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">

							<input type="hidden" id="company_id" value="<?= $m ?>">
							@if(in_array('print', $operation_rights))
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmployeeList','','1');?>
							@endif
							@if(in_array('export', $operation_rights))
                                <?php echo CommonHelper::displayExportButton('EmployeeList','','1')?>
							@endif
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="row">
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<label class="sf-label">Search By Department</label>
							<select class="form-control" name="emp_department_id" id="emp_department_id">
								<option value="">Select Department</option>
								@foreach($departments as $key => $y)
									<option value="{{ $y->id}}">{{ $y->department_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<label class="sf-label">Search By Region</label>
							<select class="form-control" name="region_id" id="region_id">
								<option value="">Select Region</option>
								@foreach($regions as $key2 => $y2)
									<option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<label class="sf-label">Search By Employee Name</label>
							<input type="text" id="emp_name" name="emp_name" class="form-control">
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
							<label class="sf-label">Search By Emp Code</label>
							<input type="number" id="emp_code" name="emp_code" class="form-control emp_code" >
						</div>

					</div>
					<div class="row text-right">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top: 28px">
							<button class="btn btn-info" onclick="viewEmployeeFilteredList()">Filter List</button>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<span id="employee-list">
					<div class="panel" style="height: 450px;" id="search_area">
						<div class="panel-body" id="PrintEmployeeList">
							<?php echo CommonHelper::headerPrintSectionInPrintView(Input::get('m'));?>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
									<div class="table-responsive">
										<table class="table table-bordered sf-table-list table-hover" id="EmployeeList">
											<thead>
												<th class="text-center col-sm-1">S.No</th>
												<th class="text-center">Emp Code</th>
												<th class="text-center">Employee Name</th>
												<th class="text-center">Department</th>
												<th class="text-center">Contact No</th>
												<th class="text-center">Current Salary</th>
												<th class="text-center">Email</th>
												<th class="text-center">Password</th>
												<th class="text-center">Status</th>
												<th class="text-center hidden-print">Action</th>
											</thead>
											<tbody id="appendGetMoreEmp">
											<?php $counter = 1;?>
											@foreach($employees as $key => $y)
												<tr class="post-id" id="<?= $y->emp_code; ?>">
													<td class="text-center counterId" id="<?php echo $counter;?>">
														<?php echo $counter++;?>
													</td>
													<td class="text-center">{{ $y->emp_code}}</td>
													<td>{{ $y->emp_name}}</td>
													<td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$y->emp_department_id)}}</td>
													{{--<td class="text-center">{{ HrHelper::date_format($y->emp_date_of_birth) }}</td>--}}
													{{--<td class="text-center">{{ HrHelper::date_format($y->emp_joining_date) }}</td>--}}
													<td class="text-center">{{ $y->emp_contact_no}}</td>
													{{--<td class="text-center">{{ $y->emp_cnic}}</td>--}}
													{{--<td class="text-center">{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$y->region_id)}}</td>--}}
													<td class="text-right"><?php echo number_format($y->emp_salary,0);?></td>

													<!--<?php $gsspCheck =  HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee_gssp_documents','counter',$y->emp_code, 'emp_code'); ?>
													@if($gsspCheck > 0)
														<td class="text-center">
															<a onclick="showMasterTableEditModel('hdc/viewEmployeeGsspVeriDetail','<?php echo $y->id;?>','View Employee GSSP Documents','<?php echo $m; ?>')" class=" btn btn-info btn-xs">View</a>
														</td>
													@else
														<td class="text-center"> -- </td>
													@endif

                                                    <?php $documentsCheck =  HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee_documents','documents_upload_check',$y->emp_code, 'emp_code'); ?>
													@if($documentsCheck == 1)
														<td class="text-center">
															<a onclick="showMasterTableEditModel('hdc/viewEmployeeDocuments','<?php echo $y->id;?>','View Employee Documents','<?php echo $m; ?>')" class=" btn btn-info btn-xs">View</a>
														</td>
													@else
														<td class="text-center"> -- </td>-->
													@endif
													<td class="text-center"><?php echo $y->emp_email;?></td>
													<td class="text-center"><?php $usr =  DB::table('users')->where('emp_code',$y->emp_code)->select('identity');
															if($usr->count() > 0)
															{
																echo $usr->first()->identity;
															}

														?></td>
													<td class="text-center"></td>
													<td @if($y->status == 4) onclick="showDetailModelTwoParamerter('hmfal/makeFormEmployeeInActive','<?php echo $y->id;?>','','<?php echo $m; ?>')" class="text-center pointer" @else class="text-center" @endif>{{HrHelper::getStatusLabel($y->status)}}</td>
													<td class="text-center hidden-print">
														 <div class="dropdown">
															<button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
																<span class="caret"></span></button>
															<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
																@if(in_array('view', $operation_rights))
																	<li role="presentation">
																		<a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewEmployeeDetail','<?php echo $y->id;?>','View Employee Detail','<?php echo $m; ?>')">
																			View
																		</a>
																	</li>
																@endif
																@if(in_array('edit', $operation_rights))
																	<li role="presentation">
																		<a  class="delete-modal btn" href="<?= url("/hr/editEmployeeDetailForm/{$y->id}/{$m}?pageType=viewlist&&parentCode=27&&m={$m}")?>">
																			Edit
																		</a>
																	</li>
																@endif
																@if(in_array('repost', $operation_rights))
																	@if($y->status == 2)
																		<li role="presentation">
																			<a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id ?>','employee')">
																				Repost
																			</a>
																		</li>
																	@endif
																@endif
																@if(in_array('delete', $operation_rights))
																	@if($y->status == 1)
																		<li role="presentation">
																			<a class="delete-modal btn" onclick="deleteEmployee('<?php echo $m ?>','<?php echo $y->id ?>','employee','<?php echo $y->emp_code ?>')">
																				Delete
																			</a>
																		</li>
																	@endif
																@endif
																@if($y->status == 4 || $y->status == 3)
																	<li role="presentation">
																	   <a class="delete-modal btn" onclick="restoreEmployee('<?php echo $m ?>','<?php echo $y->id ?>','employee')">
																		   Active
																		</a>
																	</li>
																	@else
																	 <li role="presentation">
																		<a class="delete-modal btn" onclick="showDetailModelTwoParamerter('hmfal/makeFormEmployeeInActive','<?php echo $y->id;?>','','<?php echo $m; ?>')">
																			InActive
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
				</span>
					<div class="text-center ajax-loader"></div>
				</div>
			    </div>
			</div>
		</div>
	</div>
	<script>

        $('#search_area').scroll(function() {
            var loader = '<?=url('/')?>/assets/img/loader5.gif';
            var lastId = $(".post-id:last").attr("id");
            var counterId = $(".counterId:last").attr("id");
            var rights_url = 'hr/viewEmployeeList';
            var m = '<?=Input::get('m')?>';
            if ($(this).scrollTop() + $(this).height() >= $(this)[0].scrollHeight && $(this).scrollTop() > 100) {
                $.ajax({
                    url: '<?=url('/')?>/hdc/getMoreEmployeesDetail',
                    type: "get",
                    data: {m:m,lastId:lastId,counterId:counterId, rights_url:rights_url},
                    beforeSend: function ()
                    {
                        $('.ajax-loader').html('<div class="row"><img src="'+loader+'"></div>');
                    },
                    success: function (data) {
                        setTimeout(function() {
                            $('.ajax-loader').html('');
                            $("#appendGetMoreEmp").append(data);
                        }, 1000);
                    }
                });
            }
        });

        $(document).ready(function () {
            $('#emp_department_id').select2();
            $('#region_id').select2();
        });


        function viewEmployeeFilteredList() {

            var emp_department_id = $('#emp_department_id').val();
            var region_id = $('#region_id').val();
            var emp_name = $('#emp_name').val();
            var emp_code = $(".emp_code").val();
            var m = $('#company_id').val();

			if(emp_department_id != '' || region_id != '' ||
					emp_name != '' || emp_code != '') {

				$('#employee-list').html('<div class="loading"></div>');
				$.ajax({
					url: "/hdc/viewEmployeeFilteredList",
					type: 'GET',
					data: {
						m: m,
						emp_department_id: emp_department_id,
						region_id: region_id,
						emp_name: emp_name,
						emp_code: emp_code,
					},
					success: function (response) {
						$('#employee-list').html('');
						var result = response;
						$('#employee-list').append(result);
					}
				});
			}else {
				return false;
			}
        }

        function deleteEmployee(companyId,recordId,tableName,emp_code){
            var companyId;
            var recordId;
            var tableName;
			var emp_code;

			swal({
				title: "Do you want to delete this record ?",
				text: "",
				type: "warning",
				showCancelButton: true,
				confirmButtonColor: "#DD6B55",
				confirmButtonText: "Delete",
				cancelButtonText: "Cancel",
				closeOnConfirm: false,
				closeOnCancel: true,
				allowOutsideClick: true
			},
			function(isConfirm) {
				if (isConfirm) {
					$.ajax({
						url: '<?php echo url('/')?>/cdOne/deleteEmployee',
						type: "GET",
						data: {'request_type':'delete',companyId:companyId,recordId:recordId,tableName:tableName,'emp_code':emp_code},
						success:function(data) {
							//location.reload();
						}
					});
				} else {
					return false;
				}
			});
        }

        function restoreEmployee(companyId,recordId,tableName){
            $.ajax({
                url: '<?php echo url('/')?>/cdOne/restoreEmployee',
                type: "get",
                data: {companyId:companyId,recordId:recordId,tableName:tableName},
                success:function(data) {
                    location.reload();
                }
            });
        }

	</script>


@endsection
