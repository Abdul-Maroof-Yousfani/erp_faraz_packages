<?php
$currentDate = date('Y-m-d');
$id = $_GET['id'];
$m 	= $_GET['m'];

$jobTypeDetail = DB::selectOne('select * from `job_type` where `id` = '.$id.'');
?>

<div class="panel">
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well">
					<?php echo Form::open(array('url' => 'had/editJobTypeDetail?m='.$m.'','id'=>'jobTypeForm'));?>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
					<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
					<div class="panel">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<input type="hidden" name="jobTypeSection[]" class="form-control" id="jobTypeSection" value="1" />
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>Job Type Name:</label>
									<span class="rflabelsteric"><strong>*</strong></span>
									<input type="text" name="job_type_name_1" id="job_type_name_1" value="<?php echo $jobTypeDetail->job_type_name?>" class="form-control requiredField" />
									<input type="hidden" name="job_type_id_1" id="job_type_id_1" value="<?php echo $jobTypeDetail->id?>" class="form-control requiredField" />
								</div>
							</div>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="jobTypeSection"></div>
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
		var jobType = new Array();
		var val;
		$("input[name='jobTypeSection[]']").each(function(){
			jobType.push($(this).val());
		});
		var _token = $("input[name='_token']").val();
		for (val of jobType) {

			jqueryValidationCustom();
			if(validate == 0){
				//alert(response);
			}else{
				return false;
			}
		}

	});
</script>