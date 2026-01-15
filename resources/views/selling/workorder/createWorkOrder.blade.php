@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('production_work_order','work_no','WO');
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
         
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid">
                           <h2 class="subHeadingLabelClass">View Production List</h2>
                        </div>
                                <form action="{{route('workOrderStore')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                        <div class="row qout-h">
                                            
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                    <label class="col-sm-4 control-label">Select SO.NO <span class="rflabelsteric"><strong>*</strong></span></label>
                                                    <div class="col-sm-8">
                                                        <select name="so_id" onchange="getSaleOrderDataCategory(this.value)" id="so_id" class="form-control">
                                                            <option value="">Select Order</option>
                                                            @foreach($sale_orders as $sale_order)
                                                            <option value="{{$sale_order->id}}">{{$sale_order->so_no}}</option>
                                                        
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Select Category <span class="rflabelsteric"><strong>*</strong></span></label>
                                                        <div class="col-sm-8">
                                                            <select name="category_id" onchange="getSaleOrderData(this.value)" id="category_id" class="form-control">
                                                                <option value="">Select Category</option>
                                
                                                            </select>
                                                        </div>
                                                        </div>
                                                    {{-- <div class="form-group">
                                                        <label class="col-sm-4 control-label">Sale Order No*</label>
                                                        <div class="col-sm-8">
                                                            <input name="sale_order_no" class="form-control" readonly value="{{$so_no}}" type="text">
                                                        </div>
                                                    </div> --}}
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Work Order No*</label>
                                                        <div class="col-sm-8">
                                                            <input name="work_order_no" class="form-control" readonly value="{{$so_no}}" type="text">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Work Order Date*</label>
                                                        <div class="col-sm-8">
                                                            <input  name="work_order_date" class="form-control" type="date" required>
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
                                                            <th>s.no</th>
                                                                <th>item</th>
                                                                <th>description</th>
                                                                <th>order Qty</th>
                                                                <th>order Unit</th>
                                                                <th>delivery Length</th>
                                                                <th>remarks</th>
                                                                <th>printing On Cable</th>
                                                                <th>identification Tape</th>
                                                                <th>any Specified Requirements</th>
                                                            </tr>
                                                            <tbody id="more_details">
                                                              
                                                            </tbody>
                                                          
                                                           
                                                           
                                                          
                                             
                                              
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12  text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                    <button type="button" class="btnn btn-secondary " data-dismiss="modal">Cancel</button>
                                                    
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

 
<script>
    function getSaleOrderDataCategory(id)
    {
        var id = id;
        $.ajax({
                url: '<?php echo url('/')?>/selling/getSaleOrderDataCategory',
                type: 'Get',
                data: {id:id},
             success: function (data) { 
                $('#category_id').empty();
                    $('#category_id').append(data);
             }
            });
         
    }
function getSaleOrderData(id)
    {
        var id = id;
        var so_id = $('#so_id').val();
        $.ajax({
                url: '<?php echo url('/')?>/selling/getSaleOrderData',
                type: 'Get',
                data: {category_id:id,so_id:so_id},
             success: function (data) { 
                $('#more_details').empty();
                    $('#more_details').append(data);
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