<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;



$view=ReuseableCode::check_rights(31);
$edit=ReuseableCode::check_rights(32);
$delete=ReuseableCode::check_rights(33);
$export=ReuseableCode::check_rights(235);


$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');



$first_day_this_month = date('Y-m-01');
$last_day_this_month  = date('Y-m-t');

?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">View Purchase Return List</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('printIssuanceVoucherList','','1');?>
                                        <?php if($export == true):?>
                                    <?php echo CommonHelper::displayExportButton('issuanceVoucherList','','1')?>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="from" id="from"  value="<?php echo $first_day_this_month;?>" class="form-control" />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $last_day_this_month;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Voucher Type</label>
                                    <select name="VoucherType" id="VoucherType" class="form-control">
                                        <option value="all">All</option>
                                        <option value="1">GRN</option>
                                        <option value="2">Purchase Invoice</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Supplier</label>
                                    <select name="SupplierId" id="SupplierId" class="form-control select2">
                                        <option value="all">ALL</option>
                                        <?php foreach($Supplier as $Fil):?>
                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
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
                                                                <th class="text-center">Purchase Return No.</th>
                                                                <th class="text-center">Purchase Return Date</th>
                                                                <th class="text-center">Supplier Name</th>
                                                                <th class="text-center">Grn No</th>
                                                                <th class="text-center">Grn Date</th>
                                                                <th class="text-center">Remarks</th>
                                                                <th class="text-center hide">Return Amount</th>
                                                                <th class="text-center hide">Net Stock</th>
                                                                <th class="text-center">Type</th>
                                                                <th class="text-center hidden-print">Action</th>
                                                                </thead>
                                                                <tbody id="ShowHide"> </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                        <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Demand Voucher List'))!!} ">
                                    </div> -->
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
    <script !src="">
        //issuanceDataFilter();
        $(document).ready(function(){
            viewRangeWiseDataFilter();
            $('.select2').select2();
        });

        function viewRangeWiseDataFilter()
        {
            var from= $('#from').val();
            var to= $('#to').val();
            var SupplierId= $('#SupplierId').val();
            var VoucherType= $('#VoucherType').val();
            var m  = '<?php echo $m?>';
            $('#ShowHide').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/pdc/get_data_debit_note_ajax',
                type: 'Get',
                data: {from: from,to:to,m:m,SupplierId:SupplierId,VoucherType:VoucherType},
                success: function (response) {
                    $('#ShowHide').html(response);
                }
            });
        }

        function DeletePurchaseReturn(Id,PrNo)
        {
            if (confirm('Are You Sure ? You want to delete this recored...!')) {
                var m = '<?php echo $m?>';

                //$('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
                $.ajax({
                    url: '<?php echo url('/')?>/pdc/deletePurchaseReturn',
                    type: 'Get',
                    data: {Id: Id,PrNo:PrNo,m:m},

                    success: function (response)
                    {
                        $('#RemoveTr'+response).remove();
                    }
                });
            }
            else {}
        }

        function ApprovedGoodIssuance(IssuanceId){
            var m = '<?php echo $_GET['m'];?>';
            $('#BtnApprove'+IssuanceId).prop('disabled',true);

            $.ajax({
                url: '<?php echo url('/')?>/pdc/ApprovedGoodIssuance',
                type: "GET",
                data: { IssuanceId:IssuanceId,m:m},
                success:function(data) {
                    $('#BtnApprove'+IssuanceId).css('display','none');
                    $('#BtnDelete'+IssuanceId).css('display','none');
                    $('#BtnEdit'+IssuanceId).css('display','none');

                }
            });
        }

        function Recieved(IssuanceId,m){
            var m = '<?php echo $_GET['m'];?>';
            $('#recieved'+IssuanceId).prop('disabled',true);

            $.ajax({
                url: '<?php echo url('/')?>/pdc/Recieved',
                type: "GET",
                data: { IssuanceId:IssuanceId,m:m},
                success:function(data) {
                    $('#recieved'+IssuanceId).css('display','none');
                    $('#BtnDelete'+IssuanceId).css('display','none');
                    $('#BtnEdit'+IssuanceId).css('display','none');

                }
            });
        }

        function issuanceDataFilter()
        {
            var FromDate= $('#FromDate').val();
            var ToDate= $('#ToDate').val();
            var IssuanceType = $('#IssuanceType').val();

            var m = '<?php echo $m?>';
            $('#ShowHide').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/pdc/issuanceDataFilter',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,IssuanceType:IssuanceType,m:m},

                success: function (response) {

                    $('#ShowHide').html(response);
                }
            });
        }
    </script>
@endsection