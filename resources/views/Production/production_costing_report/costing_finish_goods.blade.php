<?php

use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
$m=Session::get('run_company');
?>
@extends('layouts.default')
@section('content')
    @include('select2')


    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Finish Good Costing Report</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('data','','1');?>

                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>

                            </div>
                        </div>
                    </div>
                    <hr style="border-color: #ccc">


                    <div class="row">




                        <span id="ShowHideSoNo" style="">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <label>PPC NO</label>
                               <select onchange="get_data()" class="form-control select2" id="ppc_no">
                                   <option value="0">Select</option>
                                   @foreach(ProductionHelper::get_all_ppc_no() as $row)
                                       <option value="{{ $row->id }}">{{ strtoupper($row->order_no) }}</option>
                                   @endforeach
                               </select>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 ">
                                <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="get_data();" style="margin-top: 32px;" />
                            </div>
                        </span>
                        <div class="lineHeight">&nbsp;</div>
                        <span id="data"></span>
                    </div>


                    <div class="lineHeight">&nbsp;</div>


                </div>
                </div>
            </div>
        </div>
        
    </div>
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('data_table');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                XLSX.writeFile(wb, fn || ('Production Detail Report <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>

        $(document).ready(function(){
            $('.select2').select2();
            $('.select2-container--default').css('width','100%');
        });


        function get_data()
        {

            var ppc_no= $('#ppc_no').val();


            $('#data').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/production/get_finish_goods_data',
                type: 'Get',
                data: {ppc_no : ppc_no},

                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>

@endsection