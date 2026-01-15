<?php
   use App\Helpers\ReuseableCode;
   use App\Helpers\NotificationHelper;
   use App\Helpers\PurchaseHelper;
   use App\Helpers\SalesHelper;
   use App\Helpers\CommonHelper;
   ?>
@extends('layouts.default')
@section('content')
@include('loader')
@include('select2')
@include('modal')
<style>
   * {
   font-size: 12px!important;
   }
   label {
   text-transform: capitalize;
   }
</style>
<?php $so_no= SalesHelper::get_unique_no(date('y'),date('m')); ?>

<div class="container-fluid">
	<div class="row" style="display: none;" id="main">
	  
	   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	      <div class="well_N">
	         <div class="dp_sdw">
	         
	            <div class="lineHeight">&nbsp;</div>
	            <div class="row">
				<form  method="POST" action="{{route('salesorder.store')}}">
	               <input type="hidden" name="_token" value="{{ csrf_token() }}">
	               <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                  <div class="panel">
	                     <div class="panel-body">
	                        <div class="row">
	                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                              <div class="row">
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
	                                    <label class="sf-label">Quotation <span class="rflabelsteric"><strong>*</strong></span></label>
										 <select name="" id="">
											@foreach(CommonHelper::get_all_quotation() as $quotation)
											<option value="{{$quotation->id}}">{{$quotation->quotation_no}} -- {{$quotation->quotation_date}}</option>
											@endforeach
										 </select>
	                                 </div>
	                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
	                                    <label class="sf-label">Sale Order No <span class="rflabelsteric"><strong>*</strong></span></label>
	                                    <input readonly type="text" class="form-control" placeholder="" name="so_no" id="so_no" value="{{strtoupper($so_no)}}" />
	                                 </div>
	                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
	                                    <label class="sf-label">Sale Order Date <span class="rflabelsteric"><strong>*</strong></span></label>
	                                    <input autofocus type="date" class="form-control" placeholder="" name="so_date" id="so_date" value="{{date('Y-m-d')}}" />
	                                 </div>
	                                 <input type="hidden" id="sales_order_id" name="sales_order_id" />
	                                 <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
	                                    <label class="sf-label">Purchase order NO <span></span></label>
	                                
	                                    <input  type="text" class="form-control" placeholder="" name="purchase_order_no" id="order_no" value="" />
	                                 </div>
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 hide">
	                                    <label class="sf-label">Purchase Order/ Contract No</label>
	                                    <input  type="text" class="form-control" placeholder="" name="purchase_order_contract" id="other_refrence" value="" />
	                                 </div>
	                                 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
	                                    <label class="sf-label">Purchase Order Date <span class="rflabelsteric"></span></label>
	                                    <input  type="date" class="form-control" placeholder="" name="purchase_order_date" id="order_date" value="{{date('Y-m-d')}}" />
	                                 </div>
									
	                              </div>
	                              <div class="row">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
	                                    <label class="sf-label">Customer <span class="rflabelsteric"><strong>*</strong></span></label>
	                                    <select style="width: 100%" name="buyers_id" id="ntn" class="form-control select2 requiredField">
	                                       <option value="">Select</option>
	                                       @foreach(SalesHelper::get_all_customer() as $row)
	                                       <option value="{{$row->id.'*'.$row->cnic_ntn.'*'.$row->strn.'*'.$row->terms_of_payment}}">{{$row->name}}</option>
	                                       @endforeach
	                                    </select>
	                                 </div>
	                              </div>
	                           </div>
	                        
	                        </div>
	                        <div class="lineHeight">&nbsp;</div>
							<div class="lineHeight">&nbsp;</div>
	                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                           <span class="subHeadingLabelClass">Sale Order Data</span>
	                        </div>
	                        <div class="lineHeight">&nbsp;&nbsp;&nbsp;</div>
	                        <div class="row">
	                           <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                              <div class="table-responsive" >
	                                 <table class="table table-bordered">
	                                    <thead>
	                                       <tr class="text-center">
	                                          <th colspan="8" class="text-center">Sales Order Detail</th>
	                                          <th  class="text-center">
	                                             <input type="button" class="btn btn-sm btn-primary" onclick="AddMoreDetails()" value="Add More Rows" />
	                                          </th>
	                                          <th class="text-center">
	                                             <span class="badge badge-success" id="span">1</span>
	                                          </th>
	                                       </tr>
	                                       <tr>				
											  <th class="text-center"  style="width: 25%;">Product</th>
	                                          <th class="text-center">item Code</th>
	                                          <th class="text-center">Thickness</th>
	                                          <th class="text-center">Diameter </th>
	                                          <th class="text-center" > QTY.<span class="rflabelsteric"><strong>*</strong></span></th>
	                                          <th class="text-center" >Unit of Measurement<span class="rflabelsteric"><strong>*</strong></span></th>
                                             <th  class="text-center">Printing </th> 
                                             <th  class="text-center">Special Instruction </th> 
                                             <th  class="text-center">Delivery Date </th> 
											
											<th class="text-center">Delete<span class="rflabelsteric"><strong>*</strong></span></th>
	                                       </tr>
	                                    </thead>
	                                    <tbody id="AppnedHtml">
	                                       <tr class="cnt" title="1">
											   <td> <select onchange="get_uom('{{ 1 }}')" name="sub_ic_des[]" id="sub_ic_des{{ 1 }}" class="form-control select2">
												<option value="">Select</option>
												@foreach ( CommonHelper::get_all_subitem() as $row )
												<?php $uom =  CommonHelper::get_uom($row->id); ?>
												<option value="{{ $row->id.','.$uom.','.$row->item_code }}" >{{ $row->sub_ic }}</option>
												@endforeach
											</select>
										</td>
										<td><input type="text" class="form-control" name="item_code[]" id="item_code1" readonly></td>
										<td><input type="text" class="form-control" name="thickness[]" id="thickness"></td>
										<td><input type="text" class="form-control" name="diemetter[]" id="diemetter"></td>
										<td><input type="text" class="form-control requiredField zerovalidate" name="actual_qty[]" id="actual_qty1" onkeyup="claculation('1')" onblur="claculation('1')"></td>
										<td><input type="text" class="form-control" name="uom_id[]" id="uom_id1"  readonly></td>
    									<td><input type="text" class="form-control" name="printing[]"></td>
										<td><input type="text" class="form-control" name="special_instruction[]"></td>
										<td><input type="date" class="form-control" name="delivery_date[]"></td>
										<td style="background-color: #ccc">
											<input onclick="view_history(1)" type="checkbox" id="view_history1"></td>
	                                    </tr>
	                                    </tbody>
	                                    <tbody>
	                                       <tr  style="font-size:large;font-weight: bold">
	                                          <td class="text-center" colspan="8">Total</td>
	                                          <td id="" class="text-right" ><input readonly class="form-control" type="text" id="net"/> </td>
	                                          <td></td>
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
	                  <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
	                     {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
	              
	                  </div>
	               </div>
				</form>
	            </div>
	         
	         </div>
	      </div>
	   </div>
	</div>
</div>
<script>
   function view_history(id)
   {
   	var v= $('#sub_ic_des'+id).val();
   	if ($('#view_history' + id).is(":checked"))
   	{
   		if (v!=null)
   		{
   			showDetailModelOneParamerter('sdc/sals_history?id='+v);
   		}
   		else
   		{
   			alert('Select Item');
   		}
   	}
   }

   var x=0;


   function net_amount()
   {
   	var amount=0;
   	$('.amount').each(function (i, obj) {

   		amount += +$('#'+obj.id).val();
		   console.log(obj.id);
   	});
   	amount=parseFloat(amount).toFixed(3);


   	$('#net').val(amount);
   	$('#total').val(amount);


    var net_amount=0;
	   $('.net_amount_dis').each(function (i, obj) {

		net_amount += +$('#'+obj.id).val();
});
net_amount=parseFloat(net_amount).toFixed(3);
$('#total_after_sales_tax').val(net_amount);

   }
   $(document).ready(function() {
   	$(".btn-success").click(function(e){
   		var purchaseRequest = new Array();
   		var val;
   		purchaseRequest.push($(this).val());
   		var _token = $("input[name='_token']").val();
   		for (val of purchaseRequest) {
   			jqueryValidationCustom();
   			if(validate == 0){
   			}else{
   				return false;
   			}
   		}

   	});
   });

   $(document).ready(function() {
	off();
   });


   function claculation(number)
   {
   	var  qty=$('#actual_qty'+number).val();
   	var  rate=$('#rate'+number).val();
   	var total=parseFloat(qty*rate).toFixed(2);
   	$('#amount'+number).val(total);

   	var amount = 0;
   	count=1;
   	$('.net_amount_dis').each(function (i, obj) {

   		amount += +$('#'+obj.id).val();

   		count++;
   	});
   	amount=parseFloat(amount);
   	net_amount();
   }


   function off()
   {

   }

   function get_detail(id,number)
   {
	var item=$('#'+id).val();
   	$.ajax({
   		url:'{{url('/pdc/get_data')}}',
   		data:{item:item},
   		type:'GET',
   		success:function(response)
   		{

   			var data=response.split(',');
   			$('#uom_id'+number).val(data[0]);


   		}
   	})
   }
   $(".remove").each(function(){
   	$(this).html($(this).html().replace(/,/g , ''));
   });


   function get_uom(id)
   {
		var sub_ic_data = $('#sub_ic_des'+id).val();
		sub_ic_data = sub_ic_data.split(',');
		$('#uom_id'+id).val(sub_ic_data[1]);
		$('#item_code'+id).val(sub_ic_data[2]);
   }
</script>
<script type="text/javascript">
	var Counter = 1;
   function AddMoreDetails()
   {
   	Counter++;
   	$('#AppnedHtml').append(`<tr class="cnt" id="RemoveRows${Counter}">
											   <td> <select onchange="get_uom(${Counter})" name="sub_ic_des[]" id="sub_ic_des${Counter}" class="form-control select2">
												<option value="">Select</option>
												@foreach ( CommonHelper::get_all_subitem() as $row )
												<?php $uom =  CommonHelper::get_uom($row->id); ?>
												<option value="{{ $row->id.','.$uom }}" >{{ $row->sub_ic }}</option>
												@endforeach
											</select>
										</td>
										<td><input type="text" class="form-control" name="item_code[]" id="item_code${Counter}" readonly></td>
										<td><input type="text" class="form-control" name="thickness[]" id="thickness${Counter}"></td>
										<td><input type="text" class="form-control" name="diemetter[]" id="diemetter${Counter}"></td>
										<td><input type="text" class="form-control requiredField zerovalidate" name="actual_qty[]" id="actual_qty1" onkeyup="claculation(${Counter})" onblur="claculation(${Counter})"></td>
										<td><input type="text" class="form-control" name="uom_id[]" id="uom_id${Counter}"  readonly></td>
    									<td><input type="text" class="form-control" name="printing[]"></td>
										<td><input type="text" class="form-control" name="special_instruction[]"></td>
										<td><input type="date" class="form-control" name="delivery_date[]"></td>
										<td style="background-color: #ccc">
										<input onclick="view_history(${Counter})" type="checkbox" id="view_history${Counter}">&nbsp;&nbsp;
   										<button type="button" class="btn btn-sm btn-danger" id="BtnRemove${Counter}" onclick="RemoveSection(${Counter})"> - </button>
											</tr>`);
   	$('.select2').select2();

   	var AutoCount=1;
   	$(".AutoCounter").each(function(){
   		AutoCount++;
   		$(this).prop('title', AutoCount);

   	});
   }
   
   function RemoveSection(Row) {
   	$('#RemoveRows' + Row).remove();
   	var AutoCount = 1;
   	var AutoCount=1;
   	$(".AutoCounter").each(function() {
   		AutoCount++;
   		$(this).prop('title', AutoCount);
   	});
   	var itemsCount = $(".cnt").length;

   	$('#span').text(itemsCount);
   }


   $('.select2').select2();
</script>
<script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection
