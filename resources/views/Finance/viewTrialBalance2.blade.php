<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(248);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m =Session::get('run_company');
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
$AccYearDate = DB::table('company')->select('accyearfrom','accyearto')->where('id',$_GET['m'])->first();
$AccYearFrom = $AccYearDate->accyearfrom;
$AccYearTo = $AccYearDate->accyearto;

?>


<script>

    function show()
    {
        var from=	$('#from_datee').val();

        var nature=$("input[name='optradio']:checked").val();

        var  m = '<?php echo $company_id;?>';
        //alert(m);
        var to=	$('#to_date').val();
        //var url='< ?php echo base_url('finance_data_call/trial'); ?>';

        if(from !="" && to != "" ) {


            $('#trial_bal').html('<div class="loader"></div>');
            $('#Error').html("");

            $.ajax({
                url: '<?php echo url('/');?>/fdc/trialBalanceData',
                type: 'GET',
                data: {from: from, to: to, nature: nature,m:m},
                success: function (response) {

                    var v = $.trim(response);

                    $('#trial_bal').html(response);
                    $('#OtherArea').css('display','block');
                }
            });
        }else{
            $('#Error').html('<p class="text-danger">Select From And To Date</p>')
        }
    }

    function newTabOpen(FromDate,ToDate,AccCode)
    {
        var  m = '<?php echo $company_id;?>';
        var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
        window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode+'&&m='+m, '_blank');
    }





</script>
<script>
    $(document).ready(function(e) {
        $('#print').click(function(){
          
            $("div").removeClass("table-responsive");
            $("div").removeClass("well");
            var content = $("#includes").html()+$("#header").html()+$("#trial_bal").html();
            document.body.innerHTML = content;
            //var content = document.getElementById('header').innerHTML;
            //var content2 = document.getElementById('content').innerHTML;
            window.print();
            location.reload();
        })
    });
</script>
@extends('layouts.default')
@section('content')

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">--}}
                    {{--@include('Finance.'.$accType.'financeMenu')--}}
                    {{--</div>--}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well_N">
                        <div class="dp_sdw">    
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <span class="subHeadingLabelClass">Trial Balance 6th Column</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                    <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                                    <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                    <?php // echo CommonHelper::displayExportButton('trial_bal','','1')?>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php echo Form::open(array('url' => 'fad/addAccountDetail?m='.$m.'','id'=>'chartofaccountForm'));?>
                                    <div class="row">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">

                                            <div class="wrapper1" style="margin-top:5px;">
                                                <input

                                                        id="from_datee"
                                                        min="<?php echo $AccYearFrom?>"
                                                        max="<?php echo $AccYearTo?>"
                                                        required="required"
                                                        onchange="valid_date('from_date','to_date');"
                                                        name="from"
                                                        class="form-control"
                                                        type="date"
                                                        value="<?php echo $currentMonthStartDate?>"
                                                        />


                                            </div>

                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <date-util format="dd-mm-yyyy"></date-util>
                                            <input name="to"
                                                   class="form-control"
                                                   type="date"
                                                   min="<?php echo $AccYearFrom?>"
                                                   max="<?php echo $AccYearTo?>"
                                                   id="to_date"
                                                   required="required"
                                                   value="<?php echo $currentMonthEndDate?>"
                                                    />
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                            <label class="radio-inline"><input type="radio" id="optradio" name="optradio" value="1"><b>ASSETS</b></label>
                                            <label class="radio-inline"><input type="radio" id="optradio" name="optradio" value="2"><b>LIABILITIES</b></label>
                                            <label class="radio-inline"><input type="radio" name="optradio" value="3"><b>CAPITAL</b></label>
                                            <label class="radio-inline"><input type="radio" name="optradio" value="4"><b>EXPENSES</b></label>
                                            <label class="radio-inline"><input type="radio" name="optradio" value="5"><b>REVENUE</b></label>
                                            <input type="button" onclick="show()" class="btn btn-sm btn-primary" value="Submit"/>

                                        </div>



                                    </div>
                                    <?php echo Form::close();?>
                                    <span id="Error"></span>
                                </div>

                            </div>



                            <div class="row">


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                    <?php /*?>    	<a href="<?php echo base_url() ?>finance/income_statement" class="btn btn-xs btn-success">INCOME STATEMENT</a><?php */?>
                                    <?php /*?><a  href="<?php echo base_url() ?>finance/chart_of_accounts" class="btn btn-xs btn-success">CHART OF ACCOUNTS</a><?php */?>
                                    <?php /*?><a href="<?php echo base_url() ?>finance/bal_sheet" class="btn btn-xs btn-success">BALANCE SHEET</a><?php */?>

                                    {{--<button class="btn btn-xs btn-primary" id="print">PRINT</button>--}}


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

            var elt = document.getElementById('header-fixed1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });

            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Trial Balance 6th Column <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));

        }




    </script>

@endsection






