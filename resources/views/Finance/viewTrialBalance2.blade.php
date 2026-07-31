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

        var  m = '<?php echo $company_id;?>';
        var to=	$('#to_date').val();

        if(from !="" && to != "" ) {

            $('#trial_bal').html('<div class="loader"></div>');
            $('#Error').html("");

            $.ajax({
                url: '<?php echo url('/');?>/fdc/trialBalanceData',
                type: 'GET',
                data: {from: from, to: to, m:m},
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
        #salesFlowChartWrap .card.barChartHead > div:first-child{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;}
        #salesFlowChartWrap h6{font-size:17px !important;font-weight:700 !important;color:var(--erp-navy-dark,#0B1F59) !important;margin:0 !important;}
        #salesFlowChartWrap .selectOption select{height:38px !important;border-radius:9px !important;border:1px solid var(--erp-navy-tint,#E8ECFA) !important;background:#F7F9FD !important;font-weight:700 !important;font-size:12.5px !important;color:var(--erp-navy-dark,#0B1F59) !important;padding:6px 12px !important;}
        #salesFlowChartWrap .card-body{padding:0 !important;min-height:280px !important;}
        canvas.Business_Flow_Chart_Report{max-height:280px !important;}
        .empty-state{display:flex !important;flex-direction:column !important;align-items:center !important;justify-content:center !important;padding:50px 20px !important;color:#a7abc3 !important;text-align:center !important;min-height:200px !important;}
        .empty-state i{font-size:34px !important;margin-bottom:12px !important;color:#c9cfe6 !important;}
        .empty-state p{font-size:13.5px !important;font-weight:500 !important;margin:0 !important;color:#8892b0 !important;}
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
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card barChartHead">
                                        <div>
                                            <div>
                                                <h6>Sales Flow Chart</h6>
                                            </div>
                                            <div class="text-right">
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
                                            <canvas class="Business_Flow_Chart_Report chartjs" data-height="425"></canvas>
                                        </div>
                                    </div>
                                </div>
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

        function BusinessFlowChartAjaxReport(year)
        {
            $.ajax({
                url: '<?php echo url('/');?>/BusinessFlowChartAjax',
                type: 'Get',
                data: { year: year },
                success: function (response) {
                    Business_Flow_Chart_Report(response?.SalesFlowChart);
                }
            });
        }

        function Business_Flow_Chart_Report(data)
        {
            let labels = [];
            let datas  = [];

            if (!data || data.length === 0) {
                $('.Business_Flow_Chart_Report').closest('.card-body').html('<div class="empty-state"><i class="fa fa-bar-chart"></i><p>No sales data available for this year</p></div>');
                return;
            }

            data.forEach(item => {
                labels.push(item.month_name);
                datas.push(item.total_amount);
            });

            let barChartEx = $('.Business_Flow_Chart_Report');

            if (window.salesFlowChartReportInstance) {
                window.salesFlowChartReportInstance.destroy();
            }

            window.salesFlowChartReportInstance = new Chart(barChartEx, {
                type: 'bar',
                options: {
                    legend: { display: false },
                    scales: {
                        yAxes: [{ ticks: { beginAtZero: true } }]
                    }
                },
                data: {
                    labels: labels,
                    datasets: [
                        {
                            data: datas,
                            barThickness: 15,
                            backgroundColor: 'rgba(30, 58, 138, 0.25)',
                            borderColor: 'rgba(30, 58, 138, 1)',
                            borderWidth: 1
                        }
                    ]
                }
            });
        }
    </script>

@endsection