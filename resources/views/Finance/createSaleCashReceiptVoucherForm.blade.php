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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Sale Cash Receipt Voucher</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <?php echo Form::open(array('url' => 'fad/addSaleCashReceiptVoucherDetail?m='.$m.'','id'=>'addSaleCashReceiptVoucherDetail'));?>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Invoice No & Date</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="inv_no" id="inv_no" onchange="loadSaleCashReceiptVoucherDetailByInvoiceNo()">
                                                        <option value="">Select Invoice No & Date</option>
                                                        <?php foreach($Invoices as $row){?>
                                                        <option value="<?php echo $row->inv_no.'*'.$row->inv_date?>"><?php echo 'Invoice No =>&nbsp;&nbsp;&nbsp;'.$row->inv_no.'&nbsp;, Invoice Date =>&nbsp;&nbsp;&nbsp;'.CommonHelper::changeDateFormat($row->inv_date)?></option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="loadSaleCashReceiptVoucherDetailSection"></div>
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
        function loadSaleCashReceiptVoucherDetailByInvoiceNo(){
            var invNo = $('#inv_no').val();
            var m = '<?php echo $_GET['m']?>';
            if(invNo == ''){
                alert('Please Select Invoice No');
                $('.loadSaleCashReceiptVoucherDetailSection').html('');
            }else{
                $('.loadSaleCashReceiptVoucherDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/loadSaleCashReceiptVoucherDetailByInvoiceNo',
                    type: "GET",
                    data: { invNo:invNo,m:m},
                    success:function(data) {
                        $('.loadSaleCashReceiptVoucherDetailSection').html(data);
                    }
                });
            }
        }
        $(document).ready(function() {
            $(".btn-success").click(function(e){
                var seletedInvoiceRow = new Array();
                var val;
                $("input[name='seletedInvoiceRow[]']").each(function(){
                    seletedInvoiceRow.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of seletedInvoiceRow) {
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