<?php
use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$currentDate = date('Y-m-d');
?>

<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }

    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Issue Employees Bonus </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintIssueBonusDetailForm','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('IssueBonusDetailForm','','1')?>
                                @endif
                            </div>
                        </div>
                        <div class="lineHeight"></div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/addEmployeeBonusDetail'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                            <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
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
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Bonus Month:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="month" name="bonus_month_year" id="bonus_month_year" value="" class="form-control requiredField" required />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Bonus List:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="bonus_id" id="bonus_id" required>
                                                    <option value="">Select</option>
                                                    @foreach($bonus_list as $value)
                                                        <option value="{{ $value->id }}">{{ $value->bonus_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="margin-top: 28px">
                                                <input type="button" class="btn btn-sm btn-primary" onclick="viewEmployeesBonus()" value="View Employees Bonus" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="run_loader"></div>
                            <div id="PrintIssueBonusDetailForm">
                                <div class="employeePayslipSection" id="IssueBonusDetailForm"></div>
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

            $(".btn-success").click(function (e) {
                var employee = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function () {
                    employee.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employee) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        //alert(response);
                    } else {
                        return false;
                    }
                }

            });

            $('#department_id').select2();
            $('.emp_code').select2();
            $('#region_id').select2();
            $('#bonus_id').select2();

        });

        function viewEmployeesBonus(){

            var department_id = $('#department_id').val();
            var region_id = $('#region_id').val();
            var bonus_month_year = $('#bonus_month_year').val();
            var bonus_id = $('#bonus_id').val();
            var m = '<?= Input::get('m'); ?>';
            jqueryValidationCustom();
            if(validate == 0){
                $('#run_loader').html('<div class="loading"></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/hdc/viewEmployeesBonus',
                    type: "GET",
                    data: { bonus_id:bonus_id, department_id:department_id,region_id:region_id,
                        bonus_month_year:bonus_month_year,m:m},
                    success:function(data) {
                        $('.employeePayslipSection').empty();
                        $('.employeePayslipSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div>');
                        $('#run_loader').html('');
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

@endsection