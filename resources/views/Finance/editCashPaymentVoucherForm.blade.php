<?php


$str = DB::Connection('mysql2')->selectOne("select count(id)id from pvs where status=1 and pv_date='".date('Y-m-d')."'
			and voucherType=1
			")->id;
$pv_no = 'cpv'.($str+1);
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
	<?php     ?>
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">

		</div>
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="well">
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<span class="subHeadingLabelClass">Create Cash Payment Voucher Form</span>
					</div>
				</div>
				<div class="lineHeight">&nbsp;</div>
				<div class="row">
					<?php echo Form::open(array('url' => 'fad/editCashPaymentVoucherDetail?m='.$m.'','id'=>'cashPaymentVoucherForm'));?>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">


                {{--<input type="hidden" name="pageType" value="< ?php echo $_GET['pageType']?>">--}}
                {{--<input type="hidden" name="parentCode" value="< ?php echo $_GET['parentCode']?>">--}}

					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="hidden" name="pvsSection[]" class="form-control requiredField" id="pvsSection" value="1" />
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="row">

											<input type="hidden" name="edit_id" value="{{$id}}">
											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Advance</label>
												<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
												<input @if($cpvs->payment_type==2) checked @endif   type="checkbox" class=""  name="adv" id="adv" value="2" />
											</div>
											<input type="hidden" name="type" id="type" value="1" />
											<input  checked  type="hidden" class="" value="1" name="payment_mod"  />

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">CPV No</label>
												<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
												<input    readonly type="text" class="form-control requiredField" placeholder="Slip No"
														  name="voucher_no" id="" value="{{strtoupper($cpvs->pv_no)}}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">CPV Date.</label>
												<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
												<input onblur="change_day()" onchange="change_day()"  type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="pv_date_1" id="pv_date_1" value="<?php echo  $cpvs->pv_date ?>" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">CPV Day.</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input readonly type="text" class="form-control requiredField"  name="pv_day" id="pv_day"  />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Ref / Bill No.</label>
												<span style="font-size:17px !important; color:#F5F5F5 !important;"><strong>*</strong></span>
												<input autofocus  type="text" class="form-control requiredField" placeholder="Slip No" name="slip_no_1" id="slip_no_1" value="{{$cpvs->slip_no}}" />
											</div>

											<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
												<label class="sf-label">Bill Date.</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="date" class="form-control requiredField"  name="bill_date" id="bill_date" value="<?php echo $cpvs->bill_date ?>" />
											</div>





										</div>
									</div>



									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<p style="color: red" id="percent_cal1" class=""> </p>
									</div>


								</div>
								<div class="lineHeight">&nbsp;</div>

								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table id="buildyourform" class="table table-bordered  sf-table-th sf-table-form-padding">
												<thead>
												<tr>
													<th style="width: 250px;" class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>
													<th class="text-center" style="width:150px;">Current Bal<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
													<th style="width: 200px;" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax')" class="">Sub Item</a>
													<th style="width: 100px" class="text-center">UOM <span class="rflabelsteric"><strong>*</strong></span></th>
													<th style="width: 150px;" class="text-center">Qty. <span class="rflabelsteric"><strong>*</strong></span></th>
													<th style="width: 150px;" class="text-center">Rate. <span class="rflabelsteric"><strong>*</strong></span></th>

													</th>

													<th class="text-center" style="width:150px;">Debit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
													<th class="text-center" style="width:150px;">Credit<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
													<th class="text-center" style="width:150px;">Allocation<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span></th>
												</tr>
												</thead>
												<tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
												<?php $counter=1;
												$total_debit=0;
												$total_credit=0;
												?>


												@foreach($cpv_data as $data)

													<input type="hidden" name="pvsDataSection_1[]" class="form-control requiredField" id="pvsDataSection_1" value="<?php echo $counter?>" />
													<tr>
														<td>
															<select   onchange="get_current_amount(this.id)" style="" class="form-control requiredField select2" name="account_id_1_<?php echo $counter?>" id="account_id_1_<?php echo $counter?>">
																<option value="">Select Account</option>
																@foreach($accounts as $key => $y)
																	<option @if($data->acc_id==$y->id) selected @endif  value="{{ $y->id}}">{{$y->name}}</option>
																@endforeach
															</select>
														</td>
														<input type="hidden" id="current_amount_hidden<?php echo $counter ?>"/>
														<script>
															//get_current_amount('< ?php echo $y->id ?>');
														</script>
														<td>
															<input readonly   placeholder="" class="form-control" maxlength="15" min="0" type="text"  id="current_amount<?php echo $counter ?>"  value="" required="required"/>
														</td>
														<td>
															<select   onchange="get_detail_purchase_voucher(this.id)" style="width: 200px;" name="sub_item_id_1_<?php echo $counter ?>" id="sub_item_id_1_<?php echo $counter ?>" class="form-control select2">
																<option value="">Select</option>

																@foreach(CommonHelper::get_all_subitem() as $row)
																	<option @if($data->sub_item==$row->id) selected @endif value="{{ $row->id }}">{{ ucwords($row->sub_ic) }}</option>
																@endforeach
															</select>
														</td>

														<?php
														$uom_name='';
														if (!empty($data->sub_item)):
														$sub_item_data=CommonHelper::get_subitem_detail($data->sub_item);
														$sub_item_data=explode(',',$sub_item_data);
														$uom_name=CommonHelper::get_uom_name($sub_item_data[0]);
														endif;
														?>
														<td>
															<input type="text" value="{{$uom_name}}" readonly name="uom_1_<?php echo $counter ?>" id="uom_1_<?php echo $counter ?>" class="form-control" />
															<input type="hidden"  name="uom_id_1_<?php echo $counter ?>" id="uom_id_1_<?php echo $counter?>" class="form-control" />
														</td>

														<td>
															<input value="{{$data->qty}}" onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $counter ?>','<?php echo $counter ?>')"  type="number" step="0.01" name="qty_1_<?php echo $counter ?>" id="qty_1_<?php echo $counter ?>" class="form-control qty" />
														</td>

														<td>
															<input value="{{$data->rate}}"  onkeyup="calculation_amount(this.id);dept_cost_amount('d_amount_1_<?php echo $counter ?>','<?php echo $counter ?>')" type="text" step="0.01" name="rate_1_<?php echo $counter ?>" id="rate_1_<?php echo $counter ?>" class="form-control rate" />
														</td>
														<?php 	 ?>



														<td>
															<input  @if($data->debit_credit==1) <?php $total_debit+=$data->amount ?> value="{{$data->amount}}" @else readonly @endif    onfocus="mainDisable('c_amount_1_<?php echo $counter ?>','d_amount_1_<?php echo $counter ?>');"
																	placeholder="Debit" class="form-control requiredField d_amount_1" maxlength="15" min="0" type="text"
																	name="d_amount_1_<?php echo $counter ?>" id="d_amount_1_<?php echo $counter ?>" onkeyup="sum('1');calculation(this.id,'1');calc_amount(this.id);dept_cost_amount(this.id,'<?php echo $counter ?>')"  required="required"/>
														</td>

														<td>
															<input   @if($data->debit_credit==0) <?php $total_credit+=$data->amount ?> value="{{$data->amount}}"   @else  readonly @endif  onfocus="mainDisable('d_amount_1_<?php echo $counter ?>','c_amount_1_<?php echo $counter ?>');" placeholder="Credit" class="form-control requiredField c_amount_1" maxlength="15" min="0" type="text" name="c_amount_1_<?php echo $counter ?>" id="c_amount_1_<?php echo $counter ?>" onkeyup="sum('1');calculation(this.id,'0');dept_cost_amount(this.id,'<?php echo $counter ?>')" value="" required="required"/>
														</td>

														<?php
														$check_department_allocation= CommonHelper::department_allocation_data($data->id,4);
														$check_costing_allocation= CommonHelper::cost_center_allocation_data($data->id,4);

														?>
														<td class="text-center"><input @if(!empty($check_department_allocation) || !empty($check_costing_allocation)) checked @endif onclick="checked_unchekd({{$counter}},'0')" type="checkbox" id="allocation<?php echo $counter ?>" name="allocation<?php echo $counter ?>"/> </td>
													</tr>


													<script>


													</script>
													<?php $counter++; ?>
												@endforeach

												<input type="hidden" id="counter" value="{{$counter}}"/>

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
																value="{{$total_debit}}"/>
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
																value="{{$total_credit}}"/>
													</td>
													<td class="diff" style="width:150px;font-size: 20px;"></td>
												</tr>

												<tr>
													<td colspan="12" style="font-size: 20px;color: navy;" id="rupees"></td>
												</tr>
												</tbody>
											</table>
											<?php $count1=1;
											$dep_count=1;

											?>
											@foreach($cpv_data as $data)
												<?php
												$check_department_allocation= CommonHelper::department_allocation_data($data->id,4);
												$check_costing_allocation= CommonHelper::department_allocation_data($data->id,4);
												?>
												<div id="" class="row">

													<p style="color: #e2a0a0;text-align: center" id="paragraph{{$count1}}"> </p>

													@include('Finance.dept_allocation_edit')


													@include('Finance.cost_center_allocation_edit')

												</div>
												<?php $dep_count++; $count1++; ?>
											@endforeach
											<div id="cost_data{{$counter}}" class="row">


											</div>
										</div>
									</div>
								</div>

								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
										<input  type="button" class="btn btn-sm btn-primary" onclick="addMorePvsDetailRows('1')" value="Add More PV's Rows" />
									</div>
								</div>

								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label class="sf-label">Description</label>
											<span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
											<textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField">{{$cpvs->description}}</textarea>
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

	<?php

	?>
	<script>

		var SelectVal=[];
		var Selecttxt=[];
		var ajaxformdept=0;

		var SelectValCostCenter=[];
		var SelecttxtCostCenter=[];
		var ajaxformdeptCostCenter=0;
		$(document).ready(function() {


			var date=$('#pv_date_1').val();
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


			$('.taxes').fadeOut(500);
			$('#d_t_amount_1').number(true,2);
			$('#c_t_amount_1').number(true,2);
			var counter=$('#counter').val();
			for(i=1; i<=counter; i++)
			{
				$('#d_amount_1_'+i).number(true,2);
				$('#c_amount_1_'+i).number(true,2);
				$('#current_amount'+i).number(true,2);
				$('#rate_1_'+i).number(true,2);
				checked_unchekd(i,0);
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
				$("input[name='pvsSection[]']").each(function(){
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
		var x ='<?php echo $counter-1 ?>';

		function addMorePvsDetailRows(id){
			x++;
			var items = 1;
			var m = '<?php echo $_GET['m'];?>';
			$.ajax({
				url: '<?php echo url('/')?>/fmfal/addMoreBankPvsDetailRows_costing',
				type: "GET",
				data: { counter:x,id:id,m:m,items:items},
				success:function(data) {

					var response=data.split('*');
					//	alert(response[1]);
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

					//	$("#LeNomDeMaBaliseID").prop('id', 'LeNouveauNomDeMaBaliseID');

				}
			});
		}

		function removePvsRows(id,counter){
			var elem = document.getElementById('removePvsRows_'+id+'_'+counter+'');
			elem.parentNode.removeChild(elem);
			var elem = document.getElementById('cost_data'+counter);
			elem.parentNode.removeChild(elem);

		}
		function removePvsSection(id){
			var elem = document.getElementById('bankPvs_'+id+'');
			elem.parentNode.removeChild(elem);
		}
	</script>
	<script type="text/javascript">

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


	</script>

	<script>
		function applicable(number)
		{
			if (number==1)
			{
				$('#supplier_div').fadeIn(500);
			}
			else
			{
				$('#supplier_div').fadeOut(500);
			}

		}


		function supplier_income_tax_data()
		{
			var supplier_id=$('#supplier_id').val();
			var loader_img = '<img src="/assets/img/103.gif" alt="Loading" />';
			$("#response").html(loader_img);

			$.ajax({
				url: '<?php echo url('/')?>/fdc/income_tax_calculation',
				type: "GET",
				data: { supplier_id:supplier_id},
				success:function(data)
				{


					$('#response').html(data);

				}
			});

		}
	</script>


	<script>
		function calculation_text(number){

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
	</script>



	<script type="text/javascript">
		$(document).ready(function(){
			$('input[name="adv"]').click(function(){
				if($(this).is(":checked")){
					$('#type').val(2);
				}
				else if($(this).is(":not(:checked)")){
					$('#type').val(1);
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
	</script>

	<script>
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


				// new line add 03-dec-2019
				$('#cost_center_dept_amount'+number).text(amount);
				$('#dept_amount'+number).text(amount);
				//end

				$('#paragraph'+number).html('This Allocation For '+select_account +' And Amount is '+amount +' &darr;');

				// depratment and cost center show
				dept_allocation_amount_display(number);
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


				var item_amount= $('#d_amount_1_'+i+'').val();
				if (item_amount==0 || item_amount=='')
				{
					item_amount= $('#c_amount_1_'+i+'').val();
				}

				if (typeof(item_amount) == 'undefined') {
					return auth;
					return false;

				}

				item_amount= parseFloat(item_amount);
				var dept_amount=$('#total_dept'+i).val();

				item_amount=Math.round(item_amount);
				dept_amount=Math.round(dept_amount);

				if (item_amount!=dept_amount)
				{

					if($('#dept_check_box'+i).is(":checked"))
					{


					}
					else
					{

						var dept_name = $('#account_id_1_' + i + ' :selected').text();
						alert('Department Allocation Not Macth For ' + dept_name);
						auth=0;

						return false;

					}
				}

			}
			return auth;
		}


		function cost_center_amount_validation()
		{
			var auth=1;

			for (i=1; i<=x; i++)
			{
				var item_amount= $('#d_amount_1_'+i+'').val();
				if (item_amount==0 || item_amount=='')
				{
					item_amount= $('#c_amount_1_'+i+'').val();
				}

				if (typeof(item_amount) == 'undefined') {
					return auth;
					return false;

				}
				item_amount= parseFloat(item_amount);
				var cost_center=$('#cost_center_total_dept'+i).val();


				item_amount=Math.round(item_amount);
				cost_center=Math.round(cost_center);

				if (item_amount!=cost_center)
				{

					if($('#cost_center_check_box'+i).is(":checked"))
					{


					}
					else
					{
						var dept_name = $('#account_id_1_' + i + ' :selected').text();

						alert('Cost Center Allocation Not Macth For ' + dept_name);

						auth=0;
						return false;

					}
				}

			}
			return auth;
		}


		function change_day()
		{

			var date=$('#pv_date_1').val();

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

	</script>
@endsection