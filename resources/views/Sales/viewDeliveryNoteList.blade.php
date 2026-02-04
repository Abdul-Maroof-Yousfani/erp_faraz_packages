<?php
$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'];
} else {
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

use App\Helpers\ReuseableCode;

$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

$view = ReuseableCode::check_rights(111);
$edit = ReuseableCode::check_rights(112);
$delete = ReuseableCode::check_rights(113);
$export = ReuseableCode::check_rights(256);

$AccYearDate = DB::table('company')->select('accyearfrom', 'accyearto')->where('id', $_GET['m'])->first();
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
                        <div class="row align-items-center">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <div class="headquid ">
                                    <span class="subHeadingLabelClass">View Delivery Note List</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <div class="headquid ">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList', '', '1');?>
                                    <?php if ($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export
                                        <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                            </div>
                        </div>
                        <hr style="border-color: #ccc">

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label>Filters</label>
                                <select id="filters" onchange="FilterSelection()" class="form-control">
                                    <option value="0">Select</option>
                                    <option value="1">Search By Date</option>
                                    <option value="2">Search By Voucher No</option>
                                    <option value="3">Search By Buyer</option>
                                </select>
                            </div>
                            <span id="ShowHideDate" style="display: none">
                                <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>From Date</label>
                                        <input type="Date" name="from" id="from"
                                            value="<?php echo $currentMonthStartDate;?>" class="form-control"
                                            min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo?>" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label>To Date</label>
                                        <input type="Date" name="to" id="to" max="<?php ?>"
                                            value="<?php echo $currentMonthEndDate;?>" class="form-control"
                                            min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo?>" />
                                    </div>
                                    <div style="margin-top: 40px" class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="radio-inline"><input value="1" type="radio"
                                                name="optradio">Open</label>

                                        <label class="radio-inline"><input value="3" type="radio"
                                                name="optradio">Complete</label>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <input type="button" value="Search" class="btn btn-sm btn-primary"
                                            onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                    </div>
                                </div>
                            </span>
                            <span id="ShowHideSoNo" style="display: none">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>(SO NO) <input type="radio" class="form-control" name="FilterType" value="1"
                                            onclick="RadioChange()"></label>
                                    <label for=""> OR </label>
                                    <label>(DN NO) <input type="radio" class="form-control" name="FilterType" value="2"
                                            onclick="RadioChange()"></label>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label for="">Search By <span id="ChangeType"></span></label>
                                    <input type="text" class="form-control" id="SearchText" name="SearchText" disabled>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <!-- <button type="button" class="btn btn-sm btn-danger" style="margin-top: 32px;"
                                        id="BtnReset" onclick="ResetFields()">Reset</button> -->
                                    <input type="button" value="Search" class="btn btn-sm btn-primary"
                                        onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </span>
                            <div class="col-lg-9 col-md-9 col-sm-9 col-xs-12" id="ShowHideBuyer" style="display: none">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label>Buyer</label>
                                    <select name="BuyerId" id="BuyerId" class="form-control">
                                        <option value="">Select Buyer</option>
                                        <?php foreach ($Customer as $Fil):?>
                                        <option value="<?php    echo $Fil->id?>"><?php    echo $Fil->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>
                                <div style="margin-top: 40px" class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="radio-inline"><input value="1" type="radio" name="optradio">Open</label>

                                    <label class="radio-inline"><input value="3" type="radio"
                                            name="optradio">Complete</label>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                    <input type="button" value="Search" class="btn btn-sm btn-primary"
                                        onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>
                        <div class="panel">
                            <div class="panel-body" id="PrintEmpExitInterviewList">
                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center col-sm-1">S.No</th>
                                                        <th class="text-center col-sm-1">SO No</th>
                                                        <th class="text-center col-sm-1">DN No</th>
                                                        <th class="text-center col-sm-1">DN Date</th>
                                                        <th class="text-center col-sm-1">Order No</th>
                                                        <th class="text-center col-sm-1">Order Date</th>
                                                        <th class="text-center">Customer</th>
                                                        <th class="text-center">Total Qty. (KG)</th>
                                                        <th class="text-center">Total Amount.</th>
                                                        <th class="text-center">DN Status</th>
                                                        <th class="text-center">Username</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data">

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
                XLSX.writeFile(wb, fn || ('Delivery Note <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function () {
            $('#BuyerId').select2();
            $('.select2-container--default').css('width', '100%');

            viewRangeWiseDataFilter();
        });
        function delivery_note_delete(id, m) {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url = '<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url + '/sad/delivery_not_delete',
                    type: 'GET',
                    data: { id: id, m: m },
                    success: function (response) {
                        //alert(response); return false;
                        if (response == '0') {
                            alert('Can not Deleted')
                        }
                        else {
                            alert('Deleted');
                            // alert(response);
                            $('#' + id).remove();
                        }
                    }
                });
            }
        }


        function delete_record(id) {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: { id: id },
                    success: function (response) {

                        alert('Deleted');
                        $('#' + id).remove();
                    }
                });
            }
        }


        function RadioChange() {
            var radioValue = $("input[name='FilterType']:checked").val();

            if (radioValue == 1) {
                $('#SearchText').prop('disabled', false);
                $('#ChangeType').html('SO NO');
                $('#SearchText').prop('placeholder', 'Enter SO NO');
            }
            else if (radioValue == 2) {
                $('#SearchText').prop('disabled', false);
                $('#ChangeType').html('DN NO');
                $('#SearchText').prop('placeholder', 'Enter DN NO');
            }
            else {
                $('#ChangeType').html('');
                $('#SearchText').prop('placeholder', '');
                $('#SearchText').prop('disabled', true);
            }
        }

        function ResetFields() {
            $('input[name="FilterType"]').attr('checked', false);
            $('#ChangeType').html('');
            $('#SearchText').prop('placeholder', '');
            $('#SearchText').val('');
            $('#SearchText').prop('disabled', true);
        }

        function viewRangeWiseDataFilter() {
            var radioValue = $("input[name='FilterType']:checked").val();
            var FilterType = $('#filters').val();
            var SearchText = $('#SearchText').val();
            var BuyerId = $('#BuyerId').val();
            var from = $('#from').val();
            var to = $('#to').val();
            var radio = $('input[name="optradio"]:checked').val();
            var m = '<?php echo $m;?>';
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '{{ url('/') }}/sdc/getDeliveryNoteFilterWise',
                type: 'Get',
                data: { from: from, to: to, m: m, radioValue: radioValue, SearchText: SearchText, FilterType: FilterType, BuyerId: BuyerId, radio: radio },
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }

        function delivery_note(id, m) {
            var base_url = '<?php echo URL::to('/'); ?>';
            window.location.href = base_url + '/sales/EditDeliveryNote?id=' + id + '&&' + 'm=' + m;
        }

        function FilterSelection() {
            //            var radioValue = $("input[name='SelectType']:checked").val();
            var radioValue = $('#filters').val();
            if (radioValue == 1) {
                $('#ShowHideDate').fadeIn('slow');
                $('#ShowHideSoNo').css('display', 'none');
                $('#ShowHideBuyer').css('display', 'none');
                $("input:radio").removeAttr("checked");
            }
            else if (radioValue == 2) {
                $('#ShowHideSoNo').fadeIn('slow');
                $('#ShowHideDate').css('display', 'none');
                $('#ShowHideBuyer').css('display', 'none');
                $("input:radio").removeAttr("checked");
            }
            else if (radioValue == 3) {
                $('#ShowHideBuyer').fadeIn('slow');
                $('#ShowHideSoNo').css('display', 'none');
                $('#ShowHideDate').css('display', 'none');
                $("input:radio").removeAttr("checked");
            }
            else {
                $('#ShowHideBuyer').css('display', 'none');
                $('#ShowHideSoNo').css('display', 'none');
                $('#ShowHideBuyer').css('display', 'none');
                $("input:radio").removeAttr("checked");
            }
        }

    </script>

@endsection