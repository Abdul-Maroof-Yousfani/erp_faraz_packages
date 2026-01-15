<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$export=ReuseableCode::check_rights(247);
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');


        $AccId = '';
        $FromDate = '';
        $ToDate = '';
    if(isset($_GET['AccId'])):
        $AccId = $_GET['AccId'];
    else:
        $AccId = '';
    endif;

    if(isset($_GET['FromDate'])):
        $currentMonthStartDate = $_GET['FromDate'];
    else:
        $currentMonthStartDate = date('Y-m-01');
    endif;

    if(isset($_GET['ToDate'])):
        $currentMonthEndDate = $_GET['ToDate'];
    else:
        $currentMonthEndDate   = date('Y-m-t');
    endif;
$All = session()->all();

        $AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',Session::get('run_company'))->first();
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
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <span class="subHeadingLabelClass">Filter Ledger Report</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInView('loadFilterLedgerReport','','1');?>
                                    <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Parent Account Head:</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" name="account_id" id="account_id" onchange="">
                                                        <option value="">Select Account</option>
                                                        @foreach($accounts as $key => $y)
                                                            <option value="{{ $y->id.','.$y->type}}" <?php if($AccId == $y->id):echo "selected";endif;?>>{{ $y->code .' ---- '. $y->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
                                                    <label>Cost Center</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <span id="Loader"></span>
                                                    <select class="form-control  select2" name="paid_to" id="paid_to">
                                                        <option value="0">Select</option>
                                                        @foreach($companydepartments as $key => $y)

                                                                <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                                    <?php
                                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `department_id` ='.$y->id.'');
                                                                    ?>
                                                                    @foreach($subdepartments as $key2 => $y2)
                                                                        <option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                                    @endforeach
                                                                </optgroup>
                                                            @endforeach
                                                    </select>
                                                </div>


                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>From Date</label>
                                                            <input type="Date" name="fromDate" id="fromDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control"/>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>To Date</label>
                                                            <input type="Date" name="toDate" id="toDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="loadFilterLedgerReport">
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
            var elt = document.getElementById('table_export1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Ledger Report <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>


    <script type="text/javascript">

        function AppendPaidTo()
        {
            var AccountAndType = $('#account_id').val();
            AccountAndType = AccountAndType.split(",");

            if(AccountAndType[0] != "0")
            {
                $('#Loader').html('<img src="/assets/img/loading.gif" alt="">');
                $.ajax({
                    url: '<?php echo url('/')?>/fmfal/getBranchClientWiseLedger',
                    type: "GET",
                    data: {AccountAndType:AccountAndType},
                    success: function (data) {
                        $('#paid_to').html('');
                        $('#paid_to').html(data);
                        $('#Loader').html('');
                    }
                });
            }
            else
            {
                $('#paid_to').html('');
            }
        }
        $(document).ready(function(){
            <?php if($AccId !=""):?>
            viewRangeWiseDataFilter();
            <?php endif;?>
            $('#account_id').select2();
            $('#paid_to').select2();
        });

        function viewRangeWiseDataFilter() {

            var ActFrom = '<?php echo $AccYearFrom?>';
//            var FromDate = $('#fromDate').val();
//
//            var FromFilter = FromDate.split('-');
//            var ActFilter = ActFrom.split('-');
//
//            FromFilter = FromFilter[0]+'-'+FromFilter[1];
//            ActFilter = ActFilter[0]+'-'+ActFilter[1];

            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            if(fromDate < ActFrom)
            {
                alert('please select correct date');
                return false;
            }
            // Parse the entries
            var startDate = Date.parse(fromDate);
            var endDate = Date.parse(toDate);
            // Make sure they are valid
            if (isNaN(startDate)) {
                alert("The start date provided is not valid, please enter a valid date.");
                return false;
            }
            if (isNaN(endDate)) {
                alert("The end date provided is not valid, please enter a valid date.");
                return false;
            }
            // Check the date range, 86400000 is the number of milliseconds in one day
            var difference = (endDate - startDate) / (86400000 * 7);
            if (difference < 0) {
                alert("The start date must come before the end date.");
                return false;
            }
            loadFilterLedgerReport();
        }

        function loadFilterLedgerReport() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var paid_to = $('#paid_to').val();
            var accountName = $('#account_id').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#loadFilterLedgerReport').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/fdc/loadFilterLedgerReport',
                method:'GET',
                data:{fromDate:fromDate,toDate:toDate,m:m,accountName:accountName,paid_to:paid_to},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    setTimeout(function(){
                        $('#loadFilterLedgerReport').html(response);
                    },1000);
                }
            });
        }
    </script>
@endsection
