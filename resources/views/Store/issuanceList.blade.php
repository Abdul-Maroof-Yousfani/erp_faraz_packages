<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\StoreHelper;

$export=ReuseableCode::check_rights(315);

$accType = Auth::user()->acc_type;
if($accType == 'client'){

}else{

}
$current_date = date('Y-m-d');
$from = date('Y-m-01');
$to   = date('Y-m-t');


?>

@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printIssuanceVoucherList','','1');?>
                        <?php if($export == true):?>
                            <?php echo CommonHelper::displayExportButton('issuanceVoucherList','','1')?>
                        <?php endif;?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Work Order List</span>
                                </div>
                            </div>

                            <div class="lineHeight">&nbsp;</div>

                            <div id="printDemandVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php //echo CommonHelper::headerPrintSectionInPrintView(Session::get('run_company'));?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="row">
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label>From Date</label>
                                                                <input type="Date" name="FromDate" id="FromDate" max="<?php echo $current_date;?>" value="<?php echo $from?>" class="form-control" />
                                                            </div>

                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label>To Date</label>
                                                                <input type="Date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                            </div>
                                                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                                <label>WO Type</label>
                                                                <select name="WoType" id="WoType" class="form-control">
                                                                    <option value="all">All</option>
                                                                    <option value="1">Return</option>
                                                                    <option value="2">Reject</option>
                                                                </select>
                                                            </div>

                                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                                <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="getWordOrderData();" style="margin-top: 32px;" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="lineHeight">&nbsp;</div>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data">
                                                        <div class="table-responsive" >
                                                            <table class="table table-bordered sf-table-list" id="issuanceVoucherList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Voucher No</th>
                                                                <th class="text-center">Voucher Date</th>
                                                                <th class="text-center">Supplier</th>

                                                                <th class="text-center">Total Make QTY</th>
                                                                <th class="text-center">Total Cost</th>

                                                                <th class="text-center">Action</th>
                                                                </thead>

                                                                <tbody id="ShowHide">

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
    </div>
    
<script>

    $(document).ready(function(){
       getWordOrderData();
    });
    function getWordOrderData()
    {
        $('#ShowHide').html('<tr><td class="loader" colspan="7"></td></tr>');
        var FromDate = $('#FromDate').val();
        var ToDate = $('#ToDate').val();
        var WoType = $('#WoType').val();

        $.ajax({
            url: '/stdc/get_work_order_data',
            type: 'Get',
            data: {FromDate: FromDate,ToDate:ToDate,WoType:WoType},

            success: function (response)
            {
                $('#ShowHide').html(response);
            }
        });
    }
    function delete_issue(voucher_no)
    {
        if (confirm('Are You Sure ? You want to delete this recored...!')) {


            $.ajax({
                url: '/stdc/delete_issue',
                type: 'Get',
                data: {voucher_no: voucher_no},

                success: function (response)
                {
                    if (response==0)
                    {
                        alert('Can not delete');
                        return false;
                    }
                    $('#remove'+response).remove();
                }
            });
        }
        else {}
    }
</script>
@endsection