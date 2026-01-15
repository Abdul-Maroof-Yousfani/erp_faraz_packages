<?php
$m = Session::get('run_company');

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate = date('Y-m-t');

?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="row well_N">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Sales Tax Invoice Report</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                <?php //echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                <?php //if($export == true):?>
                <?php //echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                <?php //endif;?>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>From Date</label>
                                    <input type="Date" name="from" id="from" value="<?php echo $currentMonthStartDate;?>"
                                        class="form-control" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center">
                                    <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
                                    <input type="text" readonly class="form-control text-center" value="Between" />
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                    <label>To Date</label>
                                    <input type="Date" name="to" id="to" max="<?php ?>"
                                        value="<?php echo $currentMonthEndDate;?>" class="form-control" />
                                </div>

                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label>Buyer</label>
                                    <select name="buyer" id="buyer" class="form-control">
                                        <option value="">Select Buyer</option>
                                        <?php foreach (SalesHelper::get_all_customer() as $row):?>
                                        <option value="<?php    echo $row->id?>"><?php    echo $row->name?></option>
                                        <?php endforeach;?>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="Search" class="btn btn-sm btn-primary"
                                        onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
                                </div>
                            </div>


                            <div class="lineHeight">&nbsp;</div>
                            <div class="panel">
                                <div class="panel-body" id="PrintEmpExitInterviewList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
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
    </div>

    <script !src="">

        function viewRangeWiseDataFilter() {
            //var BuyerId = $('#BuyerId').val();
            var from = $('#from').val();
            var to = $('#to').val();
            var buyer = $('#buyer').val();
            var m = '<?php echo $m;?>';
            $('#data').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '{{ url('/') }}/sdc/getSalesTaxInvoiceReportData',
                type: 'Get',
                data: { from: from, to: to, m: m, buyer: buyer },

                success: function (response) {

                    $('#data').html(response);


                }
            });

        }
        $(document).ready(function () {
            $('#buyer').select2();
        });
    </script>
@endsection