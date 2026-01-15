<?php
use App\Helpers\CommonHelper;
        $m = $_GET['m'];
?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="cl-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="cl-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">Detail Report</span>
                        </div>
                        <div class="cl-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('printGoodsReceiptNoteList','','1');?>
                            <?php echo CommonHelper::displayExportButton('goodsReceiptNoteList','','1')?>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>From
                                        <input class="form-control" type="date" id="from" value=""/></label>
                                    </div>


                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>To
                                        <input class="form-control" type="date" id="to" value=""/></label>
                                    </div>


                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Grn</label>
                                        <select name="VoucherType" id="VoucherType"  class="form-control">
                                            <option value="1">Grn (IN)</option>
                                            <option value="6">Sales Return (IN)</option>
                                            <option value="work_order_in">Work Order (IN)</option>
                                            <option value="work_order_issuence">Work Order issuence (OUT)</option>


                                            <option value="2">Purchae Return</option>
                                            <option value="3">Stock Transfer</option>
                                            <option value="4">Stock Received</option>
                                            <option value="5">Sales</option>

                                        </select>
                                    </div>


                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <button type="button" class="btn btn-sm btn-primary" id="BtnGetData" onclick="get_data_ajax()">Get Data</button>
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

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
                                                                <th class="text-center">Voucher No</th>
                                                                <th class="text-center">Voucher Date</th>
                                                                <th class="text-center">Amount</th>

                                                                </thead>
                                                                <tbody id="GetDataAjax">
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
    </div>

    <script>
    $(document).ready(function(){
        $('#VoucherType').select2();
    });
        function get_data_ajax()
        {
//            var FromDate = $('#FromDate').val();
//            var ToDate = $('#ToDate').val();
            var VoucherType = $('#VoucherType').val();
            var from = $('#from').val();
            var to = $('#to').val();
            var m = '<?php echo $_GET['m']?>';
            if(VoucherType !="") {
                $('#GetDataAjax').html('<tr><td colspan="10"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
                $.ajax({
                    url: '<?php echo url('/')?>/pdc/getDetailReportAjax',
                    type: 'Get',
                    data: {VoucherType: VoucherType,m: m,from:from,to:to},
                    success: function (response) {
                        $('#GetDataAjax').html(response);
                    }
                });
            }
            else
            {

            }
        }



    </script>

    <script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection