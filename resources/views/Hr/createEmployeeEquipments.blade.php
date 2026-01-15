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
                            <span class="subHeadingLabelClass">Create Employee Equipments Form</span>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'had/addEmployeeEquipmentDetail?m='.$m.'','id'=>'EmployeeEquipment'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="companyId" value="<?php echo $m ?>">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Equipment Name:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="equipment_name[]" id="equipment_name[]" value="" class="form-control requiredField" required />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="EmployeeEquipmentSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            <input type="button" class="btn btn-sm btn-primary addMoreEmployeeEquipmentsSection" value="Add More Employee Equipments" />
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            var category = 1;
            $('.addMoreEmployeeEquipmentsSection').click(function (e){
                e.preventDefault();
                category++;
                $('.EmployeeEquipmentSection').append('<div id="sectionEmployeeEquipment_'+category+'">' +
                    '<a href="#" onclick="removeEmployeeEquipmentSection('+category+')" class="btn btn-xs btn-danger">Remove</a>' +
                    '<div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">' +
                    '<div class="row">' +
                    '  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">' +
                    ' <label>Equipment Name:</label>' +
                    '<span class="rflabelsteric"><strong>*</strong></span>' +
                    '<input type="text" name="equipment_name[] " id="equipment_name[] " value="" class="form-control requiredField" required/>' +
                    '</div></div></div></div></div>');

            });


            // Wait for the DOM to be ready
            $(".btn-success").click(function(e){
                var employeeCategory = new Array();
                var val;
                $("input[name='EmployeeEquipmentSection[]']").each(function(){
                    employeeCategory.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of employeeCategory) {

                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                    }else{
                        return false;
                    }
                }

            });

        });

        function removeEmployeeEquipmentSection(id){
            var elem = document.getElementById('sectionEmployeeEquipment_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection