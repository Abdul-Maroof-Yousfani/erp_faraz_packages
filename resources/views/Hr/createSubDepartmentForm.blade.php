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
								<span class="subHeadingLabelClass">Create Salesman Form</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<?php echo Form::open(array('url' => 'had/addSubDepartmentDetail?m='.$m,'id'=>'subDepartmentForm'));?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
						<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="hidden" name="subDepartmentSection[]" class="form-control" id="subDepartmentSection" value="1" />
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 hide">
										<label>Select Department:</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<select class="form-control requiredField" name="department_id_1" id="department_id_1">

											@foreach($departments as $key => $y)
												<option value="{{ $y->id}}">{{ $y->department_name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label>Salesman Name:</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<input type="text" name="sub_department_name_1" id="sub_department_name_1" value="" class="form-control requiredField" />
									</div>
								</div>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="subDepartmentSection"></div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
								{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
								<button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
								<input type="button" class="btn btn-primary addMoreSubDepartmentSection" value="Add More Sub Department's Section" />
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
			var subDepartment = new Array();
			var val;
			$("input[name='subDepartmentSection[]']").each(function(){
				subDepartment.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of subDepartment) {

				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}

		});

		var subDepartment = 1;
		var companyId = '<?php echo $m?>';
		$('.addMoreSubDepartmentSection').click(function (e){
			e.preventDefault();
        	subDepartment++;

			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormSubDepartmentDetail',
				type: "GET",
				data: { id:subDepartment,companyId:companyId},
				success:function(data) {
					$('.subDepartmentSection').append('<div id="sectionSubDepartment_'+subDepartment+'"><a href="#" onclick="removeSubDepartmentSection('+subDepartment+')" class="btn btn-sm btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});

	});

	function removeSubDepartmentSection(id){
		var elem = document.getElementById('sectionSubDepartment_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection
