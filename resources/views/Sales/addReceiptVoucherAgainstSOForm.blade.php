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
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <span class="subHeadingLabelClass">Create Receipt Voucher against SO</span>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <?php echo Form::open(array('url' => 'sad/addSaleReceiptVoucherDetailAgainstSQ?m='.$m.'','id'=>'addSaleReceiptVoucherDetailAgainstSQ','files'=>'true'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
            <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <label class="sf-label">Sales order No & Date & Customer Name</label>
                                <span class="rflabelsteric"><strong>*</strong></span>
                                <select class="form-control requiredField" required name="sqDetail" id="sqDetail" onchange="loadSaleReceiptVoucherDetailBySONo()">
                                    <option value="">Select Sales order No & Date & Customer Name</option>
                                    <?php foreach($salesQuotationListApprovedAndNotReceived as $row){?>
                                    <option value="<?php echo $row->so_no.'*'.$row->so_date.'*'.$row->id?>">
                                        <?php echo 'So No =>&nbsp;&nbsp;&nbsp;'.$row->so_no.'&nbsp;, SO Date =>&nbsp;&nbsp;&nbsp;'.CommonHelper::changeDateFormat($row->so_date).', Customer Name =>'.$row->name ?>
                                    </option>
                                    <?php }?>
                                </select>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="loadSaleReceiptVoucherDetailSection"></div>
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
    <script>
        $(function () {
            $("select").select2();
        });
        function loadSaleReceiptVoucherDetailBySONo()
        {
            var sqDetail = $('#sqDetail').val();
            var m = '<?php echo $_GET['m']?>';
            if(sqDetail == '')
            {
                alert('Please Select Purchase Order No');
                $('.loadSaleReceiptVoucherDetailSection').html('');
            }
            else
            {
                $('.loadSaleReceiptVoucherDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/smfal/loadSaleReceiptVoucherDetailBySONo',
                    type: "GET",
                    data: { 
                            sqDetail:sqDetail,
                            m:m
                          },
                    success:function(data) 
                    {
                        $('.loadSaleReceiptVoucherDetailSection').html(data);
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