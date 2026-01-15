<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
	$m = $_GET['m'];
}else{
	$m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
$rv_no=CommonHelper::uniqe_no_for_rvs(date('y'),date('m'),1);
?>
@extends('layouts.default')

@section('content')
	@include('select2')
	@include('number_formate')
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
						@include('Finance.'.$accType.'financeMenu')
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="subHeadingLabelClass">Create  Receipt Voucher Form</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<div class="row">
								<?php echo Form::open(array('url' => 'fad/addBankReceiptVoucherDetail?m='.$m.'','id'=>'bankReceiptVoucherForm'));?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<!--
                            <input type="hidden" name="pageType" value="< ?php echo $_GET['pageType']?>">
                            <input type="hidden" name="parentCode" value="< ?php echo $_GET['parentCode']?>">
                            <!-->
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<input type="hidden" name="rvsSection[]" class="form-control requiredField" id="rvsSection" value="1" />
												</div>
											</div>

											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="row">
														<input type="hidden" class="supplier_val" value=""/>
														<div class="col-lg-6 col-md-6 col-sm-6">
															<div class="panel panel-default">
																<div class="panel-heading" style="padding: 0px 15px; font-size: 20px;"><strong>Referencing</strong></div>
																<div class="panel-body" style="border: solid 1px #ddd;">
																	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
																		<label class="sf-label">Vendor  <span class="rflabelsteric"><strong>*</strong></span></label>
																		<select onchange="" class="form-control select2" style="font-size: 13px;" name="supplier_id"  id="supplier_id">
																			<option value="">Select</option>
																			@foreach(CommonHelper::get_all_supplier() as $row)
																				<option value="{{$row->id}}">{{$row->name}}</option>
																			@endforeach
																		</select>
																		<input type="hidden" name="adv_amount" id="adv_amount"/>
																	</div>
																	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
																		<label class="sf-label">Advance / Opening  <span class="rflabelsteric"><strong>*</strong></span></label>

																		<select onchange="get_ledger_refrence_wise()" class="form-control" style="font-size: 13px" name="breakup_id"  id="breakup_id">
																			<option value="0">Select</option>
																		</select>
																		<input type="hidden" name="adv_amount" id="adv_amount"/>
																	</div>

																</div>
															</div>
														</div>
														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
															<span id="breakup_data"></span>

														</div>

													</div></div></div>
											<div class="lineHeight">&nbsp;</div>

											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


													<div class="row">


														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">


															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">Indent Commission</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input   type="checkbox" class=""  name="indent" id="indent" value="1" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">RV NO.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input readonly type="text" class="form-control requiredField"  name="rv_no" id="rv_no" value="<?php echo strtoupper($rv_no) ?>" />
															</div>

															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">RV Date.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input autofocus type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="rv_date_1" id="rv_date_1" value="<?php echo date('Y-m-d') ?>" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">Ref / Bill No.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no_1" id="slip_no_1" value="" />
															</div>

															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 cheque_proce">
																<label class="sf-label">Cheque No.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="text" class="form-control requiredField" placeholder="Cheque No" name="cheque_no_1" id="cheque_no_1" value="" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 cheque_proce">
																<label class="sf-label">Cheque Date.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="cheque_date_1" id="cheque_date_1" value="<?php echo date('Y-m-d') ?>" />
															</div>

														</div>
													</div>

													<div class="row">



														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')" class="">Currency</a></label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<select name="curren" id="curren" class="form-control select2 requiredField">


																@foreach(CommonHelper::get_all_currency() as $row)
																	<option value="{{$row->id}}">{{$row->curreny}}</option>
																@endforeach;
																	<option value="0"> PKR</option>
															</select>
														</div>



														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label"> Foreign Currency Amount</label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<input type="text" onkeyup="indent_cal()" class="form-control" placeholder="" name="exchange_amunt" id="exchange_amunt" value="" />

														</div>
														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label">Exchange Rate</label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<input type="text"  onkeyup="indent_cal()" class="form-control" placeholder="" name="exchange_rate" id="exchange_rate" value="" />

														</div>

														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label">Amount In PKR</label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<input  type="text" onkeyup="get_exchange_rate()" class="form-control" placeholder="" name="total_amount" id="total_amount" value="" />

														</div>
														<div class="col-lg-1 col-md-1 col-sm-1 col-xs-12 hidee">
															<label class="sf-label">Tax</label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<h5 id="tax_side"></h5>
														</div>

														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<input style="margin-top: 30px"  id="" type="button" onclick="inden_commision()" class="btn-primary" value="Generate Entries"/>

														</div>


													</div>



												</div>
												<div class="lineHeight">&nbsp;</div>
												<div class="well">
													<div class="panel">
														<div class="panel-body">
															<div class="row">
																<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
																	<div class="table-responsive">
																		<table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
																			<thead>
																			<tr>
																				<th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

																				</th>
																				<th class="text-center" style="width:150px;">Current Bal.<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
																				<th class="text-center" style="width:180px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
																				<th class="text-center" style="width:180px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>

																				<th class="text-center" style="width:150px;">Action<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
																			</tr>
																			</thead>
																			<tbody class="addMoreRvsDetailRows_1" id="addMoreRvsDetailRows_1">
																			<?php for($j = 1 ; $j <= 2 ; $j++){?>
																			<input type="hidden" name="rvsDataSection_1[]" class="form-control requiredField" id="rvsDataSection_1" value="<?php echo $j?>" />
																			<tr>
																				<td>
																					<select class="form-control requiredField select2" name="account_id_1_<?php echo $j?>" id="account_id_1_<?php echo $j?>">
																						@foreach(CommonHelper::get_accounts_for_jvs() as $key => $y)
																							<option value="{{ $y->id.'~'.$y->supp_id.'~'.$y->supplier_id}}">{{$y->name}}</option>
																						@endforeach
																					</select>
																				</td>
																				<td><input class="form-control" type="text" readonly/> </td>
																				<td>
																					<input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control requiredField d_amount_1" maxlength="15" min="0" type="text" name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1')" value="" required="required"/>
																				</td>
																				<td>
																					<input onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control requiredField c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1')" value="" required="required"/>
																				</td>
																				<td style="color: red" id="note{{$j}}" class="text-center">---</td>
																			</tr>
																			<?php }?>
																			</tbody>
																		</table>
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
																			</tbody>
																		</table>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>



												<div class="row">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
														<input type="button" class="btn btn-sm btn-primary" onclick="addMoreRvsDetailRows('1')" value="Add More RV's Rows" />
													</div>
												</div>

												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<div class="row">
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<label class="sf-label">Description</label>
															<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
															<textarea name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="rvsSection"></div>
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
											{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
											<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
											<input type="button" class="btn btn-sm btn-primary addMoreRvs" value="Add More RV's Section" />
										</div>
									</div>
									<?php echo Form::close();?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<script>

			$(document).ready(function() {
				$('#diff').number(true,2);
				for(i=1; i<=2; i++)
				{
					$('#d_amount_1_'+i).number(true,2);
					$('#c_amount_1_'+i).number(true,2);

				}

				$('#exchange_amunt').number(true,2);
				$('#exchange_rate').number(true,2);

				$('#d_t_amount_1').number(true,2);
				$('#c_t_amount_1').number(true,2);

				$('.hidee').fadeOut();
				$('#total_amount').number(true,2);
				var r = 1;
				$('.addMoreRvs').click(function (e){
					e.preventDefault();
					r++;
					var m = '<?php echo $_GET['m'];?>';
					$.ajax({
						url: '<?php echo url('/')?>/fmfal/makeFormBankReceiptVoucher',
						type: "GET",
						data: { id:r,m:m},
						success:function(data) {
							$('.rvsSection').append('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="bankRvs_'+r+'"><a href="#" onclick="removeRvsSection('+r+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
						}
					});
				});

			});

			$(".btn-success").click(function(e){
				CheckDebitCredit();
				var rvs = new Array();
				var val;
				$("input[name='rvsSection[]']").each(function(){
					rvs.push($(this).val());
				});
				var _token = $("input[name='_token']").val();
				for (val of rvs) {
					jqueryValidationCustom();
					if(validate == 0){
						//alert(response);
					}else{
						return false;
					}
				}

			});

			var total=0;
			var withholding=0;
			var x = 2;
			function addMoreRvsDetailRows(id){

				x++;
				var m = '<?php echo $_GET['m'];?>';
				$.ajax({
					url: '<?php echo url('/')?>/fmfal/addMoreBankRvsDetailRows',
					type: "GET",
					data: { counter:x,id:id,m:m},
					success:function(data) {

						if ($('#indent').is(':checked') && x > 4)
						{

							x--;
							$('#d_amount_1_3').val(withholding);
							$('#c_amount_1_4').val(withholding);
							return false;
						}
						$('.addMoreRvsDetailRows_'+id+'').append(data);
						$('#account_id_1_'+x+'').select2();

						if ($('#indent').is(':checked') &&  x==3 || x==4)
						{
							$('#d_amount_1_'+x).number(true,0);
							$('#c_amount_1_'+x).number(true,0);
							$('#description_1').focus();
						}
						else
						{
							$('#account_id_1_'+x+'').focus();
							$('#d_amount_1_'+x).number(true,2);
							$('#c_amount_1_'+x).number(true,2);
						}

						credit_amount_pass();

					}
				});
			}

			function removeRvsRows(id,counter){
				var elem = document.getElementById('removeRvsRows_'+id+'_'+counter+'');
				elem.parentNode.removeChild(elem);
				x--;
				sum(1);
			}
			function removeRvsSection(id){
				var elem = document.getElementById('bankRvs_'+id+'');
				elem.parentNode.removeChild(elem);
			}

			$('#indent').change(function()
			{

				if($(this).is(':checked'))
				{
					$('.hidee').fadeIn(1000);

					$('.cheque_proce').fadeOut();
					$("#cheque_no_1").removeClass("requiredField");
				}
				else
				{

					$('.hidee').fadeOut();
					$('#d_amount_1_1').val('');
					$('#d_amount_1_1').focus();
					$('#c_amount_1_2').val('');
					$('#d_amount_1_2').focus();
					$('#note1').text('');
					$('#note2').text('');
					$('.cheque_proce').fadeIn();
					$("#cheque_no_1").addClass("requiredField");

					for (i=3; i<=x; i++)
					{

						removeRvsRows(1,i);

					}
					removeRvsRows(1,4);
				}

			});
		</script>


		<script type="text/javascript">

			$('.select2').select2();

			function inden_commision()
			{
			//	var currency_rate=parseFloat($('#exchange_rate').val());

			//	var exchange_rate=parseFloat($('#exchange_amunt').val());
			//	total=currency_rate * exchange_rate;
				//$('#total_amount').val(total);

				 withholding=(total /100) * 5;

				var bank_amount=total-withholding;
				$('#d_amount_1_1').val(total);
				$('#d_amount_1_1').focus();
				$('#c_amount_1_2').val(total);
				$('#c_amount_1_2').focus();

				$('#account_id_1_1').prop('title', 'Bank Alhaib');
			//	$('#note1').text('For Bank Amount');
			//	$('#note2').text('For Withholding');

				$("#account_id_1_1").val('113~~').trigger('change');
				$("#account_id_1_2").val('276~~').trigger('change');
				addMoreRvsDetailRows(1);



			}

			function credit_amount_pass()
			{

				if($('#indent').is(':checked') &&  x==3 || x==4)

				{

					if (x==3)
					{
						var withholdings = Math.round(withholding);
						$("#tax_side").text('('+withholdings+')');
						$("#tax_side").css("color", "red");
						//alert(withholding+"sdad");
						$('#d_amount_1_3').focus();
						$('#d_amount_1_3').val(withholding);
						$("#account_id_1_3").val('278~~').trigger('change');
						$('#d_amount_1_3').prop('title', 'Withholding Tax @5%');
					}

					if (x==4)
					{
						$('#c_amount_1_4').focus();
						$('#c_amount_1_4').val(withholding);
						$("#account_id_1_4").val('113~~').trigger('change');
					}

					if (x==3)
					{
						addMoreRvsDetailRows(1);
					}
					sum(1);
				}


			}

			function indent_cal()
			{
				var currency_rate=parseFloat($('#exchange_rate').val());

				var exchange_rate=parseFloat($('#exchange_amunt').val());
				total=currency_rate * exchange_rate;
				$('#total_amount').val(total);
				 total=$('#total_amount').val();
			}

			function get_exchange_rate()
			{
				var amount=parseFloat($('#total_amount').val());
				var exchange_rate=parseFloat($('#exchange_amunt').val());
				var total_exchan=(amount / exchange_rate);
				$('#exchange_rate').val(total_exchan);
				total=amount;
			}

		$("#supplier_id").change(function(){
		var SupplierId = $('#supplier_id').val();

		if(SupplierId !="")
		{
		$.ajax({
		url: '<?php echo url('/')?>/pmfal/get_refer',
		type: "GET",
		data: { SupplierId:SupplierId},
		success:function(data)
		{

		$('#breakup_id').html(data);
		}
		});
		}
		else{
		$('#breakup_id').html("");
		$('#breakup_id').html("<option value=''>Select</option>");
		}

		});
		function get_ledger_refrence_wise()
		{

		var breakup_id=$('#breakup_id').val();
		var supplier_id=$('#supplier_id').val();
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
		</script>
		<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection