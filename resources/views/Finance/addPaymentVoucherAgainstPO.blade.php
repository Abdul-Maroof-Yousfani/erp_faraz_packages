<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else if($accType == 'superadmin'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('number_formate')
	@include('select2')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <span class="subHeadingLabelClass">Create Purchase Payment Voucher</span>
                </div>
            </div>
            <hr>
            <div class="lineHeight">&nbsp;</div>
            <div class="row">
                <?php echo Form::open(array('url' => 'fad/addPurchasePaymentVoucherDetail?m='.$m.'','id'=>'addPurchasePaymentVoucherDetail','files'=>'true'));?>
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="sf-label">Purchase Order No & Date</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <select class="form-control requiredField" required name="poDetail" id="poDetail" onchange="loadPurchaseBankPaymentVoucherDetailByPONo()">
                                        <option value="">Select Purchase Order No & Date</option>
                                        <?php foreach($getApprovedPOList as $row){?>
                                        <option value="<?php echo $row->purchase_request_no.'*'.$row->purchase_request_date.'*'.$row->id?>"><?php echo 'PO No =>&nbsp;&nbsp;&nbsp;'.$row->purchase_request_no.'&nbsp;, PO Date =>&nbsp;&nbsp;&nbsp;'.CommonHelper::changeDateFormat($row->purchase_request_date).''?></option>
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
                        {{ Form::submit('Submit', ['class' => 'btn btn-success btnSubmit']) }}
                        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>
    <script>
        $(function () {
            $("select").select2();
        });
        function loadPurchaseBankPaymentVoucherDetailByPONo(){
            var poDetail = $('#poDetail').val();
            var m = '<?php echo $_GET['m']?>';
            if(poDetail == ''){
                alert('Please Select Purchase Order No');
                $('.loadPurchaseBankPaymentVoucherDetailSection').html('');
            }else{
                $('.loadPurchaseBankPaymentVoucherDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/loadPurchaseBankPaymentVoucherDetailByPONo',
                    type: "GET",
                    data: { poDetail:poDetail,m:m},
                    success:function(data) {
                        $('.loadPurchaseBankPaymentVoucherDetailSection').html(data);
                        //disableInputFormDateAccountYear();
                    }
                });
            }
        }
        
        $(".btn-success").click(function(e){
            var rvs = new Array();
            var val;
            //$("input[name='pvsSection[]']").each(function(){
                rvs.push($(this).val());
            //});
            var _token = $("input[name='_token']").val();
            for (val of rvs) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });

    </script>
@endsection