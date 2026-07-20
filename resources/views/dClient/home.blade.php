<?php
   use App\Helpers\CommonHelper;
   use   Illuminate\Support\Carbon;
   
   use App\Helpers\DashboardHelper;
   use App\Helpers\ReuseableCode;
         $m = '';
      if(isset($_GET['m']))
      {
         $m = $_GET['m'];
      }
      else
      {
         $m = '';
      }
      $dashboard_access = explode(',',Auth::user()->dashboard_access);
  
   ?>


@extends('layouts.default')
@section('content')
{{--< ?php --}}
{{--//$Companies = DB::table('company')->where('status',1)->get();?>--}}
{{--

<style>
 /* ===================================================================== HOME PAGE — MODERN POLISH (cards,sidebar submenu,tables) ===================================================================== */
/* ---------- Content wrapper spacing ---------- */
.content-wrapper{padding:24px 26px !important;}
/* ---------- Generic content cards (Sales Flow Chart / Sales Orders / Total Receipts & Payments / Receivables & Payables) ---------- */
.dp_sdw,.dp_sdw2,.ibox,.cus-tab,.cus-tab2{border-radius:16px !important;border:1px solid #EDF0F8 !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;}
.dp_sdw h4,.ibox h4,.dp_sdw h6,.ibox-title h5,.dashTableHeading h6{font-size:17px !important;font-weight:800 !important;color:var(--erp-navy-dark) !important;margin-bottom:16px !important;}
/* card header row (title + right-side control like year dropdown / date input) */
.dp_sdw > .d-flex,.ibox-title{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:14px !important;}
/* dropdowns/date inputs sitting inside card headers (2023 selector,mm/dd/yyyy,July 2026) */
.dp_sdw select,.dp_sdw input[type="date"],.dp_sdw input[type="month"]{height:38px !important;border-radius:9px !important;border:1px solid var(--erp-navy-tint) !important;background:#F7F9FD !important;font-weight:700 !important;font-size:12.5px !important;color:var(--erp-navy-dark) !important;}
/* "View All Sales Orders" primary button already covered by earlier .dashTableHeading .btn-primary rule */
/* empty-state helper text (e.g. "Cash Coming in and going out of your business") */
.dp_sdw p.text-muted,.dp_sdw > p{color:#8892b0 !important;font-size:13px !important;font-weight:600 !important;}
/* ---------- Sales Orders table polish ---------- */
.home_table thead tr th:first-child{border-top-left-radius:10px !important;}
.home_table thead tr th:last-child{border-top-right-radius:10px !important;}
.home_table td .label,.home_table td span.badge{border-radius:20px !important;padding:5px 14px !important;font-size:11px !important;font-weight:800 !important;letter-spacing:.3px !important;}
/* "Pending" status pill */
.home_table td span:contains("Pending"){}
.home_table td .badge-warning,.home_table td span.pending,span.pendings_approvalss{background:#FFF4E5 !important;color:#B5651D !important;border:none !important;box-shadow:none !important;}
/* ---------- Sidebar:expanded submenu ("Roles / Main Menu Title / ...") ---------- */
.smastermnu .list-unstyled{padding-left:6px !important;}
.smastermnu .list-unstyled > li > a{font-size:13.5px !important;font-weight:600 !important;padding:9px 12px !important;border-radius:9px !important;color:var(--erp-sidebar-text) !important;margin-bottom:2px !important;}
.smastermnu .list-unstyled > li:hover a{background:rgba(255,255,255,0.08) !important;color:#ffffff !important;}
.smastermnu .list-unstyled li.active a{background:linear-gradient(90deg,var(--erp-navy) 0%,var(--erp-navy-dark) 100%) !important;color:#ffffff !important;}
/* top-level active item ("Users") — clean pill instead of the odd orange strip */
.sm-bx button.settingListSb.active,button.btn.settingListSb.theme-bg.active{background:linear-gradient(90deg,var(--erp-navy) 0%,var(--erp-navy-dark) 100%) !important;border-left:none !important;border-radius:10px !important;box-shadow:0 4px 14px rgba(30,58,138,0.35) !important;}
/* remove any stray orange left-border/indicator coming from old theme rules on active menu rows */
ul.m_list > li{border-left:none !important;}
.sidenavnr ul li{border-bottom:none !important;}
/* sidebar section spacing so groups don't feel cramped */
ul.m_list{margin-bottom:6px !important;}
.sm-bx button > p{font-size:14px !important;font-weight:600 !important;}
/* scroll thumb inside sidebar,subtle not harsh */
.sidenavnr::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.18) !important;border-radius:10px !important;}
/* ===================================================================== HOME PAGE — CORRECTED SELECTORS (matches actual blade markup) ===================================================================== */
/* remove ugly default focus outline on stat-card anchors */
.priorMainBox2 a,.priorMainBox2 a:focus,.priorMainBox2 a:active,.priorMainBox2 a:hover{outline:none !important;box-shadow:none !important;text-decoration:none !important;}
.mainDashBox{outline:none !important;}
/* ---------- Generic card wrapper:Sales Flow Chart / Receipts / Receivables ---------- */
.barChartHead,.pieChartHead,.card{background:#ffffff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;margin-bottom:24px !important;}
/* remove Bootstrap's default .card border/shadow so ours doesn't double up */
.card{border:1px solid #EDF0F8 !important;}
/* card header row (title + right control like year dropdown / date input) */
.barChartHead > div:first-child,.card-header{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;padding:0 !important;border:none !important;background:transparent !important;}
.barChartHead h6,.card-subtitle,.card-title,.dashTableHeading h6{font-size:17px !important;font-weight:800 !important;color:var(--erp-navy-dark) !important;margin:0 !important;}
/* select / date inputs sitting in card headers */
.selectOption select,input#monthyear,input#ReceivablesAndPayables,#year{height:38px !important;border-radius:9px !important;border:1px solid var(--erp-navy-tint) !important;background:#F7F9FD !important;font-weight:700 !important;font-size:12.5px !important;color:var(--erp-navy-dark) !important;padding:6px 12px !important;}
/* chart canvas area needs real height/visible bounds */
.barChartHead .card-body{padding:0 !important;min-height:280px !important;}
canvas.Business_Flow_Chart{max-height:280px !important;}
/* radio filter list (Sales / Purchase) above chart */
.barChartHead ul.hidden{display:flex !important;gap:16px !important;list-style:none !important;padding:0 !important;margin:10px 0 0 !important;}
.barChartHead ul.hidden li{font-size:12.5px !important;font-weight:600 !important;color:var(--erp-label) !important;display:flex !important;align-items:center !important;gap:6px !important;}
/* Sales Orders table card (dashTableHeading + table) */
.table-responsive.dashTable{background:#ffffff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;}
.dashTableHeading{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;}
/* cash-flow helper text */
.cashSection .card-title{color:#8892b0 !important;font-size:13px !important;font-weight:600 !important;}
/* Receivables & Payables list */
.payable.pieChartHead ul#ReceivablesAndPayablesDiv{list-style:none !important;padding:0 !important;margin-top:10px !important;}
.payable.pieChartHead ul#ReceivablesAndPayablesDiv > li{margin-bottom:14px !important;}
.payable.pieChartHead ul#ReceivablesAndPayablesDiv > li > h6{font-size:13.5px !important;font-weight:700 !important;color:var(--erp-navy-dark) !important;margin-bottom:8px !important;}
.empty-state{display:flex !important;flex-direction:column !important;align-items:center !important;justify-content:center !important;padding:50px 20px !important;color:#a7abc3 !important;text-align:center !important;min-height:200px !important;}
.empty-state i{font-size:34px !important;margin-bottom:12px !important;color:#c9cfe6 !important;}
.empty-state p{font-size:13.5px !important;font-weight:600 !important;margin:0 !important;color:#8892b0 !important;}
</style>

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
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500;600&display=swap');
#companyListModel{font-family:'Inter',sans-serif;}
#companyListModel .modal-dialog.modalWidth{max-width:620px;margin:60px auto;}
#companyListModel .model-n.modal-content{border-radius:22px !important;border:none !important;overflow:hidden;box-shadow:0 30px 70px rgba(19,27,46,0.35) !important;}
#companyListModel .modal-body{padding:0 !important;}
.mdel-bx{position:relative;background:#fff;}
/* header strip */
.mdel-bx .model-logo{display:flex;align-items:center;justify-content:space-between;padding:30px 36px 26px;border-bottom:1px solid #EEF0F7;}
.mdel-bx .model-logo img{max-width:150px;}
.mdel-bx .model-logo h4.modal-title{font-family:'Sora',sans-serif;font-weight:700;font-size:13px;letter-spacing:2px;text-transform:uppercase;color:#8A93A6;margin:0;}
.mdel-bx .circle{position:absolute;top:-70px;right:-70px;width:220px;opacity:.06;z-index:0;pointer-events:none;}
/* company grid */
.mdel-bx .row{margin:0 !important;padding:30px 36px 10px;}
.mdel-bx .ban-list{list-style:none;margin:0 !important;padding:0 !important;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:16px;width:100%;/* display:grid; */}
.mdel-bx .banq-box{border:1.5px solid #EEF0F7;border-radius:16px;background:#FBFCFE;transition:all .18s ease;}
.mdel-bx .banq-box:hover{border-color:#7C5CFC;background:#fff;transform:translateY(-3px);box-shadow:0 12px 26px rgba(124,92,252,0.16);}
.mdel-bx .banq-box a{display:flex;flex-direction:column;align-items:center;text-align:center;padding:24px 14px;text-decoration:none !important;}
.mdel-bx .companyLetr{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;background: transparent linear-gradient(90deg, #173CA7 0%, #0B1F59 100%) 0% 0% no-repeat padding-box !important;color:#fff !important;font-family:'Sora',sans-serif;font-weight:700;font-size:19px;margin-bottom:12px;}
.mdel-bx .item-model-company{font-size:13.5px !important;font-weight:600 !important;color:#1B2333 !important;margin:0 !important;line-height:1.4;}
/* sign out */
.mdel-bx .btn-b{display:block !important;width:calc(100% - 72px);margin:10px 36px 32px;text-align:center;padding:13px;border-radius:12px;background:#F2F3F8 !important;color:#4A5268 !important;font-weight:700;font-size:13.5px;text-decoration:none !important;transition:background .15s ease;}
.model-logo h4{font-size:18px !important;;}
.mdel-bx .btn-b:hover{background:#ea545545 !important;color:#353434 !important;transition: 0.5s !important;}
.model-logo{justify-content:space-evenly !important;flex-direction:column !important;gap:20px !important;}

</style>

<div id="companyListModel" class="modal fade in" role="dialog" aria-hidden="false" style="display: block;">
   <div class="modal-dialog modalWidth dply">
      <div class="model-n modal-content">
         <div class="modal-body">
            <div class="mdel-bx">
               <img class="circle" src="../assets/img/animation/circledot.png">
               <div class="model-logo">
                  <img src="assets/img/logos/logo.png">
                  <h4 class="modal-title">Select Company</h4>
               </div>
               <div class="row">
                  <ul class="ban-list">
                     @foreach($companiesList as $key => $cRow1)
                     <li>
                        <div class="banq-box">
                           <a href="{{url('set_user_db_id?company='.$cRow1->id)}}">
                              <span class="companyLetr theme-bg theme-f-m">{{ strtoupper(substr($cRow1->name, 0, 1)) }}</span>
                              <h3 class="item-model-company theme-f-m">{{ $cRow1->name }}</h3>
                           </a>
                        </div>
                     </li>
                     @endforeach
                  </ul>
               </div>
               <a href="{{url('/logout')}}" class="btn-b">Sign Out</a>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-backdrop fade in"></div>
</div>

@else

<?php 
   $UserId = Auth::user()->id;
   $accYear =  ReuseableCode::get_account_year_from_to(Session::get('run_company'));
   $from = $accYear[0];
   $to = $accYear[1];
 
     // receivable
 $receiable = CommonHelper::get_parent_and_account_amount(Session::get('run_company'),$from,$to,'1-2-2','1',1,0);
 $payable = CommonHelper::get_parent_and_account_amount(Session::get('run_company'),$from,$to,'2-2-2','1',0,1);
 
?>
@php

$currentDate = Carbon::now();
$monthStartDate = $currentDate->startOfMonth()->toDateString();
$monthEndDate = $currentDate->endOfMonth()->toDateString();
$currentMonthYear = $currentDate->year;

@endphp
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

                <div class="col-md-12 col-lg-12 priorMainBox2">
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
                                        <canvas class="Business_Flow_Chart chartjs" data-height="425"></canvas>
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
                                                    <table class="userlittab table table-bordered sf-table-list home_table" id="TableExportToCsv">
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
                                    <div class="card-header ">
                                        <h4 class="card-subtitle mb-25"> Total Receipts and Total Payments </h4>
                                        <input type="month" value="{{date('Y-m') }}" id="monthyear" onchange="trtpAjax(this.value)">
                                        <!-- <div class="selectOption">
                                            <select>
                                                <option value="">Weekly</option>
                                                <option value="">Monthly</option>
                                                <option value="">Yearly</option>
                                            </select>
                                        </div> -->
                                    </div>
                                    <div class="cashSection">
                                        <p class="card-title font-weight-bolder">Cash Coming in and going out of you business</p>
                                    </div>
                                    <div class="card-body">
                                        <div id="tr_tp"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="payable pieChartHead">
                                    <div class="statistics card-header Receivables_pay">
                                        <h6 class="card-title mb-sm-0 mb-1">Receivables and Payables</h6>
                                         <input type="date" onchange="ReceivablesAndPayablesAjax(this.value)" id="ReceivablesAndPayables">
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
                </div>
            </div>
            <div class="well" id="ShowHide">
            </div>
    </div>

</div>
@endif

      
<script src="assets/js/charts/chart-chartjs.js"></script>
<script src="assets/js/charts/chart-chartjs.min.js"></script>


<script !src="">
//    $(document).ready(function() {
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
//    });
   
   
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



<script>
         $(document).ready(function () {
            reportLcLg();

                $('#year').trigger('change');
                $('#monthyear').trigger('change');
                $('#ReceivablesAndPayables').trigger('change');

            });

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



        function Business_Flow_Chart(data) {

            let labels = [];
            let datas = [];

            if(!data || data.length === 0){
                $('.Business_Flow_Chart').closest('.card-body').html('<div class="empty-state"><i class="fa fa-bar-chart"></i><p>No sales data available for this year</p></div>');
                return;
            }

            data.forEach(item => {
                labels.push(item.month_name);
                datas.push(item.total_amount);
            });

            let barChartEx = $('.Business_Flow_Chart');

            var barChartExample = new Chart(barChartEx, {
                type: 'bar',
                options: {
                    legend: { display: false },
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

                // agar dono values 0/empty hain to empty-state dikhao
                let hasData = data && data.length > 0 && data.some(val => val && val > 0);

                if(!hasData){
                    $('#tr_tp').html('<div class="empty-state"><i class="fa fa-exchange"></i><p>No receipts or payments found for this month</p></div>');
                    return;
                }

                var barChartConfig = {
                    chart: { height: 180, type: 'bar', parentHeightOffset: 0, toolbar: { show: false } },
                    plotOptions: { bar: { horizontal: true, barHeight: '30%', endingShape: 'rounded' } },
                    grid: { xaxis: { lines: { show: false } }, padding: { top: -15, bottom: -10 } },
                    colors: window.colors.solid.info,
                    dataLabels: { enabled: false },
                    series: [{ data: data }],
                    xaxis: { categories: ['TR', 'TP'] }
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

                // thoda delay dekar check karo dono ajax complete hone ke baad kuch aaya ya nahi
                setTimeout(() => {
                    if($('#ReceivablesAndPayablesDiv').children().length === 0){
                        $('#ReceivablesAndPayablesDiv').html('<div class="empty-state"><i class="fa fa-file-text-o"></i><p>No receivables or payables found for this date</p></div>');
                    }
                }, 1200);
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


            $(window).on('load', function() {
    var currentUrl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
    $('ul.menu li a').each(function() {
        var hrefVal = $(this).attr('href');
        if (hrefVal == currentUrl) {
            $(this).removeClass('active');
            $(this).closest('li').addClass('active')
            $('ul.menu li.first').removeClass('active');
        }
    })

});


</script>
</span>
<?php endif;?>
@endsection