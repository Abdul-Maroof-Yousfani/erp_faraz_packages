<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
//?>
@extends('layouts.default')
@section('content')

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Create Degree Type Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/addEmployeeDegreeTypeDetail','id'=>'EmployeeDegreeType'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                        <input type="hidden" name="EmployeeDegreeTypeSection[]" value="1">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Employee Degree Type:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="degree_type_name[]" id="degree_type_name" value="" class="form-control requiredField" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="EmployeeDegreeTypeSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreEmployeeDegreeTypeSection" value="Add More Degree Type Section" />
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
                var degreeType = new Array();
                var val;
                $("input[name='EmployeeDegreeTypeSection[]']").each(function(){
                    degreeType.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of degreeType) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

            var category = 1;
            $('.addMoreEmployeeDegreeTypeSection').click(function (e){
                e.preventDefault();
                category++;
                $('.EmployeeDegreeTypeSection').append('<div id="sectionEmployeeDegreeType_'+category+'">' +
                    '<a href="#" onclick="removeEmployeeDegreeTypeSection('+category+')" class="btn btn-xs btn-danger">Remove</a>' +
                    '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                    '<div class="row">' +
                    '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    ' <label>Employee Degree Type:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" name="degree_type_name[] " id="degree_type_name[] " value="" class="form-control requiredField" required/>' +
                    '</div></div></div></div></div>');

            });
        });

        function removeEmployeeDegreeTypeSection(id){
            var elem = document.getElementById('sectionEmployeeDegreeType_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection