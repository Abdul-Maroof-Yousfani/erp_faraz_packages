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
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printGoodsReceiptNoteList','','1');?>
                        <?php echo CommonHelper::displayExportButton('goodsReceiptNoteList','','1')?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Purchase Order Status List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="date" name="FromDate" id="FromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>PO Status</label>
                                    <select name="PoStatus" id="PoStatus" class="form-control">
                                        <option value="">Select Po Status</option>
                                        <option value="1">Partial PO</option>
                                        <option value="2">Compelete PO</option>
                                        <option value="3">Reject PO</option>
                                    </select>
                                    <span id="PoStatusError"></span>
                                </div>

                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                                    <input type="button" value="Get Data" class="btn btn-sm btn-primary" onclick="get_data_ajax();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printGoodsReceiptNoteList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered sf-table-list" id="goodsReceiptNoteList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Po No</th>
                                                                <th class="text-center">Po Date</th>
                                                                
                                                                <th class="text-center">Supplier</th>
                                                                <th class="text-center">Item</th>
                                                                <th class="text-center">Location</th>
                                                                <th class="text-center">Order Qty</th>
                                                                <th class="text-center">Received Qty</th>
                                                                <th class="text-center">Remaining Qty</th>
                                                                </thead>
                                                                <tbody id="GetDataAjax"></tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Goods Receipt Note Voucher List'))!!} ">
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

        function get_data_ajax()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var PoStatus = $('#PoStatus').val();
            var m = '<?php echo $_GET['m']?>';
            if(PoStatus != "")
            {
                $('#GetDataAjax').html('<tr><td colspan="10"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
                $('#PoStatusError').html('');
                $.ajax({
                    url: '<?php echo url('/')?>/pdc/get_po_status_data',
                    type: 'Get',
                    data: {FromDate: FromDate,ToDate:ToDate,PoStatus:PoStatus,m:m},
                    success: function (response)
                    {
                        $('#GetDataAjax').html(response);
                    }
                });
            }
            else
            {
                $('#PoStatusError').html('<p class="text-danger">Please Select Status.</p>');
            }


        }



    </script>

    <script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection