<?php
$UserId = Auth::user()->id;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
//;	$m = $_GET['m'];
}else{
	$m = Auth::user()->company_id;
}
//$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;


$DemandPending = CommonHelper::table_counting('demand','demand_status','1');
$DemandApp = CommonHelper::table_counting('demand','demand_status','2');

$PrPending = CommonHelper::table_counting('purchase_request','purchase_request_status','1');
$PrApp = CommonHelper::table_counting('purchase_request','purchase_request_status','2');

$GrnPending = CommonHelper::table_counting('goods_receipt_note','grn_status','1');
$GrnApp = CommonHelper::table_counting('goods_receipt_note','grn_status','2');

$SurveyPending = 0;
$SurveyApp = 0;

$QuotationPending = CommonHelper::table_counting('quotation','quotation_status','1');
$QuotationApp = CommonHelper::table_counting('quotation','quotation_status','2');

$JOPending = 0;
$JOApp = 0;

$InvPending = CommonHelper::table_counting('invoice','inv_status','1');
$InvApp = CommonHelper::table_counting('invoice','inv_status','2');
$TotalComplaint = 0;

?>
@extends('layouts.default')

@section('content')
	<?php if($UserId == 153 || $UserId == 154):

	//ab dekh puri query
	$good_recipt_note=DB::Connection('mysql2')->table('goods_receipt_note')->where('status',1)->where('grn_status',2)->where('type','!=',3)->select('id','grn_no','grn_date','type','date')->orderBy('grn_date','ASC')->get();
	?>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">Please Make Purchase Voucher Agains This Grn


