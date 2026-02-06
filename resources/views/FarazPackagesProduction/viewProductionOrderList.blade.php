<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$export = ReuseableCode::check_rights(232);
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');
?>
@extends('layouts.default')
@section('content')
@include('select2')
<div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="headquid">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="subHeadingLabelClass">Production Order List</h2>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList', '', '1');?>
                            <?php if ($export == true):?>
                            <?php    echo CommonHelper::displayExportButton('demandVoucherList', '', '1')?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <input type="hidden" name="m" id="m" value="{{ $m }}" readonly
                                class="form-control" />
                            <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly"
                                class="form-control" />
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="date" name="from_date" id="from_date" max="{{ $current_date }}"
                                        value="{{ $currentMonthStartDate }}" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="date" name="to_date" id="to_date" max="{{ $current_date }}"
                                        value="{{ $currentMonthEndDate }}" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Voucher Status</label>
                                    <select name="approval_status" id="approval_status"
                                        class="form-control select2">
                                        <?php echo CommonHelper::voucherStatusSelectList();?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="margin-top: 40px">
                                    <a href="#" class="btn btn-primary" onclick="viewProductionOrderListDetail();">Search</a>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printDemandVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <div class="headquid">
                                                    {{ CommonHelper::headerPrintSectionInPrintView($m) }}
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="userlittab table table-bordered sf-table-list"
                                                                id="demandVoucherList">
                                                                <thead>
                                                                    <th class="text-center">S.No</th>
                                                                    <th class="text-center">PR NO.</th>
                                                                    <th class="text-center">PR Date</th>
                                                                    <th class="text-center">Created By</th>
                                                                    <th class="text-center">Status</th>
                                                                    <th class="text-center hidden-print">
                                                                        Action
                                                                    </th>
                                                                </thead>
                                                                <tbody id="tableData"></tbody>
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
<script type="text/javascript">
    $('.select2').select2();

    viewProductionOrderListDetail();
    
    function viewProductionOrderListDetail() {
        var m = $('#m').val();
        var from_date = $('#from_date').val();
        var to_date = $('#to_date').val();
        var approval_status = $('#approval_status').val();
        $('#tableData').html('<tr><td colspan="8"><div class="loader"></div></td></tr>')
        $.ajax({
            url: '{{url('/far_production/viewProductionOrderListDetail')}}',
            data: { m: m, from_date: from_date, to_date: to_date, approval_status: approval_status },
            type: 'GET',
            success: function (response) {
                $('#tableData').html(response);
            }
        });
    }

</script>

<script src="{{ URL::asset('assets/custom/js/customPurchaseFunction.js') }}"></script>
@endsection