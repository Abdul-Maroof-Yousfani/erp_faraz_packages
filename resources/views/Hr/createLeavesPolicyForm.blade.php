<?php

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

@extends('layouts.default')
@section('content')
    <script src="{{ URL::asset('assets/js/popper.js') }}"></script>
    <link rel="stylesheet" href="{{ URL::asset('assets/css/summernote-bs4.css') }}">
    <script type="text/javascript" src="{{ URL::asset('assets/js/summernote-bs4.js') }}"></script>

    <script>

        $(function() {
            $('.summernote').summernote({
                height: 200
            });

        });
    </script>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Leaves Policy Form</span>
                        </div>
                    </div>
                    {{--<div class="lineHeight">&nbsp;</div>--}}
                    <div class="row">
                        <?php echo Form::open(array('url' => 'had/addLeavesPolicyDetail','id'=>'employeeForm'));?>
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
                                        <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                            <label class="sf-label">Leaves Policy Name:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <input type="text" name="leaves_policy_name" class="form-control requiredField" id="leaves_policy_name" required value="" />
                                        </div>
                                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">

                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Policy Date from:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" name="PolicyDateFrom" class="form-control requiredField" id="PolicyDatefrom" required value=""/>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Policy Date till:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="date" name="PolicyDateTill" class="form-control requiredField" id="PolicyDateTill" required value=""/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Full Day Deduction Rate:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select readonly class="form-control requiredField" name="full_day_deduction_rate" required>
                                                <option selected value="1">1 (Day)</option>
                                                <!-- <option selected value="1">1/1&nbsp;&nbsp;(First Quarter)</option>
                                                 <option value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                 <option value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                 <option value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>-->
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Half Day Deduction Rate:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select readonly class="form-control requiredField" name="half_day_deduction_rate" required>
                                                <option selected value="0.5">0.5 (Day)</option>
                                                <!--   <option value="0.5">1/2&nbsp;&nbsp;(Second Quarter)</option>
                                                    <option value="0.33333333333">1/3&nbsp;&nbsp;(Third Quarter)</option>
                                                    <option value="0.25">1/4&nbsp;&nbsp;(Fourth Quarter)</option>-->
                                            </select>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label class="sf-label">Short Leave Deduction Rate:</label>
                                            <span class="rflabelsteric"><strong>*</strong></span>
                                            <select readonly class="form-control requiredField" name="per_hour_deduction_rate" required>

                                                <option selected value="0.25">0.25 (Day)</option>
                                                <!--<option value="0.1">1/1&nbsp;&nbsp;(Equivalent to 1 Hour)</option>
                                                <option value="0.2">1/2&nbsp;&nbsp;(Equivalent to 2 Hour)</option>
                                                <option value="0.3">1/3&nbsp;&nbsp;(Equivalent to 3 Hour)</option>
                                                <option value="0.4">1/4&nbsp;&nbsp;(Equivalent to 4 Hour)</option>
                                                <option value="0.5">1/5&nbsp;&nbsp;(Equivalent to 5 Hour)</option>
                                                <option value="0.6">1/6&nbsp;&nbsp;(Equivalent to 6 Hour)</option>
                                                <option value="0.7">1/7&nbsp;&nbsp;(Equivalent to 7 Hour)</option>
                                                <option value="0.8">1/8&nbsp;&nbsp;(Equivalent to 8 Hour)</option>-->

                                            </select>
                                        </div>

                                    </div>
                                    <div class="row">
                                    <span class="form_area">
                                        <span class="get_data">
                                             <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">Leaves Type:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select name="leaves_type_id[]" class="form-control requiredField test" required>
                                                    <option value="">Select</option>
                                                    @foreach($leaves_types as $value)
                                                        <option value="{{ $value->id }}">{{ $value->leave_type_name }}</option>
                                                    @endforeach
                                                </select>
                                             </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label class="sf-label">No. of Leaves:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input onkeyup="LeavesCount()" type="number" name="no_of_leaves[]" id="no_of_leaves[]" value="" class="form-control requiredField getLeaves" required />
                                            </div>
                                        </span>
                                    </span>
                                    </div>
                                    <div class="employeeSection"></div>
                                    <div class="row">&nbsp;</div>
                                    <div class="text-right">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"></div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><b>Total</b></div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><input readonly id="totalLeaves" name="totalLeaves" type="text" class="form-control"/></div>

                                    </div>
                                    <div class="row">&nbsp;</div>
                                    <div class="row">&nbsp;</div>
                                    <div class="text-right">
                                        <input type="button" class="btn btn-sm btn-primary addMorePolicySection" value="Add More Leaves Type" />
                                    </div>
                                    <div class="row">&nbsp;
                                        <label class="sf-label">Terms & Conditions:</label>
                                        <textarea name="terms_conditions" class="summernote" id="contents" title="Contents">-</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                    <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
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
            $('.addMorePolicySection').click(function (e){

                var form_rows_count = $(".get_data").length;
                var total_values = '<?= count($leaves_types) ?>';
                if(total_values == form_rows_count)
                {
                    return false;
                }
                var data = $('.form_area').html();
                $('.employeeSection').append('<div class="row text-center" id="remove_area_'+form_rows_count+'"><div class="row"><button onclick="removeEmployeeSection('+form_rows_count+')" type="button" class="btn btn-xs btn-danger">Remove</button></div>'+data+'</div>');

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

            var sum = 0;
            $(".getLeaves").each(function(){
                sum += +$(this).val();
            });
            $("#totalLeaves").val(sum);

        }

        function LeavesCount()
        {
            var sum = 0;
            $(".getLeaves").each(function(){
                sum += +$(this).val();
            });
            $("#totalLeaves").val(sum);
        }

    </script>


@endsection