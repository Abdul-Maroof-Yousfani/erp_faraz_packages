<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Purchase Bank Payment Voucher</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'fad/addPurchaseBankPaymentVoucherDetail?m='.$m.'','id'=>'addPurchaseBankPaymentVoucherDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Goods Receipt Note No & Date</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="grn_no" id="grn_no" onchange="loadPurchaseBankPaymentVoucherDetailByGRNNo()">
                                                        <option value="">Select Goods Receipt Note No & Date</option>
                                                        <?php foreach($GRNDatas as $row){?>
                                                        <option value="<?php echo $row->grn_no.'*'.$row->grn_date?>"><?php echo 'GRN No =>&nbsp;&nbsp;&nbsp;'.$row->grn_no.'&nbsp;, GRN Date =>&nbsp;&nbsp;&nbsp;'.CommonHelper::changeDateFormat($row->grn_date)?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="loadPurchaseBankPaymentVoucherDetailSection"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                    </div>
                                </div>
                                <?php echo Form::close();?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function loadPurchaseBankPaymentVoucherDetailByGRNNo(){
            var grnNo = $('#grn_no').val();
            var m = '<?php echo $_GET['m']?>';
            if(grnNo == ''){
                alert('Please Select Goods Receipt Note No');
                $('.loadPurchaseBankPaymentVoucherDetailSection').html('');
            }else{
                $('.loadPurchaseBankPaymentVoucherDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/loadPurchaseBankPaymentVoucherDetailByGRNNo',
                    type: "GET",
                    data: { grnNo:grnNo,m:m},
                    success:function(data) {
                        $('.loadPurchaseBankPaymentVoucherDetailSection').html(data);
                    }
                });
            }
        }
        $(document).ready(function() {
            $(".btn-success").click(function(e){
                var seletedGoodsReceiptNoteRow = new Array();
                var val;
                $("input[name='seletedGoodsReceiptNoteRow[]']").each(function(){
                    seletedGoodsReceiptNoteRow.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of seletedGoodsReceiptNoteRow) {
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
@endsection