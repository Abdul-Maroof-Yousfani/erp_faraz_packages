<?php
$currentDate = date('Y-m-d');
$m = Input::get('m');
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <?php echo Form::open(array('url' => 'had/editAllowanceTypeDetail' ));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
                    <input type="hidden" name="id" id="id" value="{{ $allowance_type->id }}" />
                    <input type="hidden" name="company_id" id="company_id" value="{{ $m }}" />
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label>Allowance Type </label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <input type="text" name="allowance_type" id="allowance_type" value="{{ $allowance_type->allowance_type }}" class="form-control requiredField" />
                                </div>
                            </div>
                        </div>
                    </div>
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
        var loanType = new Array();
        var val;
        $("input[name='employeeSection[]']").each(function(){
            loanType.push($(this).val());
        });
        var _token = $("input[name='_token']").val();
        for (val of loanType) {

            jqueryValidationCustom();
            if(validate == 0){
                //alert(response);
            }else{
                return false;
            }
        }

    });
</script>