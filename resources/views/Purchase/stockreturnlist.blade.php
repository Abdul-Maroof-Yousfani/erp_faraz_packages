<?php

use App\Helpers\CommonHelper;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printIssuanceVoucherList','','1');?>
                        <?php echo CommonHelper::displayExportButton('issuanceVoucherList','','1')?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Stock Return List</span>
                                </div>
                            </div>
                            <div class="row">

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label for="">From Date</label>
                                    <input type="date" name="FromDate" id="FromDate" class="form-control" value="<?php echo date('Y-m-d')?>">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label for="">To Date</label>
                                    <input type="date" name="ToDate" id="ToDate" class="form-control" value="<?php echo date('Y-m-d')?>">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label for="">Return Type</label>
                                    <select name="IssuanceType" id="IssuanceType" class="form-control">
                                        <option value="all">All</option>
                                        <option value="1">Return With Job Order</option>
                                        {{--<option value="2">Return Challan</option>--}}
                                        <option value="3">Return Without Job Order</option>
                                        <option value="4">Return Damage Stock</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <button type="button" class="btn btn-sm btn-success" id="BtnShow" onclick="issuanceDataFilter();">Show</button>
                                </div>

                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div id="printDemandVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data">
                                                        <div class="table-responsive" >
                                                            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">ISSUANCE NO.</th>
                                                                <th class="text-center">ISSUANCE Date</th>
                                                                <th class="text-center">ISSUANCE Type</th>
                                                                <th class="text-center">DESCRIPTION</th>
                                                                <th class="text-center">Job Order</th>
                                                                <th class="text-center hidden-print">Action</th>
                                                                </thead>

                                                                <tbody id="ShowHide">
                                                                <tr id="Loader"></tr>

                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Demand Voucher List'))!!} ">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
    <script>

        function issuanceDataFilter()
        {

            var FromDate= $('#FromDate').val();
            var ToDate= $('#ToDate').val();
            var IssuanceType = $('#IssuanceType').val();

            var m = '<?php echo $m?>';
//            if(ClientId !="" || RegionId !="")
//            {
            $('#ShowHide').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/pdc/stockReturnDataFilter',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,IssuanceType:IssuanceType,m:m},

                success: function (response) {

                    $('#ShowHide').html(response);
                }
            });
//            }
//            else{
//                $('#FilterError').html('<p class="text-danger">Please Select Client OR Region...!</p>');
//            }



        }
    </script>
@endsection