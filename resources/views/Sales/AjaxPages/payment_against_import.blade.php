<?php   use App\Helpers\CommonHelper;


        $PvDate = date('Y-m-d');
        $ChequeNo = '';
        $ChequeDate = date('Y-m-d');
        $F_currency = 0;
        $currency_rate = 0 ;
        $AmounPkr = 0;
        $CrAccount = '';
        $Desc = '';
$duty = 0; //$DutyCounter = 0;
$eto = 0; //$EtoCounter = 0;
$do = 0; //$DoCounter = 0;
$appraisal = 0; //$AppraisalCounter = 0;
$fright = 0; //$FrightCounter = 0;
$insurance = 0; //$InsuranceCounter = 0;
$expense = 0; //$ExpenseCounter = 0;
$other_expense = 0; //$OtherExpenseCounter = 0;
$AllExpTot = 0;
$payment_type=0;
if($payId > 0 && $type == 1)
{
    $PayData = DB::Connection('mysql2')->table('import_payment')->where('id',$payId)->first();
    $PvDate = $PayData->pv_date;
    $ChequeNo = $PayData->cheque_no;
    $ChequeDate = $PayData->cheque_date;
    $F_currency = $PayData->foreign_amount;
    $currency_rate = $PayData->cureency_rate;
    $AmounPkr = $PayData->amount_in_pkr;
    $CrAccount = $PayData->cr_account;
    $Desc = $PayData->des;
    $payment_type=$PayData->payment_type;
}

if($payId > 0 && $type == 2)
{
    $ExpData = DB::Connection('mysql2')->table('import_expense')->where('id',$payId)->first();
    $PvDate = $ExpData->pv_date;
    $ChequeNo = $ExpData->cheque_no;
    $ChequeDate = $ExpData->cheque_date;
    $CrAccount = $ExpData->cr_account;
    $payment_type=$ExpData->payment_type;


    $duty = $ExpData->duty;
    $eto = $ExpData->eto;
    $do = $ExpData->do;
    $appraisal = $ExpData->appraisal;
    $fright = $ExpData->fright;
    $insurance = $ExpData->insurance;
    $expense = $ExpData->expense;
    $other_expense = $ExpData->other_expense;
    $AllExpTot = $duty+$eto+$do+$appraisal+$fright+$insurance+$expense+$other_expense;

}

?>

