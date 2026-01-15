<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(249);
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
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <span class="subHeadingLabelClass">Trial Balance Report 5th Column</span>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                    <a id="dlink" style="display:none;"></a>
                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div id="printBankPaymentVoucherList">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="row">

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="">From Date</label>
                                        <input type="date" max="<?php echo $AccYearTo?>" min="<?php echo $AccYearFrom?>" value="<?php echo $currentMonthStartDate?>" class="form-control" id="FromDate" name="FromDate">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="">To Date</label>
                                        <input type="date" max="<?php echo $AccYearTo?>" min="<?php echo $AccYearFrom?>" value="<?php echo $currentMonthEndDate?>" class="form-control" id="ToDate" name="ToDate">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <button class="btn btn-sm btn-primary" id="BtnGetData" onclick="GetTrialBalanceDataAjax()" style="margin: 35px 0px 0px 0px;">Submit</button>
                                    </div>


                                </div>

                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="">
                                        <span id="AjaxDataHere"></span>
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
            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
                //$('.SpacesCls').css('display','block');
            }
            else{
                $('.SpacesCls').html('');

            }
            var elt = document.getElementById('table_export1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Trial Balance Report 5th Column <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script !src="">
        function GetTrialBalanceDataAjax()
        {
            var FromDate = $('#FromDate').val();
            var ToDate = $('#ToDate').val();
            var AccYearFrom = '<?php echo $AccYearFrom?>';
            var AccYearTo = '<?php echo $AccYearTo?>';
            var m = '<?php echo $m?>';
            $('#AjaxDataHere').html('<div class="loader"></div>');
            $.ajax({
                url: '<?php echo url('/')?>/fdc/getTrialBalanceDataAjax',
                type: "GET",
                data: {FromDate:FromDate,ToDate:ToDate,AccYearFrom:AccYearFrom,AccYearTo:AccYearTo,m:m},
                success: function (data) {
                $('#AjaxDataHere').html(data);
                    $('#OtherArea').css('display','block');
                }
            });
        }

        function newTabOpen(FromDate,ToDate,AccCode)
        {
            var m = '<?php echo $m?>';
            var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
            window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode+'&&m='+m, '_blank');
        }



        //table to excel (multiple table)
        var array1 = new Array();
        var n = 1; //Total table

        for ( var x=1; x<=n; x++ ) {
            array1[x-1] = 'table_export' + x;
        }
        var tablesToExcel = (function () {
            var uri = 'data:application/vnd.ms-excel;base64,'
                    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets>'
                    , templateend = '</x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head>'
                    , body = '<body>'
                    , tablevar = '<table>{table'
                    , tablevarend = '}</table>'
                    , bodyend = '</body></html>'
                    , worksheet = '<x:ExcelWorksheet><x:Name>'
                    , worksheetend = '</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet>'
                    , worksheetvar = '{worksheet'
                    , worksheetvarend = '}'
                    , base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
                    , format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
                    , wstemplate = ''
                    , tabletemplate = '';

            return function (table, name, filename) {
                var tables = table;
                var wstemplate = '';
                var tabletemplate = '';

                wstemplate = worksheet + worksheetvar + '0' + worksheetvarend + worksheetend;
                for (var i = 0; i < tables.length; ++i) {
                    tabletemplate += tablevar + i + tablevarend;
                }

                var allTemplate = template + wstemplate + templateend;
                var allWorksheet = body + tabletemplate + bodyend;
                var allOfIt = allTemplate + allWorksheet;

                var ctx = {};
                ctx['worksheet0'] = name;
                for (var k = 0; k < tables.length; ++k) {
                    var exceltable;
                    if (!tables[k].nodeType) exceltable = document.getElementById(tables[k]);
                    ctx['table' + k] = exceltable.innerHTML;
                }

                document.getElementById("dlink").href = uri + base64(format(allOfIt, ctx));;
                document.getElementById("dlink").download = filename;
                document.getElementById("dlink").click();
            }
        })();


        function AddRemoveSpace()
        {
            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
                //$('.SpacesCls').css('display','block');
            }
            else{
                $('#AccountSpaces').attr('disabled','disabled');
                $('.SpacesCls').hide();
            }

//            var decide = $('#AccountSpaces').val();
//            if(decide == 1)
//            {
//                $('.SpacesCls').css('display','inline');
//                $('.SpacesCls').css('display','block');
//            }
//            else{
//                $('.SpacesCls').css('display','none');
//            }
        }

        function ResetFunc()
        {

            $('#trial_bal').html('');
            GetTrialBalanceDataAjax();
            $('#AccountSpaces').attr('disabled',false);
            $('#AccountSpaces').val('1');

        }





    </script>
@endsection
