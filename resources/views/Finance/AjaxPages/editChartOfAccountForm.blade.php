<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}


use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
?>



@include('select2')
<script>
    function	check_uncheck()
    {
        if ($("#first_level_chk").is(":checked"))
        {
            $('#account_id').fadeOut();
        }

        else
        {
            $('#account_id').fadeIn();
        }
    }
</script>
<div class="well">
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                    <!--
                      include('Finance.'.$accType.'financeMenu')
                            <!-->
                </div>

                <?php   ?>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <span class="subHeadingLabelClass">Add Chart of Account</span>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="row">
                                            <?php echo Form::open(array('url' => 'fad/editAccountDetail/'.$accounts_data->id.'?m='.$m.'','id'=>'chartofaccountForm'));?>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <input type="hidden" name="chartofaccountSection[]" class="form-control" id="chartofaccountSection" value="1" />
                                            </div>
                                            <div class="form-group">

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                                    <label>
                                                        <input @if($accounts_data->operational==1)checked @endif type="checkbox" name="operational" value="1" />  <b>Operational</b>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label>Parent Account Head:</label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <select  style="width: 100%" onchange="get_nature_type()" class="form-control select2" name="account_id" id="account_id">
                                                    <option value="">Select Account</option>
                                                    @foreach(CommonHelper::get_all_account_level_wise() as $key => $y)
                                                        <option @if($accounts_data->parent_code==$y->code)selected @endif value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                                    @endforeach
                                                </select>

                                                <input type="hidden" name="parent_code" value="{{$accounts_data->parent_code}}"/>
                                            </div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <label for="acc_name">New Account </label>
                                                <span class="rflabelsteric"><strong>*</strong></span>
                                                <input type="text" autofocus="autofocus" placeholder="New Account"
                                                       class="form-control requiredField"  name="acc_name" id="acc_name" value="{{ucwords($accounts_data->name)}}" autocomplete="off" >
                                            </div>

                                            <?php
                                            $trans=FinanceHelper::get_opening_bal($accounts_data->id);
                                            $trans=explode(',',$trans);

                                            ?>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="row">
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="o_blnc" >Opening Balance </label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <input type="any"  name="o_blnc" maxlength="15" min="0" id="o_blnc" placeholder="Opening Balance" class="form-control requiredField"
                                                               value="{{$trans[0]}}" autocomplete="off"/>
                                                    </div>

                                                    <?php ?>
                                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                        <label for="o_blnc_trans">Transaction </label>
                                                        <span class="rflabelsteric"><strong>*</strong></span>
                                                        <select name="o_blnc_trans" id="o_blnc_trans" class="form-control requiredField">
                                                            <option value="">select</option>
                                                            <option @if($trans[1]==1) selected @endif value="1"><strong>Debit</strong></option>
                                                            <option @if($trans[1]==0) selected @endif value="0"><strong>Credit</strong></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div>&nbsp;</div>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                            </div>
                                            <?php
                                            echo Form::close();
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {





        $(".btn-success").click(function(e){
            var chartofAccount = new Array();
            var val;
            $("input[name='chartofaccountSection[]']").each(function(){
                chartofAccount.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of chartofAccount) {

                jqueryValidationCustom();
                if(validate == 0){
                    //return false;
                }else{
                    return false;
                }
            }
        });
    });


</script>
<script type="text/javascript">

    $('#account_id').select2();
</script>

<script>
    function get_nature_type()
    {
        var nature=  $("#account_id option:selected").text();
        nature=nature.split('-');
        nature=nature[0];
        if (nature==1 ||  nature==4)
        {
            $('#o_blnc_trans').val(1);
        }

        else
        {
            $('#o_blnc_trans').val(0);
        }
    }
</script>
