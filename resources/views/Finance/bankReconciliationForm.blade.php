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
                                    <span class="subHeadingLabelClass">Bank Reconciliation Form</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInView('loadFilterLedgerReport','','1');?>
                                    <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                            <form action="{{route('saveBankReconciliationForm')}}" method="post">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                    <label>Bank Account:</label>
                                                    <!-- <span class="rflabelsteric"><strong>*</strong></span> -->
                                                    <select class="form-control" name="account_id" id="account_id" onchange="validationDateByAccount(event)">
                                                        <option value="">Select Bank</option>
                                                        @foreach(CommonHelper::get_all_account_operat_with_unique_code('1-2-8') as $key => $value)
                                                            <option value="{{ $value->id}}">{{ $value->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                             


                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>From Date</label>
                                                            <input type="Date" onchange="validateToDateUsingFromDate(event)" disabled name="fromDate" id="fromDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control"/>
                                                        </div>

                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <label>To Date</label>
                                                            <input type="Date" disabled name="toDate" id="toDate" min="<?php echo $AccYearFrom?>" max="<?php echo $AccYearTo;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12" style="padding: 36px 30px;">
                                                            <a class="btn btn-sm btn-primary" disabled id="search" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" >Search</a>
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
                               
                            </form>
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

        function formatDate(date) {
            var yyyy = date.getFullYear().toString();
            var mm = (date.getMonth() + 1).toString().padStart(2, '0'); // January is 0!
            var dd = date.getDate().toString().padStart(2, '0');

            return yyyy + '-' + mm + '-' + dd;
        }

        var date = new Date(), y = date.getFullYear(), m = date.getMonth();
        var firstDay = new Date(y, m, 1);
        var lastDay = new Date(y, m + 1, 0);

        firstDay = formatDate(firstDay);
        lastDay = formatDate(lastDay);

        function loadFilterLedgerReport() {
            var fromDate = $('#fromDate').val();
            var toDate = $('#toDate').val();
            var paid_to = $('#paid_to').val();
            var accountName = $('#account_id').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#loadFilterLedgerReport').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/finance/bankReconciliationForm',
                method:'GET',
                data:{fromDate:fromDate,toDate:toDate,m:m,accountName:accountName,paid_to:paid_to},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    setTimeout(function(){
                        $('#loadFilterLedgerReport').html(response);
                    },1000);

                    if(response == 0)
                    {
                        $('#loadFilterLedgerReport').empty();
                        alert('Bank Reconciliation already created');
                    }
                    else
                    {
                        setTimeout(function(){
                            $('#loadFilterLedgerReport').html(response);
                        },1000);
                    }
                }
            });
        }



        function validationDateByAccount(e)
        {
            $('#loadFilterLedgerReport').empty();

            let element = e.target;

            if(element.value)
            {
                $('#fromDate').removeAttr('disabled');
                $('#search').removeAttr('disabled');
                
                $.ajax({
                url:   '<?php echo url('/');?>/finance/getLastDateByAccount',
                method: 'GET',
                data:   {
                            account_id: element.value
                        },
                error:  function(){
                    alert('error');
                },
                success: function(response){
    
                    if(response.result == 1)
                    {
                        $('#fromDate').val(response.min);
                        $('#toDate').val(response.min);
                        $('#fromDate').attr('min',response.min);
                        $('#toDate').attr('min',response.min);
                        $('#toDate').removeAttr('disabled');
                    }
                    else
                    {
                        $('#toDate').removeAttr('disabled');
                        $('#fromDate').val(firstDay);
                        $('#toDate').val(lastDay);
                        $('#fromDate').removeAttr('min');                        
                        $('#toDate').attr('min',firstDay);

                    }

                    
                }
            });

            }
            else
            {

                $('#fromDate').val(firstDay);
                $('#toDate').val(lastDay);
                $('#fromDate').removeAttr('min');
                $('#toDate').removeAttr('min');
                $('#fromDate').attr('disabled','disabled');
                $('#toDate').attr('disabled','disabled');
                $('#search').attr('disabled','disabled');
                
            }
        }


        function validateToDateUsingFromDate(e)
        {

            let element = e.target;

            if(element.value)
            {
                $('#toDate').attr('min',element.value);
                $('#toDate').val(element.value);
                $('#toDate').removeAttr('disabled');
                $('#search').removeAttr('disabled');

            }
            else
            {
                $('#toDate').attr('disabled','disabled');
                $('#search').attr('disabled','disabled');
                $('#toDate').val(lastDay);
                $('#toDate').removeAttr('min');

            }



        }




    </script>
@endsection
