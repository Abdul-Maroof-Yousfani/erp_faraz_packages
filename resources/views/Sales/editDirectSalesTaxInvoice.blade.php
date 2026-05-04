<?php


$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if ($accType == 'client') {
	$m = $_GET['m'];
} else {
	$m = Auth::user()->company_id;
}

use App\Helpers\PurchaseHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;

$selectedCustomer = null;
$selectedCustomerValue = '';
if (!empty($sale_tax_invoice->buyers_id)) {
	$selectedCustomer = DB::connection('mysql2')->table('customers')->where('id', $sale_tax_invoice->buyers_id)->first();
	if ($selectedCustomer) {
		$selectedCustomerValue = $selectedCustomer->id . '*' . ($selectedCustomer->cnic_ntn ?? '') . '*' . ($selectedCustomer->strn ?? '');
	}
}

$detailRowCount = max(isset($sale_tax_invoice_data) ? count($sale_tax_invoice_data) : 0, 1);
$expenseRowCount = isset($additional_expense) ? $additional_expense->count() : 0;
?>

@extends('layouts.default')

@section('content')
	@include('loader')
	@include('number_formate')
	@include('select2')


	<style>
		* {
			font-size: 12px !important;

		}

		label {
			text-transform: capitalize;
		}

		.table-compact {
			font-size: 0.92rem;
			/* slightly smaller than default ~1rem */
			line-height: 1.2;
			/* tighter vertical spacing */
		}

		.table-compact th,
		.table-compact td {
			padding: 4px 6px !important;
			/* much less padding → more rows visible */
			vertical-align: middle;
		}

		.table-compact input.form-control,
		.table-compact select.form-control {
			font-size: 0.9rem;
			padding: 4px 6px;
			height: 28px;
			/* smaller input height */
		}

		.table-compact .btn-sm {
			padding: 3px 8px;
			font-size: 0.8rem;
		}

		.table-compact th {
			font-size: 0.85rem;
			white-space: nowrap;
			/* prevent header wrapping */
		}

		/* Optional: make really narrow columns even tighter */
		.col-very-narrow {
			width: 60px !important;
			min-width: 60px !important;
		}

		.col-narrow {
			width: 90px !important;
			min-width: 90px !important;
		}

		/* Prevent text wrapping in critical columns */
		.nowrap {
			white-space: nowrap;
		}
	</style>


	<div class="row well_N" style="display: none;" id="main">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="well">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<span class="subHeadingLabelClass">Direct Sales Invoice</span>
					</div>
				</div>
				<div class="lineHeight">&nbsp;</div>
				<div class="row">
					<?php echo Form::open(array('url' => 'sad/updateDirectSalesTaxInvoice/' . $sale_tax_invoice->id . '?m=' . $m . '', 'id' => 'createSalesOrder'));?>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="pageType" value="<?php // echo $_GET['pageType']?>">
					<input type="hidden" name="parentCode" value="<?php // echo $_GET['parentCode']?>">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel">
							<div class="panel-body">

								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="row">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label class="sf-label">Invoice No<span
														class="rflabelsteric"><strong>*</strong></span></label>
												<input readonly type="text" class="form-control" placeholder="" name="gi_no"
													id="gi_no" value="{{ strtoupper(old('gi_no', $sale_tax_invoice->gi_no)) }}" />
											</div>

											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<label class="sf-label">Invoice Date<span
														class="rflabelsteric"><strong>*</strong></span></label>
												<input value="{{ old('gi_date', $sale_tax_invoice->gi_date) }}" autofocus type="date"
													onkeyup="calculate_due_date()" class="form-control requiredField"
													placeholder="" name="gi_date" id="gi_date" />
											</div>

										</div>


										<div class="row">

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Mode / Terms Of Payment <span
														class="rflabelsteric"></label>
												<input type="text" class="form-control" placeholder=""
													name="model_terms_of_payment" id="model_terms_of_payment"
													value="{{ old('model_terms_of_payment', $sale_tax_invoice->model_terms_of_payment) }}" />
											</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Other Reference(s) <span
														class="rflabelsteric"></label>
												<input type="text" class="form-control" placeholder="" name="other_refrence"
													id="other_refrence" value="{{ old('other_refrence', $sale_tax_invoice->other_refrence) }}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Buyer's Order No<span
														class="rflabelsteric"></span></label>
												<input type="text" class="form-control" placeholder="" name="order_no"
													id="order_no" value="{{ old('order_no', $sale_tax_invoice->order_no) }}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Buyer's Order Date<span
														class="rflabelsteric"></span></label>
												<input type="date" class="form-control" placeholder="" name="order_date"
													id="order_date" value="{{ old('order_date', $sale_tax_invoice->order_date) }}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Despatched Document No<span
														class="rflabelsteric"></span></label>
												<input type="text" class="form-control" placeholder=""
													name="despacth_document_no" id="despacth_document_no"
													value="{{ old('despacth_document_no', $sale_tax_invoice->despacth_document_no) }}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Bilty No.</label>
												<input type="text" class="form-control" placeholder=""
													name="bilty_no" id="bilty_no"
													value="{{ old('bilty_no', $sale_tax_invoice->bilty_no ?? '') }}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Despatched Document Date</label>
												<input type="date" class="form-control" placeholder=""
													name="despacth_document_date" id="despacth_document_date"
													value="{{ old('despacth_document_date', $sale_tax_invoice->despacth_document_date) }}" />
											</div>




										</div>

										<div class="row">

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Despatched through<span
														class="rflabelsteric"></span></label>
												<input type="text" class="form-control" placeholder=""
													name="despacth_through" id="despacth_through"
													value="{{ old('despacth_through', $sale_tax_invoice->despacth_through) }}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Destination<span
														class="rflabelsteric"></span></label>
												<input type="text" class="form-control" placeholder="" name="destination"
													id="destination" value="{{ old('destination', $sale_tax_invoice->destination) }}" />
											</div>


											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Terms Of Delivery<span
														class="rflabelsteric"></span></label>
												<input type="text" class="form-control" placeholder=""
													name="terms_of_delivery" id="terms_of_delivery"
													value="{{ old('terms_of_delivery', $sale_tax_invoice->terms_of_delivery) }}" />
											</div>

							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" id="customer_select_wrapper">
								<label class="sf-label">
									Buyer's Name <span class="rflabelsteric"><strong>*</strong></span>
								</label>

								<select style="width: 100%" name="buyers_id" id="ntn"
							class="form-control select2" required>
									<option value="">Select</option>

									@foreach(SalesHelper::get_all_customer() as $row)
										<option @if(old('buyers_id', $selectedCustomerValue) == ($row->id . '*' . $row->cnic_ntn . '*' . $row->strn)) selected @endif value="{{ $row->id . '*' . $row->cnic_ntn . '*' . $row->strn }}">
											{{ $row->name }}
										</option>
									@endforeach

								</select>
							</div>

							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
								<label class="sf-label">Commission Buyer</label>
								<select style="width: 100%" name="commission_buyer" id="commission_buyer"
									class="form-control select2">
									<option value="">Select</option>
									@foreach(SalesHelper::get_all_customer() as $row)
										<option @if((string) old('commission_buyer', $sale_tax_invoice->commission_buyer) === (string) $row->id) selected @endif value="{{ $row->id}}">
											{{ $row->name }}
										</option>
									@endforeach
								</select>
							</div>

								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" id="walkin_customer_wrapper" style="display: none">
									<label class="sf-label">Walkin Customer Name <span
										class="rflabelsteric"><strong>*</strong></span></label>
									<input type="text" class="form-control" placeholder=""
										name="walkin_customer_name" id="walkin_customer_name"
										value="{{ old('walkin_customer_name', $sale_tax_invoice->walkin_customer_name) }}" />
								</div>


						<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" id="buyers_ntn_wrapper">
								<label class="sf-label">Buyer's Ntn </label>
								<input readonly type="text" class="form-control" placeholder=""
									name="buyers_ntn" id="buyers_ntn" value="" />
							</div>

						</div>

						<div class="row">

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Buyer's Sales Tax No </label>
												<input readonly type="text" class="form-control" placeholder=""
													name="buyers_sales" id="buyers_sales" value="" />
											</div>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Due Date <span class="rflabelsteric"></span></label>
												<input type="date" class="form-control" placeholder="" name="due_date"
													id="due_date" value="{{ old('due_date', $sale_tax_invoice->due_date) }}" />
											</div>
											<?php

													$accounts = DB::connection('mysql2')
														->table('accounts')
														->where('status', 1)
														->where('parent_code', 'like', '5%')
														->get();

														?>
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
												<label class="sf-label">Cr Account<span
														class="rflabelsteric requiredField"><strong>*</strong></span></label>
												<select class="form-control" id="acc_id" name="acc_id">
													<option value="">Select</option>
													@foreach($accounts as $row)
														<option @if((string) old('acc_id', $sale_tax_invoice->acc_id ?: 16) === (string) $row->id) selected @endif value="{{$row->id}}">
															{{$row->name}}
														</option>
													@endforeach
												</select>
											</div>



											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
												<label class="sf-label"> <a href="#"
														onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')"
														class="">Currency</a></label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<select onchange="" name="curren" id="curren" style="width: 100%;"
													class="form-control select2 requiredField">

													<option @if((string) old('curren', $sale_tax_invoice->currency ?? 0) === '0') selected @endif value="0">PKR</option>
													@foreach(CommonHelper::get_all_currency() as $row)
														<option @if((string) old('curren', $sale_tax_invoice->currency) === (string) $row->id) selected @endif value="{{$row->id}}">{{$row->curreny}}</option>
													@endforeach;

												</select>

											</div>

											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 hide">
												<label class="sf-label"> Currency Rate</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input class="form-control" value="{{ old('currency_rate', $sale_tax_invoice->currency_rate ?: 1) }}" type="text" name="currency_rate"
													id="currency_rate" />

											</div>


										</div>



										<input type="hidden" name="demand_type" id="demand_type">
										<div class="row">


										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label class="sf-label">Description</label>
												<span class="rflabelsteric">
													<textarea name="description" id="description" rows="4" cols="50"
														style="resize:none;text-transform: capitalize"
														class="form-control">{{ old('description', $sale_tax_invoice->description) }}</textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="subHeadingLabelClass">Sales Tax Invoice Data</span>
								</div>
								<div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered table-compact invoice-table">
												<thead>
													<tr>
														<th class="text-center col-narrow">S.NO</th>
														<th class="text-center col-narrow">DN NO</th>
														<th class="text-center nowrap" style="width: 18%;">Item</th>
														<th class="text-center col-narrow">Uom</th>
														<th class="text-center col-narrow">Orderd QTY</th>
														<th class="text-center col-narrow">DN QTY</th>
														<th class="text-center col-narrow">Return QTY</th>
														<th class="text-center col-narrow">QTY.<span
																class="rflabelsteric"><strong>*</strong></span></th>
														<th class="text-center col-narrow">Rate<span
																class="rflabelsteric"><strong>*</strong></span></th>
														<th class="text-center col-narrow">Net Amount</th>
														<th class="text-center nowrap hide" style="width: 9%;">Category</th>
														<th class="text-center col-narrow hide">Bag Qty</th>
														<th class="text-center col-narrow hide">Qty (lbs)</th>
														<th class="text-center hide" style="width: 11%;">Warehouse</th>
														<th class="text-center col-narrow hide">In Stock</th>
														<th class="text-center col-narrow hide">Commission Rate</th>
														<th class="text-center col-narrow hide">Amount</th>
														<th class="text-center hide" style="width: 50px;">Action</th>
													</tr>
												</thead>
												<tbody id="AppnedHtml">
													@php
														$totalNet = 0;
													@endphp
													@forelse($sale_tax_invoice_data as $index => $detail)
														@php
															$rowNo = $index + 1;
															$itemDetail = CommonHelper::get_item_by_id((int) $detail->item_id);
															$selectedUom = CommonHelper::get_uom_name($detail->uom);
															$rowAmount = $detail->amount ?? (($detail->qty ?? 0) * ($detail->rate ?? 0));
															$rowTaxAmount = $detail->tax_amount ?? 0;
															$rowNetAmount = $rowAmount;
															$totalNet += $rowAmount;
															$orderedQty = 0;
															if (!empty($detail->so_data_id)) {
																$orderedQtyObj = CommonHelper::generic('sales_order_data', ['id' => $detail->so_data_id], ['qty'])->first();
																$orderedQty = $orderedQtyObj->qty ?? 0;
															}
															$returnQty = (!empty($detail->so_data_id) && !empty($detail->gd_no)) ? SalesHelper::return_qty(1, $detail->so_data_id, $detail->gd_no) : 0;
															$dnQty = ((float) $detail->qty) + ((float) $returnQty);
														@endphp
													<tr class="cnt" title="{{ $rowNo }}" id="@if($rowNo > 1) RemoveRows{{ $rowNo }} @endif">
														<td class="text-center">{{ $rowNo }}</td>
														<td class="text-center">{{ strtoupper($detail->gd_no ?? '') }}</td>
														<td class="text-left">{{ $itemDetail->sub_ic ?? CommonHelper::get_item_name($detail->item_id) }}</td>
														<td class="text-left">{{ $selectedUom }}</td>
														<td class="text-center">{{ number_format((float) $orderedQty, 3, '.', '') }}</td>
														<td class="text-center">{{ number_format((float) $dnQty, 3, '.', '') }}</td>
														<td class="text-center">{{ number_format((float) $returnQty, 3, '.', '') }}</td>
														<td>
															<input class="form-control requiredField"
																onchange="bag_qq({{ $rowNo }})" type="number" name="actual_qty[]"
																id="actual_qty{{ $rowNo }}" step="any" oninput="bag_qq({{ $rowNo }})"
																data-saved-value="{{ $detail->qty }}"
																value="{{ $detail->qty }}" />
															<input type="hidden" class="PackQty" name="pack_qty[]"
																id="pack_qty{{ $rowNo }}">
														</td>
														<td>
															<input type="text" onkeyup="claculation('{{ $rowNo }}')"
																onblur="claculation('{{ $rowNo }}')" class="form-control requiredField"
																name="rate[]" id="rate{{ $rowNo }}" min="1"
																data-saved-value="{{ $detail->rate }}" value="{{ $detail->rate }}">
														</td>
														<td>
															<input type="text" class="form-control net_amount_dis"
																name="after_dis_amount[]" id="after_dis_amount{{ $rowNo }}" min="1"
																value="{{ number_format($rowNetAmount, 2, '.', '') }}" readonly>
														</td>
														<td class="hide">
															<select style="width: 100% !important;"
																onchange="get_sub_item2('category_id{{ $rowNo }}')" name="category[]"
																id="category_id{{ $rowNo }}"
																class="form-control category select2 requiredField">
																<option value="">Select</option>
																@foreach (CommonHelper::get_all_category() as $category)
																	<option @if((string) ($itemDetail->main_ic_id ?? '') === (string) $category->id) selected @endif value="{{ $category->id }}">
																		{{ $category->main_ic }}
																	</option>
																@endforeach
															</select>
														</td>
														<td class="hide">
															<select style="width: 100% !important;"
																onchange="get_item_name({{ $rowNo }})" name="item_id[]" id="item_id{{ $rowNo }}"
																class="form-control requiredField select2"
																data-selected-item="{{ (int) $detail->item_id }}">
																<option value="">Select</option>
																@if($itemDetail)
																	<option selected value="{{ (int) $detail->item_id }}">
																		{{ $itemDetail->sub_ic }}
																	</option>
																@endif
															</select>
														</td>
														
														<td class="hide">
															<input type="text" name="bag_qty[]" id="bag_qty{{ $rowNo }}"
																class="form-control" oninput="bag_qq({{ $rowNo }})"
																value="{{ $detail->bag_qty }}" />
														</td>
														<td class="hide">
															<select name="uom_id[]" id="uom_id{{ $rowNo }}"
																class="form-control requiredField select2"
																data-selected-uom="{{ $selectedUom }}">
																<option value="">Select UOM</option>
																@if($selectedUom)
																	<option selected value="{{ $selectedUom }}">{{ $selectedUom }}</option>
																@endif
															</select>
														</td>
														<td class="hide">
															<input class="form-control requiredField" type="number"
																id="qty_lbs{{ $rowNo }}" name="qty_lbs[]" step="any" readonly
																value="{{ number_format(((float) $detail->qty) * 2.2, 2, '.', '') }}" />
														</td>
														<td class="hide">
															<select onchange="get_stock_qty(this.id,'{{ $rowNo }}');ApplyAll('{{ $rowNo }}')"
																class="form-control  ClsAll ShowOn{{ $rowNo }}" name="warehouse[]"
																id="warehouse{{ $rowNo }}"
																data-selected-warehouse="{{ $detail->warehouse_id }}">
																<option value="">Select</option>
																@foreach(CommonHelper::get_all_warehouse() as $row)
																	<option @if((string) $detail->warehouse_id === (string) $row->id) selected @endif value="{{$row->id}}">{{$row->name}}</option>
																@endforeach
															</select>
														</td>
														<td class="hide">
															<select onchange="get_stock_qty(this.id,'{{ $rowNo }}')"
																class="form-control " name="batch_code[]" id="batch_code{{ $rowNo }}">
																<option value="">
																	Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																</option>
															</select>
														</td>
														<td class="hide">
															<input readonly class="form-control instock zerovalidate"
																name="instock[]" type="text" value="" id="instock{{ $rowNo }}" />
														</td>
														
														<td class="hide">
															<input type="text" class="form-control" placeholder=""
																name="commission[]" id="commission{{ $rowNo }}"
																data-saved-value="{{ $detail->commission }}" value="{{ $detail->commission }}" />
														</td>
														<td class="hide">
															<input type="text" class="form-control amount" name="amount[]"
																id="amount{{ $rowNo }}" placeholder="AMOUNT" min="1" value="{{ number_format($rowAmount, 2, '.', '') }}"
																readonly>
														</td>
														<td class="hide" style="width: 110px">
															<select onchange="tax_percent(this.id)" class="form-control"
																name="tax[]" id="tax_percent{{ $rowNo }}">
																<option value="0,0">Select</option>
																@foreach (ReuseableCode::invoice_tax() as $row)
																	<option @if((string) $detail->tax === ($row->acc_id . ',' . $row->tax_rate)) selected @endif value='{{ $row->acc_id . ',' . $row->tax_rate }}'>
																		{{$row->tax_rate }}
																	</option>
																@endforeach
															</select>
														</td>
														<td class="hide">
															<input readonly type="text"
																class="form-control requiredField tax_amount"
																name="tax_amount[]" id="tax_amount{{ $rowNo }}" min="1" value="{{ number_format($rowTaxAmount, 2, '.', '') }}">
														</td>
														<td class="hide" style="background-color: #ccc">
															@if($rowNo === 1)
																<input type="button" class="btn btn-sm btn-primary"
																	onclick="AddMoreDetails()" value="+" />
															@else
																<button type="button" class="btn btn-sm btn-danger" id="BtnRemove{{ $rowNo }}" onclick="RemoveSection({{ $rowNo }})"> - </button>
															@endif
														</td>
													</tr>
													@empty
													<tr class="cnt" title="1">
														<td class="text-center">1</td>
														<td class="text-center"></td>
														<td class="text-left"></td>
														<td class="text-left"></td>
														<td class="text-center">0.000</td>
														<td class="text-center">0.000</td>
														<td class="text-center">0.000</td>
														<td>
															<input class="form-control requiredField"
																onchange="bag_qq(1)" type="number" name="actual_qty[]"
																id="actual_qty1" step="any" oninput="bag_qq(1)" />
															<input type="hidden" class="PackQty" name="pack_qty[]"
																id="pack_qty1">
														</td>
														<td>
															<input type="text" onkeyup="claculation('1')"
																onblur="claculation('1')" class="form-control requiredField"
																name="rate[]" id="rate1" min="1" value="">
														</td>
														<td>
															<input type="text" class="form-control net_amount_dis"
																name="after_dis_amount[]" id="after_dis_amount1" min="1"
																value="0.00" readonly>
														</td>
														<td class="hide">
															<select style="width: 100% !important;"
																onchange="get_sub_item2('category_id1')" name="category[]"
																id="category_id1"
																class="form-control category select2 requiredField">
																<option value="">Select</option>
																@foreach (CommonHelper::get_all_category() as $category)
																	<option value="{{ $category->id }}">
																		{{ $category->main_ic }}
																	</option>
																@endforeach
															</select>
														</td>
														<td class="hide">
															<select style="width: 100% !important;"
																onchange="get_item_name(1)" name="item_id[]" id="item_id1"
																class="form-control requiredField select2">
																<option>Select</option>
															</select>
														</td>
														<td class="hide">
															<input type="text" name="bag_qty[]" id="bag_qty1"
																class="form-control" oninput="bag_qq(1)" />
														</td>
														<td class="hide">
															<select name="uom_id[]" id="uom_id1"
																class="form-control requiredField select2">
																<option value="">Select UOM</option>
															</select>
														</td>
														<td class="hide">
															<input class="form-control requiredField" type="number"
																id="qty_lbs1" name="qty_lbs[]" step="any" readonly />
														</td>
														<td class="hide">
															<select onchange="get_stock_qty(this.id,'1');ApplyAll('1')"
																class="form-control  ClsAll ShowOn1" name="warehouse[]"
																id="warehouse1">
																<option value="">Select</option>
																@foreach(CommonHelper::get_all_warehouse() as $row)
																	<option value="{{$row->id}}">{{$row->name}}</option>
																@endforeach
															</select>
														</td>
														<td class="hide">
															<select onchange="get_stock_qty(this.id,'1')"
																class="form-control " name="batch_code[]" id="batch_code1">
																<option value="">
																	Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
																</option>
															</select>
														</td>
														<td class="hide">
															<input readonly class="form-control instock zerovalidate"
																name="instock[]" type="text" value="" id="instock1" />
														</td>
														<td class="hide">
															<input type="text" class="form-control" placeholder=""
																name="commission[]" id="commission1" value="" />
														</td>
														<td class="hide">
															<input type="text" class="form-control amount" name="amount[]"
																id="amount1" placeholder="AMOUNT" min="1" value="0.00"
																readonly>
														</td>
														<td class="hide" style="width: 110px">
															<select onchange="tax_percent(this.id)" class="form-control"
																name="tax[]" id="tax_percent1">
																<option value="0,0">Select</option>
																@foreach (ReuseableCode::invoice_tax() as $row)
																	<option value='{{ $row->acc_id . ',' . $row->tax_rate }}'>
																		{{$row->tax_rate }}
																	</option>
																@endforeach
															</select>
														</td>
														<td class="hide">
															<input readonly type="text"
																class="form-control requiredField tax_amount"
																name="tax_amount[]" id="tax_amount1" min="1" value="0.00">
														</td>
														<td class="hide" style="background-color: #ccc">
															<input type="button" class="btn btn-sm btn-primary"
																onclick="AddMoreDetails()" value="+" />
														</td>
													</tr>
													@endforelse
												</tbody>
												<tbody>
													<tr class="invoice-total-row">
														<td class="text-center" colspan="9">Total</td>
														<td id="" class="text-right"><input readonly
																class="form-control" type="text" id="net" value="{{ number_format($totalNet ?? 0, 3, '.', '') }}" /> </td>
														<td class="hide"></td>
														<td class="hide"></td>
														<td class="hide"></td>
														<td class="hide"></td>
														<td class="hide"></td>
														<td class="hide"></td>
														<td class="hide"></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<table style="width: 40%;display: none" class="table table-bordered margin-topp table-">
									<thead>
									</thead>
									<tbody>
										<tr>
											<td colspan="3">Sales Tax</td>
											<td colspan="3"><input readonly type="text" onkeyup="calculate_sales_tax()"
													class="form-control" id="sales_percent" value="17" /> </td>
											<td><input readonly class="form-control" type="text" name="sales_tax"
													id="sales_tax" value="{{ number_format((float) ($sale_tax_invoice->sales_tax ?? 0), 2, '.', '') }}" /> </td>
											<td><label><input onclick="applicable()" class="form-control" type="checkbox"
														@if((float) ($sale_tax_invoice->sales_tax ?? 0) > 0) checked @endif
														name="sales_tax_applicable" id="sales_tax_applicable" value="0" />
													Applicable </label></td>
										</tr>
										<tr>
											<td colspan="3">Further Sales Tax @3%</td>
											<td colspan="3"><input readonly type="text" id="sales_percent_other"
													onkeyup="calculate_sales_tax_other()" class="form-control" value="3" />
											</td>
											<td><input readonly class="form-control" type="text" id="sales_tax_further"
													name="sales_tax_further" id="sales_tax_further" value="{{ number_format((float) ($sale_tax_invoice->sales_tax_further ?? 0), 2, '.', '') }}" /> </td>
											<td><label><input onclick="applicable()" class="form-control" type="checkbox"
														@if((float) ($sale_tax_invoice->sales_tax_further ?? 0) > 0) checked @endif
														name="sales_tax_further_applicable"
														id="sales_tax_further_applicable" value="0" /> Applicable </label>
											</td>
										</tr>
										<tr>
											<td colspan="3">Total Sales Tax</td>
											<td colspan="3"> </td>
											<td><input style="font-weight: bold;font-size: x-large" readonly
													class="form-control" type="text" name="sales_total" id="sales_total"
													value="{{ number_format((float) (($sale_tax_invoice->sales_tax ?? 0) + ($sale_tax_invoice->sales_tax_further ?? 0)), 2, '.', '') }}" /> </td>
										</tr>
									</tbody>
									</tr>
								</table>
								<div class="form-group form-inline text-right">
									<label for="email">Total </label>
									<input readonly type="text" class="form-control" id="total">
								</div>
								<div class="form-group form-inline text-right hide">
									<label for="email">Total After Tax </label>
									<input readonly type="text" class="form-control" id="total_after_sales_tax"
										name="total_after_sales_tax"
										value="{{ number_format((float) (($totalNet ?? 0) + ($sale_tax_invoice->sales_tax ?? 0) + ($sale_tax_invoice->sales_tax_further ?? 0)), 2, '.', '') }}">
								</div>
								<table>
									<tr>
										<td style="text-transform: capitalize;" id="rupees"></td>
										<input type="hidden" value="" name="rupeess" id="rupeess1" />
									</tr>
								</table>
								<input type="hidden" id="d_t_amount_1">
							</div>
							<div class="row hide">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="subHeadingLabelClass">Addional Expenses</span>
								</div>
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div class="table-responsive">
										<table class="table table-bordered sf-table-list">
											<thead>
												<th class="text-center">Account Head</th>
												<th class="text-center">Expense Amount</th>
												<th class="text-center">
													<button type="button" class="btn btn-xs btn-primary"
														id="BtnAddMoreExpense" onclick="AddMoreExpense()">More
														Expense</button>
												</th>
											</thead>
											<tbody id="AppendExpense">
												@foreach($additional_expense as $expenseIndex => $expense)
													<tr id="RemoveExpenseRow{{ $expenseIndex + 1 }}">
														<td>
															<select class='form-control requiredField select2' name='account_id[]' id='account_id{{ $expenseIndex + 1 }}'>
																<option value=''>Select Account</option>
																@foreach (CommonHelper::get_all_account() as $Fil)
																	<option @if((string) $expense->acc_id === (string) $Fil->id) selected @endif value='{{ $Fil->id }}'>{{ $Fil->code . '--' . $Fil->name }}</option>
																@endforeach
															</select>
														</td>
														<td>
															<input type='number' name='expense_amount[]' id='expense_amount{{ $expenseIndex + 1 }}' class='form-control requiredField' value='{{ $expense->amount }}'>
														</td>
														<td class='text-center'>
															<button type='button' id='BtnRemoveExpense{{ $expenseIndex + 1 }}' class='btn btn-sm btn-danger' onclick='RemoveExpense({{ $expenseIndex + 1 }})'> - </button>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="demandsSection"></div>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
							{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
							<button type="submit" id="BtnSaveAndPrint" class="btn btn-info">Save & Print</button>
						</div>
					</div>
					<?php echo Form::close();?>
				</div>
			</div>
		</div>


		<script>
			function populateEditRowItems(rowNo) {
				var categoryId = $('#category_id' + rowNo).val();
				var selectedItemId = ($('#item_id' + rowNo).data('selected-item') || '').toString();
				if (!categoryId) {
					return;
				}

				$.ajax({
					url: '{{ url("/pdc/get_sub_category2") }}',
					type: 'GET',
					data: { category: categoryId },
					success: function(response) {
						var $itemDropdown = $('#item_id' + rowNo);
						$itemDropdown.html('');
						$itemDropdown.append(new Option('Select', ''));

						$.each(response, function(index, element) {
							var optionValue = element['id'] + '@' + element['uom_name'] + '@' + element['uom_name2'] + '@' + element['sub_ic'] + '@' + element['pack_size'] + '@' + element['secondary_pack_size'];
							var option = new Option(cleanItemName(element['sub_ic']), optionValue);
							$itemDropdown.append(option);
						});

						if (selectedItemId !== '') {
							var selectedOption = $itemDropdown.find('option').filter(function() {
								var optionValue = ($(this).val() || '').toString();
								return optionValue.split('@')[0] === selectedItemId;
							}).first();

							if (selectedOption.length) {
								$itemDropdown.val(selectedOption.val()).trigger('change.select2');
								get_item_name(rowNo);
								applySavedRowValues(rowNo);
								return;
							}
						}

						$itemDropdown.trigger('change.select2');
					}
				});
			}

			function applySavedRowValues(rowNo) {
				var $uom = $('#uom_id' + rowNo);
				var $qty = $('#actual_qty' + rowNo);
				var $rate = $('#rate' + rowNo);
				var $commission = $('#commission' + rowNo);
				var $warehouse = $('#warehouse' + rowNo);

				var savedUom = ($uom.data('selected-uom') || '').toString();
				var savedQty = ($qty.data('saved-value') || '').toString();
				var savedRate = ($rate.data('saved-value') || '').toString();
				var savedCommission = ($commission.data('saved-value') || '').toString();
				var savedWarehouse = ($warehouse.data('selected-warehouse') || '').toString();

				if (savedUom !== '') {
					var matchedUomOption = $uom.find('option').filter(function() {
						return ($(this).val() || '').toString().toLowerCase() === savedUom.toLowerCase();
					}).first();

					if (matchedUomOption.length) {
						$uom.val(matchedUomOption.val()).trigger('change.select2');
					}
				}
				if (savedQty !== '') {
					$qty.val(savedQty);
				}
				if (savedRate !== '') {
					$rate.val(savedRate);
				}
				if (savedCommission !== '') {
					$commission.val(savedCommission);
				}
				if (savedWarehouse !== '') {
					$warehouse.val(savedWarehouse).trigger('change.select2');
				}

				var numericQty = parseFloat(savedQty);
				if (!isNaN(numericQty)) {
					$('#qty_lbs' + rowNo).val((numericQty * 2.2).toFixed(2));
				}

				claculation(rowNo);
			}

			var CounterExpense = {{ $expenseRowCount }};
			function AddMoreExpense() {
				CounterExpense++;
				$('#AppendExpense').append("<tr id='RemoveExpenseRow" + CounterExpense + "'>" +
					"<td>" +
					"<select class='form-control requiredField select2' name='account_id[]' id='account_id" + CounterExpense + "'><option value=''>Select Account</option><?php foreach (CommonHelper::get_all_account() as $Fil) {?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code . '--' . $Fil->name;?></option><?php }?></select>" +
					"</td>" +
					"</td>" +
					"<td>" +
					"<input type='number' name='expense_amount[]' id='expense_amount" + CounterExpense + "' class='form-control requiredField'>" +
					"</td>" +
					"<td class='text-center'>" +
					"<button type='button' id='BtnRemoveExpense" + CounterExpense + "' class='btn btn-sm btn-danger' onclick='RemoveExpense(" + CounterExpense + ")'> - </button>" +
					"</td>" +
					"</tr>");
				$('#account_id' + CounterExpense).select2();
			}

			function RemoveExpense(Row) {
				$('#RemoveExpenseRow' + Row).remove();
			}

			var Counter = {{ $detailRowCount }}

			function AddMoreDetails() {
				Counter++;
				var category = 'category_id' + Counter;

				$('#AppnedHtml').append(`
					<tr class="cnt" id="RemoveRows${Counter}">
						<td>
							<select style="width:100%!important;"
								onchange="get_sub_item2('${category}')"
								name="category[]"
								id="category_id${Counter}"
								class="form-control category select2">

								<option value="">Select</option>

								@foreach (CommonHelper::get_all_category() as $category)
									<option value="{{ $category->id }}">
										{{ $category->main_ic }}
									</option>
								@endforeach

							</select>
						</td>

						<td>
							<select style="width:100%!important;"
								onchange="get_item_name(${Counter})"
								name="item_id[]"
								id="item_id${Counter}"
								class="form-control select2">
								<option>Select</option>
							</select>
						</td>
						  <td>
									<input type="text" name="bag_qty[]" id="bag_qty${Counter}" oninput="bag_qq(${Counter})" class="form-control" />
								</td>
			<td>
														 <select name="uom_id[]" id="uom_id${Counter}" class="form-control requiredField select2">
																<option value="">Select UOM</option>
															</select>
													</td>
													<td>
									<input class="form-control requiredField"
										onchange="bag_qq(${Counter})" type="number" name="actual_qty[]"
										id="actual_qty${Counter}" step="any" oninput="bag_qq(${Counter})" />
									<input type="hidden" name="pack_qty[]" id="pack_qty${Counter}">
								</td>
								<td>
									<input class="form-control requiredField"
										type="number" id="qty_lbs${Counter}"
										name="qty_lbs[]" step="any" readonly />
								</td>

													<td class="">
														<select onchange="get_stock_qty(this.id,${Counter});ApplyAll(${Counter})" class="form-control  ClsAll ShowOn${Counter}" name="warehouse[]" id="warehouse${Counter}">
															<option value="">Select</option>
																@foreach(CommonHelper::get_all_warehouse() as $row)
																	<option value="{{$row->id}}">{{$row->name}}</option>
																@endforeach
														</select>
													</td>
													<td class="hide">
														<select onchange="get_stock_qty(this.id,${Counter})" class="form-control" name="batch_code[]" id="batch_code${Counter}">
															<option value="">Select&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</option>
														</select>
													</td>
													<td class="hide">
														<input readonly   class="form-control instock"  type="text" name="instock[]" id="instock${Counter}"/>
													</td>
														<td>
															<input type="text" class="form-control" placeholder=""
																name="commission[]" id="commission${Counter}" value="" />
														</td>
													<td>
														<input type="text" onkeyup="claculation(${Counter})" onblur="claculation(${Counter})" class="form-control requiredField" name="rate[]" id="rate${Counter}"  min="1" value="">
													</td>
													<td>
														 <input type="text" class="form-control amount" name="amount[]" id="amount${Counter}" placeholder="AMOUNT" min="1" value="0.00" readonly>
													</td>
													 <td class="hide" style="width: 110px">
														   <select onchange="tax_percent(this.id)"  class="form-control" name="tax[]" id="tax_percent${Counter}">
															<option value="0,0">Select</option>
																@foreach (ReuseableCode::invoice_tax() as $row)
																	<option value='{{ $row->acc_id . ',' . $row->tax_rate }}'>{{$row->tax_rate }}</option>
																@endforeach
														   </select>
													</td>
													<td class="hide">
														<input readonly type="text"  class="form-control requiredField tax_amount" name="tax_amount[]" id="tax_amount${Counter}"  min="1" value="0.00">
													</td>
													<td>
														<input type="text" class="form-control net_amount_dis" name="after_dis_amount[]" id="after_dis_amount${Counter}"  min="1" value="0.00" readonly>
													</td>
													<td style="background-color: #ccc">
														<button type="button" class="btn btn-sm btn-danger" id="BtnRemove${Counter}" onclick="RemoveSection(${Counter})"> - </button>
													</td>
											</tr>`);
				$('.select2').select2();

				var AutoCount = 1;
				$(".AutoCounter").each(function () {
					AutoCount++;
					$(this).prop('title', AutoCount);

				});
				$('.sam_jass').bind("enterKey", function (e) {

					var check = (this.id).split('_');

					if ($('#product_' + check[1]).val() != '') {
						alert('Bundles Selectd Against This');
						return false;
					}
					$('#items').modal('show');


				});
				$('.sam_jass').keyup(function (e) {
					if (e.keyCode == 13) {
						selected_id = this.id;
						$(this).trigger("enterKey");


					}

				});

				$('.sam_jass').bind("enterKeyy", function (e) {


					$('#budles_dataa').modal('show');


				});

				$('.sam_jass').keyup(function (e) {
					if (e.keyCode == 113) {
						selected_id = this.id;
						$(this).trigger("enterKeyy");


					}

				});


				$('.sami').bind("enterKey", function (e) {


					$('#items_searc_for_bundless').modal('show');


				});
				$('.sami').keyup(function (e) {
					if (e.keyCode == 13) {
						selected_idd = this.id;
						$(this).trigger("enterKey");


					}

				});
				var itemsCount = $(".cnt").length;

				$('#span').text(itemsCount);
			}
			function RemoveSection(Row) {
				//            alert(Row);
				$('#RemoveRows' + Row).remove();
				//   $(".AutoCounter").html('');
				var AutoCount = 1;
				var AutoCount = 1;
				$(".AutoCounter").each(function () {
					AutoCount++;
					$(this).prop('title', AutoCount);
				});
				var itemsCount = $(".cnt").length;

				$('#span').text(itemsCount);
			}

			function get_item_name(index) {
				var item = $('#item_id' + index).val();
				var uom = item.split('@');

				console.log(uom);

				$('#item_code' + index).val(uom[3]); // sub_ic from item data

				// store both quantities in hidden attributes
				$('#uom_id' + index).data('qty1', uom[4]);
				$('#uom_id' + index).data('qty2', uom[5]);

				$('#pack_size' + index).val(1);

				var uomDropdown = $('#uom_id' + index);
				uomDropdown.html('<option value="">Select UOM</option>');

				if (uom[1]) {
					uomDropdown.append(new Option(uom[1], uom[1])); // first uom
				}

				if (uom[2] && uom[2] !== "null") {
					uomDropdown.append(new Option(uom[2], uom[2])); // second uom
				}

				bag_qq(index);
			}

			$(document).on('change', '[id^=uom_id]', function () {

				var index = this.id.replace('uom_id', '');
				var selectedText = $(this).val(); // Kilogram / Pieces / Bundle
				console.log('Selected UOM:', selectedText);

				var firstUom = $('#uom_id' + index + ' option:eq(1)').text();
				var qty1 = $(this).data('qty1') || 0;
				var qty2 = $(this).data('qty2') || 0;

				var pack_qty = (selectedText === firstUom) ? qty1 : qty2;

				// Check for Bundle (case-insensitive)
				if(selectedText.toLowerCase().includes('bundle')){
					console.log('Bundle matched');
					// Bundle: Set fixed 10 KG and make readonly
					$('#actual_qty' + index).val(10);
					$('#qty_lbs' + index).val((10 * 2.2).toFixed(2));
					$('#actual_qty' + index).prop('readonly', false);
					$('#qty_lbs' + index).prop('readonly', true);
					$('#rate' + index).val('');
					 $('#amount' + index).val('');
					 $('#net').val('');
					 $('#total').val('');
					
				} else if(selectedText.toLowerCase().includes('pcs') || selectedText.toLowerCase().includes('pieces')){
					console.log('Pcs matched');
					// Pcs: Make KG field editable for manual input
					$('#actual_qty' + index).val('');
					$('#actual_qty' + index).prop('readonly', false);
					$('#qty_lbs' + index).prop('readonly', true);
					$('#rate' + index).val('');
					 $('#amount' + index).val('');
					 $('#net').val('');
					 $('#total').val('');
				} else {
					console.log('Other UOM matched');
					// Other UOMs: Auto-calculate based on pack_qty
					$('#pack_qty' + index).val(pack_qty);
					$('#actual_qty' + index).val(pack_qty);
					$('#qty_lbs' + index).val((pack_qty * 2.2).toFixed(2));
					$('#actual_qty' + index).prop('readonly', true);
					$('#qty_lbs' + index).prop('readonly', true);
					$('#rate' + index).val('');
					 $('#amount' + index).val('');
					 $('#net').val('');
					 $('#total').val('');
				}

				bag_qq(index);
			});

			function bag_qq(counter) {
				var bags_qty = parseFloat($('#bag_qty' + counter).val()) || 1;
				var pack_qty = parseFloat($('#pack_qty' + counter).val()) || 0;
				var actual_qty = parseFloat($('#actual_qty' + counter).val()) || 0;
				var selectedUom = ($('#uom_id' + counter).val() || '').toString();
				var firstUom = ($('#uom_id' + counter + ' option:eq(1)').text() || '').toString();
				var qty1 = parseFloat($('#uom_id' + counter).data('qty1')) || 0;
				var qty2 = parseFloat($('#uom_id' + counter).data('qty2')) || 0;

				// Re-derive pack qty after reload/edit when hidden pack field is empty
				if (pack_qty <= 0 && selectedUom !== '') {
					var selectedNormalized = selectedUom.toLowerCase();
					var firstNormalized = firstUom.toLowerCase();
					pack_qty = (selectedNormalized === firstNormalized) ? qty1 : qty2;
					if (pack_qty > 0) {
						$('#pack_qty' + counter).val(pack_qty);
					}
				}

				// For Bundle: keep actual_qty editable and multiply it by pack_size
				if (selectedUom.toLowerCase().includes('bundle')) {
					var total_qty = (actual_qty).toFixed(2);
					$('#qty_lbs' + counter).val((total_qty * 2.2).toFixed(2));
				}
				// For Pcs: actual_qty is manually entered, auto-calculate lbs
				else if (selectedUom.toLowerCase().includes('pcs') || selectedUom.toLowerCase().includes('pieces')) {
					$('#qty_lbs' + counter).val((actual_qty * 2.2).toFixed(2));
				} else {
					// For other UOMs: use pack_qty
					var total_qty = (bags_qty * pack_qty).toFixed(2);
					$('#actual_qty' + counter).val(total_qty);
					$('#qty_lbs' + counter).val((total_qty * 2.2).toFixed(2));
				}

				// Keep amount/net totals in sync with the latest qty
				claculation(counter);
			}
		</script>
		<script>
			function view_history(id) {
				var v = $('#sub_ic_des' + id).val();
				if ($('#view_history' + id).is(":checked")) {
					if (v != null) {
						showDetailModelOneParamerter('sdc/sals_history?id=' + v);
					}
					else {
						alert('Select Item');
					}
				}
			}

			var x = 0;


			$('.sam_jass').bind("enterKey", function (e) {

				var check = (this.id).split('_');

				if ($('#product_' + check[1]).val() != '') {
					alert('Bundles Selectd Against This');
					return false;
				}
				$('#items').modal('show');


			});
			$('.sam_jass').keyup(function (e) {
				if (e.keyCode == 13) {
					selected_id = this.id;
					$(this).trigger("enterKey");


				}

			});

			$('.sam_jass').bind("enterKeyy", function (e) {


				$('#budles_dataa').modal('show');


			});

			$('.sam_jass').keyup(function (e) {
				if (e.keyCode == 113) {
					selected_id = this.id;
					$(this).trigger("enterKeyy");


				}

			});


			$('.stop').on('keyup keypress', function (e) {
				var keyCode = e.keyCode || e.which;
				if (keyCode === 13) {

					e.preventDefault();
					return false;
				}
			});



			function net_amount() {
				var amount = 0;
				$('.amount').each(function (i, obj) {
					amount += parseFloat($('#' + obj.id).val()) || 0;
				});
				amount = (parseFloat(amount) || 0).toFixed(3);


				$('#net').val(amount);
				$('#total').val(amount);


				var net_amount = 0;
				$('.net_amount_dis').each(function (i, obj) {
					net_amount += parseFloat($('#' + obj.id).val()) || 0;
				});
				net_amount = (parseFloat(net_amount) || 0).toFixed(3);
				$('#total_after_sales_tax').val(net_amount);

			}








			$(document).ready(function () {


				$(".btn-success").click(function (e) {

					//alert();
					var purchaseRequest = new Array();
					var val;
					//$("input[name='demandsSection[]']").each(function(){
					purchaseRequest.push($(this).val());



					//});
					var _token = $("input[name='_token']").val();
					for (val of purchaseRequest) {
						jqueryValidationCustom();
						if (validate == 0) {
							//alert(response);
						} else {
							return false;
						}
					}

				});
			});
			function removeSeletedPurchaseRequestRows(id, counter) {
				var totalCounter = $('#totalCounter').val();
				if (totalCounter == 1) {
					alert('Last Row Not Deleted');
				} else {
					var lessCounter = totalCounter - 1;
					var totalCounter = $('#totalCounter').val(lessCounter);
					var elem = document.getElementById('removeSelectedPurchaseRequestRow_' + counter + '');
					elem.parentNode.removeChild(elem);
				}

			}

			$(document).ready(function () {

				off();
			});


			function claculation(number) {
				var qty = ($('#actual_qty' + number).val() || '').toString().replace(/,/g, '');
				var packSize = parseFloat($('#pack_size' + number).val()) || 1;
				var selectedUom = $('#uom_id' + number).val() || '';
				var rate = ($('#rate' + number).val() || '').toString().replace(/,/g, '');
				var totalQty = parseFloat(qty) || 0;
				rate = parseFloat(rate) || 0;

				if (selectedUom.toLowerCase().includes('bundle')) {
					totalQty = totalQty * packSize;
				}

				var qty_lbs = parseFloat(totalQty) * 2.2 || 0;
				$('#qty_lbs' + number).val(qty_lbs.toFixed(2));

				var total = parseFloat(totalQty * rate).toFixed(2);
				$('#amount' + number).val(total);

				tax_percent('tax_percent' + number);
				net_amount();

				//  toWords(1);
			}


			function off() {

			}



		</script>
		<script>
			function ApplyAll(number) {
				var count = $('#id_count').val();

				if (number == 1) {
					for (i = 1; i <= count; i++) {

						var selectedVal = $('#warehouse' + number).val();
						$('.ClsAll').val(selectedVal);
						get_stock('warehouse' + i, i);


					}
				}

			}

			function get_stock(warehouse, number) {


				var warehouse = $('#' + warehouse).val();
				var item = $('#sub_ic_des' + number).find(":selected").val();
				var myArray = item.split(",");

				var batch_code = 0;

				$.ajax({
					url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code=' + batch_code,
					type: "GET",
					data: { warehouse: warehouse, item: myArray[0], batch_code: batch_code },
					success: function (data) {
						alert(data);
						$('#instock' + number).html(data);
					}
				});

			}


			function get_stock_qty(warehouse, number) {


				var warehouse = $('#warehouse' + number).val();
				var myArray = $('#item_id' + number).find(":selected").val();
				var item = myArray.split(",");
				var batch_code = 0;
				$.ajax({
					url: '<?php echo url('/')?>/pdc/get_stock_location_wise?batch_code=' + batch_code,
					type: "GET",
					data: { warehouse: warehouse, item: item[0] },
					success: function (data) {

						//   $('#batch_code'+number).html(data);

						data = data.split('/');

						$('#instock' + number).val(data[0]);

						if (data[0] == 0) {
							$("#" + item).css("background-color", "red");
						}
						else {
							$("#" + item).css("background-color", "");
						}

					}
				});

			}


			function tax_percent(id) {
				var number = id.replace("tax_percent", "");
				var amount = parseFloat($('#amount' + number).val()) || 0;

				var x = ($('#' + id).val() || '0,0').toString();

				x = x.split(',');
				x = parseFloat(x[1]) || 0;


				if (x > 100) {
					alert('Percentage Cannot Exceed by 100');
					$('#' + id).val(0);
					x = 0;
				}

				x = x * amount;
				var tax_amount = parseFloat(x / 100).toFixed(2);
				$('#tax_amount' + number).val(tax_amount);

				var tax_amount = parseFloat($('#tax_amount' + number).val()) || 0;


				if (isNaN(tax_amount)) {

					$('#tax_amount' + number).val(0);
					tax_amount = 0;
				}



				var amount_after_discount = parseFloat(amount + tax_amount).toFixed(3);



				$('#after_dis_amount' + number).val(amount_after_discount);
				var amount_after_discount = $('#after_dis_amount' + number).val();

				if (amount_after_discount == 0) {
					$('#after_dis_amount' + number).val(amount);
					$('#net_amounttd_' + number).val(amount);
					$('#net_amount' + number).val(amount_after_discount);
				}

				else {

					$('#net_amounttd_' + number).val(amount_after_discount);
					$('#after_dis_amount' + number).val(amount_after_discount);
				}

				$('#cost_center_dept_amount' + number).text(amount_after_discount);
				$('#cost_center_dept_hidden_amount' + number).val(amount_after_discount);


				//	sales_tax('sales_taxx');
				net_amount();
				//  	sales_tax();
				//  toWords(1);


			}



			function get_detail(id, number) {

				var item = $('#' + id).val();
				$.ajax({
					url: '{{url('/pdc/get_data')}}',
					data: { item: item },
					type: 'GET',
					success: function (response) {

						var data = response.split(',');
						$('#uom_id' + number).val(data[0]);

					}
				})



			}
			$(".remove").each(function () {

				$(this).html($(this).html().replace(/,/g, ''));
			});
			function get_ntn() {
				var ntn = $('#ntn').val() || '';
				ntn = ntn ? ntn.split('*') : [];
				console.log(ntn);
				$('#buyers_ntn').val(ntn[1] || '');
				$('#buyers_sales').val(ntn[2] || '');
				if (typeof ntn[3] !== 'undefined' && ntn[3] !== '') {
					$('#model_terms_of_payment').val(ntn[3]);
				}
				calculate_due_date();
				sales_tax();
			}

		

			function calculate_due_date() {

				var days = (parseFloat($('#model_terms_of_payment').val()) || 0) - 1;
				var tt = document.getElementById('gi_date').value;
				if (!tt) {
					return;
				}

				var date = new Date(tt);
				var newdate = new Date(date);
				newdate.setDate(newdate.getDate() + days);
				var dd = newdate.getDate();

				var dd = ("0" + (newdate.getDate() + 1)).slice(-2);
				var mm = ("0" + (newdate.getMonth() + 1)).slice(-2);
				var y = newdate.getFullYear();
				var someFormattedDate = + y + '-' + mm + '-' + dd;

				document.getElementById('due_date').value = someFormattedDate;
			}
			function sales_tax() {

				var total = parseFloat($('#net').val());
				if (isNaN(total)) {
					total = 0;
				}

				if ($("#sales_tax_applicable").prop('checked') == false) {
					total = 0;
				}

				var sales_tax_percent = parseFloat($('#sales_percent').val());
				var sales_tax = ((total / 100) * sales_tax_percent).toFixed(2);
				$('#sales_tax').val(sales_tax);


				var strn = $('#buyers_sales').val();
				var total = parseFloat($('#net').val());

				if ($("#sales_tax_further_applicable").prop('checked') == false) {
					total = 0;
				}

				if (strn == '') {
					var sales_tax_percent = parseFloat($('#sales_percent_other').val());
					var sales_tax_further = ((total / 100) * sales_tax_percent).toFixed(2);
					$('#sales_tax_further').val(sales_tax_further);

				}
				else {
					sales_tax_further = 0;
					$('#sales_tax_further').val(0);
				}

				total_cal();


				toWords(1);
			}


			function total_cal() {
				var sales_tax_amount = parseFloat($('#sales_tax').val());
				var sales_tax_amount_further = parseFloat($('#sales_tax_further').val());
				var total = sales_tax_amount + sales_tax_amount_further;
				$('#sales_total').val(total);

				var before_tax = parseFloat($('#net').val());


				$('#total').val(before_tax);
				var after_tax = parseFloat($('#sales_total').val());
				var total_after = before_tax + after_tax;
				$('#total_after_sales_tax').val(total_after);

				$('#d_t_amount_1').val(total_after);


			}


			function applicable() {
				sales_tax();
			}

			function get_uom(id) {
				var sub_ic_data = $('#sub_ic_des' + id).val();
				sub_ic_data = sub_ic_data.split(',');
				$('#uom_id' + id).val(sub_ic_data[1]);
			}
		</script>
		<script type="text/javascript">
			$('.select2').select2();
		</script>
		<script>
			$(document).ready(function () {

		function handleCustomerChange() {
			let value = $('#ntn').val();
			let parts = value ? value.split('*') : [];
			let id = parts[0] || '';

			if (parseInt(id) === 5) { // Walk-in Customer
				$('#walkin_customer_wrapper').show();
			} else {
				$('#walkin_customer_wrapper').hide();
				$('#walkin_customer_name').val('');
			}
		}

		// Handle select2:select event
		$('#ntn').on('select2:select', function () {
			let value = $(this).val();
			let parts = value ? value.split('*') : [];
			let id = parts[0] || '';
			let ntn = parts[1] || '';
			let strn = parts[2] || '';

			get_ntn(); // existing function
			handleCustomerChange();
		});

		// Handle regular change event as fallback
		$('#ntn').on('change', function () {
			handleCustomerChange();
		});

		// Handle if the select is cleared
		$('#ntn').on('select2:unselecting', function () {
			$('#walkin_customer_wrapper').hide();
			$('#walkin_customer_name').val('');
		});

		get_ntn();
		handleCustomerChange();
		total_cal();

		for (let i = 1; i <= Counter; i++) {
			populateEditRowItems(i);
			if ($('#warehouse' + i).val() && $('#item_id' + i).val()) {
				get_stock_qty('warehouse' + i, i);
			}
		}

	});
		</script>
		<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
