<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(233);
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

<?php
    $data=DB::Connection('mysql2')->select('select username,COUNT(username)countt from purchase_request where status=1 GROUP by username order by countt desc');

    ?>
    <!-- <div class="lineHeight">&nbsp;</div>
    <div class="row container-fluid">

        <?php  foreach($data as $row): ?>
        {{--<div class="col-lg-1 col-md-1 col-sm-1 col-xs-1 text-center" style="border: solid 1px #ccc">--}}
        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-center" style="border: solid 1px #ccc">
            <p>{{strtoupper($row->username)}} <span class="badge badge-primary">&nbsp;{{' '.$row->countt}}</span></p>
        </div>
        <?php endforeach;?>
    </div> -->



    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="Quotation_head">
                                        <span class="subHeadingLabelClass">View Purchase Order List</span>
                                        <span style="float: right" >
                                            <?php echo CommonHelper::displayPrintButtonInBlade('printPurchaseRequestVoucherList','','1');?>
                                            <?php if($export == true):?>
                                                <a id="dlink" style="display:none;"></a>
                                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                            <?php endif;?>
                                        </span>
                                    </div>
                                    <hr style="border:1px solid #ddd">
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <input type="hidden" name="functionName" id="functionName" value="stdc/filterPurchaseRequestVoucherList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="tbodyId" id="tbodyId" value="filterPurchaseRequestVoucherList" readonly="readonly" class="form-control" />
                            <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                            <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly" class="form-control" />
                            <input type="hidden" name="filterType" id="filterType" value="2" readonly="readonly" class="form-control" />

                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Department</label>
                                    <input type="hidden" readonly name="selectSubDepartmentId" id="selectSubDepartmentId" class="form-control" value="">
                                    <input list="selectSubDepartment" name="selectSubDepartment" id="selectSubDepartmentTwo" class="form-control">
                                    <?php echo CommonHelper::subDepartmnetSelectList($m);?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Supplier Name</label>
                                    <input type="hidden" readonly name="selectSupplierId" id="selectSupplierId" class="form-control" value="">
                                    <input list="selectSupplier" name="selectSupplier" id="selectSupplierTwo" class="form-control">
                                    <?php echo CommonHelper::supplierSelectList($m);?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>
                               
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>Voucher Status</label>
                                    <select class="form-control" name="selectVoucherStatus" id="selectVoucherStatus" class="form-control">
                                        <?php echo CommonHelper::voucherStatusSelectList();?>
                                        <option value="4">Grn Created</option>
                                    </select>
                                </div>

                                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                                    <input type="button" value="Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label for="">Po No</label>
                                    <input type="text" class="form-control" id="PoNo" name="PoNo" placeholder="PO NO">
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <input type="button" value="Search with PO NO" class="btn btn-sm btn-primary" onclick="getDataPoNoWise();" style="margin-top: 32px;" />
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div >
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel" id="printPurchaseRequestVoucherList">
                                        <div class="panel-body">
                                            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered sf-table-list table-hover" id="data_table">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">P.O. No.</th>
                                                            <th class="text-center">P.O. Date</th>
                                                            <th class="text-center hide">STRN </th>
                                                            <th class="text-center hide">Remarks</th>
                                                            <th class="text-center">Supplier Name</th>
                                                            <th class="text-center">P.O Status</th>
                                                            <th class="text-center">Created User</th>
                                                            <th class="text-center">Amount</th>
                                                            <th class="text-center hidden-print">Action</th>
                                                            </thead>
                                                            <tbody id="filterPurchaseRequestVoucherList"></tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                                    <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Purchase Request List (Office Use)'))!!} ">
                                </div> -->
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('assets/custom/js/customStoreFunction.js') }}"></script>
<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script>


        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('data_table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('PO List <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }


        var table=['purchase_request','purchase_request_data'];
        var id=['id','master_id'];
        function Delete_Again_For_Po(id)
        {
            if (confirm('Are you sure you want to delete this PO'))
            {
                $.ajax({
                    url: '<?php echo url('/') ?>/pd/DeleteAgainForPO',
                    type: 'GET',
                    data: {id:id},
                    success: function (response)
                    {
                        alert('Delete');
                        $('#tr' + id).remove();
                    }
                });
            }
            else
            {

            }
        }

        function RejectPo(Id)
        {
            if (confirm('Are you sure you want to reject this PO'))
            {
                $.ajax({
                    url: '<?php echo url('/') ?>/pd/reject_po',
                    type: 'GET',
                    data: {Id:Id},
                    success: function (response)
                    {
                        if (response=='0')
                        {
                            alert('GRN EXISTS AGAINST THIS PO');
                        }
                       else
                        {
                            $('#tr' + Id).remove();
                        }
                    }
                });
            }
            else
            {

            }
        }

        function getDataPoNoWise()
        {
            var PoNo= $('#PoNo').val();
            var m ='<?php echo $m?>';
            if(PoNo != '') {
                $('#PoNo').css('border', '');
                $('#filterPurchaseRequestVoucherList').html('<tr><td colspan="10"><div class="loader"></div></td><tr>');
                $.ajax({
                    url: '{{ url('/') }}/stdc/getPoDataPoNoWise',
                    type: 'Get',
                    data: {m:m,PoNo:PoNo},
                    success: function (response) {
                        $('#filterPurchaseRequestVoucherList').html(response);
                    }
                });
            } else {
                $('#PoNo').css('border', '2px solid red');
                return;
            }
           
        }


        //table to excel (multiple table)
        var array1 = new Array();
        var n = 1; //Total table

        for ( var x=1; x<=n; x++ ) {
            array1[x-1] = 'export_table_to_excel_' + x;
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