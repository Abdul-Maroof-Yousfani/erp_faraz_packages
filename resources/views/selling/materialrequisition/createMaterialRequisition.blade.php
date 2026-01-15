@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$mr_no =CommonHelper::generateUniquePosNo('material_requisitions','mr_no','MR');
?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Raw Material</h3>
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
                                <form action="{{route('storeMaterialRequisition')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>Create Material Requisition</h1>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Requisition No.</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                           <input name="requisition_no" type="text" value="{{$mr_no}}" readonly class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Requisition Date</label>
                                                        <div class="col-sm-8">
                                                            <input name="requisition_date" value="{{date('Y-m-d')}}" class="form-control" type="date">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Production No</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                          <input name="production_no" type="text" readonly value="{{$prodtion_data->order_no}}" class="form-control" name="" id="">
                                                          <input name="production_id" type="hidden" readonly value="{{$prodtion_data->master_id}}" class="form-control" name="" id="">
                                                          <input name="production_data_id" type="hidden" readonly value="{{$prodtion_data->id}}" class="form-control" name="" id="">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Finish Good </label>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label for="">Finish Good Product</label>
                                                          <input type="text" readonly value="{{ CommonHelper::get_item_by_id($prodtion_data->finish_goods_id)->sub_ic}}" class="form-control" name="" id="">
                                                          <input type="hidden" readonly value="{{ $prodtion_data->finish_goods_id}}" class="form-control" name="finish_goods_id">
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <label for="">Order Length/Qty</label>
                                                            <input type="text" readonly value="{{$prodtion_data->planned_qty}}" class="form-control" name="finish_good_qty" id="">
                                                          </div>
                                                    </div>
                                                    
                                                
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    
                                                </div>
                                            </div>

                                            {{-- Details  --}}
                                            <input type="hidden" name="receipt_id" value="{{$prodtion_data->receipt_id}}">
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-3">
                                                    <label for="">Category</label>
                                                    <select name="category" onchange="getOverAllstock(this)" class="form-control" id="category">
                                                      <option  value="">Select Category</option>
                                                      @foreach(CommonHelper::get_sub_category()->get() as $sub_category)
                                                     
                                                      @if($prodtion_data->category_id == $sub_category->id)
                                                      <option selected  value="{{$sub_category->id}}">{{$sub_category->sub_category_name}}</option>
                                                        @endif
                                                      @endforeach
                                                    </select>
                                                </div>
                                                {{-- <div class="col-md-3">
                                                    <label for="">In stock</label>
                                                    <input type="number" readonly class="form-control" name="in_stock[]" id="in_stock">
                                                </div> --}}
                                                <div class="col-md-3">
                                                    <label for="">Required Qty KG/Piece </label>   
                                                    <input type="text" value="{{$prodtion_data->required_qty}}" readonly class="form-control" name="qty_for_making_product" id="qty_for_making_product">
                                              
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="button" onclick="addRow()" class="btn btn-sm  btn-success" value="+" id="">
                                                </div>
                                            </div>
                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>

                                                                <th>Product Name</th>
                                                                <th>warehouse</th>
                                                                <th>In Stock </th>
                                                                <th>Total Qty issue</th>
                                                                <th></th>
                                                              
                                                            </tr>
                                                            <tbody class="add">
                                                    
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
    <script>
        $(document).ready(function(){
            getOverAllstock();
        });
        function getStock1(datas)
{
    var ids =   $(datas).closest('.main').find('.item').val();  
    var warehouse_id =   $(datas).closest('.main').find('.warehouse_id').val();  
    $.ajax({
                url: '<?php echo url('/')?>/selling/getStockForProduction',
                type: 'Get',
                data: {id:ids,warehouse_id:warehouse_id},
             success: function (data) {
                $(datas).closest('.main').find('.in_stock').val(data);
             }
            });
}

function removes(count)
{
    console.log(count);
    $('#remove'+count).remove();
    counter--;
}
var counter =1;
var option = '';
function addRow()
{
    var html = '';

    $('.add').append(`
    <tr class="main" id="remove${counter}">
    <td>
      <select  onchange="getStock1(this)" class="form-control item" name="item[]">
        <option  value="">Select Item</option>
        ${option}
      </select>
    </td>
    <td>
      <select  onchange="getStock1(this)" class="form-control warehouse_id" name="warehouse_id[]" id="">
        <option value="">Select Warehouse</option>
        @foreach(CommonHelper::get_all_warehouse() as $warehouse)
        <option value="{{$warehouse->id}}">{{$warehouse->name}}</option>
        @endforeach
      </select>
    </td>
    <td>
      <input type="number" readonly class="form-control in_stock" name="in_stock[]" id="">
    </td>
    <td>
      <input type="number" class="form-control" name="required_qty[]" id="">
    </td>
    <td>
        <input type="button" onclick="removes('${counter}')" class="btn btn-danger" value="-"  >
        
        </td>
  </tr>
    `);
    counter++;

}
function getOverAllstock()
{
    let id = $('#category').find(":selected").val();

    $('.item').each(function(){
                    $(this).empty();
     });
    $.ajax({
                url: '<?php echo url('/')?>/selling/getOverAllstock',
                type: 'Get',
                data: {id:id},
             success: function (data) {
                // $('#in_stock').val(data.overallqty);
                $('.item').each(function(){
                    $(this).append(data.items);
                });
        
                option = data.items;
             }
            });
            
}
    </script>
@endsection