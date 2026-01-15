<?php 
$accType = Auth::user()->acc_type;
$m = Input::get('m');
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
								<span class="subHeadingLabelClass">Create Designation Form</span>
							</div>
						</div>
						<?php echo Form::open(array('url' => 'had/addDesignationDetail?m='.$m.'','id'=>'designationForm'));?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="designationSection[]" class="form-control" id="designationSection" value="1" />
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<label>Department:</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<select class="form-control requiredField emp_department_id" name="department_id_1" id="department_id_1">
											<option value="">Select Department</option>
											@foreach($departments as $key => $y)
												<option value="{{ $y->id}}">{{ $y->department_name}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
										<label>Designation Name:</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<input type="text" name="designation_name_1" id="designation_name_1" value="" class="form-control requiredField" />
									</div>
								</div>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="designationSection"></div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
								{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
								<button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
								<input type="button" class="btn btn-primary addMoreDesignationSection" value="Add More Designation's Section" />
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


		var designation = 1;
		$('.addMoreDesignationSection').click(function (e){
			e.preventDefault();
        	designation++;
			var m = '<?php echo $m ?>';
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormDesignationDetail',
				type: "GET",
				data: { id:designation,m:m},
				success:function(data) {
					$('.designationSection').append('<div id="sectionDesignation_'+designation+'"><a href="#" onclick="removeDesignationSection('+designation+')" class="btn btn-sm btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});
	});
	
	function removeDesignationSection(id){
		var elem = document.getElementById('sectionDesignation_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection