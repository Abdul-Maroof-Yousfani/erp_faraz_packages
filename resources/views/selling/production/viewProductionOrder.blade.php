
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
$ppc = CommonHelper::generateUniquePosNo('production_plane','order_no','PPC');
?>
<style>
    tbody.disabled {
        opacity: 0.5;
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
    
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N" style="margin-left: unset !important;">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                            <div class="row qout-h">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-4">
                                                        <label for="">Production Request No.</label>
                                                        <select readonly onchange="work_change(this)" name="pr_id" id="pr_id" class="form-control">
                                                            @foreach (ProductionHelper::getAllProductionRequests() as $work)
                                                                @if($ProductionPlane->pr_id == $work->id)
                                                                    <option @if($ProductionPlane->pr_id == $work->id) selected @endif value="{{$work->id}}" >{{$work->pr_no}}</option>
                                                                @endif    
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label for="">Production Order No.</label>
                                                        <input readonly type="text" name="production_no" value="{{$ProductionPlane->order_no}}" class="form-control">
                                                        <input readonly type="hidden" id="ppid"  value="{{$ProductionPlane->id}}" >
                                                    </div>
                                                    <div class="col-md-4 hide">
                                                        <label for="">SO No.</label>
                                                        <input  readonly type="text" name="so_no" class="form-control" id="so_number" value="{{ $ProductionPlane->sale_order_no }}">
                                                    </div>
                                                    <div class="col-md-4 hide">
                                                        <label for="">Customer Name</label>
                                                        <input readonly type="text" name="customer_name" class="form-control" id="customer">
                                                    </div> 
                                                    <div class="col-md-4 hide">
                                                        <label for="">Wall Thick. MM</label><br>
                                                        <input readonly type="number" step="any" name="wall_thickness_1" class="" id="wall_thickness_1" style="width: 53px;" value="{{$ProductionPlane->wall_thickness_1}}">
                                                        <span>+ / -</span>
                                                        <input readonly type="number" step="any" name="wall_thickness_2" class="" id="wall_thickness_2" style="width: 53px;" value="{{$ProductionPlane->wall_thickness_2}}">
                                                        <span>---</span>
                                                        <input readonly type="number" step="any" name="wall_thickness_3" class="" id="wall_thickness_3" style="width: 53px;" value="{{$ProductionPlane->wall_thickness_3}}">
                                                        <span>to</span>
                                                        <input readonly type="number" step="any" name="wall_thickness_4" class="" id="wall_thickness_4" style="width: 53px;" value="{{$ProductionPlane->wall_thickness_4}}">
                                                
                                                    </div> 
                                                    <div class="col-md-4 hide">
                                                        <label for="">Pipe outer dia MM</label><br>
                                                        <input readonly type="number" step="any" name="pipe_outer" class="form-control" id="pipe_outer" style="" value="{{$ProductionPlane->pipe_outer}}">
                                                    
                                                    </div> 
                                                    <div class="col-md-12 hide">
                                                        <label for="">Printing on pipe</label><br>
                                                        <textarea readonly name="printing_on_pipe" class="form-control" id="printing_on_pipe" style="">{{$ProductionPlane->printing_on_pipe}}</textarea>
                                                    
                                                    </div> 
                                                    <div class="col-md-8">
                                                        <label for="">Special Instructions</label><br>
                                                        <textarea readonly name="special_instructions" class="form-control" id="special_instructions" style="">{{$ProductionPlane->special_instructions}}</textarea>
                                                    </div> 
                                                </div>
                                                <div class="col-md-12 ">
                                                    <div class="col-md-12" id="AppnedHtml">
                                                        <table  class="userlittab table table-bordered sf-table-list" id="more_details"></table>
                                                    </div>
                                                </div>
                                            </div>        
                                        </div>
                                    </div>
                                </div>
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
        let id = datas.value;

        let ppid = $('#ppid').val();
        if(id)
        {

            $.ajax({
                url: '<?php echo url('/')?>/selling/getWorkOrderDataForView',
                type: 'Get',
                data: {
                    id:id,
                    ppid:ppid
                },
                success: function (data) {

                    $('#more_details').empty();
                    $('#more_details').append(data);
                    var order_no =  $('#order_no').val();
                    var customer_name =  $('#customer_name').val();
                    $('#so_number').val(order_no);
                    $('#customer').val(customer_name);

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
        let ids = $(datas).closest('.main').find('.item').val();  
        var warehouse_id = $(datas).closest('.main').find('.warehouse_id').val();  
        $.ajax({
            url: '<?php echo url('/')?>/selling/getStockForProduction',
            type: 'Get',
            data: {id:ids,warehouse_id:warehouse_id},
            success: function (data) {
                $(datas).closest('.main').find('.in_stock').val(data);
            }
        });
    }

    function getReceipeData(instnace)
    {
        let id=  instnace.value;
        $(instnace).closest('.recipe_details').find('.receipe1').empty();
        $.ajax({
            url: '<?php echo url('/')?>/selling/getReceipeDataView',
            type: 'Get',
            data: {id:id},
            success: function (responsedata) {
                $(instnace).closest('.recipe_details').find('.receipe1').append(responsedata);
                var order_qty =  $(instnace).closest('.receipe_main').find('.order_qty').val();
                var uom_name =  $(instnace).closest('.receipe_main').find('.uom_name').val();
                var division = uom_name == 'Piece' ? 1 : 1000; 
                console.log(uom_name);
                $('.row_recipe').each(function(key,value){
                    var required_qty =   $(this).closest('.row_recipe').find('.reqired_qty').val();
                    var total = ( Number(parseFloat(order_qty) / division)) * parseFloat(required_qty);  
                    $(this).closest('.row_recipe').find('.requested_qty').val(total);
                });
            }
        });
    }

    $(document).ready(function () {
        $('#pr_id').trigger('change');
    })
</script>

   
