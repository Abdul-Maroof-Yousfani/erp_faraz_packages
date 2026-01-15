@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no = CommonHelper::generateUniquePosNo('sales_order', 'so_no', 'SO');
?>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row borderBtmMnd pTB40">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid">
                                    <h2 class="subHeadingLabelClass">Machine Processing List</h2>
                                </div>
                                <div id="printBankPaymentVoucherList">
                                    <div class="panel">
                                        <div id="PrintPanel">
                                            <div id="ShowHide">
                                                <div class="table-responsive">
                                                    <h5 style="text-align: center" id="h3"></h5>
                                                    <table class="userlittab table table-bordered sf-table-list"
                                                        id="TableExportToCsv">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">S No</th>
                                                                <th class="text-center">Production Plan No</th>
                                                                <th class="text-center">Item Name</th>
                                                                <th class="text-center">Production Qty</th>
                                                                <th class="text-center">Date</th>
                                                                <th class="text-center">Status</th>
                                                                <th class="text-center">Receive</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data"></tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row borderBtmMnd pTB40">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div id="printBankPaymentVoucherList">
                                    <div class="panel">
                                        <div id="PrintPanel">
                                            <div id="ShowHide">
                                                <div class="table-responsive">
                                                    <h5 style="text-align: center" id="h3"></h5>
                                                    <table class="userlittab table table-bordered sf-table-list"
                                                        id="TableExportToCsv">
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th class="text-center">S No</th>
                                                                <!-- <th class="text-center">Job Card No</th> -->
                                                                <th class="text-center">Out Roll No</th>
                                                                <th class="text-center">Machine</th>
                                                                <th class="text-center">Operator</th>
                                                                <th class="text-center">Shift</th>
                                                                <th class="text-center">Machine Process date</th>
                                                                <th>Ready Length</th>
                                                                {{-- <th class="text-center">After Printing Weight</th>
                                                                --}}
                                                                {{-- <th class="text-center">Transfer</th> --}}
                                                                <th class="text-center">machine process stage</th>
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data_1"></tbody>
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
    </div>
</div>
<script>
    $(document).ready(function () {
        viewProductInProccess();
        viewProductProccessComplete();
    });



    function viewProductInProccess() {

        var Filter = $('#search').val();


        $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

        $.ajax({
            url: '<?php echo url('/')?>/selling/viewProductInProccess',
            type: 'Get',
            data: { Filter: Filter },
            success: function (response) {

                $('#data').html(response);


            }
        });


    }

    function viewProductProccessComplete() {

        var Filter = $('#search').val();


        $('#data_1').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

        $.ajax({
            url: '<?php echo url('/')?>/selling/viewProductProccessComplete',
            type: 'Get',
            data: { Filter: Filter },
            success: function (response) {

                $('#data_1').html(response);


            }
        });
    }
</script>

@endsection