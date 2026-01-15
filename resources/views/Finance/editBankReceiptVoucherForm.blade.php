<?php

$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
	$m = $_GET['m'];
}else{
	$m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
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
									<span class="subHeadingLabelClass">Edit Bank Receipt Voucher Form</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<div class="row">
								<?php echo Form::open(array('url' => 'fad/editBankReceiptVoucherForm?m='.$m.'','id'=>'bankReceiptVoucherForm'));?>
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
													<input type="hidden" name="rvs_id" class="form-control requiredField" id="rvs_id" value="{{ $rvs['id'] }}" />
												</div>
											</div>
											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

													<?php
													$indent_Commission = FinanceHelper::indent_Commission($rvs['id']);
													?>


													<div class="row">

														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">Indent Commission</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="checkbox" class="" onclick="check_data()" name="indent" id="indent" value="1" {{$indent_Commission==1?'checked="checked"':''}} />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">RV NO.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input readonly type="text" class="form-control requiredField"  name="rv_no" id="rv_no" value="{{ $rvs['rv_no'] }}" />
															</div>

															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">RV Date.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="rv_date_1" id="rv_date_1" value="{{ $rvs['rv_date'] }}" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
																<label class="sf-label">Ref / Bill No.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no_1" id="slip_no_1" value="{{ $rvs['slip_no'] }}" />
															</div>

															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 cheque_proce">
																<label class="sf-label">Cheque No.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="text" class="form-control" placeholder="Cheque No" name="cheque_no_1" id="cheque_no_1" value="{{ $rvs['cheque_no'] }}" />
															</div>
															<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 cheque_proce">
																<label class="sf-label">Cheque Date.</label>
																<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
																<input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="cheque_date_1" id="cheque_date_1" value="{{ $rvs['cheque_date'] }}" />
															</div>

														</div>
													</div>

													<div class="row">
														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createCurrencyTypeForm')" class="">Currency</a></label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<select name="curren" id="curren" class="form-control select2 requiredField">
																<option @if($rvs->currency_id==0) selected @endif  value="0"> PKR</option>
																@foreach(CommonHelper::get_all_currency() as $row)
																<option @if($rvs->currency_id==$row->id) selected @endif value="{{$row->id}}">{{$row->curreny}}</option>
																@endforeach;
															</select>
														</div>

														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label"> Foreign Currency Amount</label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<input type="text" onkeyup="indent_cal()" class="form-control" placeholder="" name="foreign_currency" id="exchange_amunt" value="{{ $rvs->foreign_currency }}" />
														</div>

														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label">Exchange Rate</label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<input type="text" onkeyup="indent_cal()" class="form-control" placeholder="" name="exchange_rate" id="exchange_rate" value="{{ $rvs->exchange_rate }}" />
														</div>

														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<label class="sf-label">Amount In PKR</label>
															<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
															<input  type="text" onkeyup="get_exchange_rate()"  class="form-control" placeholder="" name="total_amount" id="total_amount" value="{{ ($rvs->foreign_currency *$rvs->exchange_rate) }}" />
														</div>

														<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hidee">
															<input style="margin-top: 30px"  id="" type="button"  onclick="inden_commision()" class="btn-primary" value="Calculate"/>
														</div>

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

																		<?php $j = 1; $debit_total=0; $credit_total=0;

																		?>

																		@foreach($rvs_data as $rvs_data_val)

																			<input type="hidden" name="rvsDataSection_1[]" class="form-control requiredField" id="rvsDataSection_1" value="<?php echo $j?>" />
																			<tr id="removeRvsRows_1_{{$j}}">
																				<td>
																					<select class="form-control requiredField select2" name="account_id_1_<?php echo $j?>" id="account_id_1_<?php echo $j?>">
																						<option  value="0">Select Account</option>
																						@foreach($accounts as $key => $y)
																							<option value="{{ $y->id}}" {{$y->id==$rvs_data_val['acc_id']?'selected':''}} >{{ $y->code .' ---- '. $y->name}}</option>
																						@endforeach
																					</select>
																				</td>
																				<td><input class="form-control" type="text" readonly/> </td>
																				<td>
																					@if($rvs_data_val['debit_credit']==1)
																						<?php $debit_total += $rvs_data_val['amount']; ?>
																					@endif

																					<input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control requiredField d_amount_1" maxlength="15" min="0" type="text" name="d_amount_1_<?php echo $j ?>" id="d_amount_1_<?php echo $j ?>" onkeyup="sum('1')" value="{{$rvs_data_val['debit_credit']==1?$rvs_data_val['amount']:''}}" required="required"/>
																				</td>
																				<td>
																					@if($rvs_data_val['debit_credit']==0)
																						<?php $credit_total += $rvs_data_val['amount']; ?>
																					@endif
																					<input onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control requiredField c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $j ?>" id="c_amount_1_<?php echo $j ?>" onkeyup="sum('1')" value="{{$rvs_data_val['debit_credit']==0?$rvs_data_val['amount']:''}}" required="required"/>
																				</td>
																				<td style="color: red" id="note{{$j}}" class="text-center">---</td>
																			</tr>
																			<?php $j++; ?>
																		@endforeach


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
																						value="{{$debit_total}}" />
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
																						value="{{$credit_total}}"/>
																			</td>
																			<td class="diff" style="width:150px;font-size: 20px;"></td>
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
														<textarea name="description_1" id="description_1" style="resize:none;" class="form-control requiredField">{{ $rvs_data_val['description'] }}</textarea>
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
									</div>
								</div>
								<?php echo Form::close();	?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>

		$(document).ready(function() {


			if ($('#indent').is(':checked'))
			{
				check_data();

			}
			else
			{
				$('.hidee').fadeOut();
			}


			//$('#diff').number(true,2);
			for(i=1; i<=x; i++)
			{
//				if ($('#d_amount_1_'+i).val()>0)
//				{
//					$('#c_amount_1_'+i).removeAttr('required','required');
//					$('#c_amount_1_'+i).removeClass("requiredField");
//				}
//				else
//				{
//					$('#d_amount_1_'+i).removeAttr('required','required');
//					$('#d_amount_1_'+i).removeClass("requiredField");
//				}
				$('#d_amount_1_'+i).number(true,2);
				$('#c_amount_1_'+i).number(true,2);

				if ($('#d_amount_1_'+i).val()>0)
				{
					$('#c_amount_1_'+i).removeAttr('required','required');
					$('#c_amount_1_'+i).removeClass("requiredField");
				}
				else
				{
					$('#d_amount_1_'+i).removeAttr('required','required');
					$('#d_amount_1_'+i).removeClass("requiredField");
				}

			}

			$('#exchange_amunt').number(true,2);
			$('#exchange_rate').number(true,2);

			$('#d_t_amount_1').number(true,2);
			$('#c_t_amount_1').number(true,2);

			//$('.hidee').fadeOut();
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

		var total='<?php echo $rvs->foreign_currency *$rvs->exchange_rate ?>';
		var withholding=0;

		var x = '<?php echo $j-1; ?>';
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

		function check_data()
		{
			if($("#indent").is(':checked')) {
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

		}
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

			$("#account_id_1_1").val(113).trigger('change');
			$("#account_id_1_2").val(276).trigger('change');
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
					$("#account_id_1_3").val(278).trigger('change');
					$('#d_amount_1_3').prop('title', 'Withholding Tax @5%');
				}

				if (x==4)
				{
					$('#c_amount_1_4').focus();
					$('#c_amount_1_4').val(withholding);
					$("#account_id_1_4").val(113).trigger('change');
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
	</script>

	<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
