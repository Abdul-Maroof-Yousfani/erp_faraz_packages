<?php 
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}

?>

	<div class="">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<?php echo Form::open(array('url' => 'had/editEmployeeRequisitionDetail','id'=>'addHiringRequestForm'));?>
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="employeeRequisitionId" id="employeeRequisitionId" value="<?php echo $_GET['id'];?>">
												<input type="hidden" name="company_id" id="company_id" value="<?php echo $m;?>">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

													<div class="row">
														<input type="hidden" name="_token" value="{{ csrf_token() }}">
														<input type="hidden" name="company_id" id="company_id" value="<?php echo $m;?>">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="row">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<input type="hidden" name="requestHiringSection[]" class="form-control" id="requestHiringSection" value="1" />
																</div>
															</div>
															<div class="row">
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Department</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" name="department_id" id="department_id">
																		<option value="">Select Department</option>
																		@foreach($departments as $key2 => $row2)
																			<option @if($EmployeeRequisitiontDetail->department_id == $row2->id) selected @endif value="{{ $row2->id }}">{{ $row2->department_name }}</option>
																		@endforeach
																	</select>
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Apply Before Date</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input type="date" class="form-control requiredField" name="apply_before_date" id="apply_before_date" value="<?php echo $EmployeeRequisitiontDetail->apply_before_date ?>">
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Seperation Date Ex-Employee</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input type="date" class="form-control requiredField" id="ex_emp_seperation_date" name="ex_emp_seperation_date" value="<?php echo $EmployeeRequisitiontDetail->ex_emp_seperation_date ?>">
																</div>
																<div class="col-lg-12 col-md-12 col-sm-12-xs-12">
																	<label class="sf-label">Salary & benefits of Ex-Employee</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input class="form-control requiredField" name="ex_emp_benefits" id="ex_emp_benefits" value="<?php echo $EmployeeRequisitiontDetail->ex_emp_benefits ?>">
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Minimum Experience (Years) Required</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" name="experience" id="experience">
																		<option value="">Select</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '0--1' ? 'selected="selected"' : '' }} value="0--1">0--1 (Years)</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '1--2' ? 'selected="selected"' : '' }} value="1--2">1--2 (Years)</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '2--3' ? 'selected="selected"' : '' }} value="2--3">2--3 (Years)</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '3--4' ? 'selected="selected"' : '' }} value="3--4">3--4 (Years)</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '4--5' ? 'selected="selected"' : '' }} value="4--5">4--5 (Years)</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '5--10' ? 'selected="selected"' : '' }} value="5--10">5--10 (Years)</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '10--15' ? 'selected="selected"' : '' }} value="10--15">10--15 (Years)</option>
																		<option {{ $EmployeeRequisitiontDetail->experience == '15--20' ? 'selected="selected"' : '' }} value="15--20">15--20 (Years)</option>
																	</select>
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Additional or Replacement position</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" id="additional_replacement" name="additional_replacement">
																		<option value="">Select</option>
																		<option {{ $EmployeeRequisitiontDetail->additional_replacement == 'Additional' ? 'selected="selected"' : '' }}  value="Additional">Additional</option>
																		<option {{ $EmployeeRequisitiontDetail->additional_replacement == 'Replacement' ? 'selected="selected"' : '' }} value="Replacement">Replacement</option>
																	</select>
																</div>

																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Location</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input type="text" class="form-control requiredField" placeholder="New York" name="location" id="location" value="<?php echo $EmployeeRequisitiontDetail->location; ?>" />
																</div>

															</div>
															<div class="row">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<label>In case of replacement, please specify how the vacancy exists</label>
																	<textarea class="form-control requiredField" name="replacement_description" id="replacement_description"><?php echo $EmployeeRequisitiontDetail->replacement_description; ?></textarea>
																</div>
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<label>If additional employee, please provide status of employment</label>
																	<textarea class="form-control requiredField	" name="additional_description" id="additional_description"><?php echo $EmployeeRequisitiontDetail->additional_description; ?></textarea>
																</div>
															</div>
															<div class="row">
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">No of Employee Required</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input type="number" class="form-control requiredField" placeholder="0123" name="no_of_emp_required" id="no_of_emp_required" value="<?php echo $EmployeeRequisitiontDetail->no_of_emp_required; ?>" />
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Job Title</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input type="text" class="form-control requiredField" placeholder="Job Title" name="job_title" id="job_title" value="<?php echo $EmployeeRequisitiontDetail->job_title;?>" />
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Desired Qualification</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" name="qualification_id" id="qualification_id">
																		<option value="">Select Qualification</option>
																		@foreach($Qualifications as $key5 => $row5)
																			<option value="{{ $row5->id}}" {{ $EmployeeRequisitiontDetail->qualification_id == $row5->id ? 'selected="selected"' : '' }}>{{ $row5->qualification_name}}</option>
																		@endforeach
																	</select>
																</div>
															</div>
															<div class="lineHeight">&nbsp;</div>
															<div class="row">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<label>Any Other Requirments</label>
																	<textarea class="form-control requiredField" name="other_requirment" id="other_requirment"><?php echo $EmployeeRequisitiontDetail->other_requirment ?></textarea>
																</div>
															</div>
															<div class="lineHeight">&nbsp;</div>
															<div class="row">
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Designation</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" name="designation_id" id="designation_id">
																		<option value="">Select Designation</option>
																		@foreach($Designations as $key4 => $row4)
																			<option @if($EmployeeRequisitiontDetail->designation_id == $row4->id) selected @endif value="{{ $row4->id}}">{{ $row4->designation_name}}</option>
																		@endforeach
																	</select>
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Age Group From</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input type="number" class="form-control requiredField" id="age_group_from" name="age_group_from" value="<?php echo $EmployeeRequisitiontDetail->age_group_from;?>">
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Age Group To</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input type="number" class="form-control requiredField" id="age_group_to" name="age_group_to" value="<?php echo $EmployeeRequisitiontDetail->age_group_to;?>">
																</div>
															</div>
															<div class="lineHeight">&nbsp;</div>
															<div class="row">
																<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
																	<label class="sf-label">Job Description Already Exist
																		<span class="rflabelsteric"><strong>*</strong></span>
																		Yes:&nbsp;&nbsp;<input @if($EmployeeRequisitiontDetail->job_description_exist == 'yes') checked @endif class="requiredField" type="radio" name="job_description_exist" id="job_description_exist" value="yes">
																		&nbsp;&nbsp;&nbsp;&nbsp;
																		No:&nbsp;&nbsp;<input @if($EmployeeRequisitiontDetail->job_description_exist == 'no') checked @endif class="requiredField "type="radio" name="job_description_exist" id="job_description_exist" value="no">
																	</label>
																</div>
																<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
																	<label class="sf-label">Job Description Attached
																		<span class="rflabelsteric"><strong>*</strong></span>
																		Yes:&nbsp;&nbsp;<input @if($EmployeeRequisitiontDetail->job_description_attached == 'yes') checked @endif  checked class="requiredField"  type="radio" name="job_description_attached" id="job_description_attached" value="yes">
																		&nbsp;&nbsp;&nbsp;&nbsp;
																		No:&nbsp;&nbsp;<input  @if($EmployeeRequisitiontDetail->job_description_attached == 'no') checked @endif class="requiredField" type="radio" name="job_description_attached" id="job_description_attached" value="yes">
																	</label>
																</div>
															</div>
															<div class="lineHeight">&nbsp;</div>
															<div class="row">
																<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
																	<label class="sf-label">Gender</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" name="gender" id="gender">
																		<option value="">Select Gender</option>
																		<option value="1" {{ $EmployeeRequisitiontDetail->gender == 1 ? 'selected="selected"' : '' }}>Male</option>
																		<option value="2" {{ $EmployeeRequisitiontDetail->gender == 2 ? 'selected="selected"' : '' }}>Female</option>
																		<option value="3" {{ $EmployeeRequisitiontDetail->gender == 3 ? 'selected="selected"' : '' }}>Doesn,t Matter</option>
																	</select>
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Job Type</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" name="job_type_id" id="job_type_id">
																		<option value="">Select Job Type</option>
																		@foreach($JobTypes as $key1 => $row1)
																			<option value="{{ $row1->id}}"{{ $EmployeeRequisitiontDetail->job_type_id == $row1->id ? 'selected="selected"' : '' }}>{{ $row1->job_type_name}}</option>
																		@endforeach
																	</select>
																</div>
																<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
																	<label class="sf-label">Career Level</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<select class="form-control requiredField" name="career_level" id="career_level">
																		<option value="">Select</option>
																		<option {{ $EmployeeRequisitiontDetail->career_level == 'Beginners' ? 'selected="selected"' : '' }}  value="Beginners">Beginners</option>
																		<option {{ $EmployeeRequisitiontDetail->career_level == 'Intermediate' ? 'selected="selected"' : '' }}  value="Intermediate">Intermediate</option>
																		<option {{ $EmployeeRequisitiontDetail->career_level == 'Experience' ? 'selected="selected"' : '' }}  value="Experience">Experience</option>
																	</select>
																</div>
															</div>
															<div class="lineHeight">&nbsp;</div>
															<div class="row">
																<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
																	<label class="sf-label">Requisitioned by</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input class="form-control requiredField" name="requisitioned_by" id="requisitioned_by" value="<?php echo $EmployeeRequisitiontDetail->requisitioned_by ?>">
																</div>
																<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
																	<label class="sf-label">Recommended by	</label>
																	<span class="rflabelsteric"><strong>*</strong></span>
																	<input class="form-control requiredField" name="recommended_by" id="recommended_by" value="<?php echo $EmployeeRequisitiontDetail->recommended_by ?>">
																</div>
																<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
																	<label class="sf-label">In case of additional vacancy,
																		approval of Chairman solicited

																		<span class="rflabelsteric"><strong>*</strong></span>
																		<br>
																		Yes : <input @if($EmployeeRequisitiontDetail->job_description_exist == 'yes') checked @endif class="requiredField" type="radio" id="chairman_approval" name="chairman_approval" value="yes">
																		No : <input  @if($EmployeeRequisitiontDetail->job_description_exist == 'no') checked @endif class="requiredField" type="radio" id="chairman_approval" name="chairman_approval"  value="no">
																	</label>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
											{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
											<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
										</div>
									</div>
									<?php echo Form::close();?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
    $(document).ready(function() {
    	$(".btn-success").click(function(e){
			var requestHiring = new Array();
			var val;
			$("input[name='requestHiringSection[]']").each(function(){
    			requestHiring.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of requestHiring) {
				
				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
		});
    });
</script>