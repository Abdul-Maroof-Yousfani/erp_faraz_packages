	<?php 
		$currentDate = date('Y-m-d');
		$id = $_GET['id'];
		$m 	= $_GET['m'];
		$d 	= DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;
		$leaveTypeDetail = DB::selectOne('select * from `leave_type` where `id` = '.$id.'');
	?>
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<?php echo Form::open(array('url' => 'had/editLeaveTypeDetail?m='.$m.'&&d='.$d.'','id'=>'leaveTypeForm'));?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
								<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<input type="hidden" name="leaveTypeSection[]" class="form-control" id="leaveTypeSection" value="1" />
											</div>		
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Leave Type Name:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="leave_type_name_1" id="leave_type_name_1" value="<?php echo $leaveTypeDetail->leave_type_name?>" class="form-control requiredField" />
												<input type="hidden" name="leave_type_id_1" id="leave_type_id_1" value="<?php echo $leaveTypeDetail->id?>" class="form-control requiredField" />
											</div>
										</div>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="leaveTypeSection"></div>
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
			var leaveType = new Array();
			var val;
			$("input[name='leaveTypeSection[]']").each(function(){
    			leaveType.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of leaveType) {
				
				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
			
		});
	</script>