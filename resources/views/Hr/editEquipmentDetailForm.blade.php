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
                    <?php echo Form::open(array('url' => 'had/editEquipmentsDetail?m='.$m.'','id'=>'employeeEquipmentssDetailForm'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="recordId" id="recordId" value="<?php echo $employeeEquipment->id?>" class="form-control requiredField" />

                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeEquipmentsSection[]" class="form-control" id="employeeEquipmentsSection" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Equipment Name:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="equipment_name" id="equipment_name" value="<?php echo $employeeEquipment->equipment_name?>" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="employeeEquipmentsSection"></div>
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
        var employeeEquipments = new Array();
        var val;
        $("input[name='employeeEquipmentsSection[]']").each(function(){
            employeeEquipments.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val of employeeEquipments) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });
</script>