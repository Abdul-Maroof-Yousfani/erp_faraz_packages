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
                <input id="from_date" required="required" name="from_date" class="date1 form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group ">
                    <label for="email">To Date</label>
                    <input id="to_date" required="required" name="to_date" class="date1 form-control" type="date" value="<?php echo date('Y-m-d'); ?>" />
                </div>
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group ">
                    <button type="button" class="btn btn-success" onclick="BookDayList();">Submit</button>
                </div>
            </div>
        </div>

        <div>&nbsp;</div>

        <div id="printBankReceiptVoucherList">
            <div class="row">
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12"></div>
                <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <?php echo CommonHelper::displayPrintButtonInBlade('filterBookDayList','HrefHide','1');?>
                            <?php //echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            <div id="filterBookDayList"></div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12"></div>
            </div>
        </div>

    </div>

    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

    </script>


    <script>
        function BookDayList(){
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var to_date = $('#to_date').val();
            var region = $('#region').val();
            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/store/InventoryStockReportAjax',
                method:'GET',
                data:{from_date:from_date, to_date:to_date, region:region},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
        }

        function calc(Id)
        {
            //alert(Id);
            var Rate = parseFloat($('#Rate'+Id).val());
            var Qty = parseFloat($('#Qty'+Id).val());
            //alert(Qty);
            var Amount = parseFloat(Rate*Qty);
            $('#Amount'+Id).val(Amount);
        }

        function UpdateRateAmount(Id)
        {
            //alert(); return false;
            var Rate = $('#Rate'+Id).val();
            var Amount = $('#Amount'+Id).val();
            $.ajax({
                url: '<?php echo url('/')?>/store/UpdateRateAmount',
                method:'GET',
                data:{Id:Id,Rate:Rate,Amount:Amount},
                error: function(){
                    alert('error');
                },
                success: function(response){
                    alert(response);
                }
            });
        }
    </script>
@endsection