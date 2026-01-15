<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(160);
$edit=ReuseableCode::check_rights(161);
$delete=ReuseableCode::check_rights(162);
$export=ReuseableCode::check_rights(225);


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
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="well">
                                <div class='headquid'>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <span class="subHeadingLabelClass">View All Payment Voucher List</span>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintPanel','','1');?>
                                                <?php if($export == true):?>
                                                <a id="dlink" style="display:none;"></a>
                                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>


                                                <?php endif;?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>From Date</label>
                                        <input type="Date" name="FromDate" id="FromDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>To Date</label>
                                        <input type="Date" name="ToDate" id="ToDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                    </div>
                                    <div class="col-lg-3 col-md-2 col-sm-4 col-xs-12">
                                        <label>Account Head</label>
                                        <select name="AccountId" id="AccountId" class="form-control select2">
                                            <option value="">Select Account</option>
                                            <?php foreach($accounts as $Fil):?>
                                            <option value="<?php echo $Fil->id?>"><?php echo $Fil->code.'=='.$Fil->name?></option>
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
                                    <div class="col-lg-3 col-md-4 col-sm-3 col-xs-12">
                                        <label class="sf-label">Payment Mode</label>
                                        <select style="width: 100%" class="form-control requiredField select2" multiple id="PaymentType" name="payment_type[]">
                                            <option value="">Select Payment Mode</option>
                                            <option value="2">Cash</option>
                                            <option value="1">Bank</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                        <input type="button" value="View Range Wise Data Filter" class="btn btn-primary" onclick="GetBpvsDateAndAccontWise();" style="margin-top: 32px;" />
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div id="printBankPaymentVoucherList">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php // Form::open(array('url' => '/approvedPaymentVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                                            <div class="panel">
                                                <div class="panel-body" id="PrintPanel">
                                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 20px; font-style: oblique; display: none;" id="ShowTitle">
                                                            <b>Payment Voucher List From :<span id="FromShow" style="color: red"><?php echo FinanceHelper::changeDateFormat($AccYearFrom);?></span> Between To <span style="color: red" id="ToShow"><?php echo FinanceHelper::changeDateFormat($AccYearTo)?></span> </b>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <h5 style="text-align: center" id="h3"></h5>
                                                                <table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv1">
                                                                    <thead>

                                                                    <th class="text-center">S.No</th>
                                                                    <th class="text-center">P.V. No.</th>
                                                                    <th class="text-center">P.V. Date</th>
                                                                    <th class="text-center">Debit/Credit</th>
                                                                    <th class="text-center">Description</th>
                                                                    <th class="text-center">Ref / Bill No.</th>
                                                                    <th class="text-center">Cheque No</th>
                                                                    <th class="text-center">Cheque Date</th>
                                                                    <th class="text-center">Verified</th>
                                                                    <th class="text-center">Voucher Status</th>
                                                                    <th style="width: 165px" class="text-center hidden-print">Action</th>
                                                                    </thead>
                                                                    <tbody id="data">
                                                                    <?php
                                                                    $counter = 1;
                                                                    $makeTotalAmount = 0;
                                                                    foreach ($pvs as $row1) {
                                                                        ?>
                                                                    <tr @if ($row1->type==2) style="background-color: darkgray" @endif
                                                                    class="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>

                                                                        <td class="text-center">
                                                                            <?php echo $counter++;?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo strtoupper($row1->pv_no);?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo FinanceHelper::changeDateFormat($row1->pv_date);?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo $row1->description ;?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo $row1->bill_no;?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo $row1->cheque_no;?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            {{ $row1->verified_by != null ? 'Yes' : 'No' }}
                                                                        </td>
                                                                        <td class="text-center status{{$row1->pv_no}}">
                                                                            <?php if($row1->pv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?>
                                                                        </td>
                                                                        <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
                                                                        <td class="text-center hidden-print">
                                                                            <div class="dropdown">
                                                                                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                                <ul class="dropdown-menu">
                                                                                    <li>
                                                                                        <?php if($view == true):?>
                                                                                        <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetail','<?php echo $row1->id;?>','View Bank P.V Detail','<?php echo $_GET['m']?>','')" class="">
                                                                                        <i class="fa-regular fa-eye"></i> View
                                                                                        </a>
                                                                                        <?php endif;?>
                                                                                            <?php if($edit == true):?>
                                                                                        <a  href="<?php echo  URL::to('/finance/editAllPaymentNew/'.$row1->id.'?m='.$m); ?>" type="button" class="BtnHide<?php echo $row1->pv_no?>">
                                                                                        <i class="fa-solid fa-pencil"></i> Edit
                                                                                        </a>
                                                                                        <?php endif;?>
                                                                                            <?php if($delete == true):?>
                                                                                        <a class=" BtnHide<?php echo $row1->pv_no?>" type="button" onclick="DeletePvActivity('<?php echo $row1->id;?>','<?php echo $row1->pv_no?>','<?php echo $row1->pv_date?>','<?php echo CommonHelper::GetAmount('new_pv_data',$row1->id)?>')"><i class="fa-solid fa-trash"></i>  Delete </a>
                                                                                        <?php endif;?>

                                                                                        <a target="_blank" href="<?php echo url('fdc/viewAllPaymentVoucherDetailPrint?id='.$row1->id.'&&m='.$m)?>" class=""><i class="fa-solid fa-print"></i> Print</a>
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
                                                                                        {{--<button class=""--}}
                                                                                        {{--onclick="DeletePvActivity('< ?php echo $PvId;?>','< ?php echo $PvNo;?>','< ?php echo $UserName;?>','< ?php echo $DeleteDate;?>','< ?php echo $DeleteTime;?>','< ?php echo $ActivityType;?>')">--}}
                                                                                        {{--Delete</button>--}}
                                                                                            <?php //endif?>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    <tr>
                                                                        <th colspan="10" class="text-center">xxxxx</th>
                                                                    </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        {{--<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">--}}
                                                        {{--<button type="submit" class="btn btn-sm btn-success" id="BtnApproved" disabled>Approved</button>--}}
                                                        {{--</div>--}}
                                                    </div>
                                                </div>
                                            </div>
                                            <?php // Form::close();?>
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
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('TableExportToCsv1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('B.P.V <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script !src="">
        $(document).ready(function(){
            $('.select2').select2();
        });

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

        function DeletePvActivity(pv_id,pv_no,pv_date,pv_amount)
        {
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
            if (confirm('Are you sure you want to delete this Voucher...?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/DeletePVoucherActivity',
                    type: "GET",
                    data: {
                        pv_id:pv_id,
                        pv_no:pv_no,
                        pv_date:pv_date,
                        pv_amount:pv_amount
                    },
                    success:function(data) {
                        //alert(data); return false;
                        alert('Successfully Deleted');
                        $(".tr"+pv_id).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }


        }


        function GetBpvsDateAndAccontWise()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var FromShow = FromDate.split('-');
            var FromShow = FromShow[2] + '-' + FromShow[1] + '-' + FromShow[0];
            var ToShow = ToDate.split('-');
            var ToShow = ToShow[2] + '-' + ToShow[1] + '-' + ToShow[0];
            var AccountId = $('#AccountId').val();
            var PaymentType = $('#PaymentType').val();
            var VoucherStatus = $('#VoucherStatus').val();
            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/fdc/getAllpvsDateAccontWiseAndTypeWise',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,PaymentType:PaymentType,AccountId:AccountId,VoucherStatus:VoucherStatus,m:m},

                success: function (response) {
                    $('#data').html(response);
                    $('#FromShow').html(FromShow);
                    $('#ToShow').html(ToShow);
                    $('#ShowTitle').css('display','block');

                }
            });
        }
    </script>
@endsection
