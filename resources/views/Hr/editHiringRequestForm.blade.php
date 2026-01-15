<?php 
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}

?>
<script src="{{ URL::asset('assets/js/popper.js') }}"></script>
<link rel="stylesheet" href="{{ URL::asset('assets/css/summernote-bs4.css') }}">
<script type="text/javascript" src="{{ URL::asset('assets/js/summernote-bs4.js') }}"></script>
<script>

	$(function() {
		$('.summernote').summernote({
			height: 200
		});

	});
</script>
	<div class="">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<?php echo Form::open(array('url' => 'had/editHiringRequestDetail','id'=>'addHiringRequestForm'));?>
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="hidden" name="RequestHiringId" id="RequestHiringId" value="<?php echo $_GET['id'];?>">
												<input type="hidden" name="company_id" id="company_id" value="<?php echo $m;?>">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<input type="hidden" name="requestHiringSection[]" class="form-control" id="requestHiringSection" value="1" />
														</div>		
													</div>
													<div class="row">
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
															<label class="sf-label">Job Title</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<input type="text" class="form-control requiredField" placeholder="Job Title" name="job_title" id="job_title" value="<?php echo $hiringRequestDetail->RequestHiringTitle;?>" />
														</div>
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
															<label class="sf-label">Job Type</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select class="form-control requiredField" name="job_type_id" id="job_type_id">
                                    							<option value="">Select Job Type</option>
                                    							@foreach($JobTypes as $key1 => $row1)
                                    								<option value="{{ $row1->id}}"{{ $hiringRequestDetail->job_type_id == $row1->id ? 'selected="selected"' : '' }}>{{ $row1->job_type_name}}</option>
                                    							@endforeach
                                    						</select>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
															<label class="sf-label">Department / Sub Department</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select class="form-control requiredField" name="sub_department_id" id="sub_department_id">
		                                    					<option value="">Select Department / Sub Department</option>
		                                    					@foreach($departments as $key2 => $row2)
                                    								<optgroup label="{{ $row2->department_name}}" value="{{ $row2->id}}">
																	<?php 
																		$subdepartments = DB::select('select id,`sub_department_name` from sub_department where company_id = '.$m.' and department_id ='.$row2->id.'');
																	?>		
																		@foreach($subdepartments as $key3 => $row3)
																			<option value="{{ $row3->id}}" {{ $hiringRequestDetail->sub_department_id == $row3->id ? 'selected="selected"' : '' }}>{{ $row3->sub_department_name}}</option>
																		@endforeach
																	</optgroup>
                                    							@endforeach
		                                    				</select>
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
                                    								<option value="{{ $row4->id}}" {{ $hiringRequestDetail->designation_id == $row4->id ? 'selected="selected"' : '' }}>{{ $row4->designation_name}}</option>
                                    							@endforeach
                                    						</select>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
															<label class="sf-label">Qualification</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select class="form-control requiredField" name="qualification_id" id="qualification_id">
                                    							<option value="">Select Qualification</option>
                                    							@foreach($Qualifications as $key5 => $row5)
                                    								<option value="{{ $row5->id}}" {{ $hiringRequestDetail->qualification_id == $row5->id ? 'selected="selected"' : '' }}>{{ $row5->qualification_name}}</option>
                                    							@endforeach
                                    						</select>
														</div>
														<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
															<label class="sf-label">Shift Types</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select class="form-control requiredField" name="shift_type_id" id="shift_type_id">
                                    							<option value="">Select Shift Type</option>
                                    							@foreach($ShiftTypes as $key6 => $row6)
                                    								<option value="{{ $row6->id}}" {{ $hiringRequestDetail->shift_type_id == $row6->id ? 'selected="selected"' : '' }}>{{ $row6->shift_type_name}}</option>
                                    							@endforeach
                                    						</select>
														</div>
													</div>
													<div class="lineHeight">&nbsp;</div>
													<div class="row">
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Gender</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select class="form-control requiredField" name="gender" id="gender">
                                    							<option value="">Select Gender</option>
                                    							<option value="1" {{ $hiringRequestDetail->RequestHiringGender == 1 ? 'selected="selected"' : '' }}>Male</option>
																<option value="2" {{ $hiringRequestDetail->RequestHiringGender == 2 ? 'selected="selected"' : '' }}>Female</option>
																<option value="3" {{ $hiringRequestDetail->RequestHiringGender == 3 ? 'selected="selected"' : '' }}>Doesn,t Matter</option>
															</select>
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Salary Start</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<input type="number" class="form-control requiredField" placeholder="Salary Start" name="salary_start" id="salary_start" value="<?php echo $hiringRequestDetail->RequestHiringSalaryStart;?>" />
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Salary End</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<input type="number" class="form-control requiredField" placeholder="Salary End" name="salary_end" id="salary_end" value="<?php echo $hiringRequestDetail->RequestHiringSalaryEnd?>" />
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Age</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<input type="number" class="form-control requiredField" placeholder="Age" name="age" id="age" value="<?php echo $hiringRequestDetail->RequestHiringAge?>" />
														</div>
													</div>
                                                    <div class="lineHeight">&nbsp;</div>
													<div class="row">
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Location</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<input type="text" class="form-control requiredField" placeholder="New York" name="location" id="location" value="<?php echo $hiringRequestDetail->location; ?>" />
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Experience (Years)</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select class="form-control requiredField" name="experience" id="experience">
																<option value="">Select</option>
																<option {{ $hiringRequestDetail->experience == '0-1' ? 'selected="selected"' : '' }} value="0-1">0--1 (Years)</option>
																<option {{ $hiringRequestDetail->experience == '1-2' ? 'selected="selected"' : '' }} value="1-2">1--2 (Years)</option>
																<option {{ $hiringRequestDetail->experience == '2-3' ? 'selected="selected"' : '' }} value="2-3">2--3 (Years)</option>
																<option {{ $hiringRequestDetail->experience == '3-4' ? 'selected="selected"' : '' }} value="3-4">3--4 (Years)</option>
																<option {{ $hiringRequestDetail->experience == '4-5' ? 'selected="selected"' : '' }} value="4-5">4--5 (Years)</option>
																<option {{ $hiringRequestDetail->experience == '5+' ? 'selected="selected"' : '' }} value="5+">5+    (Years)</option>
															</select>
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Career Level</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<select class="form-control requiredField" name="career_level" id="career_level">
																<option value="">Select</option>
																<option {{ $hiringRequestDetail->career_level == 'Beginners' ? 'selected="selected"' : '' }}  value="Beginners">Beginners</option>
																<option {{ $hiringRequestDetail->career_level == 'Intermediate' ? 'selected="selected"' : '' }}  value="Intermediate">Intermediate</option>
																<option {{ $hiringRequestDetail->career_level == 'Experience' ? 'selected="selected"' : '' }}  value="Experience">Experience</option>
															</select>
														</div>
														<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
															<label class="sf-label">Apply Before Date</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<input type="date" class="form-control requiredField" name="apply_before_date" id="apply_before_date" value="<?php echo $hiringRequestDetail->apply_before_date ?>">
														</div>
													</div>
													<div class="lineHeight">&nbsp;</div>
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<label class="sf-label">Job Description</label>
															<span class="rflabelsteric"><strong>*</strong></span>
															<textarea id="job_description" name="job_description" class="form-control requiredField summernote"  title="Contents"style="resize: none;" rows="10"><?php echo $hiringRequestDetail->RequestHiringDescription?></textarea>
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