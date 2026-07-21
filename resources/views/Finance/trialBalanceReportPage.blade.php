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

    <style>
 /* ---------- Nature filter pills (Assets / Liabilities / Capital / Expenses / Revenue) ---------- */
 .tb-nature-filter{display:flex !important;align-items:center !important;gap:12px !important;flex-wrap:wrap !important;}
.tb-nature-pill{display:inline-flex !important;align-items:center !important;gap:8px !important;background:#F7F9FD !important;border:1px solid #E3E7F3 !important;border-radius:999px !important;padding:9px 16px !important;cursor:pointer !important;transition:border-color .15s ease,background .15s ease !important;user-select:none !important;}
.tb-nature-pill:hover{border-color:#B9C2E0 !important;}
.tb-nature-pill input[type="radio"]{width:15px !important;height:15px !important;margin:0 !important;accent-color:#1E3A8A !important;cursor:pointer !important;}
.tb-nature-pill span{font-size:12px !important;font-weight:500 !important;letter-spacing:.4px !important;text-transform:uppercase !important;color:#4A5268 !important;}
.tb-nature-pill.active{background:#EEF1FA !important;border-color:#1E3A8A !important;}
.tb-nature-pill.active span{color:#0B1F59 !important;}
/* ---------- Filter bar:single row,no column-overflow wrap ---------- */
 .tb-filter-bar{display:flex !important;align-items:flex-end !important;gap:18px !important;flex-wrap:wrap !important;margin-bottom:6px !important;}
.tb-filter-bar .tb-filter-field{display:flex !important;flex-direction:column !important;gap:4px !important;}
.tb-filter-bar .tb-filter-field label{margin:0 !important;font-size:12.5px !important;font-weight:500 !important;color:#4A5268 !important;}
.tb-filter-bar .tb-filter-field input[type="date"]{height:38px !important;}
.tb-filter-bar #BtnGetData{height:38px !important;margin:0 !important;}

    </style>

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
                                    <div class="tb-filter-bar">
                                        <div class="tb-filter-field">
                                            <label for="">From Date</label>
                                            <input type="date" max="<?php echo $AccYearTo?>" min="<?php echo $AccYearFrom?>" value="<?php echo $currentMonthStartDate?>" class="form-control" id="FromDate" name="FromDate">
                                        </div>
                                        <div class="tb-filter-field">
                                            <label for="">To Date</label>
                                            <input type="date" max="<?php echo $AccYearTo?>" min="<?php echo $AccYearFrom?>" value="<?php echo $currentMonthEndDate?>" class="form-control" id="ToDate" name="ToDate">
                                        </div>
                                        <div class="tb-nature-filter" id="NatureFilterGroup">
                                            <label class="tb-nature-pill active">
                                                <input type="radio" name="NatureFilter" value="" checked>
                                                <span>All</span>
                                            </label>
                                            <label class="tb-nature-pill">
                                                <input type="radio" name="NatureFilter" value="1">
                                                <span>Assets</span>
                                            </label>
                                            <label class="tb-nature-pill">
                                                <input type="radio" name="NatureFilter" value="2">
                                                <span>Liabilities</span>
                                            </label>
                                            <label class="tb-nature-pill">
                                                <input type="radio" name="NatureFilter" value="3">
                                                <span>Capital</span>
                                            </label>
                                            <label class="tb-nature-pill">
                                                <input type="radio" name="NatureFilter" value="4">
                                                <span>Expenses</span>
                                            </label>
                                            <label class="tb-nature-pill">
                                                <input type="radio" name="NatureFilter" value="5">
                                                <span>Revenue</span>
                                            </label>
                                        </div>
                                        <button class="btn btn-sm btn-primary" id="BtnGetData" onclick="GetTrialBalanceDataAjax()">Submit</button>
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
        $(document).on('click', '.tb-nature-pill', function(){
            $('.tb-nature-pill').removeClass('active');
            $(this).addClass('active');
            applyNatureFilter();
        });

        // Client-side row filter — matches each row's data-nature attribute
        // (added on <tr> in the trial balance table partial) against the selected pill.
        // Runs immediately on pill click, and again after every AJAX reload.
        function applyNatureFilter()
        {
            var NatureFilter = $('input[name="NatureFilter"]:checked').val();
            if(NatureFilter === '' || NatureFilter === undefined){
                $('#tbl_id tr').show();
                return;
            }
            $('#tbl_id tr').each(function(){
                var rowNature = $(this).attr('data-nature');
                if(rowNature === undefined){
                    // total/difference rows etc. without a nature — always keep visible
                    $(this).show();
                }else if(rowNature === NatureFilter){
                    $(this).show();
                }else{
                    $(this).hide();
                }
            });
        }

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
            var NatureFilter = $('input[name="NatureFilter"]:checked').val();
            $('#AjaxDataHere').html('<div class="loader"></div>');
            $.ajax({
                url: '<?php echo url('/')?>/fdc/getTrialBalanceDataAjax',
                type: "GET",
                data: {FromDate:FromDate,ToDate:ToDate,AccYearFrom:AccYearFrom,AccYearTo:AccYearTo,m:m,NatureFilter:NatureFilter},
                success: function (data) {
                $('#AjaxDataHere').html(data);
                    $('#OtherArea').css('display','block');
                    applyNatureFilter();
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