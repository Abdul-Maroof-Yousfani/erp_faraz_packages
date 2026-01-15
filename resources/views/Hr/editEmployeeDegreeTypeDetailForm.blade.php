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
                    <?php echo Form::open(array('url' => 'had/editEmployeeDegreeTypeDetail?m='.$m.'','id'=>'editEmployeeDegreeTypeDetail'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="recordId" id="recordId" value="<?php echo $employeeDegreeTypeDetail->id?>" class="form-control requiredField" />

                        <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <input type="hidden" name="employeeDegreeTypeSection[]" class="form-control" id="employeeDegreeTypeSection" value="1" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Employee Degree Type Detail:</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="degree_type_name" id="degree_type_name" value="<?php echo $employeeDegreeTypeDetail->degree_type_name?>" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="employeeDegreeTypeSection"></div>
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
        var employeeDegreeType = new Array();
        var val;
        $("input[name='employeeDegreeTypeSection[]']").each(function(){
            employeeDegreeType.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val of employeeDegreeType) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });
</script>