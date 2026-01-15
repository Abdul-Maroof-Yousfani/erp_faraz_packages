<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
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
                                <span class="subHeadingLabelClass">Create Allowance Form</span>
                            </div>
                        </div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addEmployeeAllowanceDetail','id'=>'employeeForm'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="company_id" value="<?=$m?>">
                            <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Regions:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField region_id" name="region_id" id="region_id" onchange="filterEmployee()">
                                                    <option value="">Select Region</option>
                                                    @foreach($employee_regions as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Department:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField department_id" name="department_id" id="department_id" onchange="filterEmployee()">
                                                    <option value="">Select Department</option>
                                                    @foreach($employee_department as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->department_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="sf-label">Employee:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField emp_code" name="emp_code" id="emp_code" required></select>
                                                <div id="emp_loader"></div>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <label class="sf-label">Allowance Type:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField allowance_type" name="allowance_type" id="allowance_type">
                                                    <option value="">Select Allowance Type</option>
                                                    @foreach($allowance_type as $key => $y)
                                                        <option value="{{ $y->id}}">{{ $y->allowance_type}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                                <input type="button" class="btn btn-sm btn-primary" id="search" onclick="viewAllowanceForm()" value="Search" style="margin-top: 34px;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="employeeSection"></div>
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
                    }else{
                        return false;
                    }
                }

            });


            $('.emp_code').select2();
            $('.region_id').select2();
			$('.department_id').select2();
			$('.allowance_type').select2();

        });

        function viewAllowanceForm(){
            var emp_code = $('.emp_code').val();
            var region_id = $('#region_id').val();
            var department_id = $('#department_id').val();
            var allowance_type = $('#allowance_type').val();
            var m = '{{ Input::get('m') }}';
            jqueryValidationCustom();
            if(validate == 0){
                $('.employeeSection').html('<div class="loading"></div>');

                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewAllowanceForm',
                    type: "GET",
                    data: { emp_code:emp_code,region_id:region_id,department_id:department_id,allowance_type:allowance_type,m:m},
                    success:function(data) {
                        $('.employeeSection').html('');
                        $('.employeeSection').append('<div class="">'+data+'</div>');
                    }
                });
            }else{
                return false;
            }
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
                        $('select[name="emp_code"]').prepend("<option value='All' selected>All</option>");
					}
				});
			}
			else{
				$("#department_id").val('');
			}
		}

    </script>

@endsection    