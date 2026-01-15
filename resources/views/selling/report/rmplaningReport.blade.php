@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
?>

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Report</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Report </h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <ul class="cus-ul2">
                <li>
                    {{-- <a href="{{route('createSaleOrder')}}" class="btn btn-primary"  >Create New Sale Order</a> --}}
                </li>
                <li>
                    <input type="text" class="fomn1" onkeypress="viewRangeWiseDataFilter()" id="search" placeholder="Search Anything" >
                </li>
                {{-- <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">    
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                <div class="headquid">
                            
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <h2 class="subHeadingLabelClass">View RM Planing Report </h2>
                                    <span style="float: right" >
                                        <?php echo CommonHelper::displayPrintButtonInBlade('printPurchaseRequestVoucherList','','1');?>
                                    
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                            
                                    </span>

                                </div>
                            </div>
                            </div>
                                    <div class="row"  id="printPurchaseRequestVoucherList">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table userlittab  table-bordered sf-table-list" id="data_table">
                                                        <thead>
                                                            <th>S.No</th>
                                                            <th>Category</th>
                                                            <th>Product Name</th>
                                                            <th>Unit</th>
                                                            <th>Shipment Qty</th>
                                                            <th>Stock In Hand</th>
                                                            <th>Balance Quantity</th>
                                                            
                                                            <th>Status</th>
                                                            <th>Stock Sufficient  No Of Days</th>
                                                            <th>Stock Sufficient Till Date </th>
                                                            <th>Earliest Shipment ETA</th>
                                                            <th>Earliest Shipment Qty</th>
                                                            <th>Provision (No Of Days)</th>
                                                            <th>Earliest Shipment Description</th>
                                                            <th>Stock + Under Ship (No Of Days)</th>
                                                            <th>Stock +Under Ship (till Date)</th>
                                                            <th>Next Material Order Due Date</th>
                                                            
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
    </div>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script>


        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('data_table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('RM Planning Report <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    $(document).ready(function(){
        // viewRangeWiseDataFilter();
        });
         function viewRangeWiseDataFilter()
        {

            var Filter=$('#search').val();

           
            $('#data').html('<tr><td colspan="22"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/rmplaningReport',
                type: 'Get',
                data: {Filter:Filter},
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>


@endsection