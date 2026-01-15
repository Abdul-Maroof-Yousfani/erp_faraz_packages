<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>

<div class="lineHeight"></div>
<div class="well">
    <div class="row">
        <?php echo Form::open(array('url' => 'had/editLoanRequestDetail'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?= Input::get('m') ?>">
        <input type="hidden" name="loanRequestId" value="<?= Input::get('id') ?>">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="get_clone">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Needed on Month & Year:</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="month" name="needed_on_date" id="needed_on_date" value="{{ $loanRequest->year.'-'.$loanRequest->month }}" class="form-control requiredField count_rows" required />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Loan Type</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select name="loan_type_id" class="form-control requiredField" id="loan_type_id">
                                    <option value="">Select</option>
                                    @foreach($loanTypes as $laonTypeValue)
                                        <option @if($loanRequest->loan_type_id == $laonTypeValue->id) selected @endif value="{{ $laonTypeValue->id}}">{{ $laonTypeValue->loan_type_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Loan Amount</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="loan_amount" id="loan_amount" value="{{ $loanRequest->loan_amount }}" class="form-control requiredField count_rows" required />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Per Month Deduction</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <input type="number" name="per_month_deduction" id="per_month_deduction" value="{{ $loanRequest->per_month_deduction }}" class="form-control requiredField count_rows" required />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label class="sf-label">Account head</label>
                                <span class="rflabelsteric"><strong>*</strong></span><br>
                                <input @if($loanRequest->account_head_id == 1) checked @endif type="radio" name="account_head_id" id="cash" value="1" onclick="accountCheck(this.value)"> <label for="cash">Cash</label> &nbsp;
                                <input @if($loanRequest->account_head_id == 2) checked @endif type="radio" name="account_head_id" id="bank" value="2" onclick="accountCheck(this.value)"> <label for="bank">Bank</label> &nbsp;
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Accounts</label>
                                <select disabled name="account_id" id="account_id" class="form-control requiredField">
                                    <option value="">Select Account</option>
                                    @foreach($account as $key => $val)
                                        <option @if($loanRequest->account_id == $val->id) selected @endif value="{{ $val->id }}">{{ $val->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
                                <label class="sf-label">Loan Description</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <textarea required name="loan_description" class="form-control" id="contents">{{ $loanRequest->description }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
               </div>
        </div>
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