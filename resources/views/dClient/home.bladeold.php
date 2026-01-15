<?php
   use App\Helpers\CommonHelper;
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
        <span style="display: block;">
            <div class="wrapper wrapper-content">
                <div class="row">
                    <div class="col-lg-12 priorMainBox">
                        <a href="#" onclick="getDashboardSaleSummary(1,'{{date('Y-m-d')}}','{{date('Y-m-d')}}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Today's Sales</h6>
                                    <p>{{date('Y-m-d')}}</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>
                                    {{number_format(CommonHelper::getSaleSummaryAmount(date('Y-m-d'),date('Y-m-d')),0)}}
                                </h4>

                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>
                        @php
                      
                        $currentDate = Carbon::now();
                        $monthStartDate = $currentDate->startOfMonth()->toDateString();
                        $monthEndDate = $currentDate->endOfMonth()->toDateString();
                        $currentMonthYear  = 2023;
                        @endphp
                        <a href="#" onclick="getDashboardSaleSummary(2,'{{$monthStartDate}}','{{$monthEndDate}}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month Sales</h6>
                                    <p>{{$currentMonthYear}}</p>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>
                                    {{number_format(CommonHelper::getSaleSummaryAmount($monthStartDate,$monthEndDate),0)}}
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
                                <h4>$8,216.00</h4>
                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Receivables</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>$8,216.00</h4>
                                <!-- <p>Lorem ipsum dolor sit amet consectetur</p> -->
                            </div>
                        </a>
                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Payables</h6>
                                </div>
                                <img src="assets/img/miniBar.svg" alt="">
                                <h4>$8,216.00</h4>
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
                                            <h6>Business Flow Chart</h6>
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
                                            <h6>$8,216.00</h6>
                                            <p>Total Sales – Monthly</p>
                                            <div class="selectOption">
                                                <select>
                                                    <option value="">Jan</option>
                                                    <option value="">Feb</option>
                                                    <option value="">Mar</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <canvas class="bar-chart-ex chartjs" data-height="320"></canvas>
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
                                                        <h6>Sales Orders</h6>
                                                        <a class="btn btn-primary" target="_blank" id="myBtn" href="{{url('/sales/viewSalesOrderList?pageType=view&&parentCode=89&&m=1#Rototec')}}">View All Sales Orders</a>
                                                    </div>
                                                    <table class="userlittab table table-bordered sf-table-list"
                                                        id="TableExportToCsv">
                                                        <thead class="bgBlueofTd">
                                                            <tr>
                                                                <th class="text-center" colspan="2">Customer</th>
                                                                <th class="text-center">Order No</th>
                                                                <th class="text-center">Order Date</th>
                                                                <th class="text-center">Without Tax Amount</th>
                                                                <th class="text-center">Tax Amount</th>
                                                                <th class="text-center">Sub Total</th>
                                                                <th class="text-center">Status</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data" class="dashTableBody">
                                                            @php
                                                                $latestSaleOrders = CommonHelper::displayLatestSaleOrdersDetail();
                                                               
                                                                $overallSubTotal = 0;
                                                                $overallTaxAmount = 0;
                                                            @endphp
                                                            @if(!empty($latestSaleOrders))
                                                            @foreach($latestSaleOrders as $lsoKey => $lsoRow)
                                                                @php
                                                                    // $sale_order_status = App\Helpers\SalesHelper::approval_status_for_so($lsoRow->so_status,$lsoRow->id);
                                                                    $overallSubTotal += $lsoRow->total_amount;
                                                                    $overallTaxAmount += $lsoRow->total_amount_after_sale_tax;
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center" colspan="2">
                                                                        {{strtoupper($lsoRow->name)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{strtoupper($lsoRow->so_no)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{CommonHelper::changeDateFormat($lsoRow->so_date)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{number_format($lsoRow->total_amount,0)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{number_format($lsoRow->total_amount_after_sale_tax - $lsoRow->total_amount,0)}}
                                                                    </td>
                                                                    <td class="text-center">
                                                                        {{number_format($lsoRow->total_amount_after_sale_tax,0)}}
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
                                                            <tr>
                                                                <td colspan="4">Total</td>
                                                                <td class="text-right">
                                                                    {{number_format($overallSubTotal,0)}}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{-- {{number_format($overallTaxAmount,0)}} --}}
                                                                </td>
                                                                <td class="text-right">
                                                                    {{number_format($overallTaxAmount,0)}}
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
                                                <h4 class="card-subtitle  mb-25">Cash Flow</h4>
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
                                        <div id="bar-chart"  ></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payable pieChartHead">
                                    <div
                                        class="statistics card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                                        <h6 class="card-title mb-sm-0 mb-1">Receivables and Payables</h6>
                                    </div>
                                    <ul>
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
                            <div class="col-md-5">
                                <div class="topExport topSelling">
                                    <div>
                                        <h6>Top Selling Products</h6>
                                        <div>
                                            <div class="selectOption">
                                                <select>
                                                    <option value="">Volume</option>
                                                    <option value="">Sales Value</option>
                                                </select>
                                            </div>
                                            <!-- <div class="selectOption">
                                                <select>
                                                    <option value="">Sales Value</option>
                                                    <option value="">Sales Value</option>
                                                    <option value="">Sales Value</option>
                                                </select>
                                            </div> -->
                                        </div>
                                    </div>
                                    <ul>
                                        <li>
                                            <div>
                                                <h6>Bluetooth Headphones</h6>
                                                <div>
                                                    <h6>CB-71474</h6>
                                                    <p>Total Sales: <span>$60,710</span></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <h6>Bluetooth Headphones</h6>
                                                <div>
                                                    <h6>CB-71474</h6>
                                                    <p>Total Sales: <span>$60,710</span></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <h6>Bluetooth Headphones</h6>
                                                <div>
                                                    <h6>CB-71474</h6>
                                                    <p>Total Sales: <span>$60,710</span></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <h6>Bluetooth Headphones</h6>
                                                <div>
                                                    <h6>CB-71474</h6>
                                                    <p>Total Sales: <span>$60,710</span></p>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="printListBtn text-center">
                                            <button type="submit" class="btn btn-primary" id="myBtn">View All
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="topExport topSelling topLocations">
                                    <h6>Top Export Locations</h6>
                                    <ul>
                                        <li>
                                            <div>
                                                <img src="assets/img/uae.svg">
                                                <div>
                                                    <h6>United arab Emirates</h6>
                                                    <p>$446.61
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <img src="assets/img/uk.svg">
                                                <div>
                                                    <h6>United Kingdom</h6>
                                                    <p>$446.61
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <img src="assets/img/brazil.svg">
                                                <div>
                                                    <h6>Brazil</h6>
                                                    <p>$446.61
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <img src="assets/img/pk.svg">
                                                <div>
                                                    <h6>Pakistan</h6>
                                                    <p>$446.61
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div>
                                                <img src="assets/img/us.svg">
                                                <div>
                                                    <h6>United States</h6>
                                                    <p>$446.61
                                                    </p>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="topExport debitCardSection">
                                    <h6>Primary Account</h6>
                                    <div>
                                        <img src="assets/img/debitcard.svg" alt="">
                                        <div class="balance">
                                            <div>
                                                <h6>Bank Balance</h6>
                                                <h4>$60,569.20</h4>
                                            </div>
                                            <img src="assets/img/mastercard.svg" alt="">
                                        </div>
                                        <div class="numbers">
                                            <div>
                                                <p>Rototec</p>
                                                <p>12/14</p>
                                            </div>
                                            <pre>4197 **** **** 4116</pre>
                                        </div>
                                    </div>
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
<?php endif;?>
@endsection