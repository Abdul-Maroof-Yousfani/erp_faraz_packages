<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
?>
<div class="well">
    <div class="row">
        <?php echo Form::open(array('url' => 'had/editAllowanceDetail','id'=>'employeeForm'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="<?=$m?>">
            <input type="hidden" name="allowanceId" class="form-control" id="allowanceId" value="<?= $allowance->id;?>" />
            <input type="hidden" name="employeeSection[]" id="employeeSection" value="1" />
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Allowance Type:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField allowance_type" name="allowance_type" id="allowance_type">
                                    <option value="">Select Allowance Type</option>
                                    @foreach($allowance_type as $key => $y)
                                        <option @if($allowance->allowance_type == $y->id) selected @endif value="{{ $y->id}}">{{ $y->allowance_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <label class="sf-label">Allowance Amount:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="allowance_amount" id="allowance_amount" value="<?= $allowance->allowance_amount; ?>" class="form-control requiredField" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                    </div>
                </div>
            </div>
        <?php echo Form::close();?>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Wait for the DOM to be ready
        $(".btn-success").click(function(e){
            var employee = new Array();
            var val;
            $("input[name='employeeSection[]']").each(function(){
                employee.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of employee) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });

    });
</script>
