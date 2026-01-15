<?php





$str = DB::Connection('mysql2')->selectOne("select count(id)id from pvs where status=1 and pv_date='".date('Y-m-d')."'
			and voucherType=2
			")->id;
$pv_no = 'pv'.($str+1);
use App\Helpers\CommonHelper;

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
	$m = $_GET['m'];
}else{
	$m = Auth::user()->company_id;
}

?>

@extends('layouts.default')

@section('content')
	@include('number_formate')
	@include('select2')

	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
			@include('Finance.'.$accType.'financeMenu')
		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="well">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<span class="subHeadingLabelClass">Create  Payment Voucher Form</span>
					</div>




				</div>
				<div class="lineHeight">&nbsp;</div>
				<div class="row">
					<?php echo Form::open(array('url' => 'fad/addBankPaymentVoucherDetail?m='.$m.'','id'=>'bankPaymentVoucherForm'));?>

					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
					<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
									</div>
								</div>
								{{--<div class="row">--}}
								{{--<div class="col-lg-12 col-md-12 col-sm-12">--}}
								{{--<div class="panel panel-default">--}}
								{{--<div class="panel-heading" style="padding: 0px 15px; font-size: 20px;"><strong>Referencing</strong></div>--}}
								{{--<div class="panel-body" style="border: solid 1px #ddd;">--}}
								{{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
								{{--<label class="sf-label">Refrence</label>--}}
								{{--<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>--}}
								{{--<input onclick="advance()" disabled   type="checkbox" class=""  name="adv" id="adv" value="2" />--}}
								{{--</div>--}}

								{{--<input type="hidden" class="supplier_val" value=""/>--}}

								{{--<div  class="col-lg-2 col-md-2 col-sm-2 col-xs-2">--}}
								{{--<div class="vendor">--}}
								{{--<label class="sf-label">Vendor  <span class="rflabelsteric"><strong>*</strong></span></label>--}}
								{{--<select  id="vendor_id" onchange="purchase_refrence_event()" class="form-control select2" style="font-size: 13px;" name="vendor_id">--}}
								{{--<option value="0">Select</option>--}}
								{{--@foreach(CommonHelper::get_all_supplier() as $row)--}}
								{{--<option value="{{$row->id}}">{{$row->name}}</option>--}}
								{{--@endforeach--}}
								{{--</select>--}}
								{{--<input type="hidden" name="adv_amount" id="adv_amount"/>--}}
								{{--</div>--}}
								{{--</div>--}}

								{{--<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">--}}
								{{--<div style="display: none" class="refrence_nature">--}}
								{{--<label class="sf-label">Purchase Refrence  <span class="rflabelsteric"><strong>*</strong></span></label>--}}

								{{--<select onchange="get_refrence()" class="form-control" style="font-size: 13px" name="refrence"  id="refrence">--}}
								{{--<option value="0">Select</option>--}}
								{{--<option value="1">Generate New Purchase Refrence</option>--}}
								{{--<option value="2">Against  Refrence</option>--}}
								{{--</select>--}}
								{{--<input type="hidden" name="adv_amount" id="adv_amount"/>--}}
								{{--</div>--}}
								{{--</div>--}}
								{{--<div id="get_refrence" class="col-lg-3 col-md-3 col-sm-3 col-xs-3">--}}

								{{--</div>--}}
								{{--</br></br></br>--}}

								{{--<div class="col-lg-12 col-md-12 col-sm-12">--}}
								{{--<span id="breakup_data"></span>--}}
								{{--</div>--}}

								{{--</div>--}}
								{{--</div>--}}
								{{--</div>--}}

								{{--</div>--}}



								<div class="row">



									<input type="hidden" name="type" id="type" value="1" />
									<input  checked  type="hidden" class="" value="1" name="payment_mod"  />

									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										<label class="sf-label">PV No</label>
										<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
										<input  readonly type="text" class="form-control requiredField" placeholder="Slip No"
												name="" id="" value="{{strtoupper($pv_no)}}" />

									</div>

									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										<label class="sf-label">PV Date.</label>
										<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
										<input autofocus onblur="change_day()" onchange="change_day()"  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date_1" id="pv_date_1" value="<?php echo date('Y-m-d') ?>" />
									</div>

									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										<label class="sf-label">PV Day.</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<input  readonly type="text" class="form-control requiredField"  name="pv_day" id="pv_day"  />
									</div>

									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										<label class="sf-label">Ref / Bill No.</label>
										<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
										<input   type="text" class="form-control" placeholder="Slip No" name="slip_no_1" id="slip_no_1" value="-" />
									</div>




									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										<label class="sf-label">Bill Date.</label>

										<input type="date" class="form-control"  name="bill_date" id="bill_date" value="" />
									</div>



									<input type="hidden" name="with_cheque" id="with_cheque" value="0">
								</div>

								<div class="row">



									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										<label class="sf-label">With Cheque</label>
										<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
										<input  checked  type="checkbox" class="" value="1" name="cheque_status"  />
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_nature">
										<label class="sf-label">Cheque No.</label>
										<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
										<input  type="text" class="form-control requiredField" placeholder="Cheque No" name="cheque_no_1" id="cheque_no_1" value="" />
									</div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_nature">
										<label class="sf-label">Cheque Date.</label>
										<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
										<input  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="cheque_date_1" id="cheque_date_1" value="{{date('Y-m-d')}}" />
									</div>



									<div style="display: none" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 bank_cash_cls">
										<label class="sf-label">Bank / Cash</label>
										<select class="form-control" name="v_type" id="v_type">
											<option value="1">Bank</option>
											<option value="2">Cash</option>
											<option value="">select</option>

										</select>
									</div>

									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
										<label class="sf-label">Form Open.</label>
										<select name="FormOpen" id="FormOpen" class="form-control select2">
											<option value="">SELECT</option>
											<option value="Vendor">Vendor Form</option>
											<option value="Account">Account Form</option>

										</select>
									</div>
									{{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
									{{--<label class="sf-label">Supplier's <input type="radio" name="SupplierOtherPay" id="SupplierOtherPay" value="1" onclick="SuppOthPayChange(this.value)"></label>--}}
									{{--<select name="SupplierId" id="SupplierId" class="form-control select2" disabled>--}}
									{{--<option value="">Select Supplier</option>--}}
									{{--< ?php foreach($Supplier as $SuppFil):?>--}}
									{{--<option value="< ?php echo $SuppFil['id']?>">< ?php echo $SuppFil['name']?></option>--}}
									{{--< ?php endforeach;?>--}}
									{{--</select>--}}
									{{--</div>--}}
									{{--<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">--}}
									{{--<label class="sf-label">Other Payables <input type="radio" name="SupplierOtherPay" id="SupplierOtherPay" value="2" onclick="SuppOthPayChange(this.value)"></label>--}}
									{{--<select name="OtherPayables" id="OtherPayables" class="form-control select2" disabled>--}}
									{{--<option value="">Select Other Payable</option>--}}
									{{--< ?php foreach($OtherPayables as $OPFil):?>--}}
									{{--<option value="< ?php echo $OPFil['id']?>">< ?php echo $OPFil['name']?></option>--}}
									{{--< ?php endforeach;?>--}}
									{{--</select>--}}
									{{--</div>--}}
									{{--<script !src="">--}}
									{{--function SuppOthPayChange(Val)--}}
									{{--{--}}
									{{--if(Val == 1)--}}
									{{--{--}}
									{{--$('#SupplierId').prop('disabled',false);--}}
									{{--$('#OtherPayables').prop('disabled',true);--}}
									{{--}--}}
									{{--else--}}
									{{--{--}}
									{{--$('#SupplierId').prop('disabled',true);--}}
									{{--$('#OtherPayables').prop('disabled',false);--}}
									{{--}--}}
									{{--}--}}
									{{--</script>--}}

								</div>


								<div class="checkbox" style="margin-left: 15px;">
									<label><input onclick="ShowHide()"  name="AllTaxs" type="checkbox" id="AllTaxs" value="1">Tax Applicable</label>
								</div>

								<div class="container-fluid ShowHide">
									<h4>Income Tax Withholding</h4>
									<div class="well">-

										<div class="row">
											<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">

												<label class="radio-inline">
													<input onclick="applicable(1)" type="radio" name="optradio" id="business" value="1"> Applicable
												</label>
												<label class="radio-inline">
													<input checked onclick="applicable(2)" type="radio" name="optradio" id="company" value="2">Non  Applicable
												</label>

												<label class="radio-inline">
													<input onclick="applicable(3)" type="radio" name="optradio" id="btl" value="3">BTL
												</label>
												<label class="radio-inline">
													<input onclick="applicable(4)" type="radio" name="optradio" id="exmpt" value="4">Exempt
												</label>
											</div>


											<div  style="display: none"  id="supplier_div"  class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<select onchange="supplier_income_tax_data()" style="width: 100%"  class="form-control" name="supplier_id" id="supplier_id">
													<option value="0">Select Supplier</option>
													@foreach(CommonHelper::get_all_supplier() as $row)
														<option value="{{$row->id}}">{{$row->name}}</option>
													@endforeach
												</select>

											</div>
											<div  style="display: none;" class="col-lg-3 col-md-3 col-sm-3 col-xs-12" id="DisplayCode">
												<input type="text" name="VendorCode" id="VendorCode" class="form-control" placeholder="Exempt Code">
											</div>


											<span id="response" class="text-center"></span>

										</div>
										<div>&nbsp;&nbsp;&nbsp;</div>


									</div>
								</div>
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ShowHide">
									<p style="color: red" id="percent_cal1" class=""> </p>
								</div>


									<span class="ShowHide">
										@include('Finance.jvs_fbr')
										@include('Finance.jvs_srb')
										@include('Finance.jvs_pra')
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<p style="color: red" id="percent_cal2" class=""> </p>
										</div>
									</span>

								<div class="checkbox" style="margin-left: 15px;">
									<label><input onclick="show()"  name="items" type="checkbox" id="items" value="1">With Items</label>
								</div>

							</div>
							<div class="lineHeight">&nbsp;</div>

							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="table-responsive">
										<table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
											<thead>
											<tr>
												<th style="" class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>
												<th class="text-center" style="width:150px;">Current Bal<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
												<th style="width: 200px;" class="text-center hidden-print hidee"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a>
												<th style="width: 100px" class="text-center hidee">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
												<th style="width: 150px;" class="text-center hidee">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
												<th style="width: 150px;" class="text-center hidee">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>

												</th>

												<th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
												<th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
												<th class="text-center" style="width:150px;">Allocation<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
											</tr>
											</thead>
											<tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
											<?php $tab_index=6; for($j = 1 ; $j <= 2 ; $j++){?>
											<input type="hidden" name="pvsDataSection_1[]" class="form-control requiredField" id="pvsDataSection_1" value="<?php echo $j?>" />
											<tr>
												<td>
													<select  onchange="get_current_amount(this.id)" style="width: 100%" class="form-control requiredField select2" name="account_id_1_<?php echo $j?>" id="account_id_1_<?php echo $j?>">
														<option value="">Select Account</option>
														@foreach(CommonHelper::get_accounts_for_jvs() as $key => $y)
															<option value="{{$y->id.'~'.$y->supp_id.'~'.$y->supplier_id}}">{{$y->name}}</option>
														@endforeach
													</select>
												</td>
												<input type="hidden" id="current_amount_hidden<?php echo $j ?>"/>

												<script>
													//get_current_amount('< ?php echo $y->id ?>');
												</script>

												<td>
													<input readonly   placeholder="" class="form-control" maxlength="15" min="0" type="text"  id="current_amount<?php echo $j ?>"  value="" required="required"/>
												</td>
												<td class="hidee">
													<select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_<?php echo $j ?>" id="sub_item_id_1_<?php echo $j ?>" class="form-control select2">
														<option value="">Select</option>

														@foreach(CommonHelper::get_all_subitem() as $row)
															<option value="{{ $row->id }}">{{ ucwords($row->sub_ic) }}</option>
														@endforeach
													</select>
												</td>
												<td class="hidee">
													<input type="text" name="uom_1_<?php echo $j ?>" id="uom_1_<?php echo $j ?>" class="form-control" />
													<input type="hidden" name="uom_id_1_<?php echo $j ?>" id="uom_id_1_<?php echo $j ?>" class="form-control" />
												</td>

												<td class="hidee">
													<input onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $j ?>','<?php echo $j ?>')"  type="number" step="0.01" name="qty_1_<?php echo $j ?>" id="qty_1_<?php echo $j ?>" class="form-control qty" />
												</td>

												<td class="hidee">
													<input  onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $j ?>','<?php echo $j ?>')" type="text" step="0.01" name="rate_1_<?php echo $j ?>" id="rate_1_<?php echo $j ?>" class="form-control rate" />
												</td>
												<?php 	$tab_index++; ?>



												<td>
													<input   onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');"
															 placeholder="Debit" class="form-control requiredField d_amount_1" maxlength="15" min="0" type="text"
															 name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'1');calc_amount(this.id);dept_cost_amount(this.id,'<?php echo $j ?>')" value="" required="required"/>
												</td>

												<td>
													<input  @if($j==2)readonly @endif onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control requiredField c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'0');dept_cost_amount(this.id,'<?php echo $j ?>')" value="" required="required"/>
												</td>
												<td class="text-center"><input onclick="checked_unchekd({{$j}},'0')" type="checkbox" id="allocation<?php echo $j ?>" name="allocation<?php echo $j ?>"/> </td>
											</tr>
											<?php }?>

											<?php $wihholdings = array("income_tax_1", "income_tax_2","income_tax_3",
													"sales_tax_fbr_3",'sales_tax_srb_4','sales_tax_pra_5',''); ?>
											@for($i=1; $i<=6; $i++)
												<tr style="" class="taxes" id="tax{{$i}}">
													<input type="hidden" name="pvsDataSection_1[]" class="form-control requiredField" id="pvsDataSection_1" value="<?php echo $j?>" />
													<td>


														<select  style="width:100%;"   onchange="get_current_amount(this.id)" class="form-control select2"
																 name="account_id_1_<?php echo $j?>" id="account_id_1_<?php echo $j?>">
															<option value="">Select Account</option>
															@foreach($accounts as $key => $y)
																<option @if($j==1)  @if ($supplier_acc_id==$y->id) selected  @endif @endif value="{{ $y->id}}">{{$y->name}}</option>
															@endforeach
														</select>

													</td>
													<input type="hidden" id="current_amount_hidden<?php echo $j ?>"/>
													<script>
														//get_current_amount('< ?php echo $y->id ?>');
													</script>
													<td>
														<input readonly   placeholder="" class="form-control" maxlength="15" min="0" type="text"  id="current_amount<?php echo $j ?>"  value="" required="required"/>
													</td>

													<td class="hidee">
														<select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_<?php echo $j ?>" id="sub_item_id_1_<?php echo $j ?>" class="form-control select2">
															<option value="">Select</option>

															@foreach(CommonHelper::get_all_subitem() as $row)
																<option value="{{ $row->id }}">{{ ucwords($row->sub_ic) }}</option>
															@endforeach
														</select>
													</td>
													<td class="hidee">
														<input type="text" name="uom_1_<?php echo $j ?>" id="uom_1_<?php echo $j ?>" class="form-control" />
														<input type="hidden" name="uom_id_1_<?php echo $j ?>" id="uom_id_1_<?php echo $j ?>" class="form-control" />
													</td>

													<td class="hidee">
														<input onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $j ?>','<?php echo $j ?>')"  type="number" step="0.01" name="qty_1_<?php echo $j ?>" id="qty_1_<?php echo $j ?>" class="form-control qty" />
													</td>

													<td class="hidee">
														<input  onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $j ?>','<?php echo $j ?>')" type="text" step="0.01" name="rate_1_<?php echo $j ?>" id="rate_1_<?php echo $j ?>" class="form-control rate" />
													</td>


													<?php  ?>
													<td>
														<input  disabled  onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');"
																placeholder="Debit" class="form-control requiredField d_amount_1" maxlength="15" min="0" type="text"

																name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'1');calc_amount(this.id);dept_cost_amount(this.id,'<?php echo $j ?>')" value="0" required="required"/>
													</td>



													<script></script>
													<td>
														<input  onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control @if($j>1) @endif c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1');calculation(this.id,'0')"value="@if($j==2) {{$total_net_amount,2}} @endif"/>
													</td>
													<td class="text-center">
														<input onclick="checked_unchekd({{$j}},'income_tax_{{$i+1}}')" type="checkbox" id="allocation<?php echo $j ?>" name="allocation<?php echo $j ?>"/>
														<button type="button"  onclick="Rmove_tax('<?php echo $i ?>','<?php echo  $j ?>')" class="btn btn-xs btn-danger">Remove</button>
														<p style="color: red"  class="perc{{$j}}"></p>

													</td>
												</tr>
												<?php $j++; ?>
											@endfor
											</tbody>
										</table>

										<?php  ?>
										<table class="table table-bordered">
											<tbody>
											<tr>
												<td></td>
												<td style="width:150px;">
													<input
															type="text"
															readonly="readonly"
															id="d_t_amount_1"
															maxlength="15"
															min="0"
															name="d_t_amount_1"
															class="form-control requiredField text-right"
															value=""/>
												</td>
												<td style="width:150px;">
													<input
															type="text"
															readonly="readonly"
															id="c_t_amount_1"
															maxlength="15"
															min="0"
															name="c_t_amount_1"
															class="form-control requiredField text-right"
															value=""/>
												</td>
												<td class="diff" style="width:150px;font-size: 20px;">
													<input readonly style="color: blue;font-weight: 600" class="form-control" type="text" id="diff" value=""/>

												</td>
											</tr>

											<tr>
												<td colspan="12" style="font-size: 20px;color: navy;" id="rupees"></td>
											</tr>
											</tbody>
										</table>

										@for($x=1; $x<=2; $x++)


											@if($x==1 || $x==2)
												<div id="" class="row">

													<p style="color: #e2a0a0;text-align: center" id="paragraph{{$x}}"> </p>
													@include('Finance.dept_allocation_finance')
													@include('Finance.cost_center_allocation_finance')

												</div>
											@endif

										@endfor
										<div id="cost_data9" class="row">


										</div>

									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
									<input  type="button" class="btn btn-sm btn-primary " id="btnAddMore" onclick="addMorePvsDetailRows('1')" value="Add More PV's Rows" />
								</div>


								<tr>
									<td colspan="12" style="font-size: 20px;color: navy;" id="rupees"></td>
								</tr>

							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label class="sf-label">Description</label>
										<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
										<textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="pvsSection"></div>
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
						{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
								<!--
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

										<input type="button" class="btn btn-sm btn-primary addMorePvs" value="Add More PV's Section" />
										<!-->
					</div>
				</div>
				<?php echo Form::close();?>
			</div>
		</div>
	</div>
	</div>


	<script type="text/javascript">
		$(document).ready(function(){
			$("#adv").prop('disabled', false);
			$('.hidee').fadeOut();
			$('.ShowHide').css('display','none');
			$('.vendor').css('display','none');

		});
		function show()
		{
			if ($('#items').is(':checked'))
			{
				$('.hidee').fadeIn(500);
			}
			else
			{
				$('.hidee').fadeOut(500);
			}
		}

		function ShowHide()
		{
			if ($('#AllTaxs').is(':checked'))
			{
				$('.ShowHide').css('display','block');
			}
			else
			{
				$('.ShowHide').css('display','none');
			}
		}


		var SelectVal=[];
		var Selecttxt=[];
		var ajaxformdept=0;

		var SelectValCostCenter=[];
		var SelecttxtCostCenter=[];
		var ajaxformdeptCostCenter=0;
		$(document).ready(function() {

			$('#fbr_amount').number(true,2);
			var d = new Date();

			var weekday = new Array(7);
			weekday[0] = "Sunday";
			weekday[1] = "Monday";
			weekday[2] = "Tuesday";
			weekday[3] = "Wednesday";
			weekday[4] = "Thursday";
			weekday[5] = "Friday";
			weekday[6] = "Saturday";

			var n = weekday[d.getDay()];

			document.getElementById("pv_day").value = n;

			$('#diff').number(true,2);
			$('.taxes').fadeOut(500);
			$('#d_t_amount_1').number(true,2);
			$('#c_t_amount_1').number(true,2);
			for(i=1; i<=2; i++)
			{
				$('#d_amount_1_'+i).number(true,2);
				$('#c_amount_1_'+i).number(true,2);
				$('#current_amount'+i).number(true,2);
				$('#rate_1_'+i).number(true,2);




				$('#cost_center_department_amount_'+i+'_1').number(true,2);
				$('#department_amount_'+i+'_1').number(true,2);
				$('#total_dept'+i).number(true,2);
				$('#cost_center_total_dept'+i).number(true,2);

				$('#c_amount_1_3').number(true,0);
				$('#c_amount_1_4').number(true,0);

			}
			$('#srb_amount').number(true,2);



			var number = 12345;


			var p = 1;
			$('.addMorePvs').click(function (e){
				e.preventDefault();
				p++;
				var m = '<?php echo $_GET['m'];?>';
				$.ajax({
					url: '<?php echo url('/')?>/fmfal/makeFormBankPaymentVoucher',
					type: "GET",
					data: { id:p,m:m},
					success:function(data) {
						$('.pvsSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bankPvs_'+p+'"><a href="#" onclick="removePvsSection('+p+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
					}
				});
			});

			$(".btn-success").click(function(e){

				var pvs = new Array();
				var val;
				$("input[name='pvsSection[]']").each(function()
				{
					pvs.push($(this).val());
				});
				var _token = $("input[name='_token']").val();

				var debit=$('#d_t_amount_1').val();
				debit=debit.replace(/,/g, "");
				var credit=$('#c_t_amount_1').val();
				credit=credit.replace(/,/g, "");

				if (debit!=credit)
				{
					alert('DEBIT & CREDIT NOt EQUAL');
					return false;
				}

				var auth=dept_amount_validation();


				var auth2=cost_center_amount_validation();


				for (val of pvs) {


					if (auth==1 && auth2==1)
					{
						jqueryValidationCustom();


					}
					else

					{

						return false;
					}
					if(validate == 0){

					}else{
						return false;
					}
				}

			});
		});
		var x ='<?php echo $j-1 ?>';
		function addMorePvsDetailRows(id){
			$('#btnAddMore').prop('disabled',true);
			x++;
			var items=0;
			if ($('#items').is(':checked'))
			{
				items=1;
			}
			else
			{
				items=0;
			}
			var m = '<?php echo $_GET['m'];?>';
			$.ajax({
				url: '<?php echo url('/')?>/fmfal/addMoreBankPvsDetailRows_costing',
				type: "GET",
				data: { counter:x,id:id,m:m,items:items},
				success:function(data) {

					var response=data.split('*');



					$('.addMorePvsDetailRows_'+id+'').append(response[0]);
					$('#cost_data'+x).append(response[1]);

					$('#d_amount_1_'+x).number(true,2);
					$('#sub_item_id_1_'+x).select2();
					$('#department_'+x+'_1').select2();
					$('#cost_center_department_'+x+'_1').select2();
					$('#c_amount_1_'+x).number(true,2);
					$('#current_amount'+x).number(true,2);
					$('#account_id_1_'+x+'').select2();
					$('#account_id_1_'+x+'').focus();

					$('#cost_center_department_amount_'+x+'_1').number(true,2);
					$('#department_amount_'+x+'_1').number(true,2);
					$('#cost_center_total_dept'+x).number(true,2);
					$('#total_dept'+x).number(true,2);
					$('#btnAddMore').prop('disabled',false);

					//	$("#LeNomDeMaBaliseID").prop('id', 'LeNouveauNomDeMaBaliseID');

				}
			});
		}

		function removePvsRows(id,counter){


			var elem = document.getElementById('removePvsRows_'+id+'_'+counter+'');
			elem.parentNode.removeChild(elem);



			var elem = document.getElementById('remove_'+counter);
			elem.parentNode.removeChild(elem);



		}
		function removePvsSection(id){
			var elem = document.getElementById('bankPvs_'+id+'');
			elem.parentNode.removeChild(elem);
		}


		$('.select2').select2();

		$('#supplier_id').select2();
		//	$("#account_id_1_1").prop('tabindex',6);
		//$("#account_id_1_2").prop('tabindex',9);

	</script>

	<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
	<script>
		$(document).keydown(function(evt){
			if (evt.keyCode==77 && (evt.ctrlKey)){
				evt.preventDefault();
				addMorePvsDetailRows(1);
			}


		});



		function applicable(number)
		{
			if (number==1)
			{
				$('#supplier_div').fadeIn(500);
				$("#supplier_id").attr("onchange", "supplier_income_tax_data()");
				$('#DisplayCode').css('display','none');
				$("#supplier_id").val("0");
			}
			else if(number == 2)
			{
				$('#DisplayCode').css('display','none');
				$('#supplier_div').fadeOut(500);
				$('.payment_mod_div').html('');
			}
			else if(number == 4)
			{
				$('#DisplayCode').css('display','block');
				$('#supplier_div').fadeIn(500);
				$("#supplier_id").attr("onchange", "");
				$('.payment_mod_div').html('');
			}
			else{
				$('#DisplayCode').css('display','none');
				$('#supplier_div').fadeOut(500);
				$('.payment_mod_div').html('');
			}
		}




		function supplier_income_tax_data()
		{
			var supplier_id=$('#supplier_id').val();
			var loader_img = '<img src="/assets/img/103.gif" alt="Loading" />';
			if(supplier_id != "0") {
				$("#response").html(loader_img);

				$.ajax({
					url: '<?php echo url('/')?>/fdc/income_tax_calculation',
					type: "GET",
					data: {supplier_id: supplier_id},
					success: function (data) {


						$('#response').html(data);
						$('.nature1').focus();
						$('.nature1').number(true, 2);
						$('#income1').number(true, 2);
						$('#payment_mod1').select2();
						$('#tax_payment_section1').select2();
						var srb = $('input[name=pra_sales_tax]:checked').val();
						if (srb == 1) {
							srb_sales_tax_function(1);
						}

					}
				});
			}

		}

		function calculation_textt(number){

			var count=1;
			var filer=0;
			var nature= $('#payment_mod'+number).val();
			//  nature= String(nature);
			//  var array=nature.split(",");


			var nature_name=$("#payment_mod"+number+" :selected").text();


			var business=$('#business_type').val();
			if($('#filer3').prop("checked") == true)
			{
				filer=2;

			}

			if($('#filer4').prop("checked") == true)
			{
				filer=1;

			}


			if (filer==0)
			{
				alert('Required All Information');
				return false;
			}


			for(i=1; i<=count; i++)
				var nature= $('#payment_mod'+i).val();
			{
				if (nature == 0) {
					alert('Select Tax Nature');
					return false;
				}
			}

			$('#btn_cal').val('waiting......');
			$('#btn_cal').attr("disabled", true);




			for(i=1; i<=count; i++)
			{
				var nature= $('#payment_mod'+i).val();

				$.ajax({
					url: '<?php echo url('/')?>/fdc/tax_calculation',
					type: "GET",
					data:{nature:nature,filer:filer,business:business,i:i},

					success:function(data)
					{

						data=data.split(',');
						count=data[1];
						var
								data=data[0];

						var percent=data;
						$('#btn_cal').val('Calculate');
						$('#btn_cal').attr("disabled", false);
						var d_t_amount=$('#d_amount_1_1').val();
						$('#tax').fadeIn(500);
						$('#percent_cal'+count).html(data+' % Tax Applied');
						var cal_amount=$('.nature'+count).val();
						cal_amount=(data / 100)*cal_amount;

						if (count==1)
						{

							$('#c_amount_1_3').val(cal_amount);
							$('#dept_hidden_amount3').val(cal_amount);
							$('#dept_amount3').text(cal_amount);

							$('#cost_center_dept_amount3').text(cal_amount);
							$('#cost_center_dept_hidden_amount3').val(cal_amount);
						}
						else

						{
							$('#c_amount_1_4').val(cal_amount);
							$('#dept_hidden_amount4').val(cal_amount);
							$('#dept_amount4').text(cal_amount);

							$('#cost_center_dept_amount4').text(cal_amount);
							$('#cost_center_dept_hidden_amount4').val(cal_amount);

						}

						var  countt=count-1;
						$('#tax'+countt).fadeIn(500);
						$('.perc1').html('Income tax Withholding ' +data);

						sum(1);



					}

				});




			}
		}


		function Rmove_tax(number,id)
		{

			$('#c_amount_1_'+id).val(0);
			$('#tax'+number).fadeOut(500);
			sum(1);
			$('#allocation'+id).prop('checked', false);

			$('#dept_allocation'+id).css("display","none");
			$('#cost_center'+id).css("display","none");
			$('#paragraph'+id).html('');
			$('#cost_center_check_box'+id).prop('checked', true);
			$('#dept_check_box'+id).prop('checked', true);

			$('#dept_check_box'+id).prop('checked', true);
			$('#cost_center_check_box'+id).prop('checked', true);

			//	var diffrence= parseFloat($('#diff').val());
			//	var credit_amount= parseFloat($('#c_amount_1_2').val());
			//	var total_amount=credit_amount+diffrence;
			//	$('#c_amount_1_3').val(0);
			//	sum(1);
		}

		$(document).ready(function(){
			$('input[name="adv"]').click(function(){
				if($(this).is(":checked")){
					$('#type').val(2);
				}
				else if($(this).is(":not(:checked)")){
					$('#type').val(1);
				}
			});


			$('input[name="cheque_status"]').click(function(){
				if($(this).prop("checked") == true){
					$('.payment_nature').fadeIn(500);
					$('.bank_cash_cls').fadeOut(500);
					$('#v_type').val(1);
					$("#cheque_no_1").addClass("requiredField");
					$("#cheque_date_1").addClass("requiredField");
					$('#with_cheque').val(0);
					$("#account_id_1_2").val(113).trigger('change')
				}
				else if($(this).prop("checked") == false){
					$('.payment_nature').fadeOut(500);
					$('.bank_cash_cls').fadeIn(500);
					$('#v_type').val('');
					$("#cheque_no_1").removeClass("requiredField");
					$("#cheque_date_1").removeClass("requiredField");
					$('#with_cheque').val(1);
					$("#account_id_1_2").val(109).trigger('change')


				}
			});
		});






		function get_detail_purchase_voucher(id) {



			var number=id.replace("sub_item_id_", "");
			number=number.split('_');
			number=number[1];

			// for finance department
			var dept_name = $('#' + id + ' :selected').text();
			$('#dept_item'+number).text(number+'-'+' '+dept_name);
			$('#cost_center_dept_item'+number).text(number+'-'+' '+dept_name);

			// End
			id=$('#'+id).val();
			var m = '<?php echo $_GET['m'];?>';
			$.ajax({
				url: '<?php echo url('/')?>/pmfal/get_detail_purchase_voucher',
				type: "GET",
				data: { id:id},
				success:function(data)
				{

					data=data.split('*');
					$('#uom_1_'+number).val(data[0]);
					$('#rate_1_'+number).val(data[1]);
					$('#uom_id_1_'+number).val(data[2]);
				}
			});
		}

		function calculation_amount(id)
		{

			var   number= id.split('_');
			number=number[2];

			var rate= $('#rate_1_'+number).val();
			if (isNaN(rate)==true)
			{
				rate=0;
			}
			var qty=$('#'+'qty_1_'+number).val();
			if (isNaN(qty)==true)
			{
				qty=0;
			}



			var total_amount=parseFloat(rate * qty).toFixed(2);
			$('#d_amount_1_'+number).val(total_amount);

			sum(1);

		}
		function dept_cost_amount(id,number)
		{
			var amount= $('#'+id).val();

			if (amount>0)
			{
				$('#dept_hidden_amount'+number).val(amount);
				$('#dept_amount'+number).text(amount);

				$('#cost_center_dept_amount'+number).text(amount);
				$('#cost_center_dept_hidden_amount'+number).val(amount);

			}

		}

		function calc_amount(id)
		{

			var  number= id.replace("d_amount_1_","");
			var amount= $('#'+id).val();

			amount= parseFloat(amount);
			if (isNaN(amount)==true)
			{
				amount=0;
			}

			var qty=$('#'+'qty_1_'+number).val();
			var totalrate=parseFloat(amount / qty);
			$('#rate_1_'+number).val(totalrate.toFixed(2));


			var amount= $('#d_amount_1_'+number+'').val();
			$('#dept_hidden_amount'+number).val(amount);
			$('#dept_amount'+number).text(amount);

			$('#cost_center_dept_amount'+number).text(amount);
			$('#cost_center_dept_hidden_amount'+number).val(amount);

		}
		function checked_unchekd(number,type)
		{

			if ($('#allocation' + number).is(":checked"))
			{
				if (type==0)
				{
					var select_account= $("#account_id_1_"+number+" option:selected").text();
				}
				else
				{

					var select_account= $("#"+type+" option:selected").text();
				}


				var amount= $('#d_amount_1_'+number+'').val();
				if (amount==0 || amount=='')
				{
					amount= $('#c_amount_1_'+number+'').val();
				}
				$('#paragraph'+number).html('This Allocation For '+select_account +' And Amount is '+amount +' &darr;');

				// depratment and cost center show
				dept_allocation_amount_display(number);;
				cost_center_allocation_amount_display(number);

				// allow null true or false
				$('#cost_center_check_box'+number).prop('checked', false);
				$('#dept_check_box'+number).prop('checked', false);
			}
			else
			{
				$('#dept_allocation'+number).css("display","none");
				$('#cost_center'+number).css("display","none");
				$('#paragraph'+number).html('');
				$('#cost_center_check_box'+number).prop('checked', true);
				$('#dept_check_box'+number).prop('checked', true);
			}

		}

		function dept_amount_validation()
		{
			var auth=1;

			for (i=1; i<=x; i++)
			{

				if (i<3 || i>8)
				{


					var item_amount= $('#d_amount_1_'+i+'').val();
					if (item_amount==0 || item_amount=='')
					{
						item_amount= $('#c_amount_1_'+i+'').val();
					}

					if (typeof(item_amount) == 'undefined') {
						//	return auth;
						//return false;

					}
					else {

						item_amount = parseFloat(item_amount);
						var dept_amount = $('#total_dept' + i).val();

						item_amount = Math.round(item_amount);
						dept_amount = Math.round(dept_amount);

						if (item_amount != dept_amount) {

							if ($('#dept_check_box' + i).is(":checked")) {


							}
							else {

								var dept_name = $('#account_id_1_' + i + ' :selected').text();
								alert('Department Allocation Not Macth For ' + dept_name);
								auth = 0;

								return false;

							}
						}
					}

				}
			}
			return auth;
		}


		function cost_center_amount_validation()
		{
			var auth=1;

			for (i=1; i<=x; i++) {

				if (i < 3 || i > 8) {
					var item_amount = $('#d_amount_1_' + i + '').val();
					if (item_amount == 0 || item_amount == '') {
						item_amount = $('#c_amount_1_' + i + '').val();
					}

					if (typeof(item_amount) == 'undefined') {
						//	return auth;
						//	return false;

					}

					else
					{
						item_amount = parseFloat(item_amount);
						var cost_center = $('#cost_center_total_dept' + i).val();


						item_amount = Math.round(item_amount);
						cost_center = Math.round(cost_center);

						if (item_amount != cost_center) {

							if ($('#cost_center_check_box' + i).is(":checked")) {


							}
							else {
								var dept_name = $('#account_id_1_' + i + ' :selected').text();

								alert('Cost Center Allocation Not Macth For ' + dept_name);

								auth = 0;
								return false;

							}
						}
					}
				}
			}
			return auth;
		}


		function change_day()
		{

			var date=$('#pv_date_1').val();
			$('#cheque_date_1').val(date);
			var d = new Date(date);

			var weekday = new Array(7);
			weekday[0] = "Sunday";
			weekday[1] = "Monday";
			weekday[2] = "Tuesday";
			weekday[3] = "Wednesday";
			weekday[4] = "Thursday";
			weekday[5] = "Friday";
			weekday[6] = "Saturday";

			var n = weekday[d.getDay()];

			document.getElementById("pv_day").value = n;
		}

		function active_status_check(type)
		{
			if (type==1)
			{

				$(".active_status").fadeIn(500);
				$('.checkbox1').prop("checked", false);
				$(".exemt").fadeIn(500);
			}
			else
			{

				$(".active_status").fadeOut(500);
				$('.checkbox1').prop("checked", false);
				$(".exemt").fadeOut(500);
			}



		}







		var add_more_count=1;
		function add_more()
		{

			add_more_count++;
			$('.append').append('<div class="row"><div id="payment_mod_div" class="col-lg-3 col-md-3 col-sm-3 col-xs-12 payment_mod_div">' +
					'<select  style="width: 100%" onchange="" id="payment_mod'+add_more_count+'"  name="nature'+add_more_count+'" class="form-control select2">'+
					'<option  value="0" style="color: red">SELECT</option><option value="1">ALL GOODS</option><option value="2">IN CASE OF RICE,COTTON,SEED,EDIBLE OIL</option>'+
					'<option value="3">DISTRIBUTORS OF FAST MOVING CONSUMER GOODS</option>'+
					'<option value="4">SERVICES</option>'+
					'<option value="5">TRANSPORT SERVICES</option>'+
					'<option value="6">ELECTRONIC AND PRINT MEDIA FOR ADVERTISING</option>'+
					'<option value="7">CONTRACTS</option>'+
					'<option value="8">SPORT PERSON</option>'+
					'<option value="9">Services of Stitching , Dyeing , Printing , Embroidery etc</option>'+
					'</select></div>'+
					'<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 payment_mod_div">'+
					'<select name="tax_payment_section'+add_more_count+'" id="tax_payment_section'+add_more_count+'" class="form-control select2">'+
					'<option value="">Select Tax Payment Section</option>'+
					<?php foreach($taxSection as $Filter):?>
                    '<option value="<?php echo $Filter['id']?>"><?php echo $Filter['tax_payment_section']?></option>'+
					<?php endforeach;?>
                    '</select>'+
					'</div>'+
					'<div id="" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">'+
					'<input type="text" class="form-control" value="" name="income'+add_more_count+'" id="income'+add_more_count+'">'+
					'</div>'+
					'<div style=""  class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div"><p style="color: red" id="percent_cal'+add_more_count+'" class=""> </p></div>'+
					'<div style="" id="submit" class="col-lg-2 col-md-2 col-sm-2 col-xs-12 payment_mod_div">'+
					'<input  style="float:left" id="btn_cal'+add_more_count+'" type="button" onclick="calculation_text('+add_more_count+')" class="btn-primary" value="Calculate"/>'+
					'<input type="hidden" name="income_tax_id'+add_more_count+'" id="income_tax_id'+add_more_count+'"></div>'

			); //add input box
			$('#payment_mod'+add_more_count).select2();
			$('#tax_payment_section'+add_more_count).select2();
			$('#income'+add_more_count).number(true,2);

		}

		function calculation_text(number){


			var filer=0;
			var nature= $('#payment_mod'+number).val();
			//  nature= String(nature);
			//  var array=nature.split(",");


			var nature_name=$("#payment_mod"+number+" :selected").text();


			var business=$('#business_type').val();
			if($('#filer3').prop("checked") == true)
			{
				filer=2;

			}

			if($('#filer4').prop("checked") == true)
			{
				filer=1;

			}


			if (filer==0)
			{
				alert('Required All Information');
				return false;
			}



			var nature= $('#payment_mod'+number).val();

			if (nature == 0)
			{
				alert('Select Tax Nature');
				return false;
			}


			$('#btn_cal'+number).val('waiting......');
			$('#btn_cal'+number).attr("disabled", true);


			var nature= $('#payment_mod'+number).val();



			$.ajax({
				url: '<?php echo url('/')?>/fdc/tax_calculation',
				type: "GET",
				data:{nature:nature,filer:filer,business:business,i:i},

				success:function(data)
				{

					var response=data.split(',');
					data=data.split(',');
					count=data[1];
					var income_tax_id =data[3];

					$('#income_tax_id'+number).val(income_tax_id);
					//	alert(data[3]);
					data=data[0];



					var percent=data;
					$('#btn_cal'+number).val('Calculate');
					$('#btn_cal'+number).attr("disabled", false);
					var d_t_amount=$('#d_amount_1_1').val();
					$('#tax').fadeIn(500);

					var cal_amount=$('#income'+number).val();

					cal_amount=(data / 100)*cal_amount;


					var credit_amount=number+2;
					$('#c_amount_1_'+credit_amount).val(cal_amount);

					var deduct_amount=parseFloat(cal_amount);
					deduct_amount=Math.round(deduct_amount);
					$('#percent_cal'+number).html(data+' % Tax Applied ( '+deduct_amount+' )');
					$('.perc'+credit_amount).text('Income Tax Withholding '+data+' %');


					$('#tax'+number).fadeIn(500);
					if (response[2]!=0)
					{

						$('#account_id_1_'+credit_amount).val(response[2]).trigger('change');
					}

					sum(1);
					dept_cost_amount('c_amount_1_'+number,'2');
					credit_amount_mines();





				}

			});

		}

		$('#FormOpen').on('change', function() {
			var FormName = this.value;
			FormName=FormName.trim();
			if (FormName=='Vendor')
			{
				showDetailModelOneParamerter('pdc/createSupplierFormAjax/pvs')
			}
			else if(FormName == "Account")
			{
				showDetailModelOneParamerter('fdc/createAccountFormAjax')
			}
			//alert(this.value);
		});

		function advance()
		{
			if ($("#adv").is(":checked"))
			{
				$('.vendor').css('display','block');

				$('#vendor_id').focus();
			}
			else
			{
				$('.vendor').css('display','none');
				$('.refrence_nature').css('display','none');refrence
				$("#vendor_id").val(0).trigger('change');
				$("#refrence").val(0);
			}
		}

		function purchase_refrence_event()
		{

			var vendor_id=$('#vendor_id').val();

			if (vendor_id!=0)
			{
				$('.refrence_nature').css('display','block');
			}
			else
			{
				$('.refrence_nature').css('display','none');
				$("#refrence").val(0);
			}
		}
		function get_refrence()
		{

			var vendor_id=$('#vendor_id').val();
			var refrence=$("#refrence").val();
			if (refrence==0)
			{
				$("#breakup_data").html('');
				return false;
			}
			var loader_img = '<img src="/assets/img/103.gif" alt="Loading" />';
			$("#get_refrence").html(loader_img);
			$.ajax({
				url: '<?php echo url('/')?>/pmfal/new_refrence',
				type: "GET",
				data:{refrence:refrence,vendor_id:vendor_id},

				success:function(data)
				{
					$("#get_refrence").html('');
					$("#get_refrence").html(data);
					if (refrence==1)
					{
						$("#breakup_data").html('');

					}
				}

			});
		}


		function get_ledger_refrence_wise()
		{

			var breakup_id=$('#purchase_refrence').val();
			var supplier_id=$('#vendor_id').val();
			breakup_id=breakup_id.split(',');
			breakup_id=breakup_id[1];

			$.ajax({
				url: '<?php echo url('/')?>/pdc/get_ledger_refrence_wise',
				type: "GET",
				data: { breakup_id:breakup_id,supplier_id:supplier_id},
				success:function(data)
				{
					$('#breakup_data').html(data);


				}
			});
		}


		function bank_cash()
		{

			if ($("#bank_radio").prop("checked"))
			{


				$("#account_id_1_2").val(113).trigger('change');

			}
			else
			{

				$("#account_id_1_2").val(109).trigger('change');
			}
		}

		$('#bankPaymentVoucherForm').on('keyup keypress', function(e) {
			var keyCode = e.keyCode || e.which;
			if (keyCode === 13) {
				e.preventDefault();
				return false;
			}
		});
		
	</script>
@endsection