<div class="row">

    <?php
        if($payId > 0 && $type == 1):
            echo Form::open(array('url' => 'sad/update_import_po','id'=>'bankPaymentVoucherForm'));
        ?>
        <input type="hidden" id="PaymentEditId" name="PaymentEditId" value="<?php echo $payId?>">
        <?php
        elseif($payId > 0 && $type == 2):
        echo Form::open(array('url' => 'sad/update_import_exp','id'=>'bankPaymentVoucherForm'));
        ?>
        <input type="hidden" id="ExpenseEditId" name="ExpenseEditId" value="<?php echo $payId?>">
        <?php
        else:
            echo Form::open(array('url' => 'sad/add_import_po','id'=>'bankPaymentVoucherForm'));
        endif;
    ?>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="panel">
            <div class="panel-body">



                    <div class="row">

                    <input type="hidden" name="type" id="type" value="{{$type}}" />
                    <input  checked  type="hidden" class="" value="1" name="payment_mod"  />



                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <label class="sf-label">PV Date.</label>
                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                        <input readonly autofocus onblur="" onchange=""  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date_1" id="pv_date_1" value="<?php echo $PvDate ?>" />
                    </div>


                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <label class="sf-label">Credit Acc.</label>
                            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                            <select  name="cr_account" class="form-control required" id="cr_account">
                                <option value="">Select</option>
                                <?php foreach(CommonHelper::get_all_account_operat() as $row): ?>
                                    <option value="<?php echo $row->id ?>" <?php if($CrAccount == $row->id):echo "selected"; endif;?>><?php echo $row->name ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label class="sf-label">Payment Type</label>
                            <select @if($payment_type!=0) readonly @endif id="payment" name="payment_typee"  onchange="payment_type()" class="form-control">
                                <option @if($payment_type==1) selected @endif value="1">Bank</option>
                                <option @if($payment_type==2) selected @endif value="2">Cash</option>

                            </select>
                        </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank">
                        <label class="sf-label">Cheque No.</label>
                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                        <input  type="text" class="form-control required bank_input" placeholder="Cheque No" name="cheque_no_1" id="cheque_no_1" value="<?php echo $ChequeNo?>" />
                    </div>

                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank">
                        <label class="sf-label">Cheque Date.</label>
                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                        <input  type="date" class="form-control required zero bank_input" max="<?php echo date('Y-m-d') ?>" name="cheque_date_1" id="cheque_date_1" value="{{$ChequeDate}}" />
                    </div>

                </div>

                <div class="row">




                </div>


                @if ($type==1)

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Amount In Foreign Currency</label>

                        <input onkeyup="cal()" onblur="cal()" step="any" type="number" class="form-control required zero"  name="f_currency" id="f_currency" value="<?php echo $F_currency?>" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Currency Rate</label>
                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                        <input  readonly type="text" class="form-control required zero" placeholder="" name="rate" id="rate" value="<?php echo $currency_rate?>" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Amount In Pkr</label>
                        <span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
                        <input onkeyup="cal()" onblur="cal()"  type="number" class="form-control required zero" max="<?php echo date('Y-m-d') ?>" name="amount_pkr" id="amount_pkr" value="<?php echo $AmounPkr?>" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label class="sf-label">Description</label>
                        <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
                        <textarea  name="description_1"  id="description_1" style="resize:none;" class="form-control required"><?php echo $Desc?></textarea>
                    </div>

                </div>


                @else

                <div class="row">

                    <input type="hidden" name="type" id="type" value="{{$type}}" />
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Duty.</label>
                        <input value="<?php echo $duty?>" autofocus onblur="" onchange=""  type="number" class="form-control text-right LoopVal"  name="duty" id="duty" step="any" onkeyup="CalcExp()" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">ETO.</label>
                        <input type="number" class="form-control text-right LoopVal"  name="eto" id="eto" value="<?php echo $eto?>" step="any" onkeyup="CalcExp()"/>
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">DO.</label>
                        <input  type="number" class="form-control text-right LoopVal" placeholder="" name="do" id="do" value="<?php echo $do?>" step="any" onkeyup="CalcExp()" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Appraisal.</label>
                        <input  type="number" class="form-control required text-right LoopVal"  name="appraisal" id="appraisal" value="<?php echo $appraisal?>" step="any" onkeyup="CalcExp()" />
                    </div>

                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Fright.</label>
                        <input  type="number" class="form-control text-right LoopVal"  name="fright" id="fright" value="<?php echo $fright?>" step="any" onkeyup="CalcExp()" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Insurance.</label>
                        <input  type="number" class="form-control text-right LoopVal"  name="insurance" id="insurance" value="<?php echo $insurance?>" step="any" onkeyup="CalcExp()" />
                    </div>

                    <input  checked  type="hidden" class="" value="1" name="payment_mod" value="0" step="any" onkeyup="CalcExp()" />

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Expense.</label>
                        <input  type="number" class="form-control text-right LoopVal"  name="expense" id="expense" value="<?php echo $expense?>" step="any" onkeyup="CalcExp()" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label for="">Other Expense.</label>
                        <input  type="number" class="form-control text-right LoopVal"  name="other_expense" id="other_expense" value="<?php echo $other_expense?>" step="any" onkeyup="CalcExp()" />
                    </div>

                </div>


                <div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div></div>
                <div class="row">
                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                        <pre class="sf-label text-center" style="padding: 6.5px !important;"><strong style="font-size: 20px;">TOTAL ALL EXPENSE</strong></pre>
                    </div>
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                        <pre class="sf-label" style="padding: 6.5px !important; font-size: 20px;" id="TotalAllExpenses"><?php echo $AllExpTot?></pre>
                    </div>
                </div>

                @endif
                <input type="hidden" value="{{$voucher_no}}" name="voucher_no">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

                    </div>
                </div>
                    <input type="hidden" name="grand_total" class="grand_total"/>


                </div>
            </div>
        </div>
        <?php Form::close(); ?>
    </div>


<script>
    $(document).ready(function(){
        CalcExp();
        payment_type();
    });
    $('#bankPaymentVoucherForm').submit(function(e)
    {
        
        e.preventDefault();
        var validate=1;
        var me=$(this);

        $(".required").each(function() {
            if ($(this).val()=='')
            {

                $('#'+this.id).css('border-color', 'red');
                validate=0;
            }

        });




        $(".zero").each(function() {
            if (parseFloat($(this).val())==0 || $(this).val()=='')
            {
                $('#'+this.id).css('border-color', 'red');
                validate=0;
                return false;
            }
        });



        if (validate==0)
        {

            e.preventDefault();
            return false;
        }


        $.ajax({
            url:me.attr('action'),
            data:me.serialize(),
            type:'post',

            success: function(response)
            {
                alert('submit');
                $('#pay').html('');
                get_data();
            }
        });
    });

    function cal()
    {
        var curen=parseFloat($('#f_currency').val());

        var amount=0;
        $(".foreign").each(function() {

               amount+=+ parseFloat($('#'+this.id).val());

        });
        amount=curen+amount;
      //  console.log(amount);

        var actual=parseFloat($('#tot_amount').val());
        if (amount>actual)
        {
            alert('amount can not exceed '+actual);
            $('#f_currency').val(0);
            return false;
        }

        var pkr=parseFloat($('#amount_pkr').val());
        var total=(pkr / curen).toFixed(2);
        if(isNaN(total) || total == Number.POSITIVE_INFINITY || total == Number.NEGATIVE_INFINITY){
            total = 0;
        }
        $('#rate').val(total);
    }

    function CalcExp()
    {
        var TotalExp = 0;

        $('.LoopVal').each(function() {
            TotalExp += Number($(this).val());
        });
        $('#TotalAllExpenses').html(TotalExp.toFixed(2));
    }

    function payment_type()
    {
      var payment=  $('#payment').val();

        if (payment==2)
        {
            $('.bank').css('display','none');
            $(".bank_input").removeClass("required");
        }
        else
        {
            $('.bank').css('display','block');
            $(".bank_input").addClass("required");
        }
    }


</script>
