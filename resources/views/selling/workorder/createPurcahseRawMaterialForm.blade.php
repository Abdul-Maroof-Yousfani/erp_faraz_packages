@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('sales_order','so_no','SO');
?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Create Work Order</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
                {{-- <li>
                    <input type="text" class="fomn1" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul> 
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <form action="{{route('salesorder.store')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>Create Work Order</h1>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="col-sm-4 control-label">Select SO.NO <span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <div class="col-sm-8">
                                                        <select name="" id="" class="form-control">
                                                            <option value="">Select Order</option>
                                                            @foreach($sale_orders as $sale_order)
                                                            <option value="{{$sale_order->id}}">{{$sale_order->so_no}}</option>
                                                        
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Sale Order No*</label>
                                                        <div class="col-sm-8">
                                                            <input name="sale_order_no" class="form-control" readonly value="{{$so_no}}" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Work Order No*</label>
                                                        <div class="col-sm-8">
                                                            <input name="sale_order_no" class="form-control" readonly value="{{$so_no}}" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Sale Order Date*</label>
                                                        <div class="col-sm-8">
                                                            <input  name="sale_order_date" class="form-control" type="date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    
                                                </div>
                                            </div>


                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>ITEM</th>
                                                                <th>DESCRIPTION</th>
                                                                <th>ORDER QTY</th>
                                                                <th>ORDER UNIT</th>
                                                                <th>DELIVERY LENGTH</th>
                                                                <th>REMARKS</th>
                                                                <th>Identification Tape</th>
                                                                <th>Any specified requirements</th>
                                                            </tr>
                                                            <tbody>
                                                                <tr>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                    <td>dummy</td>
                                                                </tr>
                                                            </tbody>
                                                          
                                                           
                                                           
                                                          
                                             
                                              
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
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
        </div>
    </div>

    <script type="text/javascript">
      
        var Counter =0
       function AddMoreDetails()
       {
        mainCount = $('.main').length;
        Counter =mainCount+1;
        $('#more_details').append(`<tr class="m-tab main" id="RemoveRows${Counter}">
            <td>
        <select onchange="item_change(this)" class="form-control" name="item_id[]" id="">
                @foreach(CommonHelper::get_all_subitem() as $item)
                <option  value="{{$item->id}}">{{$item->sub_ic}}</option> 
                @endforeach
        </select>             
                </td>
                <td><input class="form-control items_class" type="text" name="item_code[]" id="item_code" value=""></td>
                <td><input class="form-control" type="text" name="thickness[]" id="thickness"></td>
                <td><input class="form-control" type="text" name="diameter[]" id="diameter"></td>
                <td><input class="form-control" onkeyup="calculation_amount()" type="text" name="qty[]" id="qty" value=""></td>
                <td><input class="form-control" onkeyup="calculation_amount()" type="text" name="rate[]" id="rate" value=""></td>
                <td><input class="form-control" type="text" name="uom[]" id="" value=""></td>
                <td><input class="form-control" type="text" name="printing[]" id=""></td>
                <td><input class="form-control" type="text" name="special_ins[]" id="item_description"></td>
                <td><input class="form-control" type="date" name="delivery_date[]" id=""></td>
                <td><input class="form-control" type="text" name="total[]" id="total" value=""></td>
                    <td>
                                                                    <button type="button" class="btn btn-sm btn-danger" onclick="RemoveSection(${Counter})"> <span class="glyphicon glyphicon-trash"></span> </button>
                                                                </td>
                                                            </tr>`);
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
      function item_change(datas)
    {
        var id = datas.value;
        $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/get_item_by_id',
                type: 'Get',
                data: {id:id},
             success: function (data) {
                $(datas).closest('.main').find('#item_code').val(data.item_code);
                $(datas).closest('.main').find('#item_description').val(data.description);
             }
            });

    }
    function get_customer_details(id)
    {
        var id = id;
        $.ajax({
                url: '<?php echo url('/')?>/customer/get_customer',
                type: 'Get',
                processData: false,  
                contentType: false,
                data: {id:id},
             success: function (data) {
                console.log(data);
            
               
             }
            });

    }
</script>
<script>
function get_quotation_data(id)
    {
        var id = id;
        $.ajax({
                url: '<?php echo url('/')?>/saleQuotation/get_quotation_data',
                type: 'Get',
                data: {id:id},
             success: function (data) { 
                    $('#more_details').append(data);
                    var customer_id = $('#customer_id').val();
                    setSelectedOptionById('op'+customer_id);
                    calculation_amount();

             }
            });
         
    }
    function calculation_amount()
    {
        var grad_total = 0;
        $('.items_class').each(function(){
           var actual_rate =  $(this).closest('.main').find('#rate').val();
           var actual_qty =  $(this).closest('.main').find('#qty').val();
           var rate =  actual_rate? actual_rate : 0;
           var qty =  actual_qty? actual_qty : 0;
           var total = parseFloat(qty) * parseFloat(rate);
           grad_total +=total;
            $(this).closest('.main').find('#total').val(total);
        })
        document.getElementById('grand_total').innerHTML = grad_total;
    }
</script>

@endsection