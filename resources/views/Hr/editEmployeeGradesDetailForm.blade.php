<?php
$currentDate = date('Y-m-d');
$id = $_GET['id'];
$m 	= $_GET['m'];

?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <?php echo Form::open(array('url' => 'had/editEmployeeGradesDetail?m='.$m.'','id'=>'employeeGradesDetailForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="recordId" id="recordId" value="<?php echo $employeeGradesDetail->id?>" class="form-control requiredField" />

                        <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeGradesSection[]" class="form-control" id="employeeGradesSection" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Category:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" name="category" id="category">
                                        <option value="">Select Category</option>
                                        <option @if($employeeGradesDetail->category == 'Executives') selected @endif value="Executives">Executives</option>
                                        <option @if($employeeGradesDetail->category == 'Engineering') selected @endif value="Engineering">Engineering</option>
                                        <option @if($employeeGradesDetail->category == 'Clearing') selected @endif value="Clearing">Clearing</option>
                                    </select>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label>Grade Type:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="employee_grade_type" id="employee_grade_type" value="{{ $employeeGradesDetail->employee_grade_type }}" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="employeeGradesSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(".btn-success").click(function(e){
        var employeeGrades = new Array();
        var val;
        $("input[name='employeeGradesSection[]']").each(function(){
            employeeGrades.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val of employeeGrades) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });
</script>