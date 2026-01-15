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
                    <?php echo Form::open(array('url' => 'had/editEmployeeRegionsDetail?m='.$m.'','id'=>'editEmployeeRegionsDetail'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="recordId" id="recordId" value="<?php echo $employeeRegionsDetail->id?>" class="form-control requiredField" />
                        <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeRegionSection[]" class="form-control" id="employeeRegionSection" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Employee Location:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="employee_region" id="employee_region" value="<?php echo $employeeRegionsDetail->employee_region?>" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="employeeRegionSection"></div>
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
        var employeeRegion = new Array();
        var val;
        $("input[name='employeeRegionSection[]']").each(function(){
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
</script>