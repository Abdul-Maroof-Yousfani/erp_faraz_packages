<?php

$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
use App\Helpers\CommonHelper;
?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">View Employee Location </span>
                    </div>

                </div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <?php echo Form::open(array('url' => 'had/addEmployeeFuelDetail','id'=>'employeeFuelDetailnForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="company_id" value="<?=$m?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <label class="sf-label">Department:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="sub_department_id" id="sub_department_id">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $key => $y)
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
                                        <div id="emp_loader"></div>
                                    </div>
                                    <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                        <label class="sf-label">Employee:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select class="form-control requiredField" name="emr_no" id="emr_no" required>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <div class="row">&nbsp;</div>
                                        <button class="btn btn-primary" id="searchPromotions" type="button">Search</button>
                                    </div>

                                </div>
                                <div id="data_loader"></div>
                                <div class="employeeSection"></div>
                            </div>
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            $(function(){
                $('select[name="sub_department_id"]').on('change', function() {
                    $('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                    var sub_department_id = $(this).val();
                    var m = '<?= Input::get('m'); ?>';
                    if(sub_department_id) {
                        $.ajax({
                            url: '<?php echo url('/')?>/slal/employeeLoadDependentDepartmentID',
                            type: "GET",
                            data: { sub_department_id:sub_department_id,m:m},
                            success:function(data) {
                                $('#emp_loader').html('');
                                $('select[name="emr_no"]').empty();
                                $('select[name="emr_no"]').html(data);
                                $('select[name="emr_no"]').find('option').get(0).remove();

                            }
                        });
                    }else{
                        $('select[name="emr_no"]').empty();
                    }
                });
            });



            $("#searchPromotions").click(function(){
                $('#data_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

                var emr_no  = $('#emr_no').val();
                var m = '<?=$m?>';
                var data = {'emr_no':emr_no,'m':m};
                var url = '<?php echo url('/')?>/hdc/viewEmployeeFuelDetailForm';
                $.get(url,data, function(result){
                    $('.employeeSection').html(result);
                    $('#data_loader').html('');
                });

            });

        });


        $(document).ready(function () {

            $('#sub_department_id').select2();
            $('#emr_no').select2();

        });


    </script>
    <script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>
@endsection