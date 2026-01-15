<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Finance.'.$accType.'financeMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Paid To Expense Report</span>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="row">
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>From Date</label>
                                                            <input type="Date" name="FromDate" id="FromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>To Date</label>
                                                            <input type="Date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Paid To</label>
                                                            <select name="PaidTo" id="PaidTo" class="form-control">
                                                                <option value="All">All</option>
                                                                <option value="1">Employee</option>
                                                                <option value="2">Customer</option>
                                                                <option value="3">Supplier</option>
                                                                <option value="4">Buyer</option>
                                                                <option value="5">Owner</option>
                                                                <option value="6">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <input type="button" value="Show" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="PaidToReport"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#account_id').select2();
        });
        function viewRangeWiseDataFilter() {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            // Parse the entries
            var startDate = Date.parse(FromDate);
            var endDate = Date.parse(ToDate);
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
            PaidToReport();
        }

        function PaidToReport() {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var PaidTo = $('#PaidTo').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#PaidToReport').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/fdc/paidToExpenseReport',
                method:'GET',
                data:{FromDate:FromDate,ToDate:ToDate,m:m,PaidTo:PaidTo},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    setTimeout(function(){
                        $('#PaidToReport').html(response);
                    },1000);
                }
            });
        }
    </script>
@endsection