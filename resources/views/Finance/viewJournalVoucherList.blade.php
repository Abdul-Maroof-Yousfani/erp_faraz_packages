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
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printJournalVoucherList','','1');?>

                            <a id="dlink" style="display:none;"></a>
                            <input type="button" class="btn btn-sm btn-warning" onclick="tablesToExcel(array1, 'Sheet1', 'J.V <?php echo date('d-M-Y')?>.xls')" value="Export to Excel">
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Journal Voucher List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <input type="hidden" name="functionName" id="functionName" value="fdc/filterJournalVoucherList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="tbodyId" id="tbodyId" value="filterJournalVoucherList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="filterType" id="filterType" value="1" readonly="readonly" class="form-control" />

                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Account Head</label>
                                    <input type="hidden" readonly name="selectAccountHeadId" id="selectAccountHeadId" class="form-control" value="">
                                    <input list="selectAccountHead" name="selectAccountHead" id="selectAccountHeadTwo" class="form-control clearable">
                                    <?php echo CommonHelper::accountHeadSelectList($m);?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" /></div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>

                <input type="hidden" id="viewRangeWiseDataFilter" value="0"/>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printJournalVoucherList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered sf-table-list" id="journalVoucherList1">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">ID</th>
                                                            <th class="text-center">J.V. No.</th>
                                                            <th class="text-center">J.V. Date</th>
                                                            <th class="text-center">Debit/Credit</th>
                                                            <th class="text-center">Ref / Bill No.</th>
                                                            <th class="text-center">Voucher Status</th>
                                                            <th class="text-center">Amount</th>
                                                            <th class="text-center">Total Amount</th>
                                                            <th class="text-center hidden-print">Action</th>
                                                            </thead>
                                                            <tbody id="filterJournalVoucherList">

                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Journal Voucher List'))!!} ">
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php /*?>
<div class="dropdown" style="display: none;">
            <button class="btn btn-default dropdown-toggle" type="button" id="menu1" data-toggle="dropdown">Tutorials
                <span class="caret"></span></button>
            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">HTML</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">CSS</a></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">JavaScript</a></li>
                <li role="presentation" class="divider"></li>
                <li role="presentation"><a role="menuitem" tabindex="-1" href="#">About Us</a></li>
            </ul>
        </div>
<?php */?>
    <script src="{{ URL::asset('assets/custom/js/customFinanceFunction.js') }}"></script>
    <script type="text/javascript">
        function DeleteJvActivity(JvId,JvNo,UserName,DeleteDate,DeleteTime,ActivityType)
        {
            if (confirm('Are you sure you want to delete this thing into the Software?'))
            {
                var m = '<?php echo $_GET['m'];?>';
                $.ajax({
                    url: '<?php echo url('/')?>/fdc/DeleteJvActivity',
                    type: "GET",
                    data: { JvId:JvId,JvNo:JvNo,UserName:UserName,DeleteDate:DeleteDate,
                        DeleteTime:DeleteTime,ActivityType:ActivityType},
                    success:function(data) {
                        alert('Delete');
                        $("."+data).remove();
                        return false;
                    //    filterVoucherList();
                    }
                });
            }
            else
            {
                // Do nothing!
            }

        }

        var array1 = new Array();
        var n = 1; //Total table

        for ( var x=1; x<=n; x++ ) {
            array1[x-1] = 'journalVoucherList' + x;
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

    </script>
@endsection