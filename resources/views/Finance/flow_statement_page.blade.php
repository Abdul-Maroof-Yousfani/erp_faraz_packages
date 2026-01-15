<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(251);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>

@extends('layouts.default')
@section('content')

    <div class="">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

                        <?php // echo CommonHelper::displayExportButton('trial_bal','','1')?>
                    </div>
                    {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">--}}
                    {{--@include('Finance.'.$accType.'financeMenu')--}}
                    {{--</div>--}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-2">
                                    <span class="subHeadingLabelClass">Flow Statement</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">

                                    <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                                    <?php if($export == true):?>
                                    <a id="dlink" style="display:none;"></a>
                                    <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-right">
                                    <button type="button" class="btn btn-xs btn-primary" style="width: 67px;" id="BtnDown"><i class="fa fa-arrow-down" aria-hidden="true"></i></button>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 text-left">
                                    <button style="width: 67px;" type="button" class="btn btn-xs btn-primary" id="BtnUp"><i class="fa fa-arrow-up" aria-hidden="true"></i></button>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row" id="SlideUpDown">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <input name="from_date" id="from_date" max="<?php echo $AccYearTo ?>" min="<?php echo $AccYearFrom?>" required="required"  class="form-control" type="date" value="<?php echo $currentMonthStartDate?>" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <input name="to_date" id="to_date" class="form-control" type="date" max="<?php echo $AccYearTo?>" min="<?php echo $AccYearFrom?>"  required="required" value="<?php echo $currentMonthEndDate?>" />
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <select name="" id="" class="form-control">
                                                <option value="">Summary</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <button onclick="Generate()" type="button" class="btn btn-sm btn-primary">Submit</button>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">

                                        </div>

                                    </div>
                                    <span id="Error"></span>
                                </div>

                            </div>

                            <span id="trial_bal"></span>
                            <span id="NewAjax" style="display: none;"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('exportIncomeStatement1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Flow <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        $(document).ready(function(){
            $('#BtnDown').css('display','none');
            $('#BtnUp').css('display','none');
        });
        $("#BtnDown").click(function(){
            $("#SlideUpDown").slideDown();
            $('#BtnDown').css('display','none');
            $('#BtnUp').css('display','block');
        });
        $("#BtnUp").click(function(){
            $("#SlideUpDown").slideUp();
            $('#BtnDown').css('display','block');
            $('#BtnUp').css('display','none');
        });
        function Generate()
        {
            $('#trial_bal').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            from_date = $("#from_date").val();
            to_date = $("#to_date").val();
            m = '<?= $_GET['m']; ?>';
            $.ajax({
                url: '<?php echo url('/');?>/fdc/flow_statement_ajax',
                type: 'GET',
                data: {from_date: from_date, to_date: to_date, m:m},
                success: function (response) {
                    $('#BtnUp').css('display','none');
                    $('#BtnDown').css('display','block');

                    $('#trial_bal').html(response);
                    $('#NewAjax').html(response);

                    $('#SlideUpDown').slideUp();

                }
            });
        }


        function newTabOpen(FromDate,ToDate,AccCode)
        {

            var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
            window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode, '_blank');
        }


    </script>
    <div class="col-sm-12">&nbsp;</div>

@endsection
