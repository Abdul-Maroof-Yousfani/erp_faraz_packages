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
                <label for="email">Month-Year</label>
                <input id="from_date" required="required" name="from_date" class="date1 form-control" type="month" value="<?php echo date('Y-m'); ?>" />
            </div>

            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <div class="form-group">
                    <button type="button" class="btn btn-primary" onclick="BookDayList();" style="margin: 30px 0px 0px 0px;">Submit</button>
                </div>
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
        function BookDayList(){
            var from_date = $('#from_date').val();
            //var to_date = $('#to_date').val();
            //var to_date = $('#to_date').val();
            var region = $('#region').val();

            //alert(from_date); return false;

            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/store/rateAndAmountupdateAjax',
                method:'GET',
                data:{from_date:from_date, region:region},
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

        function calcGrn(Id)
        {
            //alert(Id);
            var Rate = parseFloat($('#GrnRate'+Id).val());
            var Qty = parseFloat($('#GrnQty'+Id).val());
            //alert(Qty);
            var Amount = parseFloat(Rate*Qty);
            $('#GrnAmount'+Id).val(Amount);
        }

        function calcReturn(Id)
        {
            //alert(Id);
            var Rate = parseFloat($('#ReturnRate'+Id).val());
            var Qty = parseFloat($('#ReturnQty'+Id).val());
            //alert(Qty);
            var Amount = parseFloat(Rate*Qty);
            $('#ReturnAmount'+Id).val(Amount);
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

        function UpdateRateAmountGrn(Id)
        {
            //alert(); return false;
            var Rate = $('#GrnRate'+Id).val();
            var Amount = $('#GrnAmount'+Id).val();
            $.ajax({
                url: '<?php echo url('/')?>/store/UpdateRateAmountGrn',
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

        function UpdateRateAmountReturn(Id)
        {
            //alert(); return false;
            var Rate = $('#ReturnRate'+Id).val();
            var Amount = $('#ReturnAmount'+Id).val();
            $.ajax({
                url: '<?php echo url('/')?>/store/UpdateRateAmountReturn',
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