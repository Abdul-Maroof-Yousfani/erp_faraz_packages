<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Region Form</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'had/addEmployeeRegionsDetail?m='.$m.'','id'=>'EmployeeRegion'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="companyId" value="<?php echo $m ?>">
                    <input type="hidden" name="EmployeeRegionSection[]" value="1">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Region:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="employee_region[]" id="employee_region" value="" class="form-control requiredField" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="EmployeeRegionSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                            <input type="button" class="btn btn-primary addMoreEmployeeRegionSection" value="Add More Employee Region Section" />
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {

            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var employeeRegion = new Array();
                var val;
                $("input[name='EmployeeRegionSection[]']").each(function(){
                    employeeRegion.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employeeRegion) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            var region = 1;
            $('.addMoreEmployeeRegionSection').click(function (e){
                e.preventDefault();
                region++;
                $('.EmployeeRegionSection').append('<div id="sectionEmployeeRegion_'+region+'">' +
                    '<a href="#" onclick="removeEmployeeRegionSection('+region+')" class="btn btn-sm btn-danger">Remove</a>' +
                    '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                    '<div class="row">' +
                    '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    ' <label>Region:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" name="employee_region[] " id="employee_region[] " value="" class="form-control requiredField" required/>' +
                    '</div></div></div></div></div>');

            });

        });

        function removeEmployeeRegionSection(id){
            var elem = document.getElementById('sectionEmployeeRegion_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection