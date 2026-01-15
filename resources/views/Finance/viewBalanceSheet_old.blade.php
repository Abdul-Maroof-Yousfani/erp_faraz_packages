<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(250);


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

    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                        <?php if($export == true):?>
                            <a id="dlink" style="display:none;"></a>
                            <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                        <?php endif;?>
                        <?php // echo CommonHelper::displayExportButton('trial_bal','','1')?>
                    </div>
                    {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">--}}
                    {{--@include('Finance.'.$accType.'financeMenu')--}}
                    {{--</div>--}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Balance Sheet</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                                    @if($AccYearFrom=='2020-07-01')
                                        <?php $from_date='2020-06-30'; ?>
                                    @else
                                        <?php $from_date=$AccYearFrom; ?>
                                    @endif
                                    <div class="row">
                                        <input type="hidden" id="from_date" value="{{$from_date}}"/>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                            <label>As On </label>
                                            <input name="to_date" id="to_date" class="form-control" type="date" max="<?php echo $AccYearTo?>" min="<?php ?>"  required="required" value="<?php echo date('Y-m-d')?>" />

                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label>Type</label>
                                            <select style="width: 100%" id="RadioVal" class="form-control">
                                                <option value="1">SUMMARY</option>
                                                <option value="2">DETAIL</option>
                                            </select>


                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label> &nbsp; &nbsp;  </label>
                                            <button onclick="Generate()" type="button" class="btn btn-sm btn-primary" style="margin: 30px 0px 0px 0px;">Submit</button>

                                        </div>
                                        

                                    </div>
                                    <span id="Error"></span>
                                </div>

                            </div>

                            <span id="trial_bal"></span>
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
            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
                //$('.SpacesCls').css('display','block');
            }
            else{
                $('.SpacesCls').html('');

            }
            var elt = document.getElementById('MultiExport');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet3" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Balance Sheet <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
        }
    </script>
    <script>
        function Generate()
        {
            $('#trial_bal').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
            from_date = $("#from_date").val();
            to_date = $("#to_date").val();
            var type=5;
            var RadioVal = $("#RadioVal").val();
            if(from_date!="" && to_date!="") {
                m = '<?= $_GET['m']; ?>';
                $.ajax({
                    url: '<?php echo url('/');?>/fdc/trialBalanceSheet_old',
                    type: 'GET',
                    data: {from_date: from_date, to_date: to_date, m: m,type:type,RadioVal:RadioVal},
                    success: function (response) {
                        //var v = $.trim(response);
                        $('#trial_bal').html(response);
                        $('#OtherArea').css('display','block');
                        //alert(response);
                    }
                });
            }
        }

        function newTabOpen(FromDate,ToDate,AccCode)
        {

            var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
            window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode, '_blank');
        }

        function AddRemoveSpace()
        {
            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
                //$('.SpacesCls').css('display','block');
            }
            else{
                $('#AccountSpaces').attr('disabled','disabled');
                $('.SpacesCls').hide();
            }

//            var decide = $('#AccountSpaces').val();
//            if(decide == 1)
//            {
//                $('.SpacesCls').css('display','inline');
//                $('.SpacesCls').css('display','block');
//            }
//            else{
//                $('.SpacesCls').css('display','none');
//            }
        }

        function ResetFunc()
        {

            $('#trial_bal').html('');
            Generate();
            $('#AccountSpaces').attr('disabled',false);
            $('#AccountSpaces').val('1');

        }
        
    </script>
@endsection
