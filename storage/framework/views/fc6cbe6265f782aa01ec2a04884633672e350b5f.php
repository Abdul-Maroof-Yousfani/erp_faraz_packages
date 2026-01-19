<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(234);


$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
        $m=Session::get('run_company');
        $data=ReuseableCode::get_account_year_from_to($m);
        $from=$data[0];
        $to=$data[0];
?>



<?php $__env->startSection('content'); ?>
    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Goods Receipt Note List</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('printGoodsReceiptNoteList','','1');?>
                                <?php if($export == true):?>
                                <a id="dlink" style="display:none;"></a>
                                <input type="button" class="btn btn-warning" onclick="tablesToExcel(array1, 'Sheet1', 'GRN <?php echo date('d-M-Y')?>.xls')" value="Export to Excel">
                            <?php endif;?>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <input type="hidden" name="functionName" id="functionName" value="pdc/filterGoodsReceiptNoteList" readonly="readonly" class="form-control" />
                    <input type="hidden" name="tbodyId" id="tbodyId" value="filterGoodsReceiptNoteList" readonly="readonly" class="form-control" />
                    <input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
                    <input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />
                    <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly" class="form-control" />
                    <input type="hidden" name="filterType" id="filterType" value="2" readonly="readonly" class="form-control" />
                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Sub Department</label>
                            <input type="hidden" readonly name="selectSubDepartmentId" id="selectSubDepartmentId" class="form-control" value="">
                            <input list="selectSubDepartment" name="selectSubDepartment" id="selectSubDepartmentTwo" class="form-control clearable">
                            <?php echo CommonHelper::subDepartmnetSelectList($m);?>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>Supplier Name</label>
                            <input type="hidden" readonly name="selectSupplierId" id="selectSupplierId" class="form-control" value="">
                            <input list="selectSupplier" name="selectSupplier" id="selectSupplierTwo" class="form-control clearable">
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
                            <label>Select Voucher Status</label>
                            <select name="selectVoucherStatus" id="selectVoucherStatus" class="form-control">
                                <?php echo CommonHelper::voucherStatusSelectList();?>
                            </select>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter(0);" style="margin-top: 32px;" />
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>GRN No</label>
                            <input type="text"  name="grn_no" id="grn_no" class="form-control" value="">
                            <span id="GrnNoError"></span>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 text-right">
                            <input type="button" value="Search" class="btn btn-sm btn-primary" onclick="get_grn(1);" style="margin-top: 32px;" />
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row" id="printGoodsReceiptNoteList">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list" id="export_table_to_excel_1">
                                                    <thead>
                                                    <th class="text-center">S.No</th>
                                                    <th class="text-center">GRN NO..</th>
                                                    <th class="text-center">PI NO..</th>
                                                    <th class="text-center">GRN Date</th>
                                                    <th class="text-center">PO No.</th>
                                                    <th class="text-center">Supplier Invoice No.</th>
                                                    <th class="text-center">Supplier Name</th>
                                                    <th class="text-center">GRN Net Amount</th>
                                                    <th class="text-center">GRN Status</th>
                                                    <th class="text-center">Created User</th>
                                                    <th class="text-center hidden-print">Action</th>
                                                    </thead>
                                                    <tbody id="filterGoodsReceiptNoteList"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
                            <img src="data:image/png;base64, <?php echo base64_encode(QrCode::format('png')->size(200)->generate('View Goods Receipt Note Voucher List')); ?> ">
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            get_grn();
        });
        function approve_grn(id)
        {
            $.ajax({
                url: '<?php echo url('/')?>/pdc/approve_grn',
                type: 'Get',
                data: {id: id},
                success: function (response)
                {
                    $('.'+id).html('Approve');
                    $('#showDetailModelOneParamerter').modal('toggle');
                }
            });
        }


        function get_grn(id)
        {
            var GrnNo = $('#grn_no').val();
            var m='<?php echo e(Session::get('run_company')); ?>';
            if(GrnNo !="")
            {
                $('#GrnNoError').html('');
                $('#filterGoodsReceiptNoteList').html('<tr><td colspan="11" class="loader"></td></tr>')
                $.ajax({
                    url: '<?php echo e(url('/')); ?>/pdc/filterGoodsReceiptNoteList',
                    type: 'Get',
                    data: {GrnNo: GrnNo,m:m},

                    success: function (response) {
                        //alert(response);
                        $('#filterGoodsReceiptNoteList').html(response);
                    }
                });
            }
            else
            {
                $('#GrnNoError').html('<p class="text-danger">Enter Grn No..!</p>');
            }



        }

        function import_costing(id,number)
        {
            if ($('#' + id).is(":checked"))
            {
                $('.'+id).fadeIn(500);
                var total_qty= $('#total_qty_'+number).val();
                $('#Sachets_'+number).text(total_qty);
                var amount=$('#amount_'+number).val();
            }
            else
            {
                $('.'+id).fadeOut(500);
            }
        }

        function DeleteGrn(Id)
        {
            //alert();
            ///*
            if (confirm('Are You Sure ? You want to delete this recored...!')) {
                var m = '<?php echo $m?>';

                //$('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
                $.ajax({
                    url: '/pdc/DeleteGrn',
                    type: 'Get',
                    data: {Id: Id,m:m},

                    success: function (response) {
                        //alert(response);
                        $('#RemoveTr'+response).remove();
                    }
                });
            }
            else {}
            //*/
        }

        function MasterDeleteGrn(Id)
        {
            if (confirm('Are You Sure ? You want to delete this recored...!')) {
                var m = '<?php echo $m?>';
                //$('#data').html('<tr><td colspan="14"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
                $.ajax({
                    url: '<?php echo url('/'); ?>/pdc/MasterDeleteGrn',
                    type: 'Get',
                    data: {Id: Id,m:m},
                    success: function (response) {
              
                        if (response==0)
                        {
                            alert('Delete Purchase Return First');
                        }
                        else if(response == 'yes')
                        {
                            alert('Negative Stock Not Allowed...!');
                        }
                        else
                        {
                            $('#RemoveTr'+response).remove();
                        }

                    }
                });
            }
            else {}
            //*/
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



    <script src="<?php echo e(URL::asset('assets/custom/js/customPurchaseFunction.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>