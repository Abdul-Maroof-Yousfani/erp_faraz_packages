@extends('layouts.default')
@section('content')
@include('number_formate')

<script>

	$(document).ready(function() {
	var count=$('#count').val();

	$('.Form-control1').number(true,2);

	});

</script>
<?php
		use App\Helpers\SaleHelper;
		use App\Helpers\CommonHelper;
		use App\Helpers\SalesHelper;
?>

	<style type="text/css">
		.margin-topp {
			margin-top: 10px;
		}
		.table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
			border-bottom-width: 2px;
			text-align: center;
			font-size: 10px !important;
		}
		.table-bordered>tbody>tr>td, .table-bordered>tbody>tr>th, .table-bordered>tfoot>tr>td, .table-bordered>tfoot>tr>th, .table-bordered>thead>tr>td, .table-bordered>thead>tr>th {
			border: 1px solid #ddd;
			text-align: center;
			font-size: 11px !important;
		}
		.form-control1 {
			border: solid 0px #ccc;
			width: 100%;
		}
	</style>

<?php
$sub_item=CommonHelper::get_item_by_category();
?>


<table style="width: 40%" class="table table-bordered margin-topp table-">
	<thead>

	</thead>
	<tbody>
	<tr>
		<td>Distributor Name</td>
		<td><select style="width: 100%" class="">
				@foreach(SalesHelper::get_all_customers() as $row)
				<option value="{{$row->id}}">{{$row->name}}</option>
					@endforeach
			</select></td>

	</tr>

	<tr>
		<td>Closing Bal.</td>
		<td><input style="width: 100%" type="number" ></td>

	</tr>

	<tr>
		<td>Recipt C.M</td>
		<td><input style="width: 100%" type="number" ></td>

	</tr>
	<tr>
		<td>Recipt L.M</td>
		<td><input style="width: 100%" type="number" ></td>

	</tr>
	</tbody>
	</tr>
	</table>
<div class="container-fluid">
	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 table-responsive">
			<?php $count=1; ?>
			@foreach($sub_item as $row)

				<h4 style="text-align: center">{{$row->sub_ic}}</h4>
			<table class="table table-bordered margin-topp table-">
				<thead>
				<tr>

					<th colspan="7">Price List</th>
					<th rowspan="2">STOCK IN HAND</th>
					<th rowspan="2">ORDER QTY</th>

				</tr>
				<tr>
					<th>BRAND&nbsp;NAME</th>
					<th>BATCH</th>
					<th>EXPIRY</th>
					<th>MRP</th>
					<th>TP</th>
					<th>DISC%</th>
					<th>PACK SIZE</th>
				</tr>
				</thead>
				<tbody>
				<tr>

					<td><input type="text" name="" class="Form-control1" value="{{ucwords($row->sub_ic)}}"></td>

					<td><select onchange="get_batch_detail(this.id,'<?php echo $count ?>')" style="width: 100%" name="batch_no<?php echo $count ?>" id="batch_no<?php echo $count ?>"><option>Select</option>
						@foreach(SalesHelper::get_bacth_data_by_item_wise($row->id) as $row1)
							<option value="{{$row1->id}}">{{$row1->batch_no}}</option>
							@endforeach
						</select></td>
					<td class="text-center"><input type="text" name="expiry_date<?php echo $count ?>" id="expiry_date<?php echo $count ?>" class="Form-control1"></td>
					<td class="text-center"><input type="text" name="mrp<?php echo $count ?>" id="mrp<?php echo $count ?>" class="Form-control1"></td>
					<td class="text-center"><input type="text" name="tp<?php echo $count ?>" id="tp<?php echo $count ?>" class="Form-control1"></td>
					<td class="text-center"><input type="text" name="disc<?php echo $count ?>" id="disc<?php echo $count ?>" value="10%" class="Form-control1"></td>
					<td><input  type="text" name="pack_size<?php echo $count ?>" id="pack_size<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text" name="stock_in_hane<?php echo $count ?>" id="stock_in_hand<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text" onkeyup="for_amount_calculation(this.id,'<?php echo $count ?>')" name="order_qty<?php echo $count ?>" id="order_qty<?php echo $count ?>" class="Form-control1"></td>

				</tr>
				</tbody>
				<thead>
				<tr>

					<th rowspan="2">AMOUNT</th>
					<th rowspan="2">REQUIRED QUANTITY</th>
					<th colspan="3">PREVIOUS MONTH</th>
					<th colspan="3">THIS MONTH</th>
					<th rowspan="2">CURRENT STOCK</th>
				</tr>
				<tr>

					<th>SALE</th>
					<th>BONUS</th>
					<th>CLOSING STOCK</th>
					<th>SALE</th>
					<th>BONUS</th>
					<th>DISPATCH</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td><input type="text" onkeyup="amir()" name="amount<?php echo $count ?>" id="amount<?php echo $count ?>"  class="Form-control1"></td>
					<td><input type="text"  readonly name="required_qty<?php echo $count ?>" id="required_qty<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text" onkeyup="get_required_qty(this.id,'<?php echo $count ?>')" name="previous_sale<?php echo $count ?>" id="previous_sale<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text"  onkeyup="get_required_qty(this.id,'<?php echo $count ?>')" name="previous_bonus<?php echo $count ?>"  id="previous_bonus<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text" onkeyup="get_required_qty(this.id,'<?php echo $count ?>');get_current_stock(this.id,'<?php echo $count ?>')"  name="previous_closing_stock<?php echo $count ?>" id="previous_closing_stock<?php echo $count ?>" class="Form-control1"></td>

					<td><input type="text" onkeyup="get_required_qty(this.id,'<?php echo $count ?>');get_current_stock(this.id,'<?php echo $count ?>')" name="this_sale<?php echo $count ?>" id="this_sale<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text" onkeyup="get_required_qty(this.id,'<?php echo $count ?>');get_current_stock(this.id,'<?php echo $count ?>')" name="this_bonus<?php echo $count ?>" id="this_bonus<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text" onkeyup="get_required_qty(this.id,'<?php echo $count ?>');get_current_stock(this.id,'<?php echo $count ?>')" name="this_dispatch<?php echo $count ?>" id="this_dispatch<?php echo $count ?>" class="Form-control1"></td>
					<td><input type="text" name="this_current_stock<?php echo $count ?>" id="this_current_stock<?php echo $count ?>" class="Form-control1"></td>

				</tr>
				</tbody>
			</table>
			<table class="table table-bordered margin-topp">

			</table>
				<?php $count++; ?>
				@endforeach
			<input type="hidden" id="count" name="count" value="{{$count}}">

		</div>
	</div>
