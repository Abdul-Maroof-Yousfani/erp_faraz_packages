<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName;


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
?>

@extends('layouts.default')
@section('content')

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        @include('Hr.'.$accType.'hrMenu')
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Vehicle Type Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'had/addVehicleTypeDetail','id'=>'employeeForm'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="company_id" value="<?=$m?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
                                                </div>
                                            </div>
                                            <div class="row">
                                                <span class="form_area">
                                                <span class="get_data">

                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Vehicle Type:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" name="vehicle_type[]" id="vehicle_type[]" value="" class="form-control requiredField" required />

                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <label class="sf-label">Vehicle CC:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="number" name="vehicle_cc[]" id="vehicle_cc[]" value="" class="form-control requiredField" required />
                                                </div>

                                                </span>
                                                </span>

                                                </span>
                                            </div>
                                            <div class="employeeSection"></div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                        <input type="button" class="btn btn-sm btn-primary addMoreVehicleSection" value="Add More Vehicle Type Section" />
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



            var m = "<?= $_GET["m"]; ?>";

            $('.addMoreVehicleSection').click(function (e){
                var form_rows_count = $(".get_data").length;
                var data = $('.form_area').html();
                $('.employeeSection').append('<div class="row" id="remove_area_'+form_rows_count+'"><div class="row"><button onclick="removeEmployeeSection('+form_rows_count+')" type="button" class="btn btn-xs btn-danger">Remove</button></div>'+data+'</div>');

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

            });
        });
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



        function removeEmployeeSection(id){

            $("#remove_area_"+id).remove();

        }
    </script>
@endsection