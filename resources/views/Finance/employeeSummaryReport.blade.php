<?php
use App\Helpers\CommonHelper;
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
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                        <span class="subHeadingLabelClass">Employee Summary</span>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                        <button type="button" class="btn btn-sm btn-warning" onclick="tableToExcel('EmpExitInterviewList', 'Receivable Report Detail')">Export to Excel</button>
                                    </div>
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
                                                            <input type="Date" name="FromDate" id="FromDate" max="<?php echo $current_date;?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                                            <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>To Date</label>
                                                            <input type="Date" name="ToDate" id="ToDate" max="<?php echo $current_date;?>" value="<?php echo date('Y-m-d');?>" class="form-control" />
                                                        </div>
                                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                                            <label>Format</label>
                                                            <select name="Format" id="Format" class="form-control" >
                                                                <option value="1">Summary</option>
                                                                <option value="2">Detail</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                            <input type="button" value="Show" class="btn btn-sm btn-primary" onclick="EmployeeSummaryReport();" style="margin-top: 32px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="employeeSummaryReport"></div>
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


        function EmployeeSummaryReport() {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var Format = $('#Format').val();
            var m = '<?php echo $_GET['m'];?>';
            $('#employeeSummaryReport').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            $.ajax({
                url: '<?php echo url('/');?>/fdc/employeeSummaryReport',
                method:'GET',
                data:{FromDate:FromDate,ToDate:ToDate,Format:Format,m:m},
                error: function(){
                    alert('error');
                },
                success: function(response)
                {

                    $('#employeeSummaryReport').html(response);

                }
            });
        }

        var tableToExcel = (function() {
            var uri = 'data:application/vnd.ms-excel;base64,'
                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
                    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
                    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
            return function(table, name) {
                if (!table.nodeType) table = document.getElementById(table)
                var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
                window.location.href = uri + base64(format(template, ctx))
            }
        })()
    </script>
@endsection