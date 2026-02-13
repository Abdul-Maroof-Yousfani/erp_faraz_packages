@extends('layouts.default')

@section('content')
@include('select2')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('sales_order','so_no','SO');
$count = 1;
// echo "<pre>";
// print_r($sale_orders);
// echo "<pre>";
// print_r($sales_order_data);
// exit();
?>
<style>
    .my-lab label {
    padding-top:0px; 
}
</style>
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form action="{{route('updateSaleOrder' ,$sale_orders->id )}}" method="post" id="dataForm" >
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="formSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>Edit Sales Order</h1>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <!-- <div class="col-md-3">
                                                <label class="control-label">Quotation </label>
                                                <select name="quotation_id" onchange="get_quotation_data(this.value)" class="form-control" id="quotation">
                                                    <option value="">Select</option>
                                                    @foreach(CommonHelper::get_all_quotation() as $quotation)
                                                        <option value="{{$quotation->id}}">{{$quotation->quotation_no}} -- {{$quotation->quotation_date}}</option>
                                                    @endforeach
                                                </select>  
                                            </div>  -->
                                            <div class="col-md-3">
                                                <label class="control-label">SO No*</label>
                                                <input name="sale_order_no" class="form-control" readonly value="{{$sale_orders->so_no}}" type="text" />
                                            </div> 
                                            <div class="col-md-3">
                                                <label class="control-label">SO Date*</label>
                                                <input  name="sale_order_date" value="{{$sale_orders->so_date}}" class="form-control" type="date" />
                                            </div> 
                                            <div class="col-md-3">
                                                <label class="control-label">PO No</label>
                                                <input name="purchase_order_no" value="{{$sale_orders->purchase_order_no}}" class="form-control" type="text" />
                                            </div> 
                                            <div class="col-md-3">
                                                <label class="control-label">PO Date</label>
                                                <input name="purchase_order_date" value="{{$sale_orders->purchase_order_date}}" class="form-control" type="date" />
                                            </div> 
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control-label">Customer *</label>
                                                <select name="customer" id="customer_id" class="form-control requiredField select2">
                                                    <option value="">Select Customer</option>
                                                    @foreach (CommonHelper::get_customer() as $customer)
                                                        <option @if($sale_orders->buyers_id == $customer->id) selected @endif value="{{$customer->id}}" id="op{{$customer->id}}">{{$customer->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div> 
                                            <div class="col-md-3">
                                                <label class="control-label">Sales Tax Group</label>
                                                <select onchange="saletax(this)" name="sale_taxt_group" class="form-control select2" id="sale_taxt_group">
                                                    <option value="">Select</option>
                                                    @foreach(CommonHelper::get_table_data('gst') as $item)
                                                        <option @if($sale_orders->sales_tax_group == $item->id && $sale_orders->sales_tax_rate == $item->rate ) selected @endif  value="{{$item->id}},{{$item->rate}}" id="tax{{$item->id}}">{{$item->rate}} %</option>
                                                    @endforeach
                                                </select>
                                            </div> 
                                            <div class="col-md-3">
                                                <label class="control-label">Sales Tax Rate</label>
                                                <input type="text" class="form-control" readonly name="sale_tax_rate" id="sale_tax_rate" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Further Tax Group</label>
                                                <select onchange="furtherTax(this)" name="further_taxes_group" class="form-control select2" id="further_taxes_group">
                                                    <option value="">Select</option>
                                                    @foreach(CommonHelper::get_table_data('gst') as $item)
                                                        <option  @if($sale_orders->further_taxes_group == $item->id) selected @endif value="{{$item->id}},{{$item->rate}}" id="tax{{$item->id}}">{{$item->rate}} %</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control-label">Further Tax Rate</label>
                                                <input type="text" class="form-control" readonly name="further_tax" id="further_tax" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Advance Tax Group</label>
                                                <select onchange="advanceTax(this)" name="advance_tax_rate" id="advance_tax_rate" class="form-control">
                                                    <option value="">Select</option>
                                                    <option @if($sale_orders->advance_tax_group == 1) selected @endif data-value="1" value="1,1.0">1.0 %</option>
                                                    <option @if($sale_orders->advance_tax_group == 2) selected @endif data-value="2" value="2,1.5">1.5 %</option>
                                                    <option @if($sale_orders->advance_tax_group == 3) selected @endif data-value="3" value="3,2.0">2.0 %</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Advance Tax Rate</label>
                                                <input type="number" class="form-control"
                                                    name="advance_tax" id="advance_tax" value="{{ $sale_orders->advance_tax }}" readonly />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Cartage Amount</label>
                                                <input type="number" class="form-control"
                                                    name="cartage_amount" id="cartage_amount" value="{{ $sale_orders->cartage_amount }}" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label for="control-label">Currency</label>
                                                <select required onchange="getrate()" name="currency_id" id="curren" class="form-control select2 requiredField">
                                                    <option value="">Select Currency</option>
                                                    @foreach(CommonHelper::get_all_currency() as $row)
                                                        <option @if($sale_orders->currency_id == $row->id) selected @endif data-value="{{ $row->rate }}" value="{{$row->id.','.$row->rate}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="control-label">Exchange Rate</label>
                                                <input type="text" name="exchange_rate" id="exchange_rate" class="form-control" value="{{ $sale_orders->exchange_rate}}" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="control-label">Priority</label>
                                                <select name="priority" id="priority" class="form-control">
                                                    <option @if($sale_orders->priority == 1) selected @endif value="1">High</option>
                                                    <option @if($sale_orders->priority == 2) selected @endif value="2">Normal</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-10">
                                                <!-- <h2>Product Details</h2> -->
                                            </div>
                                            <div class="col-md-2 text-right">
                                                <a href="#" onclick="AddMoreDetails()" class="btn btn-primary">Add More</a>
                                            </div>
                                        </div>
                                        <div class="lineHeight">&nbsp;</div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <table class="userlittab table table-bordered sf-table-list">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center col-sm-2">Item</th>
                                                                <th class="text-center">Bags Qty</th>
                                                                <th class="text-center">UOM</th>
                                                                {{-- <th class="text-center">Color</th> --}}
                                                                <th class="text-center">Qty in KG</th>
                                                                <th class="text-center">Qty (lbs)</th>
                                                                <th class="text-center">Unit Price</th>
                                                                <th class="text-center">Total</th>
                                                                <th class="text-center">Action</th>
                                                        </tr>
                                                    </thead>
                                                
                                                    <tbody id="more_details">
                                                        @foreach($sales_order_data as $key => $value)
                                                            <tr id="RemoveRows{{$count}}" class="m-tab main">
                                                                <td style="width: 18%">
                                                                    <select onchange="get_item_name1({{$count}})" class="form-control select2 item_id itemsclass " name="item_id[]" id="item_id{{$count}}">
                                                                        <option value="">Select</option>
                                                                        @foreach($sub_item as $val)
                                                                            <option @if($value->item_id == $val->id) selected @endif
                                                                                value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic. '@' . $val->pack_size . '@' . $val->type. '@' . $val->color }}">
                                                                                {{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->type. ' ' . $val->color }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>                  
                                                                </td>
                                                                    <input style="display: none" class="form-control" type="text" name="item_code[]" id="item_code" value="{{$value->item_code}}">
                                                                    <input style="display: none" class="form-control" type="text" name="thickness[]" id="thickness" value="{{$value->thickness}}">
                                                                    <input style="display: none" class="form-control" type="text" name="diameter[]" id="diameter" value="{{$value->diameter}}">
                                                                <td>
                                                                    <input type="text" name="pack_size[]" id="pack_size{{ $count }}" class="form-control" value="{{ number_format($value->qty / ($value->pack_size ?: 1), 2) }}" oninput="bag_qq({{ $count }})" />
                                                                </td>
                                                                <td>
                                                                    <input readonly id="uom_id{{ $count }}" type="text"
                                                                        name="uom[]"
                                                                        class="form-control uom">
                                                                </td>
                                                                {{-- <td>
                                                                    <input readonly type="text" name="color[]" id="color{{ $count }}" class="form-control" />
                                                                </td> --}}
                                                                <td>
                                                                    <input readonly class="form-control" onkeyup="calculation_amount()" type="text" name="qty[]" id="qty{{ $count }}" value="{{$value->qty}}">
                                                                    <input type="hidden" class="PackQty" name="pack_qty[]" id="pack_qty" value="{{ $value->pack_size }}">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control requiredField"
                                                                        type="number" id="qty_lbs1" value="{{$value->qty_lbs}}"
                                                                        name="qty_lbs[]" step="any" readonly />
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" onkeyup="calculation_amount()" type="text" name="rate[]" id="rate" value="{{$value->rate}}">
                                                                </td>
                                                                
                                                                <td>
                                                                    <input class="form-control" type="text" name="total[]" id="total" value="">
                                                                </td>
                                                                <td style="width: 5%;">
                                                                    <a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection('{{ $count }}')">
                                                                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        
                                                            <script>
                                                                $(document).ready(function(){
                                                                    get_item_name1('{{ $count }}');

                                                                    calculation_amount();

                                                                });
                                                            </script>
                                                            <?php $count++ ;?>

                                                        @endforeach
                                                    </tbody>
                                                </table>`
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 padtb">
                                                <div class="col-md-8">
                                                    <div style="text-transform: capitalize;" id="rupees">Amount: in Words: {{ $sale_orders->amount_in_words }}</div>
                                                    <input type="hidden" value="" name="rupeess" id="rupeess1" value="{{ $sale_orders->amount_in_words }}" />
                                                </div>    
                                                <div class="col-md-4 my-lab">
                                                    <label for="">
                                                        Total Amount 
                                                    </label>
                                                    <input type="text" readonly value="" name="grand_total" id="grand_total" class="form-control">
                                                    <label for="">
                                                        Total Tax 
                                                    </label>
                                                        <input type="text" readonly value="" name="total_tax" id="total_tax" class="form-control">
                                                    
                                                    <label for="">
                                                        Total Amount With Tax
                                                    </label>
                                                    <input type="text" readonly value="" name="grand_total_with_tax" id="grand_total_with_tax" class="form-control">
                                                    <input type="hidden" name="d_t_amount_1" id="d_t_amount_1">
                                                </div>    
                                            </div>
                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-success mr-1" data-dismiss="modal">Update</button>
                                                    <button type="button" class="btnn btn-secondary" data-dismiss="modal">Cancel</button>
                                                </div>    
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">

        $(document).ready(function(){
            $(".btn-success").click(function (e) {
                var purchaseRequest = new Array();
                var val;
                //$("input[name='demandsSection[]']").each(function(){
                purchaseRequest.push($(this).val());
                //});
                var _token = $("input[name='_token']").val();
                for (val of purchaseRequest) {
                    jqueryValidationCustom();
                    if (validate == 0) {
                        $('#dataForm').submit();
                    }
                    else {
                        return false;
                    }
                }
            });

            $('.select2').select2();
        });
      
        var Counter =0
        function AddMoreDetails() {
            mainCount = $('.main').length;
            Counter =mainCount+1;

             $('#more_details').append(`
                <tr class="m-tab main" id="RemoveRows${Counter}">
                    <td style="width: 15%">
                        <select onchange="get_item_name(${Counter})" class="form-control  item_id itemsclass" name="item_id[]" id="item_id${Counter}">
                            <option value="">Select</option>
                            @foreach($sub_item as $val)
                                <option value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic. '@' . $val->pack_size . '@' . $val->type. '@' . $val->color }}">
                                    {{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->type. ' ' . $val->color }}
                                </option>
                            @endforeach
                        </select>                  
                    </td>
                    <input style="display: none" class="form-control" type="text" name="item_code[]" id="item_code" value="">
                    <input style="display: none" class="form-control" type="text" name="thickness[]" id="thickness">
                    <input style="display: none" class="form-control" type="text" name="diameter[]" id="diameter">
                    <td>
                        <input type="text" name="pack_size[]" id="pack_size${Counter}" oninput="bag_qq(${Counter})" class="form-control" />
                    </td>
                    <td>
                        <input readonly id="uom_id${Counter}" type="text" name="uom[]" class="form-control uom" />
                    </td>
                   
                    <td>
                        <input readonly class="form-control" onkeyup="calculation_amount()" type="text" name="qty[]" id="qty${Counter}" value="">
                        <input type="hidden" name="pack_qty[]" id="pack_qty">
                    </td>
                    <td>
                        <input class="form-control requiredField"
                            type="number" id="qty_lbs${Counter}"
                            name="qty_lbs[]" step="any" readonly />
                    </td>
                    <td>
                        <input class="form-control" onkeyup="calculation_amount()" type="text" name="rate[]" id="rate" value="">
                    </td>
                    <td>
                        <input class="form-control" type="text" name="total[]" id="total" value="">
                    </td>
                    <td class="text-center">
                        <a href="#" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr> `);
            $('.select2').select2();    
            Counter++;
            calculation_amount();
                                                   
        } 

        function RemoveSection(row) {
            var element = document.getElementById("RemoveRows" + row);

            if (element) {
                element.parentNode.removeChild(element);
            }

            Counter--;
            calculation_amount();
        }

        function getrate() {
            var selectedOption = document.querySelector('#curren option:checked');
            var rate = selectedOption.getAttribute('data-value');
            document.getElementById('exchange_rate').value = rate ? rate : '1.0';
        }

    // function  get_sub_item_by_id(instance,item_id)
	// {


	// 	var category= instance.value;

    //     $(instance).closest('.main').find('.itemsclass').empty();
	// 	$.ajax({
	// 		url: '{{ url("/getSubItemByCategory") }}',
	// 		type: 'Get',
	// 		data: {category: category},
	// 		success: function (response) {

    //             $(instance).closest('.main').find('.itemsclass').append(response);
    //             $(instance).closest('.main').find('.itemsclass').val(item_id)

	// 		}
	// 	});
	// }

       

    function setSelectedOptionById(id) {
        // Get the select element
        console.log(id);
        var selectElement = document.getElementById('customer_id');
        
        // Get the option element by ID
        var optionElement = document.getElementById(id);
        
        // Check if the option element and select element exist
        if (optionElement && selectElement) {
        // Set the selected attribute of the option
        optionElement.selected = true;
        } else {
        console.error('Option or select element not found.');
        }
    }

    function get_item_name(index) {
            var item = $('#item_id' + index).val();

            var uom = item.split('@');
            console.log(uom);
            $('#uom_id' + index).val(uom[1]);
            $('#item_code' + index).val(uom[2]);
            $('#qty' + index).val(uom[3]);
            $('#pack_qty').val(uom[3]);
            $('#qty_lbs' + index).val(uom[3]*2.20462);
            $('#color' + index).val(uom[5]);
            $('#pack_size' + index).val(1);
            console.log(index);

            bag_qq(index);

        }

        function get_item_name1(index) {
            var item = $('#item_id' + index).val();

            var uom = item.split('@');
            console.log(uom);
            $('#uom_id' + index).val(uom[1]);
            $('#item_code' + index).val(uom[2]);
            $('#qty' + index).val(uom[3]);
            $('#pack_qty').val(uom[3]);
            $('#color' + index).val(uom[5]);
            console.log(index);

            bag_qq(index);

        }

        
        function bag_qq(counter) {
            var bags_qty = parseFloat($('#pack_size' + counter).val()) || 1;
            console.log(bags_qty);
            var pack_qty = parseFloat($('#pack_qty').val()) || 0;
            var total_qty = (bags_qty * pack_qty).toFixed(2);
            $('#qty' + counter).val(total_qty);
            $('#qty_lbs' + counter).val(total_qty*2.20462);
        }


    // function item_change(element_or_id) {
    //     let element = typeof element_or_id === 'object' ? element_or_id : $('[data-item-id="'+ element_or_id +'"]');
    //     var id = $(element).val(); // get the item ID from the input/select

    //     $.ajax({
    //         url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
    //         type: 'GET',
    //         data: {id: id},
    //         success: function (data) {
    //             $(element).closest('.main').find('#item_code').val(data.item_code);
    //             $(element).closest('.main').find('#item_description').val(data.description);
    //             $(element).closest('.main').find('#uom').val(data.uom_name);
    //         }
    //     });
    // }

    // function get_customer_details(id)
    // {
    //     var id = id;
    //     $.ajax({
    //             url: '<?php echo url('/')?>/customer/get_customer',
    //             type: 'Get',
    //             processData: false,  
    //             contentType: false,
    //             data: {id:id},
    //          success: function (data) {
    //             console.log(data);
            
               
    //          }
    //         });

    // }

// function get_quotation_data(id)
//     {
//         var id = id;
//         $('#more_details').empty();
//         $('#customer_id').val([]);
//         $('#sale_taxt_group').val([]);
//         $('#sale_tax_rate').val('');

//         $.ajax({
//                 url: '<?php echo url('/')?>/saleQuotation/get_quotation_data',
//                 type: 'Get',
//                 data: {id:id},
//              success: function (data) { 
//                     $('#more_details').append(data);
//                     var customer_id = $('#customer_id').val();
//                     var sale_tax_group = $('#sale_tax_group').val();
//                     var sale_tax_rate1 = $('#sale_tax_rate').val();
//                     setSelectedOptionById('op'+customer_id);
//                     setSelectedOptionById('tax'+sale_tax_group);
//                     $('#sale_tax_rate').val(sale_tax_group);
                
//                     calculation_amount();
                  

//              }
//         });
//         calculation_amount();
         
//     }

function calculation_amount(index) {

            var grad_total = 0;

            var tax = $('#sale_tax_rate').val();
            var fTax = $('#further_tax').val();
            var aTax = $('#advance_tax').val();

            let cartage_amount = parseFloat($('#cartage_amount').val()) || 0;

            var sale_tax = tax ? tax : 0;
            var advance_tax = aTax ? aTax : 0;
            var further_tax = fTax ? fTax : 0;

            var befor_tax = 0;
            var all_tax = 0;

            $('.itemsclass').each(function () {

                var row = $(this).closest('.main');

                var actual_rate = row.find('[name="rate[]"]').val();
                var actual_qty = row.find('[name="qty[]"]').val();

                var rate = actual_rate ? actual_rate : 0;
                var qty = actual_qty ? actual_qty : 0;

                var qty_lbs = parseFloat(qty) * 2.20462 || 0;
                row.find('[name="qty_lbs[]"]').val(qty_lbs.toFixed(2));

                var total = parseFloat(qty_lbs) * parseFloat(rate);

                var sale_tax_amount = total / 100 * sale_tax;
                var further_tax_amount = total / 100 * further_tax;
                var advance_tax_amount = total / 100 * advance_tax;

                grad_total += total + sale_tax_amount + advance_tax_amount + cartage_amount + further_tax_amount;
                befor_tax += total;
                all_tax += sale_tax_amount + advance_tax_amount + further_tax_amount;

                row.find('[name="total[]"]').val(total);
            });

            $('#total_tax').val(all_tax);
            $('#grand_total').val(befor_tax);
            $('#grand_total_with_tax').val(grad_total);
            $('#d_t_amount_1').val(grad_total);

            toWords(1);
        


        // var grad_total = 0;
        // $('.items_class').each(function(){
        //    var actual_rate =  $(this).closest('.main').find('#rate').val();
        //    var actual_qty =  $(this).closest('.main').find('#qty').val();
        //    var rate =  actual_rate? actual_rate : 0;
        //    var qty =  actual_qty? actual_qty : 0;
        //    var total = parseFloat(qty) * parseFloat(rate);
        //    grad_total +=total;
        //     $(this).closest('.main').find('#total').val(total);
        // })
        // document.getElementById('grand_total').innerHTML = grad_total;
    }

        function saletax(instance) {
            var value = $(instance).val();
            let excet_value = value.split(',');
            $('#sale_tax_rate').val(excet_value[1]);
            calculation_amount();
        }

        function furtherTax(instance) {
            var value = $(instance).val();
            let excet_value = value.split(',');
            $('#further_tax').val(excet_value[1]);
            calculation_amount();
        }

        function advanceTax(instance) {
            var value = $(instance).val();
            let excet_value = value.split(',');
            $('#advance_tax').val(excet_value[1]);
            calculation_amount();
        }
 
 $(document).ready(function(){

    
    $('#sale_taxt_group').trigger('change')
    $('#further_taxes_group').trigger('change')
   setTimeout(() => {
    //    $('.category').trigger('change')
       calculation_amount(1)
       
       
   }, 1000);
   setTimeout(() => {
           
           //$('.item_id').trigger('change')
       }, 2000);
 })

    function getItemColors(id, selectedColor = null) {
        var item_id = $('#item_id' + id).val();
        $.ajax({
            url: '{{ url('/saleQuotation/getItemColors') }}',
            data: { item_id: item_id },
            type: 'GET',
            success: function (response) {
                var colorSelect = $('#color' + id);
                colorSelect.empty().append('<option value="">Select Color</option>');

                response.forEach(function (color) {
                    colorSelect.append('<option value="' + color + '">' + color + '</option>');
                });

                colorSelect.select2();
                if (selectedColor) {
                    $(colorSelect).val(selectedColor).trigger('change');
                }
            }
        });
    }


</script>

@endsection