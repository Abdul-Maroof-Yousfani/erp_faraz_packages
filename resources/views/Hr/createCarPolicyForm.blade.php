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
                                    <span class="subHeadingLabelClass">Create Car Policy Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'had/addCarPolicyDetail','id'=>'employeeForm'));?>
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
                                               <div class="form_area">
                                                    <div class="get_data">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Policy Name:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="text" name="policy_name[]"  class="form-control requiredField"/>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Designation:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select name="designation_id[]" class="form-control requiredField">
                                                             <option value="">Select</option>
                                                                @foreach($designation as $value2)
                                                                    <option value="{{ $value2->id }}">{{ $value2->designation_name }}</option>
                                                                @endforeach
                                                            </select>
                                                         </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label class="sf-label">Vehicle Type & CC:</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <select name="vehicle_type_id[]" class="form-control requiredField">
                                                             <option value="">Select</option>
                                                                @foreach($vehicleType as $value)
                                                                    <option value="{{ $value->id }}">{{ $value->vehicle_type_name.'&nbsp;&nbsp;'.$value->vehicle_type_cc.'CC' }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label class="sf-label">Start Salary Range</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="number" name="start_salary_range[]" class="form-control requiredField"/>
                                                        </div>
                                                         <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                            <label class="sf-label">End Salary Range</label>
                                                            <span class="rflabelsteric"><strong>*</strong></span>
                                                            <input type="number" name="end_salary_range[]"  class="form-control requiredField"/>
                                                         </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                        <input type="button" class="btn btn-sm btn-primary addMoreCarPolicySection" value="Add More Allowance Section" />
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

            $('.addMoreCarPolicySection').click(function (e){
                var form_rows_count = $(".get_data").length;
                var data = $('.form_area').html();
                $('.form_area').append('<div class="row" id="remove_area_'+form_rows_count+'"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><button onclick="removeEmployeeSection('+form_rows_count+')" type="button" class="btn btn-xs btn-danger">Remove</button></div><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">'+data+'</div></div>');

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



        function removeEmployeeSection(id){

            $("#remove_area_"+id).remove();

        }

    </script>

@endsection