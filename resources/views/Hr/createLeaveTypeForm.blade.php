<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>
@extends('layouts.default')
@section('content')

	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="well_N">
					<div class="dp_sdw">						
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<span class="subHeadingLabelClass">Create Leave Type Form</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<?php echo Form::open(array('url' => 'had/addLeaveTypeDetail?m='.$m.'','id'=>'leaveTypeForm'));?>
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
										<input type="text" name="leave_type_name_1" id="leave_type_name_1" value="" class="form-control requiredField" />
									</div>
								</div>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="leaveTypeSection"></div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
								{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
								<button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
								<input type="button" class="btn btn-primary addMoreLeaveTypeSection" value="Add More Leave Type Section" />
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


		var leaveType = 1;
		$('.addMoreLeaveTypeSection').click(function (e){
			e.preventDefault();
        	leaveType++;
			
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormLeaveTypeDetail',
				type: "GET",
				data: { id:leaveType},
				success:function(data) {
					$('.leaveTypeSection').append('<div id="sectionLeaveType_'+leaveType+'"><a href="#" onclick="removeLeaveTypeSection('+leaveType+')" class="btn btn-sm btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});

	});
	
	function removeLeaveTypeSection(id){
		var elem = document.getElementById('sectionLeaveType_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection