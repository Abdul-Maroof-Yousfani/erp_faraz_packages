<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>

@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Deduction Form</span>
                            </div>
                        </div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addEmployeeDeductionDetail','id'=>'employeeForm'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="{{ $m }}">
                            <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Regions:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="region_id" id="region_id" onchange="filterEmployee()">
                                                    <option value="">Select Region</option>
                                                    @foreach($employee_regions as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Department:</label>
                                                <select class="form-control" name="department_id" id="department_id" onchange="filterEmployee()">
                                                    <option value="">Select Department</option>
                                                    @foreach($employee_department as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                          
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField emp_code" name="emp_code" id="emp_code" ></select>
                                                <span id="emp_loader"></span>
                                            </div>
                                        </div>
                                        <div class="lineHeight"></div>
                                        <div class="row">
                                            <span class="form_area">
                                                <span class="get_data">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Deduction Type:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="text" name="deduction_type[]" id="deduction_type" value="" class="form-control requiredField" required />
                                                    </div>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label class="sf-label">Deduction Amount:</label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="number" name="deduction_amount[]" id="deduction_amount" value="" class="form-control requiredField" required/>
                                                    </div>
                                                </span>
                                            </span>
                                        </div>
                                        <div class="employeeSection"></div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                        <input type="button" class="btn btn-sm btn-primary addMoreDeductionSection" value="Add More Deduction" />
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
    <script>

        $(document).ready(function() {
            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else if(validate == 1){
                        return false;
                    }
                }

            });

            var m = "{{ Input::get('m') }}";

            $('.addMoreDeductionSection').click(function (e){
                var form_rows_count = $(".get_data").length;
                var data = $('.form_area').html();
                $('.employeeSection').append('<div class="row" id="remove_area_'+form_rows_count+'"><div class="row"><button onclick="removeEmployeeSection('+form_rows_count+')" type="button" class="btn btn-xs btn-danger">Remove</button></div>'+data+'</div>');

                // Wait for the DOM to be ready

            });

            $('#department_id').select2();
            $('.emp_code').select2();
            $('#region_id').select2();

        });

        function removeEmployeeSection(id){
            $("#remove_area_"+id).remove();
        }


		function filterEmployee(){
			var region_id = $("#region_id").val();
			var department_id = $("#department_id").val();
            var m = "{{ Input::get('m') }}";
			var url = '{{ url('/') }}/slal/getEmployeeRegionList';
			var data;    
			
			if(region_id != ''){
				data = {region_id:region_id,m:m};
			}
		    if(department_id != '' && region_id != ''){
				data = {department_id:department_id,region_id:region_id,m:m};
			}

			if(region_id != ''){
				 $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
				$.ajax({
					type:'GET',
					url:url,
					data:data,
					success:function(res){
						$('#emp_loader').html('');
                        $('select[name="emp_code"]').empty();
					    $('select[name="emp_code"]').html(res);
                        $('select[name="emp_code"]').prepend("<option value='' selected>Select Employee</option>");
					}
				});
			}
			else{
				$("#department_id").val('');
			}
		}
    </script>
@endsection