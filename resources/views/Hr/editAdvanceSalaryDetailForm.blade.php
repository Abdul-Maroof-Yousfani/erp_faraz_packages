<?php

$m = Input::get('m');
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
?>
<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
</style>
<div class="well">
    <div class="row">
        <?php echo Form::open(array('url' => 'had/editAdvanceSalaryDetail','id'=>'employeeForm'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?=$m?>">
        <input type="hidden" name="id" value="{{ $advance_salary->id }}">
        <input type="hidden" name="employeeSection[]" class="form-control" id="employeeSection" value="1" />
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Amount Needed</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="number" class="form-control requiredField" name="advance_salary_amount" id="advance_salary_amount" value="{{ $advance_salary->advance_salary_amount }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Advance Salary to be Needed On</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="date" class="form-control requiredField" name="salary_needed_date" id="salary_needed_date" value="{{ $advance_salary->salary_needed_on }}" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label class="sf-label">Deduction Month & Year</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="month" class="form-control requiredField" name="deduction_month_year" id="deduction_month_year" value="{{ date($advance_salary->deduction_year.'-'.sprintf('%02d',$advance_salary->deduction_month)) }}" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label class="sf-label">Account head</label>
                            <span class="rflabelsteric"><strong>*</strong></span><br>
                            <input @if($advance_salary->account_head_id == 1) checked @endif type="radio" name="account_head_id" id="cash" value="1" onclick="accountCheck(this.value)"> <label for="cash">Cash</label> &nbsp;
                            <input @if($advance_salary->account_head_id == 2) checked @endif type="radio" name="account_head_id" id="bank" value="2" onclick="accountCheck(this.value)"> <label for="bank">Bank</label> &nbsp;
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Accounts</label>
                            <select disabled name="account_id" id="account_id" class="form-control requiredField">
                                <option value="">Select Account</option>
                                @foreach($account as $key => $val)
                                    <option @if($advance_salary->account_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                            <label class="sf-label">Reason (Detail)</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <textarea name="advance_salary_detail" class="form-control requiredField">{{ $advance_salary->detail }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="employeeSection"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                </div>
            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>

<script>
    $(function(){
        var radio_value = $('input[name="account_head_id"]:checked').val();
        if(radio_value == 1) {
            $('#account_id').removeClass('requiredField');
            $('#account_id').prop("disabled", true);
            $('#account_id').val('');
        }else {
            $('#account_id').addClass('requiredField');
            $('#account_id').prop("disabled", false);
        }
    });

    function accountCheck(value)
    {
        if(value == 1){
            $('#account_id').removeClass('requiredField');
            $('#account_id').prop("disabled", true);
            $('#account_id').val('');
        }else if(value == 2) {
            $('#account_id').addClass('requiredField');
            $('#account_id').prop("disabled", false);
        }
    }
</script>
