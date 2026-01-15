<?php
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}
$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<span class="subHeadingLabelClass">Create Employee Category Form</span>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'had/addEmployeeCategoryDetail?m='.$m.'','id'=>'EmployeeCategory'));?>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="companyId" value="<?php echo $m ?>">
					<div class="panel">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<input type="hidden" name="designationSection[]" class="form-control" id="designationSection" value="1" />
								</div>
							</div>
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<label>Employee Category Name:</label>
									<span class="rflabelsteric"><strong>*</strong></span>
									<input type="text" name="employee_category_name[]" id="employee_category_name" value="" class="form-control requiredField" required />
								</div>
							</div>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="EmployeeCategorySection"></div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
							{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
							<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
							<input type="button" class="btn btn-sm btn-primary addMoreEmployeeCategorySection" value="Add More Employee Category Section" />
						</div>
					</div>
                    <?php echo Form::close();?>
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


            var category = 1;
            $('.addMoreEmployeeCategorySection').click(function (e){
                e.preventDefault();
				category++;
                $('.EmployeeCategorySection').append('<div id="sectionEmployeeCategory_'+category+'">' +
						'<a href="#" onclick="removeEmployeeCategorySection('+category+')" class="btn btn-xs btn-danger">Remove</a>' +
						'<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                    '<div class="row">' +
                    '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    ' <label>Employee Category Name:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" name="employee_category_name[] " id="employee_category_name[] " value="" class="form-control requiredField" required/>' +
                    '</div></div></div></div></div>');

            });

        });

        function removeEmployeeCategorySection(id){
            var elem = document.getElementById('sectionEmployeeCategory_'+id+'');
            elem.parentNode.removeChild(elem);
        }
	</script>
@endsection