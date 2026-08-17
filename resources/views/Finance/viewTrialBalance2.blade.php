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
    var from = $('#from_datee').val();
    var m = '<?php echo $company_id;?>';
    var to = $('#to_date').val();

    if(from !="" && to != "" ) {

        $('#trial_bal').html('<div class="loader"></div>');
        $('#Error').html("");

        $.ajax({
            url: '<?php echo url('/');?>/fdc/trialBalanceData',
            type: 'GET',
            data: {from: from, to: to, m:m},
            success: function (response) {
                $('#trial_bal').html(response);
                $('#OtherArea').css('display','block');
            }
        });

        // NEW: chart ko bhi wahi from/to bhej kar refresh karein
        $.ajax({
            url: '<?php echo url('/');?>/TrialBalanceChartAjax',
            type: 'GET',
            data: {from: from, to: to, m: m},
            success: function (response) {
                Business_Flow_Chart_Report_TrialBalance(response?.sections);
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
            window.print();
            location.reload();
        })
    });
</script>
@extends('layouts.default')
@section('content')

    <style>
        /* ===== Sales Flow Chart (report page) ===== */
        #salesFlowChartWrap{margin-bottom:20px;}
        #salesFlowChartWrap .card.barChartHead{background:#fff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;height: auto !important;}
        #salesFlowChartWrap .card.barChartHead > div:first-child{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;flex-wrap:wrap !important;gap:12px !important;}
        #salesFlowChartWrap h6{font-size:17px !important;font-weight:700 !important;color:var(--erp-navy-dark,#0B1F59) !important;margin:0 !important;}
        #salesFlowChartWrap .selectOption select{height:38px !important;border-radius:9px !important;border:1px solid var(--erp-navy-tint,#E8ECFA) !important;background:#F7F9FD !important;font-weight:700 !important;font-size:12.5px !important;color:var(--erp-navy-dark,#0B1F59) !important;padding:6px 12px !important;}
        #salesFlowChartWrap .card-body{padding:0 !important;min-height:280px !important;}
        .empty-state{display:flex !important;flex-direction:column !important;align-items:center !important;justify-content:center !important;padding:50px 20px !important;color:#a7abc3 !important;text-align:center !important;min-height:200px !important;}
        .empty-state i{font-size:34px !important;margin-bottom:12px !important;color:#c9cfe6 !important;}
        .empty-state p{font-size:13.5px !important;font-weight:500 !important;margin:0 !important;color:#8892b0 !important;}

        /* ===== NEW: Chart view switcher (Line / Bar / Pivot) ===== */
        .tb-chart-view-switch{display:inline-flex !important;background:#F1F3FB !important;border-radius:10px !important;padding:4px !important;gap:4px !important;}
        .tb-view-btn{border:none !important;background:transparent !important;padding:7px 14px !important;font-size:12.5px !important;font-weight:700 !important;color:#5A6180 !important;border-radius:7px !important;cursor:pointer !important;transition:all .18s ease !important;white-space:nowrap !important;}
        .tb-view-btn:hover{color:var(--erp-navy-dark,#0B1F59) !important;}
        .tb-view-btn.active{background:#fff !important;color:var(--erp-navy-dark,#0B1F59) !important;box-shadow:0 2px 6px rgba(20,38,92,0.12) !important;}

        /* ===== NEW: Pivot table view ===== */
        .tb-pivot-table-wrap{padding:4px 4px 8px 4px !important;}
        table.tb-pivot-table{width:100% !important;background:#fff !important;border-collapse:collapse !important;margin:0 !important;}
        table.tb-pivot-table thead th{background:#EDF0F8 !important;color:#0B1F59 !important;font-weight:700 !important;font-size:12.5px !important;text-transform:uppercase !important;letter-spacing:.03em !important;padding:10px 14px !important;border-bottom:2px solid #D8DEF7 !important;}
        table.tb-pivot-table tbody td{padding:9px 14px !important;font-size:13px !important;color:#2b2f4a !important;border-bottom:1px solid #EDF0F8 !important;}
        table.tb-pivot-table tbody tr:hover td{background:#F7F9FD !important;}
        table.tb-pivot-table tbody tr:last-child td{font-weight:700 !important;background:#F1F3FB !important;color:#0B1F59 !important;}
    </style>

    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well_N">
                        <div class="dp_sdw">    
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                    <span class="subHeadingLabelClass">Trial Balance 6th Column</span>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right">
                                    <button type="button" class="btn btn-primary" id="btnViewChart" onclick="toggleSalesFlowChart()">View Chart</button>
                                    <?php echo CommonHelper::displayPrintButtonInBlade('trial_bal','','1');?>
                                    <?php if($export == true):?>
                                        <a id="dlink" style="display:none;"></a>
                                        <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">Export <b>(xlsx)</b></button>
                                    <?php endif;?>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <!-- ===== Sales Flow Chart (hidden by default, toggled via button) ===== -->
                            <div class="row" id="salesFlowChartWrap" style="display:none;">
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"> </div>
                                <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <div class="card barChartHead">
                                        <div>
                                            <div>
                                                <h6>Trial Balance 6th Column</h6>
                                            </div>
                                            <div class="text-right" style="display:flex;align-items:center;gap:12px;">
                                                <div class="tb-chart-view-switch">
                                                    <button type="button" class="tb-view-btn active" data-view="line" onclick="switchTrialBalanceChartView('line')">Line Graph</button>
                                                    <button type="button" class="tb-view-btn" data-view="bar" onclick="switchTrialBalanceChartView('bar')">Bar Chart</button>
                                                    <button type="button" class="tb-view-btn" data-view="pie" onclick="switchTrialBalanceChartView('pie')">Pie Chart</button>
                                                    <button type="button" class="tb-view-btn" data-view="pivot" onclick="switchTrialBalanceChartView('pivot')">Pivot</button>
                                                </div>
                                                <div class="selectOption">
                                                    @php $currentChartYear = date('Y'); @endphp
                                                    <select id="report_chart_year" onchange="BusinessFlowChartAjaxReport(this.value)">
                                                        @for($y = $currentChartYear; $y >= $currentChartYear - 4; $y--)
                                                            <option value="{{ $y }}" {{ $y == $currentChartYear ? 'selected' : '' }}>{{ $y }}</option>
                                                        @endfor
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <canvas class="Business_Flow_Chart_Report" data-height="425"></canvas>
                                            <p id="tbPieNegativeNote" class="text-center" style="display:none;font-size:11.5px;color:#8892b0;margin-top:8px;">Pie slices show absolute values — sections with a credit (negative) balance are shown as positive size.</p>
                                            <div class="tb-pivot-table-wrap" style="display:none;">
                                                <table class="table table-bordered tb-pivot-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Section / Month</th>
                                                            <th class="text-right">Amount</th>
                                                            <th class="text-right">Cumulative Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="tbPivotTableBody"></tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2"> </div>
                            </div>

                            <div class="row  align-items-center">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <?php echo Form::open(array('url' => 'fad/addAccountDetail?m='.$m.'','id'=>'chartofaccountForm'));?>
                                    <div class="row align-items-center">
                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label>From Date</label>
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
                                               <label>To Date</label>
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

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top:24px;">
                                            <input type="button" onclick="show()" class="btn btn-sm btn-primary" value="Submit"/>
                                        </div>

                                    </div>
                                    <?php echo Form::close();?>
                                    <span id="Error"></span>
                                </div>

                            </div>



                            <div class="row">


                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

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
    <script src="{{ asset('assets/js/charts/chart-chartjs.js') }}"></script>
    <script src="{{ asset('assets/js/charts/chart-chartjs.min.js') }}"></script>
    <script !src="">
        function ExportToExcel(type, fn, dl) {

            var decide = $('#AccountSpaces').val();
            if(decide == 1)
            {
                $('.SpacesCls').show();
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

        // ===== Sales Flow Chart toggle logic (report page) =====
        var salesFlowChartLoadedReport = false;

        function toggleSalesFlowChart()
        {
            var $wrap = $('#salesFlowChartWrap');

            if ($wrap.is(':visible')) {
                $wrap.slideUp();
                $('#btnViewChart').text('View Chart');
            } else {
                $wrap.slideDown();
                $('#btnViewChart').text('Hide Chart');

                if (!salesFlowChartLoadedReport) {
                    BusinessFlowChartAjaxReport($('#report_chart_year').val());
                    salesFlowChartLoadedReport = true;
                }
            }
        }

        // ===== NEW: shared state for the 4-view chart switcher =====
        var trialBalanceChartCurrentView = 'line'; // 'line' | 'bar' | 'pie' | 'pivot'
        var trialBalanceChartLastData = null;       // { labels: [], barData: [], lineData: [], barLabel: '', lineLabel: '' }

        function switchTrialBalanceChartView(view)
        {
            trialBalanceChartCurrentView = view;

            $('.tb-view-btn').removeClass('active');
            $('.tb-view-btn[data-view="' + view + '"]').addClass('active');

            if (view === 'pivot') {
                $('.Business_Flow_Chart_Report').hide();
                $('#tbPieNegativeNote').hide();
                $('.tb-pivot-table-wrap').show();
                renderTrialBalancePivotTable(trialBalanceChartLastData);
            } else {
                $('.tb-pivot-table-wrap').hide();
                $('.Business_Flow_Chart_Report').show();
                renderTrialBalanceFlowChart(trialBalanceChartLastData);
            }
        }

        function renderTrialBalancePivotTable(chartData)
        {
            var $tbody = $('#tbPivotTableBody');
            $tbody.empty();

            if (!chartData || !chartData.labels || chartData.labels.length === 0) {
                $tbody.html('<tr><td colspan="3" class="text-center">No data available for selected dates</td></tr>');
                return;
            }

            chartData.labels.forEach(function (label, i) {
                var amount = chartData.barData[i] || 0;
                var cumulative = chartData.lineData[i] || 0;
                $tbody.append(
                    '<tr>' +
                        '<td>' + label + '</td>' +
                        '<td class="text-right">' + amount.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                        '<td class="text-right">' + cumulative.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + '</td>' +
                    '</tr>'
                );
            });
        }

        // ===== NEW: hand-drawn canvas rendering (bypasses the buggy chart-chartjs.js curvy/smoothed output) =====
        function tbSetupCanvasForDPR(canvas, cssWidth, cssHeight)
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

        function tbFormatNum(n)
        {
            return Number(n).toLocaleString(undefined, { minimumFractionDigits: 0, maximumFractionDigits: 0 });
        }

        function tbEaseOutCubic(t)
        {
            return 1 - Math.pow(1 - t, 3);
        }

        var tbActiveAnimationId = null;

        // Generic rAF loop: calls drawFn(canvas, chartData, progress) every frame,
        // progress goes 0 -> 1 (eased), used for both first-load and tab-switch animation.
        function tbAnimateChart(drawFn, canvas, chartData, duration)
        {
            if (tbActiveAnimationId) {
                cancelAnimationFrame(tbActiveAnimationId);
                tbActiveAnimationId = null;
            }
            duration = duration || 650;
            var startTime = null;

            function step(timestamp) {
                if (!startTime) startTime = timestamp;
                var elapsed = timestamp - startTime;
                var rawProgress = Math.min(elapsed / duration, 1);
                var eased = tbEaseOutCubic(rawProgress);

                drawFn(canvas, chartData, eased);

                if (rawProgress < 1) {
                    tbActiveAnimationId = requestAnimationFrame(step);
                } else {
                    tbActiveAnimationId = null;
                }
            }

            tbActiveAnimationId = requestAnimationFrame(step);
        }

        function tbDrawBarChart(canvas, chartData, progress)
        {
            progress = (typeof progress === 'number') ? progress : 1;
            var cssWidth = canvas.parentElement.clientWidth || 700;
            var cssHeight = 380;
            var ctx = tbSetupCanvasForDPR(canvas, cssWidth, cssHeight);
            ctx.clearRect(0, 0, cssWidth, cssHeight);

            var labels = chartData.labels;
            var values = chartData.barData;
            var padding = { top: 40, right: 30, bottom: 45, left: 95 };
            var plotW = cssWidth - padding.left - padding.right;
            var plotH = cssHeight - padding.top - padding.bottom;

            var maxVal = Math.max(0, Math.max.apply(null, values));
            var minVal = Math.min(0, Math.min.apply(null, values));
            if (maxVal === minVal) { maxVal += 1; minVal -= 1; }
            var range = maxVal - minVal;

            function yFor(v) { return padding.top + plotH - ((v - minVal) / range) * plotH; }
            var zeroY = yFor(0);

            // legend / title
            ctx.fillStyle = '#0B1F59';
            ctx.font = 'bold 12.5px Arial';
            ctx.textAlign = 'left';
            ctx.fillText('■ ' + chartData.barLabel, padding.left, 18);

            // gridlines + y labels
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
                ctx.fillText(tbFormatNum(v), padding.left - 10, y);
            }

            ctx.strokeStyle = '#B9C0DE';
            ctx.beginPath();
            ctx.moveTo(padding.left, zeroY);
            ctx.lineTo(padding.left + plotW, zeroY);
            ctx.stroke();

            // separate rectangular bars — one per label, gap between each
            var n = labels.length;
            var slot = plotW / n;
            var barWidth = Math.min(56, slot * 0.5);
            ctx.textAlign = 'center';
            for (var j = 0; j < n; j++) {
                var val = values[j];
                var cx = padding.left + slot * j + slot / 2;
                var yTopFull = yFor(Math.max(0, val));
                var yBottomFull = yFor(Math.min(0, val));

                // grow the bar out from the zero line as progress goes 0 -> 1
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

                // value label: positive bars -> above the bar; negative bars -> inside the
                // bar just below the zero line (keeps it clear of the x-axis labels below).
                // Only draw once the bar has (almost) finished growing, avoids jumpy numbers.
                if (progress > 0.9) {
                    ctx.font = 'bold 10.5px Arial';
                    if (val >= 0) {
                        ctx.fillStyle = '#2b2f4a';
                        ctx.fillText(tbFormatNum(val), cx, yTopFull - 8);
                    } else {
                        var fullBarH = yBottomFull - yTopFull;
                        ctx.fillStyle = fullBarH > 22 ? '#fff' : '#2b2f4a';
                        var negLabelY = fullBarH > 22 ? (yTopFull + 14) : (yTopFull - 8);
                        ctx.fillText(tbFormatNum(val), cx, negLabelY);
                    }
                }

                ctx.fillStyle = '#5A6180';
                ctx.font = '11.5px Arial';
                ctx.fillText(labels[j], cx, padding.top + plotH + 20);
            }
        }

        function tbDrawLineChart(canvas, chartData, progress)
        {
            progress = (typeof progress === 'number') ? progress : 1;
            var cssWidth = canvas.parentElement.clientWidth || 700;
            var cssHeight = 380;
            var ctx = tbSetupCanvasForDPR(canvas, cssWidth, cssHeight);
            ctx.clearRect(0, 0, cssWidth, cssHeight);

            var labels = chartData.labels;
            var values = chartData.lineData;
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
            ctx.fillText('— ' + chartData.lineLabel, padding.left, 18);

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
                ctx.fillText(tbFormatNum(v), padding.left - 10, y);
            }

            ctx.strokeStyle = '#B9C0DE';
            ctx.beginPath();
            ctx.moveTo(padding.left, zeroY);
            ctx.lineTo(padding.left + plotW, zeroY);
            ctx.stroke();

            var n = labels.length;
            var slot = plotW / n;

            // animate each point from the zero baseline out to its target y as progress -> 1
            var animatedYs = [];
            for (var p = 0; p < n; p++) {
                var targetY = yFor(values[p]);
                animatedYs.push(zeroY + (targetY - zeroY) * progress);
            }

            // straight-segment polyline (no bezier smoothing)
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
                    ctx.fillText(tbFormatNum(values[k]), cx2, yFor(values[k]) - 12);
                }

                ctx.fillStyle = '#5A6180';
                ctx.font = '11.5px Arial';
                ctx.fillText(labels[k], cx2, padding.top + plotH + 20);
            }
        }

        function tbDrawPieChart(canvas, chartData, progress)
        {
            progress = (typeof progress === 'number') ? progress : 1;
            var cssWidth = canvas.parentElement.clientWidth || 700;
            var cssHeight = 380;
            var ctx = tbSetupCanvasForDPR(canvas, cssWidth, cssHeight);
            ctx.clearRect(0, 0, cssWidth, cssHeight);

            var labels = chartData.labels;
            var values = chartData.barData.map(function (v) { return Math.abs(v); });
            var total = values.reduce(function (a, b) { return a + b; }, 0);

            var colors = ['#1E3A8A', '#F5A623', '#10B981', '#EF4444', '#8B5CF6', '#06B6D4', '#EC4899', '#EAB308'];

            var cx = 170, cy = cssHeight / 2, r = Math.min(cy - 30, 130);

            if (total <= 0) {
                ctx.fillStyle = '#8892b0';
                ctx.font = '13px Arial';
                ctx.textAlign = 'center';
                ctx.fillText('No data available for selected dates', cssWidth / 2, cssHeight / 2);
                return;
            }

            var startAngle = -Math.PI / 2;
            var totalSweep = Math.PI * 2 * progress; // full circle grows in as progress -> 1
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

        // Renders either the Line, Bar or Pie view depending on trialBalanceChartCurrentView
        function renderTrialBalanceFlowChart(chartData)
        {
            if (!chartData || !chartData.labels || chartData.labels.length === 0) {
                $('.Business_Flow_Chart_Report').closest('.card-body').find('.empty-state').remove();
                $('.Business_Flow_Chart_Report').hide();
                $('#tbPieNegativeNote').hide();
                $('.card-body').append('<div class="empty-state"><i class="fa fa-bar-chart"></i><p>No data available for selected dates</p></div>');
                return;
            }

            // agar pehle empty-state dikha diya tha to hataein aur canvas wapis dikhaein
            $('.card-body .empty-state').remove();
            $('.Business_Flow_Chart_Report').show();

            if (trialBalanceChartCurrentView === 'pie') {
                $('#tbPieNegativeNote').show();
            } else {
                $('#tbPieNegativeNote').hide();
            }

            var canvas = document.querySelector('.Business_Flow_Chart_Report');

            if (trialBalanceChartCurrentView === 'pie') {
                tbAnimateChart(tbDrawPieChart, canvas, chartData);
            } else if (trialBalanceChartCurrentView === 'bar') {
                tbAnimateChart(tbDrawBarChart, canvas, chartData);
            } else {
                tbAnimateChart(tbDrawLineChart, canvas, chartData);
            }
        }

        // Trial Balance date-range data (from show()'s TrialBalanceChartAjax call)
        function Business_Flow_Chart_Report_TrialBalance(sections)
        {
            let labels  = [];
            let barData = [];
            let lineData = [];

            if (sections && sections.length > 0) {
                var runningTotal = 0;
                sections.forEach(item => {
                    labels.push(item.label);
                    var amount = parseFloat(item.amount) || 0;
                    barData.push(amount);
                    runningTotal += amount;
                    lineData.push(runningTotal);
                });
            }

            trialBalanceChartLastData = {
                labels: labels,
                barData: barData,
                lineData: lineData,
                barLabel: 'Closing Balance',
                lineLabel: 'Cumulative Total'
            };

            if (trialBalanceChartCurrentView === 'pivot') {
                renderTrialBalancePivotTable(trialBalanceChartLastData);
            } else {
                renderTrialBalanceFlowChart(trialBalanceChartLastData);
            }
        }

        // Year-wise monthly sales data (from BusinessFlowChartAjaxReport -> BusinessFlowChartAjax controller)
        function Business_Flow_Chart_Report(data)
        {
            let labels = [];
            let barData  = [];
            let lineData = [];

            if (data && data.length > 0) {
                var runningTotal = 0;
                data.forEach(item => {
                    labels.push(item.month_name);
                    var amount = parseFloat(item.total_amount) || 0;
                    barData.push(amount);
                    runningTotal += amount;
                    lineData.push(runningTotal);
                });
            }

            trialBalanceChartLastData = {
                labels: labels,
                barData: barData,
                lineData: lineData,
                barLabel: 'Monthly Sales',
                lineLabel: 'Cumulative Sales'
            };

            if (trialBalanceChartCurrentView === 'pivot') {
                renderTrialBalancePivotTable(trialBalanceChartLastData);
            } else {
                renderTrialBalanceFlowChart(trialBalanceChartLastData);
            }
        }

        // redraw crisply on resize (canvas needs re-measuring, not just CSS stretch)
        var tbChartResizeTimer = null;
        $(window).on('resize', function () {
            clearTimeout(tbChartResizeTimer);
            tbChartResizeTimer = setTimeout(function () {
                if (trialBalanceChartLastData && $('#salesFlowChartWrap').is(':visible') && trialBalanceChartCurrentView !== 'pivot') {
                    renderTrialBalanceFlowChart(trialBalanceChartLastData);
                }
            }, 150);
        });
    </script>

@endsection