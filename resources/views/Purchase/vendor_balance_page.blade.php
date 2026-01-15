<?php


use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;

$export=ReuseableCode::check_rights(302);



$data=ReuseableCode::get_account_year_from_to(Session::get('run_company'));
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
                        <div class="">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Vendor Balance </span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <button class="btn btn-primary" onclick="printView('PrintEmpExitInterviewList','','1')" style="">
                                    <span class="glyphicon glyphicon-print"></span> Print
                                </button>
                                <?php if($export == true):?>
                                <a id="dlink" ></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div style=""  class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>From Date</label>
                            <input type="Date" name="from" id="from"  value="<?php echo '2017-07-01';?>" class="form-control" min="<?php ?>" max="<?php ?>"/>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>As On</label>
                            <input type="Date" name="to" id="to" max="<?php  ?>" value="<?php echo date('Y-m-d')?>" class="form-control" min="<?php  ?>"  />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <label for="">Vendor Name</label>
                            <select onchange="" name="vendor" id="vendor" class="form-control select2">
                                <option value="0">All</option>
                                <?php foreach(CommonHelper::get_all_supplier() as $row):?>
                                <option value="<?php echo $row->id?>"><?php echo $row->name?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-left">
                            <input type="button" value="Submit" class="btn btn-sm btn-primary" onclick="get_data()" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView(Session::get('run_company'));?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive" id="data">

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
            var elt = document.getElementById('MultiExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Vendor Balance <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function(){
            $('.select2').select2();
        });




        function get_data()
        {

            var from= $('#from').val();
            var to= $('#to').val();
            var vendor= $('#vendor').val();
            $('#data').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/pdc/vendor_balance_ajax_data',
                type: 'Get',
                data: {from: from,to:to,vendor:vendor},

                success: function (response)
                {

                    $('#data').html(response);


                }
            });


        }


    </script>

@endsection