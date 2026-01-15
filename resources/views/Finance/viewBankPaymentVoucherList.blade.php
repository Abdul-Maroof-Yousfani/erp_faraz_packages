<?php
	use App\Helpers\CommonHelper;
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}
	$current_date = date('Y-m-d');
	$currentMonthStartDate = date('Y-m-01');
    $currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printBankPaymentVoucherList','','1');?>
                        <?php echo CommonHelper::displayExportButton('bankPaymentVoucherList','','1')?>
					</div>
					<div class="lineHeight">&nbsp;</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="subHeadingLabelClass">View Bank Payment Voucher List</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<input type="hidden" name="functionName" id="functionName" value="fdc/filterBankPaymentVoucherList" readonly="readonly" class="form-control" />
							<input type="hidden" name="tbodyId" id="tbodyId" value="filterBankPaymentVoucherList" readonly="readonly" class="form-control" />
							<input type="hidden" name="m" id="m" value="<?php echo $m?>" readonly="readonly" class="form-control" />
							<input type="hidden" name="baseUrl" id="baseUrl" value="<?php echo url('/')?>" readonly="readonly" class="form-control" />

							<div class="row">
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
									<label>Account Head</label>
									<input type="hidden" readonly name="selectAccountHeadId" id="selectAccountHeadId" class="form-control" value="">
									<input list="selectAccountHead" name="selectAccountHead" id="selectAccountHeadTwo" class="form-control clearable">
                                    <?php echo CommonHelper::accountHeadSelectList($m);?>
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
									<label>From Date</label>
									<input type="Date" name="fromDate" id="fromDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthStartDate;?>" class="form-control" />
								</div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-center"><label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
									<input type="text" readonly class="form-control text-center" value="Between" /></div>
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
									<label>To Date</label>
									<input type="Date" name="toDate" id="toDate" max="<?php echo $current_date;?>" value="<?php echo $currentMonthEndDate;?>" class="form-control" />
								</div>


								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 text-right">
                                    <input type="button" value="View Filter Data" class="btn btn-sm btn-danger" onclick="viewRangeWiseDataFilter();" style="margin-top: 32px;" />
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<div id="printBankPaymentVoucherList">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="panel">
											<div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="table-responsive">
														<h5 style="text-align: center" id="h3"></h5>
														<table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
   															<thead>
																<th class="text-center">S.No</th>
                                        						<th class="text-center">P.V. No.</th>
																<th class="text-center">P.V. Date</th>
																<th class="text-center">Debit/Credit</th>
																<th class="text-center">Ref / Bill No.</th>
																<th class="text-center">Cheque No</th>
																<th class="text-center">Cheque Date</th>
																<th class="text-center">Voucher Status</th>
																<th class="text-center">Amount</th>
																<th class="text-center">Payment Type</th>
																<th class="text-center hidden-print">Action</th>
															</thead>
															<tbody id="filterBankPaymentVoucherList"></tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden qrCodeDiv">
									<img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(200)->generate('View Bank Payment Voucher List'))!!} ">
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(document).keydown(function(evt){

			if (evt.keyCode==83 && (evt.ctrlKey))
			{

				evt.preventDefault();
				addMorePvsDetailRows(1);


			}


		});
	</script>
	<script src="{{ URL::asset('assets/custom/js/customFinanceFunction.js') }}"></script>
	<script type="text/javascript">
		function DeletePvActivity(PvId,PvNo,UserName,DeleteDate,DeleteTime,ActivityType)
		{

			if (confirm('Are you sure you want to delete this thing into the Software?'))
			{
				var m = '<?php echo $_GET['m'];?>';
				$.ajax({
					url: '<?php echo url('/')?>/fdc/DeletePvActivity',
					type: "GET",
					data: { PvId:PvId,PvNo:PvNo,UserName:UserName,DeleteDate:DeleteDate,
						DeleteTime:DeleteTime,ActivityType:ActivityType},
					success:function(data) {

						filterVoucherList();
					}
				});
			}
			else
			{
				// Do nothing!
			}

		}
	</script>
@endsection