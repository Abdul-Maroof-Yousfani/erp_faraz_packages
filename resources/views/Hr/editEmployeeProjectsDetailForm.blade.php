<?php
$currentDate = date('Y-m-d');
$m 	= $_GET['m'];
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <?php echo Form::open(array('url' => 'had/editEmployeeProjectsDetail?m='.$m.'','id'=>'employeeProjectsDetailForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="recordId" value="<?php echo $employee_projects->id?>">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeProjectsSection[]" class="form-control" id="employeeProjectsSection" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Employee Project:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="project_name" id="project_name" value="<?php echo $employee_projects->project_name?>" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="employeeLocationsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
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
        var employeeProjects = new Array();
        var val;
        $("input[name='employeeProjectsSection[]']").each(function(){
            employeeProjects.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val of employeeProjects) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });
</script>