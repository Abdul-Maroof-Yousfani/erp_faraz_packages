<?php use App\Helpers\CommonHelper; ?>
<?php use App\Helpers\PurchaseHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(243);

$financial_year=ReuseableCode::get_account_year_from_to($_GET['m']);

?>
@extends('layouts.default')
@section('content')
    @include('select2')




    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label for="email">From Date</label>
                <input id="from_date"  required="required" min="{{$financial_year[0]}}" name="from_date" max="{{$financial_year[1]}}" class="date1 form-control" type="date" value="<?php echo $financial_year[0] ?>" />
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <label for="email">To Date</label>
                <input id="to_date" required="required" min="{{$financial_year[0]}}" max="{{$financial_year[1]}}" name="from_date" class="date1 form-control" type="date" value="{{$financial_year[1]}}" />
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                <label for="">Items</label>
                <select name="ItemId" id="ItemId" class="form-control">
                    <option value="all">ALL</option>
                    <?php foreach($SubItem as $ItemFil):?>
                    <option value="<?php echo $ItemFil->id?>"><?php echo $ItemFil->sub_ic?></option>
                    <?php endforeach;?>
                </select>
            </div>


            <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                <button type="button" class="btn btn-sm btn-primary" onclick="stockReportItemWise()" style="margin: 25px 0px 0px 0px;">Get Data</button>
            </div>
            <input type="hidden" id="accyearfrom" value="{{$financial_year[0]}}"/>
        </div>

        <div>&nbsp;</div>

        <div id="printBankReceiptVoucherList">
            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body">
                            <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
                            <?php echo CommonHelper::displayPrintButtonInBlade('filterBookDayList','HrefHide','1');?>
                            <?php if($export == true):?>
                            <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            <?php endif;?>
                            <div id="filterBookDayList"></div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>




    <script>

        $(document).ready(function(){
            $('#ItemId').select2();
        });
        function stockReportItemWise(){
            var ReportType =2;
            var from_date = $('#from_date').val();
            var to_date = $('#to_date').val();
            var ItemId = $('#ItemId').val();
            var accyearfrom = $('#accyearfrom').val();
            $('#filterBookDayList').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
            $.ajax({
                url: '<?php echo url('/')?>/store/stock_movemnet',
                method:'GET',
                data:{from_date:from_date,to_date:to_date,accyearfrom:accyearfrom,ItemId:ItemId,ReportType:ReportType},
                error: function()
                {
                    alert('error');
                },
                success: function(response){
                    $('#filterBookDayList').html(response);
                }
            });
        }
    </script>
@endsection