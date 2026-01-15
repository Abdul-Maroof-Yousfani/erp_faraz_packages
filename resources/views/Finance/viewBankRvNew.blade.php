<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$view = ReuseableCode::check_rights(174);
$edit = ReuseableCode::check_rights(175);
$delete = ReuseableCode::check_rights(176);
$export = ReuseableCode::check_rights(227);


$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

$AccYearDate = DB::table('company')->select('accyearfrom', 'accyearto')->where('id', Session::get('run_company'))->first();
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
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <span class="subHeadingLabelClass">View Bank Reciept Voucher List</span>
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintPanel', '', '1');?>
                                            <?php if ($export == true):?>
                                            <a id="dlink" style="display:none;"></a>
                                            <button type="button" class="btn btn-warning"
                                                onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>From Date</label>
                                        <input type="Date" name="FromDate" id="FromDate" min="<?php echo $AccYearFrom?>"
                                            max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthStartDate;?>"
                                            class="form-control" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label>To Date</label>
                                        <input type="Date" name="ToDate" id="ToDate" min="<?php echo $AccYearFrom?>"
                                            max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthEndDate;?>"
                                            class="form-control" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-12">
                                        <label>Account Head</label>
                                        <select name="AccountId" id="AccountId" class="form-control select2">
                                            <option value="">Select Account</option>
                                            <?php foreach ($accounts as $Fil):?>
                                            <option value="<?php    echo $Fil->id?>"><?php    echo $Fil->code . '==' . $Fil->name?>
                                            </option>
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
                                        <input type="button" value="Search" class="btn btn-primary"
                                            onclick="GetBrvsDateAndAccontWise();" style="margin-top: 32px;" />
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                                <div id="printBankPaymentVoucherList">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <?php //echo Form::open(array('url' => '/approvedPaymentVoucher?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>
                                            <div class="panel">
                                                <div class="panel-body" id="PrintPanel">
                                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                                                            style="font-size: 20px; font-style: oblique; display: none;"
                                                            id="ShowTitle">
                                                            <b>Bank Receipt Voucher List From :<span id="FromShow"
                                                                    style="color: red"><?php echo FinanceHelper::changeDateFormat($AccYearFrom);?></span>
                                                                Between To <span style="color: red"
                                                                    id="ToShow"><?php echo FinanceHelper::changeDateFormat($AccYearTo)?></span>
                                                            </b>
                                                        </div>
                                                    </div>

                                                    <div class="row" id="ShowHide">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            <div class="table-responsive">
                                                                <h5 style="text-align: center" id="h3"></h5>
                                                                <table class="userlittab table table-bordered sf-table-list"
                                                                    id="TableExport">
                                                                    <thead>
                                                                        {{--<th class="text-center">Check/Uncheck</th>--}}
                                                                        <th class="text-center">S.No</th>
                                                                        <th class="text-center">R.V. No.</th>
                                                                        <th class="text-center">R.V. Date</th>
                                                                        <th class="text-center">Ref / Bill No.</th>
                                                                        <th class="text-center">Description</th>
                                                                        <th class="text-center">Cheque No</th>
                                                                        <th class="text-center">Cheque Date</th>
                                                                        <th class="text-center">Debit/Credit</th>
                                                                        <th class="text-center">Verified</th>
                                                                        <th class="text-center">Voucher Status</th>
                                                                        <th class="text-center hidden-print">Action</th>
                                                                    </thead>
                                                                    <tbody id="data">
                                                                        <?php
                                                                        $counter = 1;
                                                                        $makeTotalAmount = 0;
                                                                        foreach ($Rvs as $row1) {
                                                                        ?>
                                                                        <tr class="tr<?php    echo $row1->id ?>"
                                                                            id="tr<?php    echo $row1->id ?>"
                                                                            title="<?php    echo $row1->id ?>"
                                                                            id="1row<?php    echo $counter ?>" <?php    if ($row1->pv_status == 1):?>
                                                                            onclick="checkUncheck('1chk<?php        echo $counter ?>','1row<?php        echo $counter ?>')"
                                                                            <?php    endif;?>>
                                                                            {{--<td class="text-center">--}}
                                                                                {{--< ?php if($row1->pv_status ==1):?>--}}
                                                                                    {{--<input name="checkbox[]"
                                                                                        class="checkbox1"
                                                                                        id="1chk< ?php echo $counter?>"
                                                                                        type="checkbox"
                                                                                        value="< ?php echo $row1->id?>" />--}}
                                                                                    {{--< ?php endif;?>--}}
                                                                                        {{--</td>--}}
                                                                            <td class="text-center"><?php    echo $counter++;?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php    echo strtoupper($row1->rv_no);?></td>
                                                                            <td class="text-center">
                                                                                <?php    echo FinanceHelper::changeDateFormat($row1->rv_date);?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php    echo $row1->ref_bill_no;?></td>
                                                                            <td class="text-center">
                                                                                <?php    echo $row1->description;?></td>
                                                                            <td class="text-center">
                                                                                <?php    echo $row1->cheque_no;?></td>
                                                                            <td class="text-center">
                                                                                <?php    echo FinanceHelper::changeDateFormat($row1->cheque_date);?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php    echo $Account = CommonHelper::debit_credit_amount('new_rv_data', $row1->id);?>
                                                                            </td>

                                                                            <td class="text-center">
                                                                                {{ $row1->verified_by != null ? 'Yes' : 'No' }}
                                                                            </td>
                                                                            <td id="Append{{$row1->id}}"
                                                                                class="text-center status<?php echo $row1->rv_no?>">
                                                                                @if ($row1->rv_status == 1)
                                                                                    <span style="color: orange">Pending</span>
                                                                                @else
                                                                                    <span style="color: green">Approved</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <div class="dropdown">
                                                                                    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li>


                                                                                            @if($view == true)
                                                                                                <a onclick="showDetailModelOneParamerter('fdc/viewBankRvDetailNew','<?php echo $row1->id;?>','View Bank Reciept Voucher Detail','<?php echo $m?>','')"
                                                                                                    class=""><i
                                                                                                        class="fa-regular fa-eye"></i>
                                                                                                    View</a>
                                                                                            @endif


                                                                                            @if($row1->rv_status == 1)
                                                                                                @if($edit == true)
                                                                                                    <a href="<?php echo URL::to('/finance/editBankRv/' . $row1->id . '?m=' . $m); ?>"
                                                                                                        type="button"
                                                                                                        ><i
                                                                                                            class="fa-solid fa-pencil"></i>
                                                                                                        Edit</a>
                                                                                                @endif
                                                                                            @endif

                                                                                            @if ($delete == true)
                                                                                                <a 
                                                                                                    type="button"
                                                                                                    onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data', $row1->id)?>')"><i
                                                                                                        class="fa-solid fa-trash"></i>
                                                                                                    Delete</a>
                                                                                            @endif
                                                                                            <a onclick="change_colour('{{$row1->id }}')"
                                                                                                target="_blank"
                                                                                                href="<?php echo url('fdc/viewBankRvDetailNewPrint?id=' . $row1->id . '&&m=' . $m)?>"
                                                                                                class=""><i
                                                                                                    class="fa-solid fa-print"></i>
                                                                                                Print</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>

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
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                            {{--<button type="submit" class="btn btn-sm btn-success"
                                                                id="BtnApproved" disabled>Approved</button>--}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php //echo Form::close();?>
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
            var elt = document.getElementById('TableExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('B.R.V <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script type="text/javascript">

        $(document).ready(function () {
            $('.select2').select2();
        });

        function GetBrvsDateAndAccontWise() {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var FromShow = FromDate.split('-');
            var FromShow = FromShow[2] + '-' + FromShow[1] + '-' + FromShow[0];
            var ToShow = ToDate.split('-');
            var ToShow = ToShow[2] + '-' + ToShow[1] + '-' + ToShow[0];
            var AccountId = $('#AccountId').val();
            var VoucherStatus = $('#VoucherStatus').val();
            var m = '<?php echo $m?>';
            $('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '/fdc/getBrvsDateAndAccontWise',
                type: 'Get',
                data: { FromDate: FromDate, ToDate: ToDate, AccountId: AccountId, VoucherStatus: VoucherStatus, m: m },

                success: function (response) {
                    $('#data').html(response);
                    $('#FromShow').html(FromShow);
                    $('#ToShow').html(ToShow);
                    $('#ShowTitle').css('display', 'block');

                }
            });
        }

        function DeleteRvActivity(rv_id, rv_no, rv_date, rv_amount) {
            //alert(pv_id+pv_no+pv_date+pv_amount); return false;
            if (confirm('Are you sure you want to delete this Voucher...?')) {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/DeleteRVoucherActivity',
                    type: "GET",
                    data: {
                        rv_id: rv_id,
                        rv_no: rv_no,
                        rv_date: rv_date,
                        rv_amount: rv_amount
                    },
                    success: function (data) {
                        //alert(data); return false;
                        alert('Successfully Deleted');
                        $(".tr" + rv_id).remove();
                        //return false;
                        //    filterVoucherList();
                    }
                });
            }


        }
    </script>

    <script>

        function checkUncheck(chkbox, rowid) {

            if ($('#' + chkbox).is(':checked')) {
                $('#' + chkbox).prop('checked', false);
                $('#' + rowid).removeClass("bg-info");
            } else {
                $('#' + chkbox).prop('checked', true);
                $('#' + rowid).addClass("bg-info");
            }
            var Len = $('input[name="checkbox[]"]:checked').length;
            if (Len > 0) { $('#BtnApproved').prop('disabled', false); }
            else { $('#BtnApproved').prop('disabled', true); }


        }

        function change_colour(id) {
            $('#tr' + id).css("background-color", "#FFF8DC");
        }

    </script>


@endsection