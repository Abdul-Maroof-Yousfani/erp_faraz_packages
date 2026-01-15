<?php

$m = Session::get('run_company');
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;

$LabourWorking = DB::Connection('mysql2')->table('production_labour_working')->where('status',1)->get();
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    @include('modal')
    <style>
        * {
            font-size: 12px!important;
            font-family: Arial;
        }
        .select2 {
            width: 100%;
        }
    </style>

    <div class="container-fluid">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass"><?php if(count($LabourWorking) > 0):?> Already Created Labours Working<?php else:?> Create Labours Working<?php endif;?></span>
                    </div>
                </div>
                <div class="lineHeight">&nbsp;</div>
                <?php if(count($LabourWorking) == 0):?>
                <div class="row">
                    <?php echo Form::open(array('url' => 'prad/insert_labours_working?m='.$m.'','id'=>'labour_working_form','class'=>'stop'));?>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="lineHeight">&nbsp;</div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" id="">
                                            <table class="table table-bordered">
                                                <thead>
                                                <tr class="text-center">
                                                    <th colspan="5" class="text-center">Labours Working Detail</th>
                                                    <th class="text-center">
                                                        <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
                                                    </th>
                                                    <th class="text-center">
                                                        <span class="badge badge-success" id="span">1</span>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center">Sr No.</th>
                                                    <th class="text-center" style="width: 40% !important;">Description</th>
                                                    <th style="" class="text-center">No of Employee</th>
                                                    <th style="" class="text-center">Wages / Work</th>
                                                    <th style="" class="text-center">Monthly Wages</th>
                                                    <th style="" class="text-center">Yearly Wages</th>
                                                </tr>
                                                </thead>
                                                <tbody id="AppnedHtml">
                                                <tr id="" class="AutoNo">
                                                    <td class="text-center">1</td>
                                                    <td>
                                                        <input type="text" class="form-control requiredField" name="Description[]" id="Description1">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control requiredField NoOfEmployeeLoop" id="NoOfEmployee1" name="NoOfEmployee[]" step="any" placeholder="Amount" onkeyup="Calc(1)">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control requiredField" id="WagesWork1" name="WagesWork[]" step="any" placeholder="Wages Work" onkeyup="Calc(1)">
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control requiredField MonthlyWagesLoop" id="MonthlyWages1" name="MonthlyWages[]" step="any" placeholder="" readonly>
                                                    </td>
                                                    <td>
                                                        <input type="number" class="form-control requiredField YearlyWagesLoop" id="YearlyWages1" name="YearlyWages[]" step="any" placeholder="" readonly>
                                                    </td>
                                                    <td>-</td>
                                                </tr>

                                                </tbody>
                                                <tbody>
                                                    <tr class="text-center">
                                                        <td colspan="4" style="font-size: 20px !important; font-weight: 700;">TOTAL</td>
                                                        <td id="TotalMonthlyWages" style="font-size: 20px !important; font-weight: 700;"></td>
                                                        <td id="TotalYearlyWages" style="font-size: 20px !important; font-weight: 700;"></td>
                                                        <input type="hidden" id="TotalYearlyWagesHidden" name="TotalYearlyWagesHidden" value="">
                                                    </tr>
                                                    {{--EOBI SECTION--}}
                                                    <tr class="text-center" style="display: none">
                                                        <td colspan="2" style="font-size: 20px !important; font-weight: 700;">EOBI</td>
                                                        <td id="TotalEmployee" class="text-center" style="font-size: 20px !important; font-weight: 700;"></td>
                                                        <td><input type="number" id="EobiAmount" name="EobiAmount" class="form-control" placeholder="Enter EOBI Amount" onkeyup="CalcWorkingNote();"></td>
                                                        <td id="TotalMonthlyWagesEobi" style="font-size: 20px !important; font-weight: 700;"></td>
                                                        <td id="TotalYearlyWagesEobi" style="font-size: 20px !important; font-weight: 700;"></td>
                                                        <input type="hidden" id="TotalMonthlyWagesHiddenEobi" name="TotalMonthlyWagesHiddenEobi" value="">
                                                        <input type="hidden" id="TotalYearlyWagesHiddenEobi" name="TotalYearlyWagesHiddenEobi" value="">
                                                    </tr>
                                                    {{--EOBI SECTION--}}
                                                    {{--Grand Total--}}
                                                    <tr class="text-center" style="display: none">
                                                        <td colspan="4" style="font-size: 20px !important; font-weight: 700;">GRAND TOTAL</td>
                                                        <td id="TotalMonthlyWagesOverAll" style="font-size: 20px !important; font-weight: 700;"></td>
                                                        <td id="TotalYearlyWagesOverAll" style="font-size: 20px !important; font-weight: 700;"></td>
                                                        <input type="hidden" id="TotalYearlyWagesHidden" name="TotalYearlyWagesHidden" value="">
                                                    </tr>
                                                    {{--Grand Total--}}
                                                    <tr class="text-center">
                                                        <td rowspan="2" colspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">D/L Rate</td>
                                                        <td id="TotalYearlyWagesSecond" style="font-size: 20px !important; font-weight: 700; border-bottom: solid">0.00</td>
                                                        <td rowspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">Per Hour</td>
                                                        <td id="PerHour" rowspan="2" colspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">0.00</td>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <td id="TotalWorkingHoursTd" rowspan="2" style="font-size: 20px !important; font-weight: 700;">0.00</td>
                                                        <td rowspan="2" colspan="2"></td>
                                                    </tr>

                                                    {{--Eobi Section--}}
                                                    <tr style="display: none">
                                                        <td rowspan="2"></td>
                                                    </tr>
                                                    <tr class="text-center" style="display: none">
                                                        <td rowspan="2" colspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">EOBI & S.Security</td>
                                                        <td id="TotalYearlyWagesSecondEobi" style="font-size: 20px !important; font-weight: 700; border-bottom: solid">0.00</td>
                                                        <td rowspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">Per Hour</td>
                                                        <td id="PerHourEobi" rowspan="2" colspan="2" style="font-size: 20px !important; font-weight: 700; padding: 30px 0px 0px 0px;">0.00</td>
                                                    </tr>
                                                    <tr class="text-center">
                                                        <td id="TotalWorkingHoursTd" rowspan="2" style="font-size: 20px !important; font-weight: 700;">0.00</td>
                                                        <td rowspan="2" colspan="2"></td>
                                                    </tr>
                                                    {{--Eobi Section--}}
                                                </tbody>
                                                <tbody>
                                                <tr>
                                                    <td colspan="2" style="font-size: 20px !important; font-weight: 700;">Working Note</td>
                                                    <td colspan="5" style="font-size: 20px !important; font-weight: 700;">Working Note Remarks</td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px !important; font-weight: 700;">Working Hours</td>
                                                    <td><input type="number" class="form-control requiredField pull-right" id="WorkingHours" name="WorkingHours" placeholder="Working Hours" step="any" style="width: 40%" onkeyup="CalcWorkingNote()"></td>
                                                    <td rowspan="3" colspan="5">
                                                        <textarea name="WorkingNoteRemarks" id="WorkingNoteRemarks" cols="30" rows="10" style="resize: none;" class="form-control requiredField" placeholder="Remarks"></textarea>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px !important; font-weight: 700;">No of Worker</td>
                                                    <td><input type="number" class="form-control pull-right" id="NoOfWorker" name="NoOfWorker" step="any" readonly style="width: 40%"></td>
                                                </tr>
                                                <tr>
                                                    <td style="font-size: 14px !important; font-weight: 700;">Total Working Hours</td>
                                                    <td><input type="number" class="form-control pull-right" id="TotalWorkingHours" name="TotalWorkingHours" step="any" readonly style="width: 40%"></td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="demandsSection"></div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}

                        </div>
                    </div>
                    <?php echo Form::close();?>
                </div>
                <?php endif;?>
            </div>
            </div>
        </div>
    </div>
    </div>

    <script>




        $(document).ready(function(){
            //$('.select2').select2();


            $(".btn-success").click(function(e){
                var category = new Array();
                var val;
                //$("input[name='chartofaccountSection[]']").each(function(){
                category.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of category) {

                    jqueryValidationCustom();

                    if(validate == 0)
                    {

                    }
                    else
                    {
                        return false;
                    }
                }
            });

        });
        var Counter = 1;
        var countt=1;


        function AddMoreDetails()
        {

            countt++;
            $('#AppnedHtml').append(
                    '<tr id="" class="AutoNo RemoveRows'+countt+'">' +
                    '<td class="text-center">' +countt+'</td>' +
                    '<td>' +
                    '<input type="text" class="form-control requiredField" name="Description[]" id="Description'+countt+'">' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control requiredField NoOfEmployeeLoop" id="NoOfEmployee'+countt+'" name="NoOfEmployee[]" step="any" placeholder="Amount" onkeyup="Calc('+countt+')">' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control requiredField " id="WagesWork'+countt+'" name="WagesWork[]" step="any" placeholder="Wages Work" onkeyup="Calc('+countt+')">' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control MonthlyWagesLoop" id="MonthlyWages'+countt+'" name="MonthlyWages[]" step="any" placeholder="" readonly>' +
                    '</td>' +
                    '<td>' +
                    '<input type="number" class="form-control YearlyWagesLoop" id="YearlyWages'+countt+'" name="YearlyWages[]" step="any" placeholder="" readonly>' +
                    '</td>' +
                    '<td><button type="button" onclick="RemoveSection('+countt+')" class="btn btn-danger btn-xs">Remove</button></td>'+
                    '</tr>');

            $('#AccountId'+countt).select2();

            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);

            var AutoCount = 1;
            $(".AutoCounter").each(function(){
                AutoCount++;
                $(this).html(AutoCount);
            });

        }
        function addCommas(x) {
            var parts = x.toString().split(".");
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            return parts.join(".");
        }

        function CalcWorkingNote()
        {
            var NoOfWorker = $('#NoOfWorker').val();

            var WorkingHours = $('#WorkingHours').val();
            var EobiAmount = $('#EobiAmount').val();

            var Result = parseFloat(NoOfWorker*EobiAmount).toFixed(2);
            $('#TotalMonthlyWagesEobi').html(addCommas(parseFloat(Result).toFixed(2)));
            $('#TotalYearlyWagesEobi').html(addCommas(parseFloat(Result*12).toFixed(2)));

            $('#TotalMonthlyWagesHiddenEobi').html(Result);
            $('#TotalYearlyWagesHiddenEobi').html(Result*12);




            $('#TotalWorkingHours').val(parseFloat(WorkingHours*NoOfWorker).toFixed(2));
            $('#TotalWorkingHoursTd').html(addCommas(parseFloat(WorkingHours*NoOfWorker).toFixed(2)));
            //var TotalYearlyWages = parseFloat($('#TotalYearlyWagesHidden').val());

            var TotalYearlyWages = 0;
            $('.YearlyWagesLoop').each(function() {
                TotalYearlyWages += parseFloat($(this).val()) || 0;
            });


            var TotalWorkingHours = $('#TotalWorkingHours').val();
            if(TotalWorkingHours > 0)
            {
                $('#PerHour').html(parseFloat(TotalYearlyWages/TotalWorkingHours).toFixed(2));
            }
            else{
                $('#PerHour').html('0.00');
            }

        }

        function Calc(Row)
        {
            var NoOfEmployee = $('#NoOfEmployee'+Row).val();
            var WagesWork = $('#WagesWork'+Row).val();
            if(isNaN(WagesWork))
            {
                WagesWork = 0;
            }
            var Result = parseFloat(NoOfEmployee*WagesWork).toFixed(2);
            $('#MonthlyWages'+Row).val(Result);
            $('#YearlyWages'+Row).val(Result*12);

            var TotalMonthlyWages = 0;
            var TotalYearlyWages = 0;
            var TotalNoOfEmployee = 0;

            $('.MonthlyWagesLoop').each(function() {

                TotalMonthlyWages += parseFloat($(this).val()) || 0;
            });

            $('.YearlyWagesLoop').each(function() {
                TotalYearlyWages += parseFloat($(this).val()) || 0;
            });

            $('.NoOfEmployeeLoop').each(function() {
                TotalNoOfEmployee += parseFloat($(this).val()) || 0;
            });



            $('#TotalMonthlyWages').html(addCommas(parseFloat(TotalMonthlyWages).toFixed(2)));
            $('#TotalYearlyWages').html(addCommas(parseFloat(TotalYearlyWages).toFixed(2)));
            $('#TotalYearlyWagesHidden').html(parseFloat(TotalYearlyWages).toFixed(2));

            $('#TotalYearlyWagesSecond').html(addCommas(parseFloat(TotalYearlyWages).toFixed(2)));

            $('#NoOfWorker').val(TotalNoOfEmployee);
            $('#TotalEmployee').html(TotalNoOfEmployee);


            CalcWorkingNote();

        }

        function RemoveSection(Row) {
            $('.RemoveRows' + Row).remove();
            $(".AutoCounter").html('');
            var AutoCount = 1;
            $(".AutoCounter").each(function () {
                AutoCount++;
                $(this).html(AutoCount);
            });
            var AutoNo = $(".AutoNo").length;
            $('#span').text(AutoNo);
            Calc(Row);
            CalcWorkingNote();

        }




    </script>



    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection