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
								<span class="subHeadingLabelClass">Create Marital Status Form</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<?php echo Form::open(array('url' => 'had/addMaritalStatusDetail?m='.$m.'','id'=>'departmentForm'));?>
							<input type="hidden" name="_token" value="{{ csrf_token() }}">
							<div class="panel">
								<div class="panel-body">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<input type="hidden" name="martitalStatusSection[]" class="form-control" id="martitalStatusSection" value="1" />
										</div>
									</div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>Marital Status Name:</label>
											<span class="rflabelsteric"><strong>*</strong></span>
											<input type="text" name="marital_status_name_1" id="marital_status_name_1" value="" class="form-control requiredField" />
										</div>
									</div>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<div class="departmentSection"></div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
									{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
									<button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
									<input type="button" class="btn btn-primary addMoreMartialStatusSection" value="Add More Marital Status" />
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
			$("input[name='martitalStatusSection[]']").each(function(){
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

		var martialStatus = 1;
		$('.addMoreMartialStatusSection').click(function (e){
			e.preventDefault();
        	martialStatus++;
			
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormMaritalStatusDetail',
				type: "GET",
				data: { id:martialStatus},
				success:function(data) {
					$('.departmentSection').append('<div id="sectionDepartment_'+martialStatus+'"><a href="#" onclick="removeDepartmentSection('+martialStatus+')" class="btn btn-sm btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});
	});
	
	function removeDepartmentSection(id){
		var elem = document.getElementById('sectionDepartment_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection