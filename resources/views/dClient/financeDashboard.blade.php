<?php
   use App\Helpers\CommonHelper;
   use App\Helpers\DashboardHelper;
   use App\Helpers\ReuseableCode;
  $accYear =  ReuseableCode::get_account_year_from_to(Session::get('run_company'));
  $from = $accYear[0];
  $to = $accYear[1];

    // receivable
$receiable = CommonHelper::get_parent_and_account_amount(Session::get('run_company'),$from,$to,'1-2-2','1',1,0);
$payable = CommonHelper::get_parent_and_account_amount(Session::get('run_company'),$from,$to,'2-2-2','1',0,1);

   use   Illuminate\Support\Carbon;
         $m = '';
      if(isset($_GET['m']))
      {
         $m = $_GET['m'];
      }
      else
      {
         $m = '';
      }
   $UserId = Auth::user()->id;

   ?>
@extends('layouts.default')
@section('content')
{{--< ?php --}}
{{--//$Companies = DB::table('company')->where('status',1)->get();?>--}}
{{--
<div class="row">
   --}}
   {{--
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
      --}}
      {{--
      <div class="panel">
         --}}
         {{--
         <div class="panel-body">
            --}}
            {{--
            <div class="">
               --}}
               {{--< ?php foreach($Companies as $Fil):?>--}}
               {{--&nbsp;--}}
               {{--
               <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">--}}
                  {{--<a href="#" class="btn btn-lg btn-primary" style="width: 100%; border-radius: 25px;">--}}
                  {{--<i class="fa fa-arrow-right" aria-hidden="true"></i>--}}
                  {{--< ?php echo $Fil->name;?>--}}
                  {{--<i class="fa fa-university" aria-hidden="true" style="float: right;"></i>--}}
                  {{--</a>--}}
                  {{--
               </div>
               --}}
               {{--< ?php endforeach;?>--}}
               {{--
            </div>
            --}}
            {{--
         </div>
         --}}
         {{--
      </div>
      --}}
      {{--
   </div>
   --}}
   {{--
</div>
--}}
{{--< ?php ?>--}}
<?php $count=0;
   if(Auth::user()->id == 104)
   {
   $companiesList = DB::table('company')->select(['name','id','dbName'])->where('status','=','1')->get();


   }
         else{
   $companiesList = DB::table('company')->select(['name','id','dbName'])->where('id','!=',4)->where('status','=','1')->get();

         }

   ?>
@if(Session::get('run_company')==''):
<div id="companyListModel" class="modal fade in" role="dialog" aria-hidden="false" style="display: block;">
   <div class="modal-dialog modalWidth dply">
      <!-- Modal content-->
      <div class="model-n modal-content">
         <div class="modal-body">
            <div class="mdel-bx">
               <img class="circle" src="../assets/img/animation/circledot.png">
               <div class="model-logo">
                  <img src="assets/img/logos/logo.png">
                  <h4 class="modal-title">Select Company</h4>
               </div>
               @foreach($companiesList  as $key => $cRow1)
               <div class="row">
                  <ul class="ban-list">
                     <li>
                        <div class="banq-box">
                           <a href="{{url('set_user_db_id?company='.$cRow1->id)}}">
                              <span class="companyLetr theme-bg theme-f-m">D</span>
                              <h3 class="item-model-company theme-f-m">{{ $cRow1->name }}</h3>
                           </a>
                        </div>
                     </li>
                     {{-- <li>
                        <div class="banq-box">
                           <a href="{{url('set_user_db_id?company='.$cRow1->id)}}">
                              <span class="companyLetr theme-bg theme-f-m">D</span>
                              <h3 class="item-model-company theme-f-m">{{ $cRow1->name }}</h3>
                           </a>
                        </div>
                     </li> --}}
                  </ul>
                  @endforeach
               </div>
               <a href="{{url('/logout')}}" class="btn-b">Sign Out</a>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-backdrop fade in"></div>
</div>
@endif
<div class="well_N">
    <div>
        <div class="row" style="display: none;">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="">
                            <?php $count=0; ?>
                            @foreach($companiesList as $key => $cRow1)
                            @if($count==0 && $cRow1->id<=5) <h2 style="text-align: center">
                                <p class="">Select Company
                                    </h2>
                                    <?php $count++ ?>
                                    @elseif($count==1 && $cRow1->id>5)
                                <h2 style="text-align: center">
                                    <p class="outset">Financial Year :2022-2023
                                </h2>
                                @endif
                                <a href="{{url('set_user_db_id?company='.$cRow1->id)}}" class="">
                                    {{--{{ $cRow1->name}}--}}
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 " style="font-size: 20px;">
                                        {{--{{ $cRow1->name }}--}}
                                        <?php echo CommonHelper::get_company_logo_front($cRow1->id)?> <span
                                            id="Loading<?php echo $cRow1->id?>"></span></i>
                                    </div>
                                </a>
                                @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if(Session::get('run_company')):?>

            @php

                $currentDate = Carbon::now();
                $monthStartDate = $currentDate->startOfMonth()->toDateString();
                $monthEndDate = $currentDate->endOfMonth()->toDateString();
                $currentMonthYear = $currentDate->year;

            @endphp
        <span style="display: block;">
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12 priorMainBox2">
                        <a href="#" class="hide" onclick="getDashboardSaleSummary(1,'{{$monthStartDate}}','{{$monthEndDate}}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Today's Salesss</h6>
                                    <p>{{$currentMonthYear}}</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>
                                    {{number_format(CommonHelper::getSaleSummaryAmountF($monthStartDate,$monthEndDate),0)}}
                                </h4>

                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>

                        <a href="#" onclick="getDashboardSaleSummary(2,'{{$monthStartDate}}','{{$monthEndDate}}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month Sales</h6>
                                    <p>{{$currentMonthYear}}</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>

                                    {{number_format(CommonHelper::getSaleSummaryAmountF($monthStartDate,$monthEndDate),0)}}
                                </h4>
                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month's Collection</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>{{ number_format($collection,2) }}</h4>
                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Receivables</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4 class="salesagingtotal" >{{  number_format($receiable,2) }}</h4>
                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Payables</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4 class="vendoragingtotal">{{ number_format($payable) }}</h4>
                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card barChartHead">
                                    <div>
                                        <div>
                                            <h6>Sales  Flow Chart</h6>
                                            <ul class="hidden">
                                                <li>
                                                    <input type="radio" name="" id="" checked stlye="color:red" readonly>
                                                    Sales
                                                </li>
                                                <li>
                                                    <input type="radio" name="" id="" readonly>
                                                    Purchase
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-right">

                                            <div class="selectOption">
                                                <select id="year" onchange="BusinessFlowChartAjax(this.value)" >
                                                    <option value="2023">2023</option>
                                                    <option value="2024">2024</option>
                                                    <option value="2025">2025</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas class="Business_Flow_Chart chartjs" data-height="320"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-7">
                                <div id="printBankPaymentVoucherList">
                                    <div class="panel ">
                                        <div id="PrintPanel">
                                            <div id="ShowHide">
                                                <div class="table-responsive dashTable mhe">
                                                    <div class="dashTableHeading printListBtn">
                                                        <h6>Pending Order Sheet</h6>
                                                        <a class="btn btn-primary" target="_blank" id="myBtn" href="{{url('/sales/viewSalesOrderList?pageType=view&&parentCode=89&&m=1#Rototec')}}">View All Sales Orders</a>
                                                    </div>
                                                    <table class="userlittab table table-bordered sf-table-list"
                                                        id="TableExportToCsv">
                                                        <thead class="bgBlueofTd">
                                                            <tr>
                                                                <th class="text-center" colspan="2">Customer Name</th>
                                                                <th class="text-center">SO No</th>
                                                                <th class="text-center"> Sales Invoice No.</th>
                                                                <th class="text-center">Item Name</th>
                                                                <th class="text-center">Sale Qty</th>
                                                                <th class="text-center">Qty Delivered</th>
                                                                <th class="text-center">Pending Qty</th>
                                                                <th class="text-center">Status</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id="data" class="dashTableBody">
                                                            @php
                                                                $latestSaleOrders = CommonHelper::displayLatestSaleOrdersDetailF();

                                                                $overallSubTotal = 0;
                                                                $overallTaxAmount = 0;
                                                            @endphp
                                                            @if(!empty($latestSaleOrders))
                                                            @foreach($latestSaleOrders as $lsoKey => $lsoRow)
                                                                @php
                                                                    // $sale_order_status = App\Helpers\SalesHelper::approval_status_for_so($lsoRow->so_status,$lsoRow->id);
                                                                  //  $overallSubTotal += $lsoRow->total_amount;
                                                                  //  $overallTaxAmount += $lsoRow->total_amount_after_sale_tax;
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center" colspan="2">
                                                                            {{strtoupper($lsoRow->name)}}
                                                                    </td>
                                                                    <td class="text-center" >
                                                                            {{strtoupper($lsoRow->so_no)}}
                                                                    </td>
                                                                    <td class="text-center" >
                                                                            {{-- {{strtoupper($lsoRow->gi_no ?? '--')}} --}}
                                                                            {{CommonHelper::getInvoiceNoBySO($lsoRow->id)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                            {{$lsoRow->items_name ?? '---'}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                            {{number_format($lsoRow->sale_qty,0)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                            {{number_format($lsoRow->delivery_qty??0,0)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                            {{number_format($lsoRow->remaining_qty??0,0)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        @if($lsoRow->so_status == 0)
                                                                        Pending
                                                                    @elseif($lsoRow->so_status ==  2)
                                                                        Draft
                                                                    @else
                                                                      Sale Order Created
                                                                    @endif

                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                            <tr class="hide">
                                                                <td colspan="4">Total</td>
                                                                <td class="text-right">
                                                                {{-- {{number_format($overallSubTotal,0)}} --}}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{-- {{number_format($overallTaxAmount,0)}} --}}
                                                                </td>
                                                                <td class="text-right">
                                                                {{--   {{number_format($overallTaxAmount,0)}} --}}
                                                                </td>
                                                                <td class="text-center">---</td>
                                                            </tr>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card barChartHead">
                                    <div
                                        class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                                        <div class="cashSection">
                                            <div>
                                                <h4 class="card-subtitle  mb-25"> Total Receipts and Total Payments
                                                    <input type="month" value="{{date('Y-m') }}" id="monthyear" onchange="trtpAjax(this.value)">
                                                </h4>
                                                <p class="card-title font-weight-bolder">Cash Coming in and going out
                                                    of
                                                    you business</p>
                                            </div>
                                            <!-- <div class="selectOption">
                                                <select>
                                                    <option value="">Weekly</option>
                                                    <option value="">Monthly</option>
                                                    <option value="">Yearly</option>
                                                </select>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div id="tr_tp"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payable pieChartHead">
                                    <div
                                        class="statistics card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                                        <h6 class="card-title mb-sm-0 mb-1">Receivables and Payables <input type="date" onchange="ReceivablesAndPayablesAjax(this.value)" id="ReceivablesAndPayables"></h6>
                                    </div>
                                    <ul id="ReceivablesAndPayablesDiv">
                                        <li>
                                            <h6>Invoice payable to you</h6>
                                            <ul>
                                                <li>
                                                    <p>Due</p>
                                                    <p>$0.00</p>
                                                </li>
                                                <li>
                                                    <p>Due in 1-30 days</p>
                                                    <p>$0.00</p>
                                                </li>
                                                <li>
                                                    <p>Due</p>
                                                    <p>$0.00</p>
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <h6>Payable bills you owe</h6>
                                            <ul>
                                                <li>
                                                    <p>Due</p>
                                                    <p>$0.00</p>
                                                </li>
                                                <li>
                                                    <p>Due in 1-30 days</p>
                                                    <p>$0.00</p>
                                                </li>
                                                <li>
                                                    <p>Due</p>
                                                    <p>$0.00</p>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mp-20">
                        <div class="row">

                            <div class="col-md-8">
                                <div class="topExport topSelling">
                                    <div class="dashTableHeading printListBtn">
                                        <h6> Limit Utilisation LC/LG </h6> 
                                    </div>
                                    <table class="userlittab table table-bordered sf-table-list">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Type</th>
                                                <th class="text-center">Account</th>
                                                <th class="text-center">Sanctioned Limit</th>
                                                <th class="text-center">Limit Utilized</th>
                                                <th class="text-center">Un Utilized</th>
                                                <th class="text-center">Remaining %</th>
                                            </tr>
                                        </thead>
                                        <tbody id="reportData">

                                        </tbody>

                                    </table>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="topExport topSelling">
                                    <div class="dashTableHeading printListBtn">
                                        <h6>Bank Balance</h6> 
                                    </div>
                                    <table class="userlittab table table-bordered sf-table-list">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Bank</th>
                                                <th class="text-center">Balance</th>
                                            </tr>
                                        </thead>
                                        <tbody id="">
                                            @php
                                                $total_bank_balance = 0;
                                            @endphp
                                            @foreach(DashboardHelper::BankBalanceReport() as $key => $value)
                                            <?php  ?>
                                                <tr>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ number_format($value->balance,2) }}</td>
                                                </tr>
                                                @php
                                                    $total_bank_balance += $value->balance;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">{{ number_format($total_bank_balance,2) }}</th>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-md-12 mp-20">
                        <div class="row">

                            <div class="col-md-6">
                                <div class="topExport topSelling">
                                    <div class="statistics card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                                        <h6 class="card-title mb-sm-0 mb-1">Filter By Financial Year 
                                            {{-- <input type="date" onchange="ReceivablesAndPayablesAjax(this.value)" id="ReceivablesAndPayables"> --}}
                                            <select name="" id="customer_sales_year" onchange="filterCutumerSaleYearWise();">
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
                                        </h6>
                                    </div>

                                    <table class="userlittab table table-bordered sf-table-list">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Customer Name</th>
                                                <th class="text-center">Sales</th>
                                            </tr>
                                        </thead>
                                        <tbody id="customer_data">
                                            @php
                                                $customer_total_amount = 0;
                                            @endphp
                                            @foreach(DashboardHelper::CustomerWiseSales() as $key => $value)
                                                <tr>
                                                    <td>{{ $value->name }}</td>
                                                    <td>{{ number_format($value->amount , 2) }}</td>
                                                </tr>
                                                @php
                                                    $customer_total_amount += $value->amount;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <th class="text-center">Total</th>
                                                <th class="text-center">{{number_format($customer_total_amount , 2)}}</th>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="well" id="ShowHide">
            </div>
    </div>

</div>


<script src="assets/js/charts/chart-chartjs.js"></script>
<script src="assets/js/charts/chart-chartjs.min.js"></script>


<script !src="">
   $(document).ready(function() {
   /*
      var formWidth = $('.sliding_form').width();
      $('.sliding_form').css('right', '-' + formWidth + 'px');
      $("#form_trigger").on('click', function() {

         if ($('.sliding_form').hasClass('slide_out')) {
            $('.sliding_form').removeClass('slide_out').addClass('slide_in')
            $(".sliding_form").animate({ right: 0 + 'px' });

            $('#AjaxDataOnlineUsers').html('<div class="loader"></div>');
            var m = '< ?php echo $m?>';
            $.ajax({
               url: '/pdc/getOnlineUserAjax',
               type: 'Get',
               data: {m:m},

               success: function (response)
               {
                  $('#AjaxDataOnlineUsers').html(response);
               }
            });

         } else {
            $('.sliding_form').removeClass('slide_in').addClass('slide_out')
            $('.sliding_form').animate({ right: '-' + formWidth + 'px' });

         }

      });
      */
   });
   function filterCutumerSaleYearWise()
   {
    value = $('#customer_sales_year').val();
    var m = '<?php echo $m?>';
      $('#customer_data').html('<div class="loader"></div>');

      $.ajax({
         url: '/get_customer_sales_info',
         type: 'Get',
         data: {m:m , year : value},

         success: function (response)
         {
            console.log(response);
            $('#customer_data').html(response);
         }
      });

    console.log(appendData);
   }

   function getDashboardInfo(Type)
   {
      var m = '<?php echo $m?>';
      $('#ShowHide').html('<div class="loader"></div>');

      $.ajax({
         url: '/pdc/get_dashboard_info',
         type: 'Get',
         data: {Type: Type,m:m},

         success: function (response)
         {
            $('#ShowHide').html(response);
         }
      });


   }
</script>


</span>

<script>

         function reportLcLg()
        {

            let rate_date = $('#rate_date').val();
            let to_date = $('#to_date').val();
             $('#reportData').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/import/LcAndLg/reportLcLg',
                type: 'Get',
                data: {
                        rate_date:rate_date,
                        to_date:to_date
                    },
                success: function (response) {

                    $('#reportData').html(response);


                }
            });


        }


    </script>



<script>
         $(document).ready(function () {
            reportLcLg();
            filterCutumerSaleYearWise();

                $('#year').trigger('change');
                $('#monthyear').trigger('change');
                $('#ReceivablesAndPayables').trigger('change');

            });


            function Business_Flow_Chart(data) {

                let labels = [];
                let datas = [];

                // Loop through the array and extract month_name and total_amount
                data.forEach(item => {
                    labels.push(item.month_name);
                    datas.push(item.total_amount);
                });

                let barChartEx = $('.Business_Flow_Chart');

                var barChartExample = new Chart(barChartEx, {
                    type: 'bar',
                    options: {
                        legend: {
                            display: false
                        },
                    },
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                data: datas,
                                barThickness: 15,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)'
                            }
                        ]
                    }
                });
            }

            function tr_tp(data) {
                var barChartEl = document.querySelector('#tr_tp');

                if (typeof barChartEl !== 'undefined' && barChartEl !== null) {
                    console.log('innn');
                    var barChartConfig = {
                        chart: {
                            height: 180,
                            type: 'bar',
                            parentHeightOffset: 0,
                            toolbar: {
                                show: false
                            }
                        },
                        plotOptions: {
                            bar: {
                                horizontal: true,
                                barHeight: '30%',
                                endingShape: 'rounded'
                            }
                        },
                        grid: {
                            xaxis: {
                                lines: {
                                    show: false
                                }
                            },
                            padding: {
                                top: -15,
                                bottom: -10
                            }
                        },
                        colors: window.colors.solid.info,
                        dataLabels: {
                            enabled: false
                        },
                        series: [
                            {
                                data: data
                            }
                        ],
                        xaxis: {
                            categories: ['TR', 'TP']
                        }
                    };

                    var barChart = new ApexCharts(barChartEl, barChartConfig);
                    barChart.render();
                }
            }

            function BusinessFlowChartAjax(year)
            {
                    $.ajax({
                        url: '/BusinessFlowChartAjax',
                        type: 'Get',
                        data: {
                                year : year
                              },
                        success: function (response) {

                            Business_Flow_Chart(response?.SalesFlowChart)

                        }
                    });

            }
            
            function trtpAjax(monthyear)
            {
                $('#tr_tp').empty()
                    $.ajax({
                        url: '/trtpAjax',
                        type: 'Get',
                        data: {
                                monthyear:monthyear
                              },
                        success: function (response) {

                            // console.log(response)
                            setTimeout(() => {
                                tr_tp(response);
                            }, 2000);
                            // Business_Flow_Chart(response?.SalesFlowChart)

                        }
                    });

            }
            
            function ReceivablesAndPayablesAjax(date)
            {
                $('#ReceivablesAndPayablesDiv').empty();

                salesAgingAjax(date);
                vendorAgingAjax(date);
            }
            
            function salesAgingAjax(date)
            {
                    $.ajax({
                        url: '/salesAgingAjax',
                        type: 'Get',
                        data: {
                                date:date
                              },
                        success: function (response) {


                            $('#ReceivablesAndPayablesDiv').append(response);
                            
                            $('.salesagingtotal').text($('#salesagingtotal').text());

                        }
                    });

            }
            function vendorAgingAjax(date)
            {
                    $.ajax({
                        url: '/vendorAgingAjax',
                        type: 'Get',
                        data: {
                                date:date
                              },
                        success: function (response) {

                            $('#ReceivablesAndPayablesDiv').append(response);
                            

                            $('.vendoragingtotal').text($('#vendoragingtotal').text());
                            // vendoragingtotal
                        }
                    });

            }


</script>
<?php endif;?>
@endsection
