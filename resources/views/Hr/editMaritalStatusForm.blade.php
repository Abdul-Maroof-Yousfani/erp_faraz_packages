<?php 
$currentDate = date('Y-m-d');
$id = $_GET['id'];
$m 	= $_GET['m'];
//$d 	= DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;
$maritalStatusDetail = DB::selectOne('select marital_status_name from `marital_status` where `id` = '.$id.'');
?>
	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="well_N">
					<div class="dp_sdw">	
						<?php echo Form::open(array('url' => 'had/editMaritalStatusDetail?m='.$m.'&&id='.$id,'id'=>'departmentForm'));?>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<input type="hidden" name="departmentSection[]" class="form-control" id="departmentSection" value="1" />
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>Marital Status Name:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<input type="text" name="marital_status_name" id="marital_status_name" value="<?php echo $maritalStatusDetail->marital_status_name; ?>" class="form-control requiredField" />
										</div>
									</div>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<div class="departmentSection"></div>
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
	</div>

	<script>
		$(document).ready(function() {

			// Wait for the DOM to be ready
			$(".btn-success").click(function(e){
				var department = new Array();
				var val;
				$("input[name='departmentSection[]']").each(function(){
					department.push($(this).val());
				});
				var _token = $("input[name='_token']").val();
				for (val of department) {

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