</div>


	<script>
		var base_url='<?php echo url('/')?>';

		function get_batch_detail(id,count)
		{

			id=$('#'+id).val();

			$.ajax({
				url: base_url+'/sa/get_batch_detail',
				type: "GET",
				data: { id:id},
				success:function(data)
				{
					data=data.split(',');
					$('#expiry_date'+count).val(data[0]);
					$('#mrp'+count).val(data[1]);
					$('#tp'+count).val(data[2]);
					$('#pack_size'+count).val(data[3]);
					$('#stock_in_hand'+count).val(data[4]);
				}
			});
		}


		function for_amount_calculation(id,count)
		{

			var order_qty=parseFloat($('#'+id).val());

			if (isNaN(order_qty))
			{
				order_qty=0;
			}
			var tp=parseFloat($('#tp'+count).val());
			var total=order_qty*tp*90;
			$('#amount'+count).val(total);

		}

		function get_required_qty(id,count)
		{
			var previous_sale=parseFloat($('#previous_sale'+count).val());
			previous_sale=checkNan(previous_sale);
			var previous_bonus=parseFloat($('#previous_bonus'+count).val());
			previous_bonus=checkNan(previous_bonus);
			var closing_stock=parseFloat($('#previous_closing_stock'+count).val());
			closing_stock=checkNan(closing_stock);
			var this_sale=parseFloat($('#this_sale'+count).val());
			this_sale=checkNan(this_sale);
			var this_bonus=parseFloat($('#this_bonus'+count).val());
			this_bonus=checkNan(this_bonus);
			var this_dispatch=parseFloat($('#this_dispatch'+count).val());
			this_dispatch=checkNan(this_dispatch);
			var required_qty=(previous_sale+previous_bonus)*1.5;
			required_qty=required_qty-closing_stock+this_sale+this_bonus-this_dispatch;
			$('#required_qty'+count).val(required_qty);
		}


		function get_current_stock(id,count)
		{

			var closing_stock=parseFloat($('#previous_closing_stock'+count).val());
			closing_stock=checkNan(closing_stock);
			var this_sales=parseFloat($('#this_sale'+count).val());
			this_sales=checkNan(this_sales);
			var this_bonus=parseFloat($('#this_bonus'+count).val());
			this_bonus=checkNan(this_bonus);
			var dispatch=parseFloat($('#this_dispatch'+count).val());
			dispatch=checkNan(dispatch);
			var current_stock=closing_stock-this_sales-this_bonus+dispatch;
			$('#this_current_stock'+count).val(current_stock);
		}

		function checkNan(value)
		{
			if (isNaN(value))
			{
				value=0;
			}
			return value;
		}

	</script>
@endsection






