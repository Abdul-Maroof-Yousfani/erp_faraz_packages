<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}

$m = $_GET['m'];
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
                                <span class="subHeadingLabelClass">Create Disease Form</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php echo Form::open(array('url' => 'had/addDiseaseDetail','id'=>'EmployeeDegreeType'));?>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
                        <input type="hidden" name="EmployeeDegreeTypeSection[]" value="1">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <label>Disease Type:</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" name="disease_type[]" id="disease_type" value="" class="form-control requiredField" required />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="DiseaseTypeSection"></div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreDiseaseTypeSection" value="Add More Degree Type Section" />
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
                $("input[name='EmployeeDegreeTypeSection[]']").each(function(){
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

            var category = 1;
            $('.addMoreDiseaseTypeSection').click(function (e){
                e.preventDefault();
                category++;
                $('.DiseaseTypeSection').append('<div id="sectionDiseaseType_'+category+'">' +
                    '<a href="#" onclick="removeDiseaseTypeSection('+category+')" class="btn btn-xs btn-danger">Remove</a>' +
                    '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                    '<div class="row">' +
                    '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    ' <label>Disease Type:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" name="disease_type[] " id="disease_type[] " value="" class="form-control requiredField" required/>' +
                    '</div></div></div></div></div>');

            });
        });

        function removeDiseaseTypeSection(id){
            var elem = document.getElementById('sectionDiseaseType_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection