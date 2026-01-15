<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(245);
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Purchase Detail Report</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayExportButton('expToExcel','','1')?>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="FromDate" id="FromDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $AccYearFrom;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="ToDate" id="ToDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $AccYearTo;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Voucher Type</label>
                                    <select name="VoucherType" id="VoucherType" class="form-control">
                                        <option value="">Select Type</option>
                                        <option value="1">Purchase Invoice</option>
                                        <option value="2">Purchase Return</option>
                                        <option value="3">Sales Tax Invoice</option>
                                        <option value="4">Sales Return</option>
                                        <option value="5">Opening</option>
                                    </select>
                                    <span id="VoucherTypeError"></span>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Data Filter" class="btn btn-sm btn-danger" onclick="GetPurchaseReport();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>

                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <h5 style="text-align: center" id="h3"></h5>
                                                            <table class="table table-bordered sf-table-list" id="expToExcel">
                                                                <thead>

                                                                <th class="text-center">SR.NO </th>
                                                                <th class="text-center">Voucher NO </th>
                                                                <th class="text-center">Voucher Date </th>
                                                                <th class="text-center">ITEM NAME </th>
                                                                <th class="text-center">Qty</th>
                                                                <th class="text-center">Amount</th>
                                                                <th class="text-center">Voucher Amount</th>
                                                                <th class="text-center">Running Amount</th>
                                                                </thead>
                                                                <tbody id="data">

                                                                </tbody>
                                                            </table>
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
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2').select2();
        });

        function GetPurchaseReport()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var VoucherType = $('#VoucherType').val();
            var m = '<?php echo $m?>';
            if(VoucherType !="")
            {
                $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

                $.ajax({
                    url: '/store/getDataScReportAjax',
                    type: 'Get',
                    data: {FromDate:FromDate,ToDate:ToDate,VoucherType: VoucherType,m:m},

                    success: function (response) {
                        $('#data').html(response);
                    }
                });
            }
            else
            {
                $('#VoucherTypeError').html('<p style="color:#265a88"> Select Type.</p>');
            }
        }
    </script>
@endsection
