<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');

$view=ReuseableCode::check_rights(134);
$edit=ReuseableCode::check_rights(135);
$delete=ReuseableCode::check_rights(136);
$approved=ReuseableCode::check_rights(137);
$export=ReuseableCode::check_rights(262);

$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;
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
                            <span class="subHeadingLabelClass">View Receipt Voucher List</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php if($export == true):?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
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
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <input type="button" value="Search" class="btn btn-primary" onclick="GetRvsDateAndAccontWiseForSales();" style="margin-top: 32px;" />
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center" style="font-size: 20px; font-style: oblique; display: none;" id="ShowTitle">
                                    <b>Receipt Voucher List From :<span id="FromShow" style="color: red"><?php echo FinanceHelper::changeDateFormat($AccYearFrom);?></span> Between To <span style="color: red" id="ToShow"><?php echo FinanceHelper::changeDateFormat($AccYearTo)?></span> </b>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center">S.No</th>
                                            <th class="text-center">R.V. No.</th>
                                            <th class="text-center">R.V. Date</th>

                                            <th class="text-center">Cheque No</th>
                                            <th class="text-center">Cheque Date</th>
                                            <th class="text-center">Received Amount</th>
                                            <th class="text-center">Tax Amount</th>
                                            <th class="text-center">Debit/Credit</th>
                                            <th class="text-center">Voucher Status</th>
                                            <th class="text-center hidden-print">Action</th>
                                            </thead>
                                            <tbody id="data">
                                            <?php
                                            $counter = 1;
                                            $makeTotalAmount = 0;
                                            foreach ($NewRvs as $row1) {

                                           $received_data = SalesHelper::get_received_data($row1->id);
                                            ?>
                                            <tr class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
                                                {{--<td class="text-center">--}}
                                                {{--< ?php if($row1->pv_status ==1):?>--}}
                                                {{--<input name="checkbox[]" class="checkbox1" id="1chk< ?php echo $counter?>" type="checkbox" value="< ?php echo $row1->id?>" />--}}
                                                {{--< ?php endif;?>--}}
                                                {{--</td>--}}
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
                                                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
                                     
                                                <td class="text-center"><?php echo $row1->cheque_no;?></td>
                                                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
                                                <td class="text-center">{{number_format($received_data->net_amount,2)}}</td>
                                                <td class="text-center">{{number_format($received_data->tax_amount,2)}}</td>
                                                <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_rv_data',$row1->id);?></td>
                                                {{--<td class="text-center">< ?php echo $row1->slip_no;?></td>--}}
                                                <?php //die();?>

                                                <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
                                                    <?php if($row1->rv_status == 1):?>
                                                    <span class="" style="color: #fb3 !important;">Pending</span>
                                                    <?php else:?>
                                                    <span class="" style="color: #00c851 !important">Approved</span>
                                                    <?php endif;?>
                                                </td>
                                                <?php   //$count=CommonHelper::check_amount_in_ledger($row1->rv_no,$row1->id,2) ?>
                                                <td style="width: 162px;" class="text-center hidden-print">
                                                
                                                    <div class="dropdown">
                                                        <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                        <ul class="dropdown-menu">
                                                            <li>


                                                                <?php //if($row1->rv_status ==1):?>

                                                                <?php //endif;?>
                                                                <?php if($view == true):?>
                                                                    <a onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','<?php echo $row1->id;?>','View Bank Reciept Voucher Detail','<?php echo $m?>','')" class=""><i class="fa-regular fa-eye"></i>  View</a>
                                                                <?php endif;?>



                                                                    @if($row1->rv_status==1)
                                                                        <?php if($edit == true):?>
                                                                        
                                                                        <?php endif;?>


                                                                    @endif

                                                                    <?php if($delete == true):?>
                                                                    <a class=" BtnHide<?php echo $row1->rv_no?>" type="button"
                                                                        onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data',$row1->id)?>')"
                                                                        ><i class="fa-solid fa-trash"></i> Delete </a>
                                                                    <?php endif;?>

                                                                    
                                                                    <a target="_blank" href="<?php echo url('sdc/viewReceiptVoucherPrint?id='.$row1->id.'&&m='.$m)?>" class=""><i class="fa-solid fa-print"></i> Print</a>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                            
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
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('EmpExitInterviewList');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Receipt <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });

        function GetRvsDateAndAccontWiseForSales()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var VoucherStatus = $('#VoucherStatus').val();

            var FromShow = FromDate.split('-');
            var FromShow = FromShow[2] + '-' + FromShow[1] + '-' + FromShow[0];
            var ToShow = ToDate.split('-');
            var ToShow = ToShow[2] + '-' + ToShow[1] + '-' + ToShow[0];
            var AccountId = $('#AccountId').val();
            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/fdc/getRvsDateAndAccontWiseForSales',
                type: 'Get',
                data: {FromDate: FromDate,ToDate:ToDate,VoucherStatus:VoucherStatus,AccountId:AccountId,m:m},

                success: function (response) {
                    $('#data').html(response);
                    $('#FromShow').html(FromShow);
                    $('#ToShow').html(ToShow);
                    $('#ShowTitle').css('display','block');

                }
            });
        }

        function delete_record(id)
        {
            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deleteResourceAssignedList',
                    type: 'Get',
                    data: {id: id},
                    success: function (response) {
                        alert('Deleted');
                        $('#' + id).remove();
                    }
                });
            }
            else{}
        }

        function DeleteRvActivity(rv_id,rv_no,rv_date,rv_amount)
        {
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
            if (confirm('Are you sure you want to delete this Voucher...?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/DeleteRVoucherActivity',
                    type: "GET",
                    data: {
                        rv_id:rv_id,
                        rv_no:rv_no,
                        rv_date:rv_date,
                        rv_amount:rv_amount
                    },
                    success:function(data) {
                        //alert(data); return false;
                        alert('Successfully Deleted');
                        $(".tr"+rv_id).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }


        }
    </script>

@endsection