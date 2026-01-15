<?php
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}
$m = Input::get('m')
?>
@extends('layouts.default')
@section('content')

    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Employee Grade Form</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'had/addEmployeeGradesDetail?m='.$m.'','id'=>'Grade'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="companyId" value="{{ $m }}">
                    <input type="hidden" name="EmployeeGrageSection[]" value="1">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Category:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="category[]" id="category">
                                        <option value="">Select Category</option>
                                        <option value="Executives">Executives</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Clearing">Clearing</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Grade Type:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="employee_grade_type[]" id="employee_grade_type" value="" class="form-control requiredField" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="EmployeeGradeSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreEmployeeGradesSection" value="Add More Grade Section" />
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

<script>
    $(document).ready(function() {

        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var grade = new Array();
            var val;
            $("input[name='EmployeeGrageSection[]']").each(function(){
                grade.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of grade) {

                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });

        var grade = 1;
        $('.addMoreEmployeeGradesSection').click(function (e){
            e.preventDefault();
            grade++;
            $('.EmployeeGradeSection').append('<div id="sectionGrade_'+grade+'">' +
                '<a href="#" onclick="removeEmployeeGradesSection('+grade+')" class="btn btn-xs btn-danger">Remove</a>' +
                '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                '<div class="row">' +
                '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"><label>Category:</label>' +
                '<span class="rflabelsteric"><strong>*</strong></span>' +
                '<select class="form-control requiredField" name="category[]" id="category"> ' +
                '<option value="">Select Category</option> ' +
                '<option value="Executives">Executives</option> ' +
                '<option value="Engineering">Engineering</option> ' +
                '<option value="Clearing">Clearing</option> </select></div>'+
                '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                '<label>Grade Type</label>' +
                '<span class="rflabelsteric"><strong>*</strong></span>' +
                '<input type="text" name="employee_grade_type[] " id="employee_grade_type" value="" class="form-control requiredField" required />' +
                '</div></div></div></div></div>');

        });
    });

    function removeEmployeeGradesSection(id){
        var elem = document.getElementById('sectionGrade_'+id+'');
        elem.parentNode.removeChild(elem);
    }
</script>
@endsection