@extends('layouts.default')

@section('content')
    @include('select2')
    <?php
    use App\Helpers\CommonHelper;
    $so_no = CommonHelper::generateUniquePosNo('sales_order', 'so_no', 'SO');
                        ?>
    <style>
        .my-lab label {
            padding-top: 0px;
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
                                    <form action="{{route('salesorder.store')}}" method="post" id="dataForm">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="formSection[]" class="form-control requiredField"  id="demandsSection" value="1" />
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>Sales Order</h1>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control-label">Quotation </label>
                                                <select name="quotation_id" onchange="get_quotation_data(this.value)" class="form-control" id="quotation">
                                                    <option value="">Select</option>
                                                    @foreach(CommonHelper::get_all_quotation() as $quotation)
                                                
                                                        <option value="{{$quotation->id}}">{{$quotation->quotation_no}} -- {{$quotation->quotation_date}}</option>
                                                    @endforeach
                                                </select>  
                                            </div> 
                                            <div class="col-md-3">
                                                <label class="control-label">SO No*</label>
                                                <input name="sale_order_no" class="form-control" readonly value="{{$so_no}}" type="text" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">SO Date*</label>
                                                <input name="sale_order_date" value="{{date('Y-m-d')}}" class="form-control" type="date" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">PO No</label>
                                                <input name="purchase_order_no" class="form-control" type="text" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control-label">PO Date</label>
                                                <input name="purchase_order_date" value="{{date('Y-m-d')}}" class="form-control" type="date" />
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Customer *</label>
                                                <select style="width: 100%" name="customer" id="customer"
                                                    class="form-control requiredField">
                                                    <option value="">Select Customer</option>
                                                    @foreach (CommonHelper::get_customer() as $customer)
                                                        <option value="{{$customer->id}}"
                                                            id="op{{$customer->id}}">{{$customer->name}}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Sales Tax Group </label>
                                                <select style="width: 100%" onchange="saletax(this)"
                                                    name="sale_taxt_group" class="form-control select"
                                                    id="sale_taxt_group">
                                                    <option value="">Select</option>
                                                    @foreach(CommonHelper::get_table_data('gst') as $item)
                                                        <option data-value="{{$item->id}}" value="{{$item->id}},{{$item->rate}}">
                                                            {{$item->rate}} %
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Sales Tax Rate</label>
                                                <input type="text" class="form-control" readonly name="sale_tax_rate" id="sale_tax_rate" />
                                            </div>
                                            
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="control-label">Further Tax Group</label>
                                                <select style="width: 100%" onchange="furtherTax(this)" name="further_taxes_group" class="form-control select" id="further_taxes_group">
                                                    <option value="">Select</option>
                                                    @foreach(CommonHelper::get_table_data('gst') as $item)
                                                        <option data-value="{{$item->id}}" value="{{$item->id}},{{$item->rate}}"
                                                            id="tax{{$item->id}}">{{$item->rate}} %
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="control-label">Further Tax rate</label>
                                                <input type="text" class="form-control" readonly name="further_tax" id="further_tax" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Advance Tax Group</label>
                                                <select onchange="advanceTax(this)" name="advance_tax_rate" id="advance_tax_rate" class="form-control">
                                                    <option value="">Select</option>
                                                    <option data-value="1" value="1,1.0">1.0 %</option>
                                                    <option data-value="2" value="2,1.5">1.5 %</option>
                                                    <option data-value="3" value="3,2.0">2.0 %</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Advance Tax Rate</label>
                                                <input type="number" class="form-control"
                                                    name="advance_tax" id="advance_tax" readonly />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="">Cartage Amount</label>
                                                <input type="number" class="form-control"
                                                    name="cartage_amount" id="cartage_amount" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="control-label">Currency</label>
                                                <select required onchange="getrate()" name="currency_id" id="curren" class="form-control select2 requiredField">
                                                    <option value="">Select Currency</option>
                                                    @foreach(CommonHelper::get_all_currency() as $row)
                                                        <option @if($row->id == 3) selected @endif data-id="{{ $row->id }}" data-value="{{ $row->rate }}" value="{{$row->id.','.$row->rate}}">{{$row->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="control-label">Exchange Rate</label>
                                                <input required class="form-control requiredField" value="1.0" type="text" name="exchange_rate" id="exchange_rate" />
                                            </div>
                                            <div class="col-md-3">
                                                <label for="control-label">Priority</label>
                                                <select name="priority" id="priority" class="form-control">
                                                    <option value="1">High</option>
                                                    <option selected value="2">Normal</option>
                                                </select>
                                            </div>
                                            
                                         </div>
                                         <br>
                                        <div class="row">
                                            <div class="col-md-12 ">
                                                <div>
                                                    <table class="table" id="more_details">
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
                                                        <tbody id="appendData">
                                                            <tr class="m-tab main" id="RemoveRows1">

                                                                <td style="width: 15%;">
                                                                    <select onchange="get_item_name(1);"
                                                                        class="form-control requiredField item_id itemsclass"
                                                                        name="item_id[]" id="item_id1">
                                                                        <option value="">Select</option>
                                                                        @foreach($sub_item as $val)
                                                                            <option
                                                                                value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic . '@' . $val->pack_size . '@' . $val->color }}">
                                                                                {{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->color }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </td>
                                                                <input style="display: none" class="form-control"
                                                                    type="text" name="item_code[]" id="item_code"
                                                                    value="">
                                                                <input style="display: none" class="form-control"
                                                                    type="text" name="thickness[]" id="thickness">
                                                                <input style="display: none" class="form-control"
                                                                    type="text" name="diameter[]" id="diameter">
                                                                <td>
                                                                    <input type="text" name="pack_size[]"
                                                                        id="pack_size1" class="form-control" oninput="bag_qq(1)" />
                                                                </td>
                                                                <td>
                                                                    <input readonly class="form-control" type="text" name="uom[]" id="uom_id1" value="">
                                                                </td>
                                                                {{-- <td>
                                                                    <input readonly type="text" name="color[]"
                                                                        id="color1" class="form-control" />
                                                                </td> --}}
                                                                <td>
                                                                    <input class="form-control requiredField"
                                                                        onchange="calculation_amount()" type="number"
                                                                        name="qty[]" id="qty1" step="any" readonly />
                                                                         <input type="hidden" class="PackQty" name="pack_qty[]"
                                                                id="pack_qty">
                                                                </td>
                                                                <td>
                                                                    <input class="form-control requiredField"
                                                                        type="number" id="qty_lbs1"
                                                                        name="qty_lbs[]" step="any" readonly />
                                                                </td>
                                                                <td>
                                                                    <input class="form-control requiredField"
                                                                        onkeyup="calculation_amount()" type="number"
                                                                        name="rate[]" id="rate" step="any" />
                                                                </td>
                                                                <td>
                                                                    <input class="form-control" type="number"
                                                                        name="total[]" id="total" step="any" readonly />
                                                                </td>
                                                                <td class="text-center">
                                                                    <a href="#" onclick="AddMoreDetails()" class="btn btn-primary">
                                                                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 padtb">
                                                <div class="col-md-8">
                                                    <div style="text-transform: capitalize;" id="rupees"></div>
                                                    <input type="hidden" value="" name="rupeess" id="rupeess1"/>
                                                </div>

                                                <div class="col-md-4 my-lab">
                                                    <label for="">
                                                        Total Amount
                                                    </label>
                                                    <input type="text" readonly value="" name="grand_total"
                                                        id="grand_total" class="form-control">
                                                    <label for="">
                                                        Total Tax
                                                    </label>
                                                    <input type="text" readonly value="" name="total_tax" id="total_tax"
                                                        class="form-control">

                                                    <label for="">
                                                        Total Amount With Tax
                                                    </label>
                                                    <input type="text" readonly value="" name="grand_total_with_tax"
                                                        id="grand_total_with_tax" class="form-control">
                                                    <input type="hidden" name="d_t_amount_1" id="d_t_amount_1">

                                                </div>
                                            </div>
                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-success mr-1"
                                                        data-dismiss="modal">Save</button>
                                                    <a type="button" href="{{url('selling/listSaleOrder')}}"
                                                        class="btnn btn-secondary" data-dismiss="modal">Cancel</a>
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

        $(function () {
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

        });

        var Counter = 0
        function AddMoreDetails() {
            mainCount = $('.main').length;
            Counter = mainCount + 1;

            $('#more_details').append(`
                <tr class="m-tab main" id="RemoveRows${Counter}">
                    <td style="width: 15%">
                        <select onchange="get_item_name(${Counter})" class="form-control  item_id itemsclass" name="item_id[]" id="item_id${Counter}">
                            <option value="">Select</option>
                            @foreach($sub_item as $val)
                                <option value="{{ $val->id . '@' . $val->uom_name . '@' . $val->sub_ic . '@' . $val->pack_size . '@' . $val->color }}">
                                    {{ $val->item_code . ' -- ' . $val->sub_ic . ' ' . $val->pack_size . ' ' . $val->uom_name . ' ' . $val->color }}
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
            $('#item_id' + Counter).select2();

            Counter++;
            calculation_amount();
            
        }

        function getrate() {
            var selectedOption = document.querySelector('#curren option:checked');
            var rate = selectedOption.getAttribute('data-value');
            document.getElementById('exchange_rate').value = rate ? rate : '1.0';
        }
    

        function get_sub_item_by_id(instance) {
            var category = instance.value;
            $(instance).closest('.main').find('.itemsclass').empty();
            $.ajax({
                url: '{{ url("/getSubItemByCategory") }}',
                type: 'Get',
                data: { category: category },
                success: function (response) {
                    $(instance).closest('.main').find('.itemsclass').append(response);
                }
            });
        }

        function RemoveSection(row) {
            var element = document.getElementById("RemoveRows" + row);

            if (element) {
                element.parentNode.removeChild(element);
            }

            Counter--;
            calculation_amount();
        }
    </script>
    <script>
        function setSelectedOptionById(id) {
            // Get the select element
            console.log(id);
            var selectElement = document.getElementById('customer');

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
        // function item_change(datas) {
        //     var id = datas.value;
        //     $.ajax({
        //         url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
        //         type: 'Get',
        //         data: { id: id },
        //         success: function (data) {
        //             $(datas).closest('.main').find('#item_code').val(data.item_code);
        //             $(datas).closest('.main').find('#item_description').val(data.description);
        //             $(datas).closest('.main').find('#uom').val(data.uom_name);
        //         }
        //     });

        // }

        function get_item_name(index) {
            var item = $('#item_id' + index).val();

            var uom = item.split('@');
            console.log(uom);
            $('#uom_id' + index).val(uom[1]);
            $('#item_code' + index).val(uom[2]);
            $('#qty' + index).val(uom[3]);
            $('#qty_lbs' + index).val(uom[3]*2.20462);
            $('#pack_qty').val(uom[3]);
            $('#color' + index).val(uom[5]);
            $('#pack_size' + index).val(1);
            console.log(index);

            bag_qq(index);

        }

        function get_customer_details(id) {
            var id = id;
            $.ajax({
                url: '<?php echo url('/')?>/customer/get_customer',
                type: 'Get',
                processData: false,
                contentType: false,
                data: { id: id },
                success: function (data) {
                    console.log(data);


                }
            });

        }

        // function get_quotation_data(id)
        //     {
        //         var id = id;
        //         $('#more_details tbody').remove();
        //         $('#customer').val([]);
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
        //             });
        //             calculation_amount();

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
        }


        function bag_qq(counter) {
            var bags_qty = parseFloat($('#pack_size' + counter).val()) || 1;
            var pack_qty = parseFloat($('#pack_qty').val()) || 0;
            var total_qty = (bags_qty * pack_qty).toFixed(2);
            $('#qty' + counter).val(total_qty);
            $('#qty_lbs' + counter).val(total_qty*2.20462);
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

        $(document).ready(function () {

            $('#customer').select2();
            $('select').select2();
        })

        function getItemColors(id) {
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
                }
            });
        }

        function get_quotation_data(id)
        {
            var id = id;
            $('#appendData').empty();
            $('#customer_id').val([]);
            $('#sale_taxt_group').val([]);
            $('#sale_tax_rate').val('');
            $('#further_taxes_group').val([]);
            $('#further_tax').val('');
            $('#advance_tax_rate').val([]);
            $('#advance_tax').val('');

            $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/get_quotation_data',
                type: 'Get',
                data: { id:id },
                success: function (data) { 
                    $('#appendData').append(data);
                    var customer_id = $('#customer_id').val();
                    var sale_tax_rate1 = $('#sales_tax_rate').val();
                    var further_tax1 = $('#further_tax1').val();
                    var advance_tax1 = $('#advance_tax1').val();

                    var cartage = $('#cartage_amount1').val();
                    $('#cartage_amount').val(cartage);
                    
                    $('#customer').val(customer_id).change();
                    
                    var sale_tax_group = $('#sales_tax_group').val();
                    $('#sale_taxt_group option').each(function () {
                        if ($(this).data('value') == sale_tax_group) {
                            $('#sale_taxt_group').val($(this).val()).change(); 
                            return false;
                        } else {
                            $('#sale_taxt_group').val('').change();
                        }
                    });

                    var advance_tax_rate = $('#advance_tax_group1').val();
                    $('#advance_tax_rate option').each(function () {
                        if ($(this).data('value') == advance_tax_rate) {
                            $('#advance_tax_rate').val($(this).val()).change(); 
                            return false;
                        } else {
                            $('#advance_tax_rate').val('').change();
                        }
                    });

                    var further_taxes_group = $('#further_tax_group1').val();
                    $('#further_taxes_group option').each(function () {
                        if ($(this).data('value') == further_taxes_group) {
                            $('#further_taxes_group').val($(this).val()).change(); 
                            return false;
                        } else {
                            $('#further_taxes_group').val('').change();
                        }
                    });
                    // setSelectedOptionById('customer'+customer_id);
                    // setSelectedOptionById('tax'+sale_tax_group);
                    $('#sale_tax_rate').val(sale_tax_rate1);
                    $('#further_tax').val(further_tax1);
                    $('#advance_tax').val(advance_tax1);

                    var currency = $('#currency').val();
                    $('#curren option').each(function () {
                        if ($(this).data('id') == currency) {
                            $('#curren').val($(this).val()).change(); // Set the full "id,rate" as value
                            return false; // Stop loop once found
                        } else {
                            $('#curren').val('').change();
                        }
                    });
                
                    calculation_amount();
                }
            });
            calculation_amount();
        }

        // function setSelectedOptionById(id) {
        //     var optionElement = document.getElementById(id);
        //     var selectElement = optionElement?.parentElement;

        //     if (optionElement && selectElement) {
        //         selectElement.value = optionElement.value;

        //         // Trigger change event manually
        //         const event = new Event('change', { bubbles: true });
        //         selectElement.dispatchEvent(event);
        //     } else {
        //         console.error('Option or select element not found.');
        //     }
        // }
    </script>

@endsection