<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper; ?>
@extends('layouts.default')
@section('content')
    @include('select2')

    <style>
        element.style {
            width: 183px;
        }
    </style>


    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label for="email">From Date</label>
                <input id="from_date" readonly required="required" name="from_date" class="date1 form-control" type="date" value="<?php echo '2020-07-01' ?>" />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label for="email">To Date</label>
                <input id="to_date" required="required" name="from_date" class="date1 form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <button type="button" class="btn btn-sm btn-primary" onclick="stockReportItemWise()" style="margin: 25px 0px 0px 0px;">Get Data</button>
            </div>

        </div>

        <div>&nbsp;</div>

        <div id="printBankReceiptVoucherList">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <?php echo CommonHelper::displayPrintButtonInBlade('filterBookDayList','HrefHide','1');?>
                            <?php //echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            <div id="filterBookDayList"></div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

    </script>


    <script>
        function stockReportItemWise(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();

            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/store/stockReportItemWiseAjax',
                method:'GET',
                data:{from_date:from_date,to_date:to_date},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
        }
    </script>
@endsection