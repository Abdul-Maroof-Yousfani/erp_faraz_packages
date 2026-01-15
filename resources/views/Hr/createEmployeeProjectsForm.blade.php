<?php
$accType = Auth::user()->acc_type;
//	if($accType == 'client'){
//		$m = $_GET['m'];
//	}else{
//		$m = Auth::user()->company_id;
//	}
$m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <span class="subHeadingLabelClass">Create Employee Projects Form</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'had/addEmployeeProjectsDetail?m='.$m.'','id'=>'EmployeeProject'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="companyId" value="<?php echo $m ?>">
                    <input type="hidden" name="EmployeeProjectSection[]" value="1">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Employee Project:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="project_name[]" id="project_name" value="" class="form-control requiredField" required/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="EmployeeProjectSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreEmployeeProjectSection" value="Add More Employee Project Section" />
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
                var employeeProject = new Array();
                var val;
                $("input[name='EmployeeProjectSection[]']").each(function(){
                    employeeProject.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employeeProject) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });


            var location = 1;
            $('.addMoreEmployeeProjectSection').click(function (e){
                e.preventDefault();
                location++;
                $('.EmployeeProjectSection').append('<div id="sectionEmployeeProject_'+location+'">' +
                    '<a href="#" onclick="removeEmployeeProjectSection('+location+')" class="btn btn-xs btn-danger">Remove</a>' +
                    '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                    '<div class="row">' +
                    '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    ' <label>Employee Project:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" name="project_name[] " id="project_name[] " value="" class="form-control requiredField" required/>' +
                    '</div></div></div></div></div>');

            });
        });

        function removeEmployeeProjectSection(id){
            var elem = document.getElementById('sectionEmployeeProject_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection