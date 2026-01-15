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
    @include('select2')
    <script src="{{ URL::asset('assets/custom/js/multi-select-js-library.js') }}"></script>
    <link href="{{ URL::asset('assets/custom/css/multi-select-css-library.css') }}" rel="stylesheet">

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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="head_flex">
                                        <div class="headquids">
                                            <h2 class="subHeadingLabelClass">Profit & Loss Statement</h2>
                                        </div>
                                        <div class="prints">
                                            <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                                            <?php if($export == true):?>
                                            <a id="dlink" style="display:none;"></a>
                                            <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                    <hr style="border-bottom:1px solid #000">
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
                                    <div class="row align-items-center">
                                        
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label>Financial year </label>
                                                <input type="checkbox" onclick="changeFilert(event)" class="form-control" id="financial_year_checkbox">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 normalFilter">
                                            <label>Filter Year</label>
                                            <select class="form-control"  name="filterYear" id="filterYear">
                                                <?php
                                                    $cur_year = date('Y');
                                                    for($i=2014; $i <= ($cur_year+10); $i++){
                                                        if ($i == $cur_year) {
                                                            echo '<option value="'.$i.'" selected="selected">'.$i.'</option>';
                                                        } else {
                                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 normalFilter">
                                            <label>Compare Year</label>
                                            <select class="form-control"  name="compareYear" id="compareYear">
                                                <option value="">Select Compare year</option>
                                                <?php
                                                    $cur_year = date('Y');
                                                    for($i=2014; $i <= ($cur_year+10); $i++){
                                                        if ($i == $cur_year) {
                                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                                        } else {
                                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 normalFilter">
                                            <label>Month</label>
                                            <select class="multiselect-ui profit_Select form-control" name="filterMonth[]" multiple="multiple" id="dates-field3">
                                            <?php 
                                                for ($mm=1; $mm <= 12; $mm++) {
                                                    $month = date('F', mktime(0,0,0,$mm, 1, date('Y')));
                                                    echo '<option value="'.$mm.'" selected>'.$month.'</option>';
                                                }
                                            ?>
                                            </select>
                                        </div>

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 financialFilter hide" >
                                            <label>Year</label>
                                            <select class="form-control"  id="financial_year">
                                                <?php
                                                    $cur_year = date('Y');
                                                    $new_year;
                                                    for($i=2014; $i <= ($cur_year+10); $i++){
                                                        $new_year = $i + 1;
                                                        
                                                        if ($i == $cur_year) {
                                                            echo '<option value="'.$i.' '.$new_year.'" selected="selected">'.$i.' - '.$new_year.'</option>';
                                                        } else {
                                                            echo '<option value="'.$i.' '.$new_year.'">'.$i.' - '.$new_year.'</option>';
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
                                            <lable class="control-label">Summary</lable>
                                            <select name="" id="" class="form-control">
                                                <option value="">Summary</option>
                                            </select>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <lable class="control-label">Comparative</lable>
                                            <select name="" id="comparetive" class="form-control">
                                                <option value="2">Summary</option>
                                                <option value="1">Comparative</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-1 col-md-1 col-sm-2 col-xs-12">
                                            <button onclick="Generate()" type="button" class="btn btn-sm btn-primary">Submit</button>
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
        $('.select2').select2();
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
            var elt = document.getElementById('exportIncomeStatement1');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                    XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
                    XLSX.writeFile(wb, fn || ('Profit & Loss <?php echo date('d-M-Y')?>.' + (type || 'xlsx')));
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

            let financial_year_checkbox = $('#financial_year_checkbox').prop('checked');
            let m = '<?= $_GET['m']; ?>';
            let filterYear = $("#filterYear").val();
            let compareYear = $("#compareYear").val();
            let comparetive = $("#comparetive").val();
            let monthArray=[]; 
            let data;
            let financial_year;

            if(financial_year_checkbox)
            {
                financial_year_condition = true;
                financial_year = $('#financial_year').val();

                data = {
                            financial_year_condition: financial_year_condition,
                            financial_year: financial_year, 
                            m:m ,
                            comparetive:comparetive
                        };

            }
            else
            {
                financial_year_condition = false;

                $('select[name="filterMonth[]"] option:selected').each(function() {
                    monthArray.push($(this).val());
                });


                data = {
                            financial_year_condition: financial_year_condition,
                            filterYear: filterYear, 
                            compareYear: compareYear, 
                            monthArray: monthArray, 
                            m:m ,
                            comparetive:comparetive
                        };
            }
            
            if(financial_year_condition)
            {
                if(!financial_year)
                {
                    alert('Something Wrong! Please Select financial year.');
                    return
                }
            }
            else
            {

                if(!monthArray)
                {
                    alert('Something Wrong! Please Select Month.');
                    return
                }

            }

            $('#trial_bal').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '<?php echo url('/');?>/fdc/IncomeStatement',
                type: 'GET',
                data: data,
                success: function (response) {
                 //   $('#BtnUp').css('display','none');
                 //   $('#BtnDown').css('display','block');

                    $('#trial_bal').html(response);
                //    $('#NewAjax').html(response);

                  //  $('#SlideUpDown').slideUp();
             //       $('#OtherArea').css('display','block');

                }
            });


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
        function hideExpenseRecordRow(id,param,value){
            //alert(value);
            if(value == 0){
                $('#'+id+''+param+'').addClass('hide');
            }else {
                $('#'+id+''+param+'').removeClass('hide');
            }
            
        }

        function makeGrossProfitMonthWise(idOne,idTwo,param,display){

        }

        function changeFilert(e)
    {
        let element = e.target;

        let normalFilterElement = document.querySelectorAll('.normalFilter');
        let financialFilterElement = document.querySelectorAll('.financialFilter');

        if(element.checked)
        {
            normalFilterElement.forEach((e)=>{
                e.classList.add('hide');
            });
            financialFilterElement.forEach((e)=>{
                e.classList.remove('hide')
            });
        }
        else
        {
            normalFilterElement.forEach((e)=>{
                e.classList.remove('hide')
            });
            financialFilterElement.forEach((e)=>{
                e.classList.add('hide');
            });

        }

    }

    </script>
    <div class="col-sm-12">&nbsp;</div>

@endsection
