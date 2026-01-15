<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
$from = date('Y-m-01');
$to   = date('Y-m-t');

$view=ReuseableCode::check_rights(124);
$edit=ReuseableCode::check_rights(125);
$export=ReuseableCode::check_rights(258);


?>
@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">POS List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                <?php endif;?>
                            </div>
                        </div>
                    </div>


                    <div class="row">

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>From Date</label>
                            <input type="Date" name="from" id="from"  value="<?php echo $from;?>" class="form-control" />
                        </div>

                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                            <label>To Date</label>
                            <input type="Date" name="to" id="to" max="<?php ?>" value="<?php echo $to;?>" class="form-control" />
                        </div>


                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                            <input type="button" value="View Filter Data" class="btn btn-sm btn-primary" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                        </div>
                    </div>


                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitInterviewList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView(Session::get('run_company'));?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <span id="data"></span>
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
            var elt = document.getElementById('pos');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Pos <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script !src="">
        function delete_pos(id)
        {


            if (confirm('Are you sure you want to delete this request')) {
                var base_url='<?php echo URL::to('/'); ?>';
                $.ajax({
                    url: base_url+'/sdc/delete_pos',
                    type: 'GET',
                    data: {id: id},
                    success: function (response) {

                      
                        if (response=='0')
                        {
                            alert('Can not Delete');
                            return false;
                        }

                            $('#' + id).remove();

                    }
                });
            }
            else{}
        }

        function get_pos_data()
        {
            //var BuyerId = $('#BuyerId').val();
            var from= $('#from').val();
            var to= $('#to').val();

            $('#data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '/sdc/pos_list_flter_wise',
                type: 'Get',
                data: {from: from,to:to},

                success: function (response)
                {

                    $('#data').html(response);


                }
            });

        }
        $( document ).ready(function() {
            get_pos_data();
        });



        
    </script>


@endsection