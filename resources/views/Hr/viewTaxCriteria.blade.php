<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
$m = $_GET['m'];
$currentDate = date('Y-m-d');
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
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
                                <span class="subHeadingLabelClass">View Tax Criteria</span>
                            </div>

                        </div>
                        <div class="row">
                            <?php echo Form::open(array('url' => 'had/createPayslipForm'));?>
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="m" value="<?= Input::get('m') ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Department:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="sub_department_id" id="sub_department_id">
                                                    <option value="">Select Department</option>
                                                    @foreach($departments as $key => $y)
                                                        <optgroup label="All Employees" value="all"> <option selected value="all">All Employees</option></optgroup>
                                                        <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                            <?php
                                                            $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');
                                                            ?>
                                                            @foreach($subdepartments as $key2 => $y2)
                                                                <option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <input type="button" class="btn btn-primary" onclick="viewTaxCriteria()" value="Check" style="margin-top: 32px;" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label class="sf-label">Taxes List:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select class="form-control requiredField" name="tax_id" id="tax_id">
                                                    <option value="">Select Tax</option>
                                                    @foreach($taxes as $key2 => $value)
                                                       <option value="{{ $value->id}}">{{ $value->tax_name}}</option>
                                                    @endforeach

                                                </select>
                                            </div>
                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                <input type="button" class="btn btn-primary" onclick="viewTax($('#tax_id').val())" value="View " style="margin-top: 32px;" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="viewCarPolicyArea"></div>
                            <?php echo Form::close();?>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#sub_department_id').select2();
            $('#tax_id').select2();

        });


        function viewTaxCriteria()
        {
            $('.viewCarPolicyArea').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            var m = '<?= Input::get('m'); ?>';
            var sub_department_id = $('#sub_department_id').val();
            var url= '<?php echo url('/')?>/hdc/viewTaxCriteria';
            $.getJSON(url, { sub_department_id:sub_department_id,m:m} ,function(result){
                $.each(result, function(i, field){

                    $('.viewCarPolicyArea').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+field+'</div>');

                });
            })
        }
        function viewTax(tax_id)
        {
            var m = '<?= Input::get('m'); ?>';
            var tax_id = tax_id;
            showDetailModelTwoParamerterJson('hdc/viewTax',tax_id,'View Tax ',m)
        }


        $(function(){
            $('select[name="sub_department_id"]').on('change', function() {
                var sub_department_id = $(this).val();
                var m = '<?= Input::get('m'); ?>';
                if(sub_department_id) {
                    $.ajax({
                        url: '<?php echo url('/')?>/slal/employeeLoadDependentDepartmentID',
                        type: "GET",
                        data: { sub_department_id:sub_department_id,m:m},
                        success:function(data) {
                            $('select[name="employee_id"]').empty();
                            $('select[name="employee_id"]').html(data);
                        }
                    });
                }else{
                    $('select[name="employee_id"]').empty();
                }
            });
        });


    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

@endsection