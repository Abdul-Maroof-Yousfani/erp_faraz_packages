<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
?>
@extends('layouts.default')
@section('content')
    @include('select2')

 
    <div class="lineHeight">&nbsp;</div>
    <div class="row container-fluid">

        <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Sale Order List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                               
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                               
                            </div>
                        </div>
                    </div>
                    <hr style="border-color: #ccc">


                    <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="from" id="from"  class="form-control" />
                                </div>

                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="to" id="to"  class="form-control"  />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>So No</label>
                                    <input type="text" name="SoNo" id="SoNo" class="form-control" placeholder="SO NO"  />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                               
                            </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView(1);?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center col-sm-1">SO No</th>
                                            <th class="text-center col-sm-1">SO Date</th>
                                            <th class="text-center col-sm-1">Purchase Order NO </th>
                                            <th class="text-center col-sm-1">Purchase Order Date</th>
                                            <th class="text-center">Customer</th>
                                            <th class="text-center">Approval Status</th>
                                            <th class="text-center">Action</th>
                                            </thead>
                                            <tbody id="data">
                                            

                                            <tr>
                                                <td class="text-center" colspan="7" style="font-size: 20px;">Total</td>
                                                <td class="text-right" colspan="1" style="font-size: 20px;color: white"></td>
                                                <td class="text-center" colspan="2" style="font-size: 20px;"></td>

                                            </tr>
                                          
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
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('EmpExitInterviewList');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Sales Order <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function(){
            $('#BuyerId').select2();
            viewRangeWiseDataFilter();
        });
  
        function sale_order_delete(id)
        {
            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/sad/sale_order_delete',
                    type: 'GET',
                    data: {id: id},
                    success: function (response) {

                        if (response=='0')
                        {
                            alert('Can not Deleted')
                        }

                        else {
                            alert('Deleted');
                            // alert(response);
                            $('#' + id).remove();
                        }

                    }
                });
            }
            else{}
        }


        function delete_record(id)
        {

            if (confirm('Are you sure you want to delete this request')) {
                $.ajax({
                    url: '/pdc/deletepurchasevoucher',
                    type: 'Get',
                    data: {id: id},

                    success: function (response) {


                    }
                });
            }
            else{}
        }


        function viewRangeWiseDataFilter()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            var SoNo= $('#SoNo').val();
            
     
            $('#data').html('<tr><td colspan="13"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            $.ajax({
                url:"{{route('salesorder.index')}}",
                type: 'Get',
                data: {from: from,to:to,SoNo:SoNo},
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }
    </script>

@endsection