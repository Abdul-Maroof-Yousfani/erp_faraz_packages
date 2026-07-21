<?php
   use App\Helpers\CommonHelper;
   use Illuminate\Support\Carbon;
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
   $dashboard_access = explode(',', Auth::user()->dashboard_access);
?>

@extends('layouts.default')
@section('content')

<style>
/* ===================================================================== HOME PAGE — MODERN POLISH (cards,sidebar submenu,tables) ===================================================================== */
/* ---------- Content wrapper spacing ---------- */
.content-wrapper{padding:24px 26px !important;}
/* ---------- Generic content cards ---------- */
.dp_sdw,.dp_sdw2,.ibox,.cus-tab,.cus-tab2{border-radius:16px !important;border:1px solid #EDF0F8 !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;}
.dp_sdw h4,.ibox h4,.dp_sdw h6,.ibox-title h5,.dashTableHeading h6{font-size:17px !important;font-weight:800 !important;color:var(--erp-navy-dark) !important;margin-bottom:16px !important;}
.dp_sdw > .d-flex,.ibox-title{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:14px !important;}
.dp_sdw select,.dp_sdw input[type="date"],.dp_sdw input[type="month"]{height:38px !important;border-radius:9px !important;border:1px solid var(--erp-navy-tint) !important;background:#F7F9FD !important;font-weight:700 !important;font-size:12.5px !important;color:var(--erp-navy-dark) !important;}
.dp_sdw p.text-muted,.dp_sdw > p{color:#8892b0 !important;font-size:13px !important;font-weight:500 !important;}
/* ---------- Stat cards (Modern refresh) ---------- */
.priorMainBox2{display:grid !important;grid-template-columns:repeat(5,1fr) !important;gap:18px !important;margin-bottom:26px !important;align-items:stretch !important;}
.priorMainBox2 a{text-decoration:none !important;display:block !important;width:100% !important;/* ADD THIS */
 min-width:0 !important;/* ADD THIS - grid item shrink issue fix */
 padding:10px 10px !important;}
.priorMainBox2 a,.priorMainBox2 a:focus,.priorMainBox2 a:active,.priorMainBox2 a:hover{outline:none !important;box-shadow:none !important;text-decoration:none !important;border:none !important;}
.priorMainBox2>a:nth-child(2){background:#173ca7 !important;}
.mainDashBox{width:100% !important;/* ADD THIS */
 box-sizing:border-box !important;border-radius:18px !important;padding:20px 22px !important;min-height:132px !important;position:relative !important;overflow:hidden !important;outline:none !important;border:1px solid rgba(255,255,255,0.14) !important;transition:transform .2s cubic-bezier(.2,.8,.2,1),box-shadow .2s ease !important;display:flex !important;flex-direction:column !important;justify-content:space-between !important;}
.mainDashBox:hover{transform:translateY(-5px) !important;box-shadow:0 18px 34px rgba(11,25,60,0.28) !important;}
.mainDashBox::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:rgba(255,255,255,0.55);}
.mainDashBox::after{content:'';position:absolute;top:-40px;right:-40px;width:130px;height:130px;border-radius:50%;background:rgba(255,255,255,0.10);pointer-events:none;}
.mainDashBox .title{position:relative;z-index:1;}
.mainDashBox .title:first-of-type{display:flex;align-items:flex-start;justify-content:space-between;}
.mainDashBox .title h6{font-size:13px !important;font-weight:700 !important;color:rgba(255,255,255,0.9) !important;margin-bottom:3px !important;letter-spacing:.2px;}
.mainDashBox .title p{font-size:11px !important;color:rgba(255,255,255,0.55) !important;margin-bottom:0 !important;font-weight:500 !important;}
.mainDashBox .title:last-of-type{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-top:14px !important;}
.mainDashBox .title img{width:34px;height:34px;padding:7px;background:rgba(255,255,255,0.14);border-radius:10px;object-fit:contain;}
.mainDashBox h4{font-size:25px !important;font-weight:800 !important;color:#fff !important;margin:0 !important;letter-spacing:.2px !important;}
/* ---------- Color set:navy / lavender / amber family ---------- */
.dashBoxOrange{background:linear-gradient(155deg,#B8843A 0%,#7A4E17 100%) !important;}
.dashBoxPurple{background:linear-gradient(155deg,#6B4FCE 0%,#3D2A85 100%) !important;}
.dashBoxDark{background:linear-gradient(155deg,#1F2B4A 0%,#0A1226 100%) !important;}
.dashBoxNavy{background:linear-gradient(155deg,#1E3A8A 0%,#0B1F59 100%) !important;}
.dashBoxViolet{background:linear-gradient(155deg,#4A3B7E 0%,#241947 100%) !important;}
/* ---------- Sales Orders table polish ---------- */
.home_table thead tr th:first-child{border-top-left-radius:10px !important;}
.home_table thead tr th:last-child{border-top-right-radius:10px !important;}
.home_table thead.bgBlueofTd th{background:#F0F3FB !important;color:#4A5268 !important;font-size:11.5px !important;font-weight:800 !important;letter-spacing:.4px !important;text-transform:uppercase !important;border-bottom:none !important;padding:12px 10px !important;}
.home_table td{font-size:13px !important;font-weight:500 !important;color:#1B2333 !important;padding:12px 10px !important;vertical-align:middle !important;}
.home_table td .label,.home_table td span.badge,.home_table td span.pendings_approvalss{border-radius:20px !important;padding:5px 14px !important;font-size:11px !important;font-weight:800 !important;letter-spacing:.3px !important;display:inline-block !important;}
.home_table td .badge-warning,.home_table td span.pending,span.pendings_approvalss{background:#FFF4E5 !important;color:#B5651D !important;border:2px solid #fff4e5 !important;box-shadow:none !important;}
.home_table tbody tr:not(:last-child) td:last-child{background:#ffffff !important;border-radius:0px !important;padding:19px 10px !important;}
/* ---------- Sidebar:expanded submenu ---------- */
.smastermnu .list-unstyled{padding-left:6px !important;}
.smastermnu .list-unstyled > li > a{font-size:13.5px !important;font-weight:500 !important;padding:9px 12px !important;border-radius:9px !important;color:var(--erp-sidebar-text) !important;margin-bottom:2px !important;}
.smastermnu .list-unstyled > li:hover a{background:rgba(255,255,255,0.08) !important;color:#ffffff !important;}
.smastermnu .list-unstyled li.active a{background:linear-gradient(90deg,var(--erp-navy) 0%,var(--erp-navy-dark) 100%) !important;color:#ffffff !important;}
.sm-bx button.settingListSb.active,button.btn.settingListSb.theme-bg.active{background:linear-gradient(90deg,var(--erp-navy) 0%,var(--erp-navy-dark) 100%) !important;border-left:none !important;border-radius:10px !important;box-shadow:0 4px 14px rgba(30,58,138,0.35) !important;}
ul.m_list > li{border-left:none !important;}
.sidenavnr ul li{border-bottom:none !important;}
ul.m_list{margin-bottom:6px !important;}
.sm-bx button > p{font-size:14px !important;font-weight:500 !important;}
.sidenavnr::-webkit-scrollbar-thumb{background:rgba(255,255,255,0.18) !important;border-radius:10px !important;}
/* ---------- Cards:Sales Flow Chart / Receipts / Receivables ---------- */
.barChartHead,.pieChartHead,.card{background:#ffffff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;margin-bottom:24px !important;}
.card{border:1px solid #EDF0F8 !important;}
.barChartHead > div:first-child,.card-header{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;padding:0 !important;border:none !important;background:transparent !important;}
.barChartHead h6,.card-subtitle,.card-title,.dashTableHeading h6{font-size:17px !important;font-weight:500 !important;color:var(--erp-navy-dark) !important;margin:0 !important;}
.barChartHead .card-header h4,.barChartHead .card-header h6,.statistics.card-header h6{font-weight:500 !important;}
.selectOption select,input#monthyear,input#ReceivablesAndPayables,#year{height:38px !important;border-radius:9px !important;border:1px solid var(--erp-navy-tint) !important;background:#F7F9FD !important;font-weight:700 !important;font-size:12.5px !important;color:var(--erp-navy-dark) !important;padding:6px 12px !important;}
.barChartHead .card-body{padding:0 !important;min-height:280px !important;}
canvas.Business_Flow_Chart{max-height:280px !important;}
.barChartHead ul.hidden{display:flex !important;gap:16px !important;list-style:none !important;padding:0 !important;margin:10px 0 0 !important;}
.barChartHead ul.hidden li{font-size:12.5px !important;font-weight:500 !important;color:var(--erp-label) !important;display:flex !important;align-items:center !important;gap:6px !important;}
/* ---------- Sales Orders table card ---------- */
.table-responsive.dashTable{background:#ffffff !important;border:1px solid #EDF0F8 !important;border-radius:16px !important;box-shadow:0 6px 22px rgba(20,38,92,0.07) !important;padding:22px 24px !important;}
.dashTableHeading{display:flex !important;align-items:center !important;justify-content:space-between !important;margin-bottom:16px !important;}
.dashTableHeading .btn-primary{border-radius:10px !important;font-weight:700 !important;font-size:12.5px !important;padding:9px 18px !important;border:none !important;background:linear-gradient(90deg,#1E3A8A 0%,#0B1F59 100%) !important;box-shadow:0 6px 16px rgba(30,58,138,0.28) !important;}
/* ---------- Cash-flow helper text ---------- */
.cashSection .card-title{color:#8892b0 !important;font-size:13px !important;font-weight:500 !important;}
/* ---------- Receivables & Payables list ---------- */
.payable.pieChartHead ul#ReceivablesAndPayablesDiv{list-style:none !important;padding:0 !important;margin-top:10px !important;}
.payable.pieChartHead ul#ReceivablesAndPayablesDiv > li{margin-bottom:14px !important;}
.payable.pieChartHead ul#ReceivablesAndPayablesDiv > li > h6{font-size:13.5px !important;font-weight:700 !important;color:var(--erp-navy-dark) !important;margin-bottom:8px !important;}
/* ---------- Empty state ---------- */
.empty-state{display:flex !important;flex-direction:column !important;align-items:center !important;justify-content:center !important;padding:50px 20px !important;color:#a7abc3 !important;text-align:center !important;min-height:200px !important;}
.empty-state i{font-size:34px !important;margin-bottom:12px !important;color:#c9cfe6 !important;}
.empty-state p{font-size:13.5px !important;font-weight:500 !important;margin:0 !important;color:#8892b0 !important;}
/* ===================================================================== COMPANY SELECT MODAL ===================================================================== */
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500;600&display=swap');#companyListModel{font-family:'Inter',sans-serif;}
#companyListModel .modal-dialog.modalWidth{max-width:620px;margin:60px auto;}
#companyListModel .model-n.modal-content{border-radius:22px !important;border:none !important;overflow:hidden;box-shadow:0 30px 70px rgba(19,27,46,0.35) !important;}
#companyListModel .modal-body{padding:0 !important;}
.mdel-bx{position:relative;background:#fff;}
.mdel-bx .model-logo{display:flex;align-items:center;justify-content:space-evenly;flex-direction:column;gap:20px;padding:30px 36px 26px;border-bottom:1px solid #EEF0F7;}
.mdel-bx .model-logo img{max-width:150px;}
.mdel-bx .model-logo h4.modal-title{font-family:'Sora',sans-serif;font-weight:700;font-size:13px;letter-spacing:2px;text-transform:uppercase;color:#8A93A6;margin:0;}
.mdel-bx .circle{position:absolute;top:-70px;right:-70px;width:220px;opacity:.06;z-index:0;pointer-events:none;}
.mdel-bx .row{margin:0 !important;padding:30px 36px 10px;}
.mdel-bx .ban-list{list-style:none;margin:0 !important;padding:0 !important;display:flex;flex-wrap:wrap;gap:16px;width:100%;}
.mdel-bx .ban-list > li{flex:1 1 150px;}
.mdel-bx .banq-box{border:1.5px solid #EEF0F7;border-radius:16px;background:#FBFCFE;transition:all .18s ease;}
.mdel-bx .banq-box:hover{border-color:#7C5CFC;background:#fff;transform:translateY(-3px);box-shadow:0 12px 26px rgba(124,92,252,0.16);}
.mdel-bx .banq-box a{display:flex;flex-direction:column;align-items:center;text-align:center;padding:24px 14px;text-decoration:none !important;}
.mdel-bx .companyLetr{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;background:linear-gradient(90deg,#173CA7 0%,#0B1F59 100%) !important;color:#fff !important;font-family:'Sora',sans-serif;font-weight:700;font-size:19px;margin-bottom:12px;}
.mdel-bx .item-model-company{font-size:13.5px !important;font-weight:500 !important;color:#1B2333 !important;margin:0 !important;line-height:1.4;}
.mdel-bx .btn-b{display:block !important;width:calc(100% - 72px);margin:10px 36px 32px;text-align:center;padding:13px;border-radius:12px;background:#F2F3F8 !important;color:#4A5268 !important;font-weight:700;font-size:13.5px;text-decoration:none !important;transition:background .15s ease;}
.mdel-bx .btn-b:hover{background:#ea545545 !important;color:#353434 !important;transition:0.5s !important;}
/* Tablet:3 per row */
@media (max-width:992px){.priorMainBox2{grid-template-columns:repeat(3,1fr) !important;}
}
/* Small tablet:2 per row */
@media (max-width:768px){.priorMainBox2{grid-template-columns:repeat(2,1fr) !important;}
}
/* Mobile:1 per row */
@media (max-width:576px){.priorMainBox2{grid-template-columns:1fr !important;}
}

</style>

{{-- ==================== OLD/UNUSED COMPANY LOOP CODE (kept for reference only) ====================
<div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
      <div class="panel">
         <div class="panel-body">
            <div class="">
               @foreach($Companies as $Fil)
               <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                  <a href="#" class="btn btn-lg btn-primary" style="width: 100%; border-radius: 25px;">
                     <i class="fa fa-arrow-right" aria-hidden="true"></i>
                     {{ $Fil->name }}
                     <i class="fa fa-university" aria-hidden="true" style="float: right;"></i>
                  </a>
               </div>
               @endforeach
            </div>
         </div>
      </div>
   </div>
</div>
==================================================================================================== --}}

<?php
   $count = 0;
   if(Auth::user()->id == 104)
   {
      $companiesList = DB::table('company')->select(['name','id','dbName'])->where('status','=','1')->get();
   }
   else
   {
      $companiesList = DB::table('company')->select(['name','id','dbName'])->where('id','!=',4)->where('status','=','1')->get();
   }
?>

@if(Session::get('run_company') == '')

<div id="companyListModel" class="modal fade in" role="dialog" aria-hidden="false" style="display: block;">
   <div class="modal-dialog modalWidth dply">
      <div class="model-n modal-content">
         <div class="modal-body">
            <div class="mdel-bx">
               <img class="circle" src="{{ asset('assets/img/animation/circledot.png') }}">
               <div class="model-logo">
                  <img src="{{ asset('assets/img/logos/logo.png') }}">
                  <h4 class="modal-title">Select Company</h4>
               </div>
               <div class="row">
                  <ul class="ban-list">
                     @foreach($companiesList as $key => $cRow1)
                     <li>
                        <div class="banq-box">
                           <a href="{{ url('set_user_db_id?company='.$cRow1->id) }}">
                              <span class="companyLetr theme-bg theme-f-m">{{ strtoupper(substr($cRow1->name, 0, 1)) }}</span>
                              <h3 class="item-model-company theme-f-m">{{ $cRow1->name }}</h3>
                           </a>
                        </div>
                     </li>
                     @endforeach
                  </ul>
               </div>
               <a href="{{ url('/logout') }}" class="btn-b">Sign Out</a>
            </div>
         </div>
      </div>
   </div>
   <div class="modal-backdrop fade in"></div>
</div>

@else

<?php
   $UserId = Auth::user()->id;
   $accYear = ReuseableCode::get_account_year_from_to(Session::get('run_company'));
   $from = $accYear[0];
   $to = $accYear[1];

   // receivable / payable
   $receiable = CommonHelper::get_parent_and_account_amount(Session::get('run_company'), $from, $to, '1-2-2', '1', 1, 0);
   $payable   = CommonHelper::get_parent_and_account_amount(Session::get('run_company'), $from, $to, '2-2-2', '1', 0, 1);
?>

@php
    $currentDate      = Carbon::now();
    $monthStartDate   = $currentDate->copy()->startOfMonth()->toDateString();
    $monthEndDate     = $currentDate->copy()->endOfMonth()->toDateString();
    $currentMonthYear = $currentDate->year;
@endphp

<div class="well_N">
    <div>
        @if(Session::get('run_company'))
        <span style="display:block;">
            <div class="wrapper wrapper-content">
                <div class="row">

                    <div class="col-md-12 col-lg-12 priorMainBox2">
                        <a href="#" class="hide" onclick="getDashboardSaleSummary(1,'{{ $monthStartDate }}','{{ $monthEndDate }}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Today's Sales</h6>
                                    <p>{{ $currentMonthYear }}</p>
                                </div>
                                  <div class="title">
                                    <img src="{{ asset('assets/img/miniBar.svg') }}" alt="">
                                    <h4>{{ number_format(CommonHelper::getSaleSummaryAmountF($monthStartDate, $monthEndDate), 0) }}</h4>
                                 </div>
                            </div>
                        </a>

                        <a href="#" onclick="getDashboardSaleSummary(2,'{{ $monthStartDate }}','{{ $monthEndDate }}');">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month Sales</h6>
                                    <p>{{ $currentMonthYear }}</p>
                                </div>
                                 <div class="title">
                                    <img src="{{ asset('assets/img/miniBar.svg') }}" alt="">
                                    <h4>{{ number_format(CommonHelper::getSaleSummaryAmountF($monthStartDate, $monthEndDate), 0) }}</h4>
                                </div>
                            </div>
                        </a>

                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>This Month's Collection</h6>
                                </div>
                                <div class="title">
                                <img src="{{ asset('assets/img/miniBar.svg') }}" alt="">
                                <h4>{{ number_format($collection, 2) }}</h4>
                                </div>
                            </div>
                        </a>

                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Receivables</h6>
                                </div>
                                  <div class="title">
                                <img src="{{ asset('assets/img/miniBar.svg') }}" alt="">
                                <h4 class="salesagingtotal">{{ number_format($receiable, 2) }}</h4>
                                </div>
                            </div>
                        </a>

                        <a href="#">
                            <div class="mainDashBox">
                                <div class="title">
                                    <h6>Total Payables</h6>
                                </div>
                                <div class="title">
                                <img src="{{ asset('assets/img/miniBar.svg') }}" alt="">
                                <h4 class="vendoragingtotal">{{ number_format($payable) }}</h4>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="card barChartHead">
                                    <div>
                                        <div>
                                            <h6>Sales Flow Chart</h6>
                                            <ul class="hidden">
                                                <li>
                                                    <input type="radio" name="salesPurchaseToggle" checked readonly>
                                                    Sales
                                                </li>
                                                <li>
                                                    <input type="radio" name="salesPurchaseToggle" readonly>
                                                    Purchase
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="text-right">
                                            <div class="selectOption">
                                                @php $currentChartYear = date('Y'); @endphp
                                                <select id="year" onchange="BusinessFlowChartAjax(this.value)">
                                                    @for($y = $currentChartYear; $y >= $currentChartYear - 4; $y--)
                                                        <option value="{{ $y }}" {{ $y == $currentChartYear ? 'selected' : '' }}>{{ $y }}</option>
                                                    @endfor
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
                                    <div class="panel">
                                        <div id="PrintPanel">
                                            <div id="ShowHide">
                                                <div class="table-responsive dashTable mhe">
                                                    <div class="dashTableHeading printListBtn">
                                                        <h6>Sales Orders</h6>
                                                        <a class="btn btn-primary" target="_blank" id="myBtn" href="{{ url('/sales/viewSalesOrderList?pageType=view&&parentCode=89&&m=1#Rototec') }}">View All Sales Orders</a>
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
                                                                $overallSubTotal  = 0;
                                                                $overallTaxAmount = 0;
                                                            @endphp
                                                            @if(!empty($latestSaleOrders))
                                                                @foreach($latestSaleOrders as $lsoKey => $lsoRow)
                                                                    @php
                                                                        $overallSubTotal  += $lsoRow->total_amount;
                                                                        $overallTaxAmount += $lsoRow->total_amount_after_sale_tax;
                                                                    @endphp
                                                                    <tr>
                                                                        <td class="text-center" colspan="2">{{ strtoupper($lsoRow->name) }}</td>
                                                                        <td class="text-center">{{ strtoupper($lsoRow->so_no) }}</td>
                                                                        <td class="text-center">{{ CommonHelper::changeDateFormat($lsoRow->so_date) }}</td>
                                                                        <td class="text-center">{{ number_format($lsoRow->total_amount, 0) }}</td>
                                                                        <td class="text-center">{{ number_format($lsoRow->total_amount_after_sale_tax - $lsoRow->total_amount, 0) }}</td>
                                                                        <td class="text-center">{{ number_format($lsoRow->total_amount_after_sale_tax, 0) }}</td>
                                                                        <td class="text-center">
                                                                            <span class="badge badge-warning pendings_approvalss">
                                                                            @if($lsoRow->so_status == 0)
                                                                                Pending
                                                                            @elseif($lsoRow->so_status == 2)
                                                                                Draft
                                                                            @else
                                                                                Sale Order Created
                                                                            @endif
                                                                            </span>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr>
                                                                    <td colspan="4">Total</td>
                                                                    <td class="text-right">{{ number_format($overallSubTotal, 0) }}</td>
                                                                    <td class="text-right"></td>
                                                                    <td class="text-right">{{ number_format($overallTaxAmount, 0) }}</td>
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
                                    <div class="card-header">
                                        <h4 class="card-subtitle mb-25">Total Receipts and Total Payments</h4>
                                        <input type="month" value="{{ date('Y-m') }}" id="monthyear" onchange="trtpAjax(this.value)">
                                    </div>
                                    <div class="cashSection">
                                        <p class="card-title font-weight-bolder">Cash Coming in and going out of your business</p>
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
                                                <li><p>Due</p><p>$0.00</p></li>
                                                <li><p>Due in 1-30 days</p><p>$0.00</p></li>
                                                <li><p>Due</p><p>$0.00</p></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <h6>Payable bills you owe</h6>
                                            <ul>
                                                <li><p>Due</p><p>$0.00</p></li>
                                                <li><p>Due in 1-30 days</p><p>$0.00</p></li>
                                                <li><p>Due</p><p>$0.00</p></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="well" id="ShowHide"></div>
        </span>
        @endif
    </div>
</div>

@endif

<script src="{{ asset('assets/js/charts/chart-chartjs.js') }}"></script>
<script src="{{ asset('assets/js/charts/chart-chartjs.min.js') }}"></script>

<script>
function getDashboardInfo(Type)
{
    var m = '<?php echo $m; ?>';
    $('#ShowHide').html('<div class="loader"></div>');

    $.ajax({
        url: '/pdc/get_dashboard_info',
        type: 'Get',
        data: { Type: Type, m: m },
        success: function (response) {
            $('#ShowHide').html(response);
        }
    });
}

$(document).ready(function () {
    reportLcLg();

    $('#year').trigger('change');
    $('#monthyear').trigger('change');
    $('#ReceivablesAndPayables').trigger('change');
});

function reportLcLg()
{
    let rate_date = $('#rate_date').val();
    let to_date   = $('#to_date').val();

    $('#reportData').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td></tr>');

    $.ajax({
        url: '<?php echo url('/'); ?>/import/LcAndLg/reportLcLg',
        type: 'Get',
        data: { rate_date: rate_date, to_date: to_date },
        success: function (response) {
            $('#reportData').html(response);
        }
    });
}

function Business_Flow_Chart(data)
{
    let labels = [];
    let datas  = [];

    if (!data || data.length === 0) {
        $('.Business_Flow_Chart').closest('.card-body').html('<div class="empty-state"><i class="fa fa-bar-chart"></i><p>No sales data available for this year</p></div>');
        return;
    }

    data.forEach(item => {
        labels.push(item.month_name);
        datas.push(item.total_amount);
    });

    let barChartEx = $('.Business_Flow_Chart');

    if (window.barChartExampleInstance) {
        window.barChartExampleInstance.destroy();
    }

    window.barChartExampleInstance = new Chart(barChartEx, {
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

function tr_tp(data)
{
    var barChartEl = document.querySelector('#tr_tp');

    if (typeof barChartEl !== 'undefined' && barChartEl !== null) {

        let hasData = data && data.length > 0 && data.some(val => val && val > 0);

        if (!hasData) {
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
        data: { year: year },
        success: function (response) {
            Business_Flow_Chart(response?.SalesFlowChart);
        }
    });
}

function trtpAjax(monthyear)
{
    $('#tr_tp').empty();
    $.ajax({
        url: '/trtpAjax',
        type: 'Get',
        data: { monthyear: monthyear },
        success: function (response) {
            setTimeout(() => {
                tr_tp(response);
            }, 500);
        }
    });
}

function ReceivablesAndPayablesAjax(date)
{
    $('#ReceivablesAndPayablesDiv').empty();

    salesAgingAjax(date);
    vendorAgingAjax(date);

    setTimeout(() => {
        if ($('#ReceivablesAndPayablesDiv').children().length === 0) {
            $('#ReceivablesAndPayablesDiv').html('<div class="empty-state"><i class="fa fa-file-text-o"></i><p>No receivables or payables found for this date</p></div>');
        }
    }, 1200);
}

function salesAgingAjax(date)
{
    $.ajax({
        url: '/salesAgingAjax',
        type: 'Get',
        data: { date: date },
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
        data: { date: date },
        success: function (response) {
            $('#ReceivablesAndPayablesDiv').append(response);
            $('.vendoragingtotal').text($('#vendoragingtotal').text());
        }
    });
}

$(window).on('load', function() {
    var currentUrl = window.location.href.substr(window.location.href.lastIndexOf("/") + 1);
    $('ul.menu li a').each(function() {
        var hrefVal = $(this).attr('href');
        if (hrefVal == currentUrl) {
            $(this).removeClass('active');
            $(this).closest('li').addClass('active');
            $('ul.menu li.first').removeClass('active');
        }
    });
});
</script>

@endsection