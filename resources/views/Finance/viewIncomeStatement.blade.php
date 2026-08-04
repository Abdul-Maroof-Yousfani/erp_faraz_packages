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

    <style>
        /* ===== Profit & Loss chart card ===== */
        #plChartWrap{margin-bottom:20px;}
        #plChartWrap .card.barChartHead{background:#fff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;height:auto !important;}
        #plChartWrap .card.barChartHead > div:first-child{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;flex-wrap:wrap !important;gap:12px !important;}
        #plChartWrap h6{font-size:17px !important;font-weight:700 !important;color:var(--erp-navy-dark,#0B1F59) !important;margin:0 !important;}
        #plChartWrap .card-body{padding:0 !important;min-height:280px !important;position:relative;}
        #plChartWrap .empty-state{display:flex !important;flex-direction:column !important;align-items:center !important;justify-content:center !important;padding:50px 20px !important;color:#a7abc3 !important;text-align:center !important;min-height:200px !important;}
        #plChartWrap .empty-state i{font-size:34px !important;margin-bottom:12px !important;color:#c9cfe6 !important;}
        #plChartWrap .empty-state p{font-size:13.5px !important;font-weight:500 !important;margin:0 !important;color:#8892b0 !important;}

        .pl-chart-view-switch{display:inline-flex !important;background:#F1F3FB !important;border-radius:10px !important;padding:4px !important;gap:4px !important;}
        .pl-view-btn{border:none !important;background:transparent !important;padding:7px 14px !important;font-size:12.5px !important;font-weight:700 !important;color:#5A6180 !important;border-radius:7px !important;cursor:pointer !important;transition:all .18s ease !important;white-space:nowrap !important;}
        .pl-view-btn:hover{color:var(--erp-navy-dark,#0B1F59) !important;}
        .pl-view-btn.active{background:#fff !important;color:var(--erp-navy-dark,#0B1F59) !important;box-shadow:0 2px 6px rgba(20,38,92,0.12) !important;}

        .pl-pivot-table-wrap{padding:4px 4px 8px 4px !important;}
        table.pl-pivot-table{width:100% !important;background:#fff !important;border-collapse:collapse !important;margin:0 !important;}
        table.pl-pivot-table thead th{background:#EDF0F8 !important;color:#0B1F59 !important;font-weight:700 !important;font-size:12.5px !important;text-transform:uppercase !important;letter-spacing:.03em !important;padding:10px 14px !important;border-bottom:2px solid #D8DEF7 !important;}
        table.pl-pivot-table tbody td{padding:9px 14px !important;font-size:13px !important;color:#2b2f4a !important;border-bottom:1px solid #EDF0F8 !important;}
        table.pl-pivot-table tbody tr:hover td{background:#F7F9FD !important;}

        #plChartNote{font-size:11.5px;color:#8892b0;text-align:center;margin-top:8px;}
    </style>

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
                                            <button type="button" class="btn btn-primary" id="btnViewPlChart" onclick="togglePlChart()">View Chart</button>
                                            <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                                            <?php if($export == true):?>
                                            <a id="dlink" style="display:none;"></a>
                                            <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                            <?php endif;?>
                                        </div>
                                    </div>
                                    <hr style="border-bottom:1px solid #000">
                                </div>

                                <!-- ===== Profit & Loss chart (hidden by default, toggled via button) ===== -->
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="row" id="plChartWrap" style="display:none;">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"> </div>
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                            <div class="card barChartHead">
                                                <div>
                                                    <div>
                                                        <h6>Profit & Loss Chart</h6>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="pl-chart-view-switch">
                                                            <button type="button" class="pl-view-btn active" data-view="line" onclick="plSwitchChartView('line')">Line Graph</button>
                                                            <button type="button" class="pl-view-btn" data-view="bar" onclick="plSwitchChartView('bar')">Bar Chart</button>
                                                            <button type="button" class="pl-view-btn" data-view="pie" onclick="plSwitchChartView('pie')">Pie Chart</button>
                                                            <button type="button" class="pl-view-btn" data-view="pivot" onclick="plSwitchChartView('pivot')">Pivot</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <canvas class="Profit_Loss_Chart" data-height="380"></canvas>
                                                    <p id="plPieNegativeNote" class="text-center" style="display:none;font-size:11.5px;color:#8892b0;margin-top:8px;">Pie slices show absolute values — a negative category (e.g. Net Loss) is shown as positive size.</p>
                                                    <div class="pl-pivot-table-wrap" style="display:none;">
                                                        <table class="table table-bordered pl-pivot-table">
                                                            <thead>
                                                                <tr>
                                                                    <th>Category</th>
                                                                    <th class="text-right">Amount</th>
                                                                    <th class="text-right">Cumulative Total</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody id="plPivotTableBody"></tbody>
                                                        </table>
                                                    </div>
                                                    <p id="plChartNote"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"> </div>
                                    </div>
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
                                            <div class="monthPickerWrap">
                                                <button type="button" class="monthPickerBtn" id="monthPickerBtn">
                                                    <span id="monthPickerLabel">All Months</span>
                                                    <i class="fa fa-chevron-down"></i>
                                                </button>
                                                <div class="monthPickerPanel" id="monthPickerPanel">
                                                    <?php 
                                                        for ($mm=1; $mm <= 12; $mm++) {
                                                            $month = date('F', mktime(0,0,0,$mm, 1, date('Y')));
                                                            echo '<label class="monthCheckItem"><input type="checkbox" class="monthCheckbox" value="'.$mm.'" checked> '.$month.'</label>';
                                                        }
                                                    ?>
                                                </div>
                                            </div>
                                            <select class="hide" name="filterMonth[]" multiple="multiple" id="dates-field3" style="display:none;">
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

                    // NEW: chart card open ho to response ke andar embedded JSON se refresh kar dein
                    plLoadChartFromResponse();

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

    // ===================================================================
    // NEW: Profit & Loss chart (Line / Bar / Pie / Pivot) — hand-drawn on
    // canvas, no dependency on any third-party chart library. Reads chart
    // data that the AJAX-loaded report view embeds as a small JSON <script>
    // tag (#plChartDataJson) once the totals are computed server-side.
    // ===================================================================
    var plChartCurrentView = 'line'; // 'line' | 'bar' | 'pie' | 'pivot'
    var plChartLastData = null;      // { labels: [], values: [] }
    var plActiveAnimationId = null;

    function togglePlChart()
    {
        var $wrap = $('#plChartWrap');

        if ($wrap.is(':visible')) {
            $wrap.slideUp();
            $('#btnViewPlChart').text('View Chart');
        } else {
            $wrap.slideDown();
            $('#btnViewPlChart').text('Hide Chart');
            plLoadChartFromResponse();
        }
    }

    function plLoadChartFromResponse()
    {
        if (!$('#plChartWrap').is(':visible')) {
            return;
        }

        var $jsonTag = $('#plChartDataJson');
        var $noteTag = $('#plChartReportNote');

        if ($jsonTag.length === 0) {
            plChartLastData = null;
            $('#plChartNote').text('');
            plRenderChart(null);
            return;
        }

        try {
            plChartLastData = JSON.parse($jsonTag.text());
        } catch (e) {
            plChartLastData = null;
        }

        $('#plChartNote').text($noteTag.length ? $noteTag.text() : '');
        plRenderChart(plChartLastData);
    }

    function plSwitchChartView(view)
    {
        plChartCurrentView = view;

        $('.pl-view-btn').removeClass('active');
        $('.pl-view-btn[data-view="' + view + '"]').addClass('active');

        if (view === 'pivot') {
            $('.Profit_Loss_Chart').hide();
            $('#plPieNegativeNote').hide();
            $('.pl-pivot-table-wrap').show();
            plRenderPivotTable(plChartLastData);
        } else {
            $('.pl-pivot-table-wrap').hide();
            $('.Profit_Loss_Chart').show();
            plRenderChart(plChartLastData);
        }
    }

    function plRenderPivotTable(chartData)
    {
        var $tbody = $('#plPivotTableBody');
        $tbody.empty();

        if (!chartData || !chartData.labels || chartData.labels.length === 0) {
            $tbody.html('<tr><td colspan="3" class="text-center">No data available</td></tr>');
            return;
        }

        var runningTotal = 0;
        chartData.labels.forEach(function (label, i) {
            var amount = parseFloat(chartData.values[i]) || 0;
            runningTotal += amount;
            $tbody.append(
                '<tr>' +
                    '<td>' + label + '</td>' +
                    '<td class="text-right">' + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                    '<td class="text-right">' + runningTotal.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                '</tr>'
            );
        });
    }

    function plFormatNum(n)
    {
        return Number(n).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 });
    }

    function plEaseOutCubic(t)
    {
        return 1 - Math.pow(1 - t, 3);
    }

    function plAnimateChart(drawFn, canvas, chartData, duration)
    {
        if (plActiveAnimationId) {
            cancelAnimationFrame(plActiveAnimationId);
            plActiveAnimationId = null;
        }
        duration = duration || 650;
        var startTime = null;

        function step(timestamp) {
            if (!startTime) startTime = timestamp;
            var elapsed = timestamp - startTime;
            var rawProgress = Math.min(elapsed / duration, 1);
            var eased = plEaseOutCubic(rawProgress);

            drawFn(canvas, chartData, eased);

            if (rawProgress < 1) {
                plActiveAnimationId = requestAnimationFrame(step);
            } else {
                plActiveAnimationId = null;
            }
        }

        plActiveAnimationId = requestAnimationFrame(step);
    }

    function plSetupCanvasForDPR(canvas, cssWidth, cssHeight)
    {
        var dpr = window.devicePixelRatio || 1;
        canvas.style.width = cssWidth + 'px';
        canvas.style.height = cssHeight + 'px';
        canvas.width = Math.round(cssWidth * dpr);
        canvas.height = Math.round(cssHeight * dpr);
        var ctx = canvas.getContext('2d');
        ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        return ctx;
    }

    function plDrawBarChart(canvas, chartData, progress)
    {
        progress = (typeof progress === 'number') ? progress : 1;
        var cssWidth = canvas.parentElement.clientWidth || 700;
        var cssHeight = 380;
        var ctx = plSetupCanvasForDPR(canvas, cssWidth, cssHeight);
        ctx.clearRect(0, 0, cssWidth, cssHeight);

        var labels = chartData.labels;
        var values = chartData.values;
        var padding = { top: 40, right: 30, bottom: 45, left: 95 };
        var plotW = cssWidth - padding.left - padding.right;
        var plotH = cssHeight - padding.top - padding.bottom;

        var maxVal = Math.max(0, Math.max.apply(null, values));
        var minVal = Math.min(0, Math.min.apply(null, values));
        if (maxVal === minVal) { maxVal += 1; minVal -= 1; }
        var range = maxVal - minVal;

        function yFor(v) { return padding.top + plotH - ((v - minVal) / range) * plotH; }
        var zeroY = yFor(0);

        ctx.fillStyle = '#0B1F59';
        ctx.font = 'bold 12.5px Arial';
        ctx.textAlign = 'left';
        ctx.fillText('■ Amount', padding.left, 18);

        ctx.strokeStyle = '#EDF0F8';
        ctx.fillStyle = '#8892b0';
        ctx.font = '11px Arial';
        ctx.textAlign = 'right';
        ctx.textBaseline = 'middle';
        var steps = 6;
        for (var i = 0; i <= steps; i++) {
            var v = minVal + (range * i / steps);
            var y = yFor(v);
            ctx.beginPath();
            ctx.moveTo(padding.left, y);
            ctx.lineTo(padding.left + plotW, y);
            ctx.stroke();
            ctx.fillText(plFormatNum(v), padding.left - 10, y);
        }

        ctx.strokeStyle = '#B9C0DE';
        ctx.beginPath();
        ctx.moveTo(padding.left, zeroY);
        ctx.lineTo(padding.left + plotW, zeroY);
        ctx.stroke();

        var n = labels.length;
        var slot = plotW / n;
        var barWidth = Math.min(56, slot * 0.5);
        ctx.textAlign = 'center';
        for (var j = 0; j < n; j++) {
            var val = values[j];
            var cx = padding.left + slot * j + slot / 2;
            var yTopFull = yFor(Math.max(0, val));
            var yBottomFull = yFor(Math.min(0, val));

            var yTop, yBottom;
            if (val >= 0) {
                yBottom = zeroY;
                yTop = zeroY - (zeroY - yTopFull) * progress;
            } else {
                yTop = zeroY;
                yBottom = zeroY + (yBottomFull - zeroY) * progress;
            }
            var barH = Math.max(1, yBottom - yTop);

            ctx.fillStyle = val >= 0 ? 'rgba(30,58,138,0.65)' : 'rgba(239,68,68,0.55)';
            ctx.strokeStyle = val >= 0 ? 'rgba(30,58,138,1)' : 'rgba(239,68,68,1)';
            ctx.lineWidth = 1;
            ctx.fillRect(cx - barWidth / 2, yTop, barWidth, barH);
            ctx.strokeRect(cx - barWidth / 2, yTop, barWidth, barH);

            if (progress > 0.9) {
                ctx.font = 'bold 10.5px Arial';
                if (val >= 0) {
                    ctx.fillStyle = '#2b2f4a';
                    ctx.fillText(plFormatNum(val), cx, yTopFull - 8);
                } else {
                    var fullBarH = yBottomFull - yTopFull;
                    ctx.fillStyle = fullBarH > 22 ? '#fff' : '#2b2f4a';
                    var negLabelY = fullBarH > 22 ? (yTopFull + 14) : (yTopFull - 8);
                    ctx.fillText(plFormatNum(val), cx, negLabelY);
                }
            }

            ctx.fillStyle = '#5A6180';
            ctx.font = '11.5px Arial';
            ctx.fillText(labels[j], cx, padding.top + plotH + 20);
        }
    }

    function plDrawLineChart(canvas, chartData, progress)
    {
        progress = (typeof progress === 'number') ? progress : 1;
        var cssWidth = canvas.parentElement.clientWidth || 700;
        var cssHeight = 380;
        var ctx = plSetupCanvasForDPR(canvas, cssWidth, cssHeight);
        ctx.clearRect(0, 0, cssWidth, cssHeight);

        var labels = chartData.labels;
        var runningValues = [];
        var running = 0;
        chartData.values.forEach(function (v) { running += (parseFloat(v) || 0); runningValues.push(running); });

        var padding = { top: 40, right: 30, bottom: 45, left: 95 };
        var plotW = cssWidth - padding.left - padding.right;
        var plotH = cssHeight - padding.top - padding.bottom;

        var maxVal = Math.max(0, Math.max.apply(null, runningValues));
        var minVal = Math.min(0, Math.min.apply(null, runningValues));
        if (maxVal === minVal) { maxVal += 1; minVal -= 1; }
        var range = maxVal - minVal;

        function yFor(v) { return padding.top + plotH - ((v - minVal) / range) * plotH; }
        var zeroY = yFor(0);

        ctx.fillStyle = '#0B1F59';
        ctx.font = 'bold 12.5px Arial';
        ctx.textAlign = 'left';
        ctx.fillText('— Cumulative Total', padding.left, 18);

        ctx.strokeStyle = '#EDF0F8';
        ctx.fillStyle = '#8892b0';
        ctx.font = '11px Arial';
        ctx.textAlign = 'right';
        ctx.textBaseline = 'middle';
        var steps = 6;
        for (var i = 0; i <= steps; i++) {
            var v = minVal + (range * i / steps);
            var y = yFor(v);
            ctx.beginPath();
            ctx.moveTo(padding.left, y);
            ctx.lineTo(padding.left + plotW, y);
            ctx.stroke();
            ctx.fillText(plFormatNum(v), padding.left - 10, y);
        }

        ctx.strokeStyle = '#B9C0DE';
        ctx.beginPath();
        ctx.moveTo(padding.left, zeroY);
        ctx.lineTo(padding.left + plotW, zeroY);
        ctx.stroke();

        var n = labels.length;
        var slot = plotW / n;

        var animatedYs = [];
        for (var p = 0; p < n; p++) {
            var targetY = yFor(runningValues[p]);
            animatedYs.push(zeroY + (targetY - zeroY) * progress);
        }

        ctx.strokeStyle = 'rgba(245,166,35,1)';
        ctx.lineWidth = 2.5;
        ctx.beginPath();
        for (var j = 0; j < n; j++) {
            var cx = padding.left + slot * j + slot / 2;
            var cy = animatedYs[j];
            if (j === 0) { ctx.moveTo(cx, cy); } else { ctx.lineTo(cx, cy); }
        }
        ctx.stroke();

        ctx.textAlign = 'center';
        for (var k = 0; k < n; k++) {
            var cx2 = padding.left + slot * k + slot / 2;
            var cy2 = animatedYs[k];

            ctx.fillStyle = 'rgba(245,166,35,1)';
            ctx.beginPath();
            ctx.arc(cx2, cy2, 4, 0, Math.PI * 2);
            ctx.fill();

            if (progress > 0.9) {
                ctx.fillStyle = '#2b2f4a';
                ctx.font = 'bold 10.5px Arial';
                ctx.fillText(plFormatNum(runningValues[k]), cx2, yFor(runningValues[k]) - 12);
            }

            ctx.fillStyle = '#5A6180';
            ctx.font = '11.5px Arial';
            ctx.fillText(labels[k], cx2, padding.top + plotH + 20);
        }
    }

    function plDrawPieChart(canvas, chartData, progress)
    {
        progress = (typeof progress === 'number') ? progress : 1;
        var cssWidth = canvas.parentElement.clientWidth || 700;
        var cssHeight = 380;
        var ctx = plSetupCanvasForDPR(canvas, cssWidth, cssHeight);
        ctx.clearRect(0, 0, cssWidth, cssHeight);

        var labels = chartData.labels;
        var values = chartData.values.map(function (v) { return Math.abs(parseFloat(v) || 0); });
        var total = values.reduce(function (a, b) { return a + b; }, 0);

        var colors = ['#1E3A8A', '#F5A623', '#EF4444', '#10B981', '#8B5CF6', '#06B6D4', '#EC4899', '#EAB308'];

        var cx = 170, cy = cssHeight / 2, r = Math.min(cy - 30, 130);

        if (total <= 0) {
            ctx.fillStyle = '#8892b0';
            ctx.font = '13px Arial';
            ctx.textAlign = 'center';
            ctx.fillText('No data available', cssWidth / 2, cssHeight / 2);
            return;
        }

        var startAngle = -Math.PI / 2;
        var totalSweep = Math.PI * 2 * progress;
        for (var i = 0; i < labels.length; i++) {
            var slice = (values[i] / total) * totalSweep;
            if (slice <= 0) { continue; }
            ctx.beginPath();
            ctx.moveTo(cx, cy);
            ctx.arc(cx, cy, r, startAngle, startAngle + slice);
            ctx.closePath();
            ctx.fillStyle = colors[i % colors.length];
            ctx.fill();
            ctx.strokeStyle = '#fff';
            ctx.lineWidth = 2;
            ctx.stroke();
            startAngle += slice;
        }

        var legendX = cx + r + 50;
        var legendY = cy - (labels.length * 20) / 2;
        ctx.textAlign = 'left';
        ctx.textBaseline = 'middle';
        for (var j2 = 0; j2 < labels.length; j2++) {
            ctx.fillStyle = colors[j2 % colors.length];
            ctx.fillRect(legendX, legendY + j2 * 20 - 6, 12, 12);
            ctx.fillStyle = '#2b2f4a';
            ctx.font = '12px Arial';
            var pct = ((values[j2] / total) * 100).toFixed(1);
            ctx.fillText(labels[j2] + ' (' + pct + '%)', legendX + 18, legendY + j2 * 20);
        }
    }

    function plRenderChart(chartData)
    {
        if (!chartData || !chartData.labels || chartData.labels.length === 0) {
            $('#plChartWrap .card-body .empty-state').remove();
            $('.Profit_Loss_Chart').hide();
            $('#plPieNegativeNote').hide();
            $('#plChartWrap .card-body').prepend('<div class="empty-state"><i class="fa fa-bar-chart"></i><p>No chart data available for this view</p></div>');
            return;
        }

        $('#plChartWrap .card-body .empty-state').remove();

        if (plChartCurrentView === 'pivot') {
            $('.Profit_Loss_Chart').hide();
            $('#plPieNegativeNote').hide();
            $('.pl-pivot-table-wrap').show();
            plRenderPivotTable(chartData);
            return;
        }

        $('.pl-pivot-table-wrap').hide();
        $('.Profit_Loss_Chart').show();

        if (plChartCurrentView === 'pie') {
            $('#plPieNegativeNote').show();
        } else {
            $('#plPieNegativeNote').hide();
        }

        var canvas = document.querySelector('.Profit_Loss_Chart');

        if (plChartCurrentView === 'pie') {
            plAnimateChart(plDrawPieChart, canvas, chartData);
        } else if (plChartCurrentView === 'bar') {
            plAnimateChart(plDrawBarChart, canvas, chartData);
        } else {
            plAnimateChart(plDrawLineChart, canvas, chartData);
        }
    }

    var plChartResizeTimer = null;
    $(window).on('resize', function () {
        clearTimeout(plChartResizeTimer);
        plChartResizeTimer = setTimeout(function () {
            if (plChartLastData && $('#plChartWrap').is(':visible') && plChartCurrentView !== 'pivot') {
                plRenderChart(plChartLastData);
            }
        }, 150);
    });

    </script>
    <div class="col-sm-12">&nbsp;</div>

@endsection