	<?php 
		$currentDate = date('Y-m-d');
		$id = $_GET['id'];
		$m 	= $_GET['m'];
		$d 	= DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;
		$designationDetail = DB::selectOne('select * from `designation` where `id` = '.$id.'');
	?>
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<?php echo Form::open(array('url' => 'had/editDesignationDetail?m='.$m.'&&d='.$d.'','id'=>'designationForm'));?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
								<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<input type="hidden" name="designationSection[]" class="form-control" id="designationSection" value="1" />
											</div>		
										</div>
										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<label>Department:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<select class="form-control requiredField emp_department_id" name="department_id_1" id="department_id_1">
													<option value="">Select Department</option>
													@foreach($departments as $key => $y)
														<option @if($designationDetail->department_id == $y->id ) selected @endif value="{{ $y->id}}">{{ $y->department_name}}</option>
													@endforeach
												</select>
											</div>
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
												<label>Designation Name:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="designation_name_1" id="designation_name_1" value="<?php echo $designationDetail->designation_name?>" class="form-control requiredField" />
												<input type="hidden" name="designation_id_1" id="designation_id_1" value="<?php echo $designationDetail->id?>" class="form-control requiredField" />
											</div>
										</div>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="designationSection"></div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
										{{ Form::submit('Update', ['class' => 'btn btn-success']) }}
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
									</div>
								</div>
							<?php echo Form::close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	<script type="text/javascript">
		$(".btn-success").click(function(e){
			var designation = new Array();
			var val;
			$("input[name='designationSection[]']").each(function(){
    			designation.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of designation) {
				
				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
			
		});
	</script>