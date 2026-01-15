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
@include('select2')

    <div class="well_N">
    <div class="dp_sdw">    
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    {{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">--}}
                    {{--@include('Finance.'.$accType.'financeMenu')--}}
                    {{--</div>--}}
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <span class="subHeadingLabelClass">Balance Sheet</span>
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
                            <hr>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row align-items-center">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label>Financial year </label>
                                                <input type="checkbox" onclick="changeFilert(event)" class="form-control" id="financial_year_checkbox">
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 normalFilter" >
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
                                    
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <lable class="control-label">Comparative</lable>
                                            <select name="" id="RadioVal" class="form-control">
                                                <option value="1">SUMMARY</option>
                                                <!-- <option value="2">Comparative</option> -->
                                                <option value="3">Comparative</option>
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

            let filterYear;
            let compareYear;
            let filterMonth;
            let monthArray = [];
            let financial_year_condition;
            let financial_year;
            let RadioVal = $('#RadioVal').val()
            let type=5;
            let data;
            let m = '<?= $_GET['m']; ?>';

            let financial_year_checkbox = $('#financial_year_checkbox').prop('checked');

            if(financial_year_checkbox)
            {
                financial_year_condition = true;
                financial_year = $('#financial_year').val()


                data =  {
                                financial_year_condition: financial_year_condition,
                                financial_year: financial_year,
                                RadioVal: RadioVal,
                                m: m,
                                type: type,
                            };
            }
            else
            {
                financial_year_condition = false;

                filterYear = $('#filterYear').val() 
                compareYear = $('#compareYear').val()
                filterMonth = $('#filterMonth').val() 
                monthArray=[]; 
                
                $('select[name="filterMonth[]"] option:selected').each(function() {
                    monthArray.push($(this).val());
                });
                if(monthArray == ''){
                    alert('Something Wrong! Please Select Month.');
                    return false;
                }

                data =  {
                                financial_year_condition: financial_year_condition,
                                filterYear: filterYear,
                                compareYear: compareYear,
                                monthArray: monthArray,
                                RadioVal: RadioVal,
                                m: m,
                                type: type,
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
          
                $.ajax({
                    url: '<?php echo url('/');?>/fdc/trialBalanceSheet',
                    type: 'GET',
                    data: data ,
                    success: function (response) {
                        //var v = $.trim(response);
                        $('#trial_bal').html(response);
                        $('#OtherArea').css('display','block');
                        //alert(response);
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
        function removeRaw(rawId)
    {
        $('#'+rawId).remove();
    }

    $(document).ready(()=>{
        $('.profit_Select').select2();
    })

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
@endsection
