<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper; ?>
@extends('layouts.default')
@section('content')
    @include('select2')

  <style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500;600&display=swap');

.stockRptWrap { font-family: 'Inter', sans-serif; }
.stockRptWrap .dp_sdw {
    background: #fff;
    border: 1px solid #EEF0F7 !important;
    border-radius: 18px !important;
    box-shadow: 0 8px 26px rgba(20,38,92,0.06) !important;
    padding: 26px 28px !important;
    margin-bottom: 20px;
}
.stockRptWrap .form-group label {
    font-size: 12.5px !important;
    font-weight: 700 !important;
    color: #4A5268 !important;
    text-transform: uppercase;
    letter-spacing: .3px;
    margin-bottom: 6px !important;
}
.stockRptWrap select.form-control,
.stockRptWrap .select2-container .select2-selection--single {
    border-radius: 10px !important;
    border: 1.5px solid #E7EAF3 !important;
    background: #FBFCFE !important;
    height: 40px !important;
    font-size: 13.5px !important;
}
.stockRptWrap .select2-selection__rendered { line-height: 38px !important; color: #1B2333 !important; }
.stockRptWrap .select2-selection__arrow { height: 38px !important; }
.stockRptWrap select.form-control:focus,
.stockRptWrap .select2-container--focus .select2-selection--single {
    border-color: #7C5CFC !important;
}
.stockRptWrap .btn-primary {
    background: linear-gradient(135deg, #FF7A45, #7C5CFC) !important;
    border: none !important;
    border-radius: 10px !important;
    font-weight: 700 !important;
    padding: 9px 22px !important;
    box-shadow: 0 8px 18px rgba(124,92,252,0.25) !important;
}
.stockRptWrap .btn-warning {
    background: #FFF4E5 !important;
    color: #B5651D !important;
    border: 1.5px solid #FCE0B8 !important;
    border-radius: 10px !important;
    font-weight: 700 !important;
    box-shadow: none !important;
}

/* report/print card */
#printBankReceiptVoucherList .panel {
    border-radius: 18px !important;
    border: 1px solid #EEF0F7 !important;
    box-shadow: 0 8px 26px rgba(20,38,92,0.06) !important;
    overflow: hidden;
}
#printBankReceiptVoucherList .panel-body { padding: 0 !important; }
.stockRptWrap input.form-control,
.stockRptWrap input[type="date"] {
    border-radius: 10px !important;
    border: 1.5px solid #E7EAF3 !important;
    background: #FBFCFE !important;
    height: 40px !important;
    font-size: 13.5px !important;
    color: #1B2333 !important;
    box-shadow: none !important;
}
.stockRptWrap input.form-control:focus,
.stockRptWrap input[type="date"]:focus {
    border-color: #7C5CFC !important;
    background: #fff !important;
    outline: none !important;
}
.sfReportBox {
    font-family: 'Inter', sans-serif;
    padding: 6px 0 10px;   /* pehle 26px 28px 30px tha */
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


                            <div class="container-fluid stockRptWrap">
                                <div class="well_N">
                                    <div class="dp_sdw">
                                        <div id="filterBookDayList"></div>
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