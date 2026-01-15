@extends('layouts.default')

@section('content')
@include('select2')

<?php
use App\Helpers\CommonHelper;
$ppc=CommonHelper::generateUniquePosNoWithStatusOne('production_plane','order_no','PPC');


// echo "<pre>";
// print_r(CommonHelper::get_sub_category()->get());
// exit();
?>
<style>
    tbody.disabled {
  opacity: 0.5;  /* You can adjust the styling for disabled rows */
  /* Add any other styles as needed */
}
input[type="checkbox"] {
    width: 30px;
    height: 30px;
}

input[type="number"]::-webkit-inner-spin-button,
input[type="number"]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
</style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Production Order</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <!-- <ul class="cus-ul2">
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
            </ul>  -->
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
                            <div class="headquid bor-bo">
                           <h2 class="subHeadingLabelClass">Create General Production Plan</h2>
                        </div>
                                <form action="{{route('storeGeneralProductionOrder')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row qout-h">
                                           
                                            
                                            
                                            <div class="col-md-12 padt">
                                                <div class="col-md-2">
                                                    <h2>Production Details</h2>
                                                </div>
                                                <div class="col-md-10">
                                                    <div class="col-md-4">
                                                        <label for="">Category</label>
                                                        <select onchange="get_sub_item_by_id_main(this)" name="Category_main" id="Category" class="form-control">
                                                            <option value="">Select Category</option>
                                                            @foreach (CommonHelper::get_sub_category()->get() as $value)
                                                                <option value="{{$value->id}}" >{{$value->sub_category_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                 
                                                    <div class="col-md-4">
                                                        <label for="">Item </label>
                                                        <select onchange="work_change(this)" name="finish_good" id="item" class="form-control">
                                                            <option value="">Select Item</option>
                                                   
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">Production Order No.</label>
                                                        <input readonly type="text" name="production_no" value="{{$ppc}}" class="form-control">
                                                    </div>
                                                    
                                                    <div class="col-md-4">
                                                        <label for="">Wall Thick. MM</label><br>
                                                        <input type="number" step="any" name="wall_thickness_1" class="" id="wall_thickness_1" style="width: 53px;">
                                                        <span>+ / -</span>
                                                        <input type="number" step="any" name="wall_thickness_2" class="" id="wall_thickness_2" style="width: 53px;">
                                                        <span>----></span>
                                                        <input type="number" step="any" name="wall_thickness_3" class="" id="wall_thickness_3" style="width: 53px;">
                                                        <span>to</span>
                                                        <input type="number" step="any" name="wall_thickness_4" class="" id="wall_thickness_4" style="width: 53px;">
                                                 
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <label for="">Pipe outer dia MM</label><br>
                                                        <input type="number" step="any" name="pipe_outer" class="form-control" id="pipe_outer" style="">
                                                       
                                                    </div> 
                                                    <div class="col-md-4">
                                                        <label for="">Length</label><br>
                                                        <input type="number" step="any" name="length" class="form-control" id="length" style="">
                                                       
                                                    </div> 
                                                    <div class="col-md-12">
                                                        <label for="">Printing on pipe</label><br>
                                                        <textarea name="printing_on_pipe" class="form-control" id="printing_on_pipe" style=""></textarea>
                                                       
                                                    </div> 
                                                    <div class="col-md-12">
                                                        <label for="">Special Instructions</label><br>
                                                        <textarea name="special_instructions" class="form-control" id="special_instructions" style=""></textarea>
                                                       
                                                    </div> 
                                                   
                                                </div>
                                            </div>
                                            <div class="col-md-12 ">
                                                <!-- <div class="headquid ">
                                                    <h2 class="subHeadingLabelClass">Work Order Details</h2>
                                                </div> -->
                                                
                                                <div class="col-md-12" id="AppnedHtml">
                                
                                                    <table  class="userlittab table table-bordered sf-table-list" id="more_details">
                                                    </table>
                                                </div>
                                 
                                            </div>

                                            
                                            
                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" disabled data-dismiss="modal">Save</button>
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
function getBomReceipe(datas)
      {
          $(datas).closest('.receipe_main').find('.recipe_qty').empty();
         var receipe_id = $(datas).closest('.receipe_main').find('.receip_id').val();
         let category_id =datas.value;  
         $.ajax({
             url: '<?php echo url('/')?>/selling/getOverAllstock',
             type: 'Get',
             data: {category_id:category_id,receipe_id:receipe_id},
             success: function (data) {
               
                                     $(datas).closest('.receipe_main').find('.recipe_qty').val(data);
                    var Order_qty =  $(datas).closest('.receipe_main').find('.order_qty').val();
                    var total =  data*Order_qty;
                  $(datas).closest('.receipe_main').find('.required_qty').val(total);
                   }
                  });
                  
      }
 
function work_change(datas)
    {
        $('#more_details').empty();

        let id = datas.value;
        if(id)
        {

            $.ajax({
                url: '<?php echo url('/')?>/selling/getRecipeOfItem',
                type: 'Get',
                data: {id:id},
             success: function (data) {

                $('#more_details').empty();
                $('#more_details').append(data);
               
                setTimeout(() => {
                    $('.receip_id').trigger('change');
                }, 2000);
            }
        });
        
        }
        else{
            $('#more_details').empty();
            $('#so_number').val('');
            $('#customer').val('');
        }
    }

function toggleRow(checkbox) {
  // Get the closest <tr> element
  var row = checkbox.closest('.row_of_data');
  var inputs = row.querySelectorAll('input:not([type="checkbox"])');
  // Check the checkbox state
  if (checkbox.checked) {
    row.classList.add('disabled');
    $(checkbox).closest('.receipe_main').find('.category').prop('disabled', true);
    $(checkbox).closest('.receipe_main').find('.receip_id').prop('disabled', true);
   

    for (var i = 0; i < inputs.length; i++) {
      inputs[i].disabled = true;
    }

  } else {
    $(checkbox).closest('.receipe_main').find('.category').prop('disabled', false);
    $(checkbox).closest('.receipe_main').find('.receip_id').prop('disabled', false);
    // If checkbox is unchecked, disable the row
    row.classList.remove('disabled');
    for (var i = 0; i < inputs.length; i++) {
      inputs[i].disabled = false;
    }
  }
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
      <select  onchange="getStock(this)" class="form-control item" name="item[]">
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
        <input type="button" onclick="removes('${counter}')" class="btn btn-success" value="-"  >
        
        </td>
  </tr>
    `);
    counter++;

}



function getStock1(datas)
{
    let ids =   $(datas).closest('.main').find('.item').val();  
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
function getReceipeDataOfSingleItem(instnace)
{

    let uomArray = ['Metre','Mtrs'];
    let uom = $('#uom').val()
    let total = 0 ;
    let id=  instnace.value;
     $(instnace).closest('.recipe_details').find('.receipe1').empty();
     $.ajax({
                url: '<?php echo url('/')?>/selling/getReceipeDataOfSingleItem',
                type: 'Get',
                data: {id:id},
             success: function (responsedata) {
               $(instnace).closest('.recipe_details').find('.receipe1').append(responsedata);
            //    var order_qty = $('.order_qty').val();
                 var order_qty =  $(instnace).closest('.receipe_main').find('.order_qty').val();
                $('.row_recipe').each(function(key,value){
                  var required_qty =   $(this).closest('.row_recipe').find('.reqired_qty').val();

                  if(uomArray.includes(uom)){
                        total = ( Number(parseFloat(order_qty) / 1000)) * parseFloat(required_qty);  
                  }
                  else
                  {
                        total = Number(parseFloat(order_qty)) * parseFloat(required_qty);  
                  }

                  
                  
                  
                  $(this).closest('.row_recipe').find('.requested_qty').val(total);
                });
                
             }
            });

}


function  get_sub_item_by_id_main(instance)
	{
        $('#item').empty().select2();

        $('#more_details').empty();

		var category= instance.value;

        $('#item').empty();
		$.ajax({
			url: '{{ url("/getSubItemByCategory") }}',
			type: 'Get',
			data: {category: category},
			success: function (response) {

                $('#item').empty().append(response);

                // Reinitialize Select2
                $('#item').select2();
			}
		});
	}

$(document).ready(()=>{
    $('#Category').select2();
    $('#item').select2();
})
</script>


  
@endsection