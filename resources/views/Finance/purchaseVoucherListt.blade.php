<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">View Purchase Voucher List</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <button class="btn btn-sm btn-info" onclick="printView('PrintPanel','','1')" style="">
                                            <span class="glyphicon glyphicon-print"> Print</span>
                                        </button>
                                        <button class="btn btn-sm btn-warning" onclick="exportView('TableExportToCsv','','1')" style="">
                                            <span class="glyphicon glyphicon-print"> Export to CSV</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="FromDate" id="FromDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="ToDate" id="ToDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label>Account Head</label>
                                    <select name="SupplierId" id="SupplierId" class="form-control select2">
                                        <option value="">Select Supplier</option>
                                        <?php foreach($Supplier as $Fil):?>
                                        <option value="<?php echo $Fil->id?>"><?php echo $Fil->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Voucher Status</label>
                                    <select name="VoucherStatus" id="VoucherStatus" class="form-control">
                                        <option value="">All</option>
                                        <option value="1">Pending</option>
                                        <option value="2">Approved</option>
                                    </select>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Range Wise Data Filter" class="btn btn-sm btn-danger" onclick="GetprvsDateAndAccontWise();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printBankPaymentVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <?php echo Form::open(array('url' => '/approvedPaymentVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                                        <div class="panel">
                                            <div class="panel-body" id="PrintPanel">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <span id="Loader"></span>
                                                <div class="row" id="ShowHide">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <h5 style="text-align: center" id="h3"></h5>
                                                            <table class="table table-bordered sf-table-list" id="TableExportToCsv">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">P.V. No.</th>
                                                                <th class="text-center">P.V. Date</th>
                                                                <th class="text-center">GRN No.</th>
                                                                <th class="text-center">Bill Date</th>
                                                                {{--<th class="text-center">Debit/Credit</th>--}}
                                                                <th class="text-center">Ref / Bill No.</th>
                                                                <th class="text-center">Vendor.</th>
                                                                <th class="text-center" style="width: 12%;">Voucher Status</th>
                                                                <th class="text-center" style="width: 12%;">Total Amount</th>
                                                                <th class="text-center hidden-print">Action</th>
                                                                </thead>
                                                                <tbody id="data">
                                                                <?php
                                                                $counter = 1;
                                                                $makeTotalAmount = 0;
                                                                foreach ($PurchaseVoucher as $row1) {
                                                                ?>
                                                                <tr id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
                                                                    {{--<td class="text-center">--}}
                                                                    {{--< ?php if($row1->pv_status ==1):?>--}}
                                                                    {{--<input name="checkbox[]" class="checkbox1" id="1chk< ?php echo $counter?>" type="checkbox" value="< ?php echo $row1->id?>" />--}}
                                                                    {{--< ?php endif;?>--}}
                                                                    {{--</td>--}}
                                                                    <td class="text-center"><?php echo $counter++;?></td>
                                                                    <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
                                                                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
                                                                    <td class="text-center"><?php echo strtoupper($row1->grn_no);?></td>
                                                                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->bill_date);?></td>
                                                                    {{--<td class="text-center">< ?php echo $Account = CommonHelper::debit_credit_amount('new_purchase_voucher_data',$row1->id);?></td>--}}
                                                                    <td class="text-center"><?php echo $row1->slip_no;?></td>
                                                                    <td class="text-center">
                                                                        <?php $Vendor = CommonHelper::get_single_row('supplier','id',$row1->supplier);
                                                                            echo $Vendor->name;
                                                                        ?>
                                                                    </td>
                                                                    <?php //die();?>

                                                                    <td id="Append{{$row1->id}}" class="text-center">
                                                                        <?php if($row1->pv_status == 1):?>
                                                                        <span class="badge badge-warning" style="background-color: #fb3 !important;">Pending</span>
                                                                        <?php else:?>
                                                                        <span class="badge badge-success" style="background-color: #00c851 !important">Success</span>
                                                                        <?php endif;?>
                                                                    </td>
                                                                    <td><?php echo CommonHelper::get_purchase_amount($row1->id)?></td>
                                                                    <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
                                                                    <td class="text-center hidden-print">
                                                                        {{--<a href="< ?php echo  URL::to('/finance/editCashPVForm/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs">Edit</a>--}}
                                                                        <input class="btn btn-xs btn-danger" type="button" onclick="DeletePvActivity('<?php echo $row1->id;?>')" value="Delete" />
                                                                        <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row1->id;?>','View Purchase Voucher Detail','<?php echo $m?>')" class="btn btn-xs btn-success">View</a>
                                                                        <?php if ($row1->pv_status==1):?>
                                                                        <a href="<?php echo  URL::to('/finance/editPurchaseVoucherFormNew/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs" id="BtnEdit<?php echo $row1->id?>">Edit</a>
                                                                        <?php endif;?>
                                                                        <?php
                                                                        /*
                                                                    if($row1->pv_status == 1):
                                                                    date_default_timezone_set('Asia/karachi');
                                                                    $PvId = $row1->id;
                                                                    $PvNo = $row1->pv_no;
                                                                    $UserName = Auth::user()->username;
                                                                    $DeleteDate = date('Y-m-d');
                                                                    $DeleteTime = date('h:i:s');
                                                                    $ActivityType = 2;
                                                                        */
                                                                        ?>
                                                                        {{--<button class="btn btn-xs btn-danger"--}}
                                                                        {{--onclick="DeletePvActivity('< ?php echo $PvId;?>','< ?php echo $PvNo;?>','< ?php echo $UserName;?>','< ?php echo $DeleteDate;?>','< ?php echo $DeleteTime;?>','< ?php echo $ActivityType;?>')">--}}
                                                                        {{--Delete</button>--}}
                                                                        <?php //endif?>
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                }
                                                                ?>
                                                                <tr>
                                                                    <th colspan="8" class="text-center">xxxxx</th>
                                                                </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{--<div class="row">--}}
                                                {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                                                {{--<button type="submit" class="btn btn-sm btn-success" id="BtnApproved" disabled>Approved</button>--}}
                                                {{--</div>--}}
                                                {{--</div>--}}
                                            </div>
                                        </div>
                                        <?php echo Form::close();?>
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

        function GetprvsDateAndAccontWise()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var FromShow = FromDate.split('-');
            var FromShow = FromShow[2] + '-' + FromShow[1] + '-' + FromShow[0];
            var ToShow = ToDate.split('-');
            var ToShow = ToShow[2] + '-' + ToShow[1] + '-' + ToShow[0];
            var SupplierId = $('#SupplierId').val();
            var VoucherStatus = $('#VoucherStatus').val();
            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/fdc/getprvsDateAndAccontWise',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,SupplierId:SupplierId,VoucherStatus:VoucherStatus,m:m},

                success: function (response) {
                    $('#data').html(response);
                    $('#FromShow').html(FromShow);
                    $('#ToShow').html(ToShow);
                    $('#ShowTitle').css('display','block');

                }
            });
        }

        function DeletePvActivity(pv_id)
        {
            if (confirm('Are you sure you want to delete this Voucher...?'+pv_id))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/DeletePurchaseVoucher',
                    type: "GET",
                    data: {pv_id:pv_id},
                    success:function(data) {
                        if(data==2){
                            alert('This Voucher Cant be deleted because the payment issued');
                        } else{
                            alert('Successfully Deleted');
                            $("#tr"+pv_id).remove();
                        }
                    }
                });
            }
        }
    </script>

    <script>

        function checkUncheck(chkbox,rowid){

            if ($('#'+chkbox).is(':checked'))
            {
                $('#'+chkbox).prop('checked',false);
                $('#'+rowid).removeClass("bg-info");
            } else {
                $('#'+chkbox).prop('checked',true);
                $('#'+rowid).addClass("bg-info");
            }
            var Len = $('input[name="checkbox[]"]:checked').length;
            if(Len>0)
            {$('#BtnApproved').prop('disabled',false);}
            else{$('#BtnApproved').prop('disabled',true);}


        }
    </script>
@endsection
