<?php



$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(37);
$edit=ReuseableCode::check_rights(211);
$delete=ReuseableCode::check_rights(38);
$export=ReuseableCode::check_rights(236);

?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                    <div class="dp_sdw">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Purchase Voucher List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                    <?php if($export == true):?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                                <?php endif;?>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>From Date</label>
                                <input type="Date" name="from" id="from"  value="<?php echo $first_day_this_month;?>" class="form-control" />
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                <label>To Date</label>
                                <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $last_day_this_month;?>" class="form-control" />
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="">Supplier</label>
                                <select name="SupplierId" id="SupplierId" class="form-control select2">
                                    <option value="all">ALL</option>
                                    <?php foreach($Supplier as $Fil):?>
                                    <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label for="">Voucher Type </label>
                                <select name="VoucherType" id="VoucherType" class="form-control">
                                    <option selected value="1"> Goods Receip Note</option>
                                    <option value="2"> Work Order</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body" id="PrintEmpExitInterviewList">
                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list view_purchase_tab" id="EmpExitInterviewList">
                                                <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">PV No</th>
                                                <th class="text-center">PV Date</th>
                                                <th class="text-center">GRN No</th>
                                                <th class="text-center">Ref  No</th>
                                                <th class="text-center">Bill Date</th>
                                                <th class="text-center">Pv Status</th>
                                                <th class="text-center">Supplier</th>
                                                <th class="text-center">Amount</th>
                                                <th class="text-center hide">GRN Amount</th>
                                                <th class="text-center">Action</th>
                                                </thead>
                                                <tbody id="data"> </tbody>
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

    <script>
        $(document).ready(function(){
            $('#SupplierId').select2();
            viewRangeWiseDataFilter();
        });
        function delete_record(id,grnno,pvno)
        {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: {id: id,grnno:grnno,pvno:pvno},

                    success: function (response) {
                        alert('Deleted');
                        $('#' + id).remove();

                    }
                });
            }
            else{}
        }


        function viewRangeWiseDataFilter() {
            var from= $('#from').val();
            var to= $('#to').val();
            var SupplierId= $('#SupplierId').val();
            var RadioVal = $('#VoucherType').val()
            var m  = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            $.ajax({
                url: '{{ url('/pdc/purchase_voucher_list_ajax') }}',
                type: 'Get',
                data: {from: from,to:to,m:m,SupplierId:SupplierId,RadioVal:RadioVal},
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }
    </script>

@endsection