</span>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
								<?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
								<?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
							</div>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="panel">
						<div class="panel-body" id="PrintEmpExitInterviewList">
							<?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>
							<?php //echo Form::open(array('url' => 'purchase/createPurchaseVoucherFormThroughGrn?m=1','id'=>'cashPaymentVoucherForm'));?>
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="table-responsive">
										<table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
											<thead>
											<!-- <th class="text-center"></th> -->
											<th class="text-center col-sm-1">S.No</th>
											<th class="text-center">GRN No</th>
											<th class="text-center">Grn Date</th>
											<th class="text-center">Day's Pending</th>
											<!-- <th class="text-center">Supplier Invoice No.</th>
                                            <th class="text-center">PO No</th>
                                            {{--<th class="text-center">PO Date</th>--}}
                                            <th class="text-center">Supplier Name</th>
                                            <th class="text-center">View</th> -->
											</thead>
											<tbody>
											<?php $counter = 1;?>

											@foreach($good_recipt_note as $row)
												<tr @if($row->type==1)style="background-color: lightblue"@endif>
													<!-- <td class="text-center">
                                                        <input name="checkbox[]" onclick="check('< ?php echo $row->supplier_id?>')" class="checkbox1 form-control AddRemoveClass< ?php echo $row->supplier_id?>" id="< ?php echo $row->supplier_id?>" type="checkbox" value="{{$row->id}}" style="width: 30px">
                                                    </td> -->
													<td class="text-center">{{$counter++}}</td>
													<td class="text-center">{{strtoupper($row->grn_no)}}</td>
													<td class="text-center">{{ CommonHelper::changeDateFormat($row->grn_date)}}</td>
													<td class="text-center text-danger"><?php $date1=date_create(date("Y-m-d"));
														$date2=date_create($row->date);
														$diff=date_diff($date2,$date1);
														echo $days = $diff->format("%r%a");;?></td>


												</tr>

											@endforeach
											</tbody>
										</table>

										<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <input type="submit" value="Create Purchase Voucher" class="btn btn-xs btn-success pull-left" id="add" disabled="">
                                        </div> -->
									</div>
								</div>
							</div>
							<?php //Form::close(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		var temp = [];
		function check(Id)
		{
			if($(".checkbox1").is(':checked'))
			{$("#add").prop('disabled', false);}else{$("#add").prop('disabled', true);}
			if($("input:checkbox:checked").length > 0){}else{temp = [];}

			$(".AddRemoveClass"+Id).each(function() {
				if ($(this).is(':checked')) {
					var checked = ($(this).attr('id'));
					temp.push(checked);
					if(temp.indexOf(checked))
					{
						if ($(this).is(':checked')) {
							alert('Please Checked Same Supplier and then Create Voucher...!');
							$(this).prop("checked", false);

						}
					}
					else
					{}
				}
				else
				{

				}
			});
		}
	</script>
	<?php else:?>
	<style>
		* {
			margin: 0;
			padding: 0;
			-webkit-box-sizing: border-box;
			-moz-box-sizing: border-box;
			box-sizing: border-box;
		}



		ul { list-style-type: none; }

		a {
			color: #133875;
			text-decoration: none;
		}

		/** =======================
         * Contenedor Principal
         ===========================*/


		h1 {
			color: #FFF;
			font-size: 24px;
			font-weight: 400;
			text-align: center;
			margin-top: 80px;
		}

		h1 a {
			color: #c12c42;
			font-size: 16px;
		}

		.accordion {
			width: 100%;
			max-width: 360px;
			/*margin: 30px auto 20px;*/
			background: #FFF;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
		}

		.accordion .link {
			cursor: pointer;
			display: block;
			padding: 15px 15px 15px 42px;
			color: #4D4D4D;
			font-size: 14px;
			font-weight: 700;
			border-bottom: 1px solid #CCC;
			position: relative;
			-webkit-transition: all 0.4s ease;
			-o-transition: all 0.4s ease;
			transition: all 0.4s ease;
		}

		.accordion li:last-child .link { border-bottom: 0; }

		.accordion li i {
			position: absolute;
			top: 16px;
			left: 12px;
			font-size: 18px;
			color: #595959;
			-webkit-transition: all 0.4s ease;
			-o-transition: all 0.4s ease;
			transition: all 0.4s ease;
		}

		.accordion li i.fa-chevron-down {
			right: 12px;
			left: auto;
			font-size: 16px;
		}

		.accordion li.open .link { color: #133875; }

		.accordion li.open i { color: #133875; }

		.accordion li.open i.fa-chevron-down {
			-webkit-transform: rotate(180deg);
			-ms-transform: rotate(180deg);
			-o-transform: rotate(180deg);
			transform: rotate(180deg);
		}

		/**
         * Submenu
         -----------------------------*/


		.submenu {
			display: none;
			background: #444359;
			font-size: 14px;
		}

		.submenu li { border-bottom: 1px solid #4b4a5e; }

		.submenu a {
			display: block;
			text-decoration: none;
			color: #d9d9d9;
			padding: 12px;
			padding-left: 42px;
			-webkit-transition: all 0.25s ease;
			-o-transition: all 0.25s ease;
			transition: all 0.25s ease;
		}

		.submenu a:hover {
			background: #133875;
			color: #FFF;
		}

		/*Second*/
		.accordion2 {
			width: 100%;
			max-width: 360px;
			/*margin: 30px auto 20px;*/
			background: #FFF;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
		}

		.accordion2 .link2 {
			cursor: pointer;
			display: block;
			padding: 15px 15px 15px 42px;
			color: #4D4D4D;
			font-size: 14px;
			font-weight: 700;
			border-bottom: 1px solid #CCC;
			position: relative;
			-webkit-transition: all 0.4s ease;
			-o-transition: all 0.4s ease;
			transition: all 0.4s ease;
		}

		.accordion2 li:last-child .link2 { border-bottom: 0; }

		.accordion2 li i {
			position: absolute;
			top: 16px;
			left: 12px;
			font-size: 18px;
			color: #595959;
			-webkit-transition: all 0.4s ease;
			-o-transition: all 0.4s ease;
			transition: all 0.4s ease;
		}

		.accordion2 li i.fa-chevron-down {
			right: 12px;
			left: auto;
			font-size: 16px;
		}

		.accordion2 li.open .link2 { color: #133875; }

		.accordion2 li.open i { color: #133875; }

		.accordion2 li.open i.fa-chevron-down {
			-webkit-transform: rotate(180deg);
			-ms-transform: rotate(180deg);
			-o-transform: rotate(180deg);
			transform: rotate(180deg);
		}

		/**
         * Submenu
         -----------------------------*/


		.submenu2 {
			display: none;
			background: #444359;
			font-size: 14px;
		}

		.submenu2 li { border-bottom: 1px solid #4b4a5e; }

		.submenu2 a {
			display: block;
			text-decoration: none;
			color: #d9d9d9;
			padding: 12px;
			padding-left: 42px;
			-webkit-transition: all 0.25s ease;
			-o-transition: all 0.25s ease;
			transition: all 0.25s ease;
		}

		.submenu2 a:hover {
			background: #133875;
			color: #FFF;
		}
		/*Third*/

		.accordion3 {
			width: 100%;
			max-width: 360px;
			/*margin: 30px auto 20px;*/
			background: #FFF;
			-webkit-border-radius: 4px;
			-moz-border-radius: 4px;
			border-radius: 4px;
		}

		.accordion3 .link3 {
			cursor: pointer;
			display: block;
			padding: 15px 15px 15px 42px;
			color: #4D4D4D;
			font-size: 14px;
			font-weight: 700;
			border-bottom: 1px solid #CCC;
			position: relative;
			-webkit-transition: all 0.4s ease;
			-o-transition: all 0.4s ease;
			transition: all 0.4s ease;
		}

		.accordion3 li:last-child .link3 { border-bottom: 0; }

		.accordion3 li i {
			position: absolute;
			top: 16px;
			left: 12px;
			font-size: 18px;
			color: #595959;
			-webkit-transition: all 0.4s ease;
			-o-transition: all 0.4s ease;
			transition: all 0.4s ease;
		}

		.accordion3 li i.fa-chevron-down {
			right: 12px;
			left: auto;
			font-size: 16px;
		}

		.accordion3 li.open .link { color: #133875; }

		.accordion3 li.open i { color: #133875; }

		.accordion3 li.open i.fa-chevron-down {
			-webkit-transform: rotate(180deg);
			-ms-transform: rotate(180deg);
			-o-transform: rotate(180deg);
			transform: rotate(180deg);
		}

		/**
         * Submenu
         -----------------------------*/


		.submenu3 {
			display: none;
			background: #444359;
			font-size: 14px;
		}

		.submenu3 li { border-bottom: 1px solid #4b4a5e; }

		.submenu3 a {
			display: block;
			text-decoration: none;
			color: #d9d9d9;
			padding: 12px;
			padding-left: 42px;
			-webkit-transition: all 0.25s ease;
			-o-transition: all 0.25s ease;
			transition: all 0.25s ease;
		}

		.submenu3 a:hover {
			background: #133875;
			color: #FFF;
		}

		.blink_me {
			animation: blinker 1s linear infinite;
		}

		@keyframes blinker {
			50% {
				opacity: 0;
			}
		}
	</style>
<div class="well_N">
    <div class="row">
        <div class="col-lg-3">
            <div class="prodDashBox">
                <div>
                    <h6>Printing Machine</h6>
                    <span class="greenBadge">Running</span>
                </div>
                <ul>
                    <li>
                        <ul>
                            <li>Job Card No.</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Customer Name</li>
                            <li>Jerome Bell</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Capacity Utilzation</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Today's Utilization</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Quantity Produced</li>
                            <li>50,000</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="prodDashBox">
                <div>
                    <h6>Lamination Machine</h6>
                    <span class="greenBadge">Running</span>
                </div>
                <ul>
                    <li>
                        <ul>
                            <li>Job Card No.</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Customer Name</li>
                            <li>Jerome Bell</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Capacity Utilzation</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Today's Utilization</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Quantity Produced</li>
                            <li>50,000</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="prodDashBox">
                <div>
                    <h6>WAX Machine</h6>
                    <span class="redBadge">Not Running</span>
                </div>
                <ul>
                    <li>
                        <ul>
                            <li>Job Card No.</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Customer Name</li>
                            <li>Jerome Bell</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Capacity Utilzation</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Today's Utilization</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Quantity Produced</li>
                            <li>50,000</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="prodDashBox">
                <div>
                    <h6>WAX Machine</h6>
                    <span class="redBadge">Not Running</span>
                </div>
                <ul>
                    <li>
                        <ul>
                            <li>Job Card No.</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Customer Name</li>
                            <li>Jerome Bell</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Capacity Utilzation</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Today's Utilization</li>
                            <li>JC-#001</li>
                        </ul>
                    </li>
                    <li>
                        <ul>
                            <li>Quantity Produced</li>
                            <li>50,000</li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-9">
            <div id="printBankPaymentVoucherList">
                <div class="panel">
                    <div id="PrintPanel">
                        <div id="ShowHide">
                            <div class="table-responsive dashTable">
                                <div class="dashTableHeading printListBtn">
                                    <h6>Job Card List</h6>
                                    <a class="btn btn-primary" target="_blank" id="myBtn"
                                        href="{{url('/sales/viewSalesOrderList?pageType=view&&parentCode=89&&m=1#Rototec')}}">View
                                        All Orders</a>
                                </div>
                                <table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv">
                                    <thead class="bgPurpleofTd noPaddingLeft">
                                        <tr>
                                            <th class="text-center">Customer</th>
                                            <th class="text-center">Job Card No</th>
                                            <th class="text-center">Deadline</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="data" class="dashTableBody ">
                                   
                                        <tr>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                            <td class="text-center"></td>
                                        </tr>
                                      

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <div class="col-lg-3">
            <div class="card barChartHead2">
                <div
                    class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                    <div class="cashSection prodHeading">
                        <h4 class="card-subtitle text-muted mb-25">Productivity</h4>
                    </div>
                    <ul class="proFilter">
                        <li>Filter</li>
                        <li>Today</li>
                        <li>This Week</li>
                        <li>This Month</li>
                    </ul>
                </div>
                <div class="card-body">
                    <div id="bar-chart"></div>
                </div>
            </div>
        </div>
        {{-- <div class="col-lg-3">
            <div class="card dashTable">
                <div
                    class="card-header d-flex flex-sm-row flex-column justify-content-md-between align-items-start justify-content-start">
                    <div class="cashSection prodHeading">
                        <h4 class="card-subtitle text-muted mb-25">Wastage</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div id="bar-chart2"></div>
                </div>
            </div>
        </div> --}}
    </div>
    <div class="row">
        <div class="col-lg-7">
            <div class="ordersDis">
                <div id="printBankPaymentVoucherList">
                    <div class="panel">
                        <div id="PrintPanel">
                            <div id="ShowHide">
                                <div class="table-responsive dashTable">
                                    <div class="dashTableHeading printListBtn">
                                        <h6>Orders Dispatched</h6>
                                    </div>
                                    <table class="userlittab table table-bordered sf-table-list" id="TableExportToCsv">
                                        <thead class="bgBlueofTd noPaddingLeft">
                                            <tr>
                                                <th class="text-center">SO No</th>
                                                <th class="text-center">Customer Name</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data" class="dashTableBody ">
                                 
                                            <tr>
                                                <td class="text-center"></td>
                                                <td class="text-center"></td>
                                            </tr>
                                        

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="dipatchImage">
                </div>
            </div>
        </div>
    </div>

 




</div>
	<!-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<ul id="accordion" class="accordion">
				<li>
					<div class="link"><i class="fa fa-database" aria-hidden="true"></i>INVENTORY<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu">
						<li>
							<a href="<?php echo url('purchase/viewDemandList?pageType=viewlist&&parentCode=38&&m=1#SFR')?>">P/R
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

								<span class="<?php if($DemandPending>0){echo "blink_me";}?>">Pending<span class="badge badge-pill badge-danger" style="background-color: red"><?php echo $DemandPending?></span></span>
								<span>Approved<span class="badge badge-pill badge-danger" style="background-color: green"><?php echo $DemandApp?></span></span>
							</a>
						</li>
						<li>
							<a href="<?php echo url('store/viewPurchaseRequestList?pageType=viewlist&&parentCode=44&&m=1#SFR')?>">P/O
								{{--_____________--}}
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

								<span class="<?php if($PrPending>0){echo "blink_me";}?>" >Pending<span class="badge badge-pill badge-danger" style="background-color: red"><?php echo $PrPending?></span></span>
								<span>Approved<span class="badge badge-pill badge-danger" style="background-color: green"><?php echo $PrApp?></span></span>
							</a>
						</li>
						<li>
							<a href="<?php echo url('purchase/viewGoodsReceiptNoteList?pageType=viewlist&&parentCode=50&&m=1#SFR')?>">GRN
								{{--_____________--}}
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span class="<?php if($GrnPending>0){echo "blink_me";}?>">Pending<span class="badge badge-pill badge-danger" style="background-color: red"><?php echo $GrnPending?></span></span>
								<span>Approved<span class="badge badge-pill badge-danger" style="background-color: green"><?php echo $GrnApp?></span></span>
							</a>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<ul id="accordion2" class="accordion2">
				<li>
					<div class="link2"><i class="fa fa-bar-chart" aria-hidden="true"></i>SALES A<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu2">
						<li>
							<a href="<?php echo url('sales/surveylist?pageType=&&parentCode=102&&m=1#SFR')?>">Survey
								{{--_____________--}}
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								&nbsp;&nbsp;&nbsp;
								<span class="<?php if($SurveyPending>0){echo "blink_me";}?>">Pending<span class="badge badge-pill badge-danger" style="background-color: red"><?php echo $SurveyPending?></span></span>
								<span>Approved<span class="badge badge-pill badge-danger" style="background-color: green"><?php echo $SurveyApp?></span></span>
							</a>
						</li>
						<li>
							<a href="<?php echo url('sales/quotationList?pageType=&&parentCode=109&&m=1#SFR')?>">Quotation
								{{--_____________--}}
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span class="<?php if($QuotationPending>0){echo "blink_me";}?>">Pending<span class="badge badge-pill badge-danger" style="background-color: red"><?php echo $QuotationPending?></span></span>
								<span>Approved<span class="badge badge-pill badge-danger" style="background-color: green"><?php echo $QuotationApp?></span></span>
							</a>
						</li>
						<li>
							<a href="<?php echo url('purchase/viewJobOrder?pageType=&&parentCode=96&&m=1#SFR')?>">Job Order
								{{--_____________--}}
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span class="<?php if($JOPending>0){echo "blink_me";}?>">Pending<span class="badge badge-pill badge-danger" style="background-color: red"><?php echo $JOPending?></span></span>
								<span>Approved<span class="badge badge-pill badge-danger" style="background-color: green"><?php echo $JOApp?></span></span>
							</a>
						</li>
						<li>
							<a href="<?php echo url('sales/invoiceList?pageType=&&parentCode=110&&m=1#SFR')?>">Invoice
								{{--_____________--}}
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span class="<?php if($InvPending>0){echo "blink_me";}?>">Pending<span class="badge badge-pill badge-danger" style="background-color: red"><?php echo $InvPending?></span></span>
								<span>Approved<span class="badge badge-pill badge-danger" style="background-color: green"><?php echo $InvApp?></span></span>
							</a>
						</li>

						<li>
							<a href="<?php echo url('sales/complaintList?pageType=&&parentCode=112&&m=1#SFR')?>">Complaint
								{{--_____________--}}
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<span>Total<span class="badge badge-pill badge-danger" style="background-color: blue"><?php echo $TotalComplaint?></span></span>
							</a>
						</li>

					</ul>
				</li>
			</ul>
		</div>
		<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
			<ul id="accordion3" class="accordion3">
				<li>
					<div class="link3"><i class="fa fa-bookmark" aria-hidden="true"></i>FINANCE<i class="fa fa-chevron-down"></i></div>
					<ul class="submenu3">
						{{--<li><a href="#">Photoshop</a></li>--}}
						{{--<li><a href="#">HTML</a></li>--}}
						{{--<li><a href="#">CSS</a></li>--}}
					</ul>
				</li>
			</ul>
		</div>
	</div> -->

	<script !src="">
		var Multiel = "";
		$(function() {
			var Accordion = function(el, multiple) {
				this.el = el || {};
				this.multiple = multiple || false;

				// Variables privadas
				var links = this.el.find('.link');
				// Evento
				links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
			}

			Accordion.prototype.dropdown = function(e) {
				var $el = e.data.el;
				$this = $(this),
						$next = $this.next();

				$next.slideToggle();
				$this.parent().toggleClass('open');

				$(".submenu2").fadeOut('slow');
				$(".submenu3").fadeOut('slow');


				if (!e.data.multiple) {
					$el.find('.submenu').not($next).slideUp().parent().removeClass('open');
					$(".submenu2").fadeOut('slow');
					$(".submenu3").fadeOut('slow');

				};
			}

			var accordion = new Accordion($('#accordion'), false);
		});

		$(function() {
			var Accordion2 = function(el, multiple) {
				this.el = el || {};
				this.multiple = multiple || false;

				// Variables privadas
				var links = this.el.find('.link2');
				// Evento
				links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
			}

			Accordion2.prototype.dropdown = function(e) {
				var $el = e.data.el;
				$this = $(this),
						$next = $this.next();

				$next.slideToggle();
				$this.parent().toggleClass('open');

				$(".submenu").fadeOut('slow');
				$(".submenu3").fadeOut('slow');

				if (!e.data.multiple) {
					$el.find('.submenu2').not($next).slideUp().parent().removeClass('open');
					$(".submenu").fadeOut('slow');
					$(".submenu3").fadeOut('slow');
				};
			}

			var accordion2 = new Accordion2($('#accordion2'), false);
		});

		$(function() {
			var Accordion3 = function(el, multiple) {
				this.el = el || {};
				this.multiple = multiple || false;

				// Variables privadas
				var links = this.el.find('.link3');
				// Evento
				links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
			}

			Accordion3.prototype.dropdown = function(e) {
				var $el = e.data.el;
				$this = $(this),
						$next = $this.next();

				$next.slideToggle();
				$this.parent().toggleClass('open');

				$(".submenu").fadeOut('slow');
				$(".submenu2").fadeOut('slow');

				if (!e.data.multiple) {
					$el.find('.submenu3').not($next).slideUp().parent().removeClass('open');
					$(".submenu").fadeOut('slow');
					$(".submenu2").fadeOut('slow');
				};
			}

			var accordion3 = new Accordion3($('#accordion3'), false);
		});

	</script>
	<?php endif;?>
@endsection
