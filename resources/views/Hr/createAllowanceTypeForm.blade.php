<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Allowance Type Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/addAllowanceTypeDetail'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="{{ Input::get('m') }}">
                        <input type="hidden" name="employeeSection[]" value="1">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Allowance Type:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="allowance_type[]" id="allowance_type" value="" class="form-control requiredField" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="AllowanceSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreAllowanceTypeSection" value="Add More Allowance Type" />
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
                var diseaseType = new Array();
                var val;
                $("input[name='employeeSection[]']").each(function(){
                    diseaseType.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of diseaseType) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            var count = 1;
            $('.addMoreAllowanceTypeSection').click(function (e){
                e.preventDefault();
                count++;
                $('.AllowanceSection').append('<div id="sectionAllowanceType_'+count+'">' +
                        '<a href="#" onclick="removeAllowanceTypeSection('+count+')" class="btn btn-xs btn-danger">Remove</a>' +
                        '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                        '<div class="row">' +
                        '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                        ' <label>Allowance Type:</label>' +
                        '<span class="rflabelsteric"><strong>*</strong></span>' +
                        '<input type="text" name="allowance_type[] " id="allowance_type[] " value="" class="form-control requiredField" required/>' +
                        '</div></div></div></div></div>');

            });
        });

        function removeAllowanceTypeSection(id){
            var elem = document.getElementById('sectionAllowanceType_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection