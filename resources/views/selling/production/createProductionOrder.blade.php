@extends('layouts.default')
@section('content')
@include('select2')
<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
$ppc = CommonHelper::generateUniquePosNoWithStatusOne('production_plane', 'order_no', 'PPC');
$m = Session::get('run_company');
?>
<style>
    tbody.disabled {
        opacity: 0.5;
        /* You can adjust the styling for disabled rows */
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

<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="headquid bor-bo">
                                    <h2 class="subHeadingLabelClass">Create Production Order</h2>
                                </div> 
                                <div class="row">&nbsp;</div>
                                {{ Form::open(array('url' => 'selling/storeProductionOrder?m=' . $m, 'id' => 'productionPlan' )) }}
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="formSection[]" id="formSection" value="1" />
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="row qout-h">
                                                <div class="col-md-4">
                                                    <label for="">Production Request No.</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select onchange="work_change(this)" name="pr_id"
                                                        id="pr_id" class="form-control select2 requiredField">
                                                        <option value="">Select Production Request</option>
                                                        @foreach (ProductionHelper::getAllProductionRequests() as $work)
                                                            <option value="{{$work->id}}">{{$work->pr_no}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="">Production Order No.</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input readonly type="text" name="production_no"
                                                        value="{{$ppc}}" class="form-control requiredField">
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="">Batch Code</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input readonly type="text" name="batch_code"
                                                        value="{{ $batch_code }}" class="form-control requiredField">
                                                </div>
                                                <div class="col-md-4 hide">
                                                    <label for="">PR No.</label>
                                                    <input readonly type="text" name="pr_no"
                                                        class="form-control" id="pr_no">
                                                </div>
                                                <div class="col-md-4 hide">
                                                    <label for="">Customer Name</label>
                                                    <input readonly type="text" name="customer_name"
                                                        class="form-control" id="customer">
                                                </div>
                                                <div class="col-md-4 hide">
                                                    <label for="">Wall Thick. MM</label><br>
                                                    <input type="number" step="any" name="wall_thickness_1"
                                                        class="" id="wall_thickness_1" style="width: 53px;">
                                                    <span>+ / -</span>
                                                    <input type="number" step="any" name="wall_thickness_2"
                                                        class="" id="wall_thickness_2" style="width: 53px;">
                                                    <span>----></span>
                                                    <input type="number" step="any" name="wall_thickness_3"
                                                        class="" id="wall_thickness_3" style="width: 53px;">
                                                    <span>to</span>
                                                    <input type="number" step="any" name="wall_thickness_4"
                                                        class="" id="wall_thickness_4" style="width: 53px;">

                                                </div>
                                                <div class="col-md-4 hide">
                                                    <label for="">Pipe outer dia MM</label><br>
                                                    <input type="number" step="any" name="pipe_outer"
                                                        class="form-control" id="pipe_outer" style="">

                                                </div>
                                                <div class="col-md-4 hide">
                                                    <label for="">Length</label><br>
                                                    <input type="number" step="any" name="length"
                                                        class="form-control" id="length" style="">

                                                </div>
                                                <div class="col-md-12 hide">
                                                    <label for="">Printing on pipe</label><br>
                                                    <textarea name="printing_on_pipe" class="form-control"
                                                        id="printing_on_pipe" style=""></textarea>

                                                </div>
                                                <div class="col-md-12">
                                                    <label for="">Special Instructions</label><br>
                                                    <textarea name="special_instructions" class="form-control"
                                                        id="special_instructions" style=""></textarea>
                                                </div>
                                                <div class="col-md-12 ">
                                                    <!-- <div class="headquid ">
                                                    <h2 class="subHeadingLabelClass">Work Order Details</h2>
                                                </div> -->
                                                    <div class="col-md-12" id="AppnedHtml">
                                                        <table class="userlittab table table-bordered sf-table-list"
                                                            id="more_details">
                                                        </table>
                                                    </div>

                                                </div>
                                                <div class="col-md-12 padtb text-right">
                                                    <div class="col-md-9"></div>
                                                    <div class="col-md-3 my-lab">
                                                        <button type="submit" class="btn btn-success">Save</button>
                                                        <button type="button" class="btn btn-primary">Cancel</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    $(function () {
        $('.select2').select2();
        $(".btn-success").click(function(e){
            var formSection = new Array();
            var val;
            $("input[name='formSection[]']").each(function(){
                formSection.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of formSection) {
                jqueryValidationCustom();
                if(validate == 0){
                    $('#productionPlan').submit();
                }  else {
                    return false;
                }
            }
        });
    });

    function getBomReceipe(datas) {
        $(datas).closest('.receipe_main').find('.recipe_qty').empty();
        var receipe_id = $(datas).closest('.receipe_main').find('.receip_id').val();
        let category_id = datas.value;
        $.ajax({
            url: '<?php echo url('/')?>/selling/getOverAllstock',
            type: 'Get',
            data: { category_id: category_id, receipe_id: receipe_id },
            success: function (data) {

                $(datas).closest('.receipe_main').find('.recipe_qty').val(data);
                var Order_qty = $(datas).closest('.receipe_main').find('.order_qty').val();
                var total = data * Order_qty;
                $(datas).closest('.receipe_main').find('.required_qty').val(total);
            }
        });

    }

    function work_change(datas) {
        let id = datas.value;
        if (id) {

            $.ajax({
                url: '<?php echo url('/')?>/selling/getWorkOrderData',
                type: 'Get',
                data: { id: id },
                success: function (data) {

                    $('#more_details').empty();
                    $('#more_details').append(data);
                    var order_no = $('#order_no').val();
                    var customer_name = $('#customer_name').val();
                    $('#pr_no').val(order_no);
                    $('#customer').val(customer_name);

                    // setTimeout(() => {
                    //     $('.receip_id').trigger('change');
                    // }, 2000);
                }
            });

        }
        else {
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
            $(checkbox).closest('.receipe_main').find('.start_date').removeClass('requiredField', true);
            $(checkbox).closest('.receipe_main').find('.delivery_date').removeClass('requiredField', true);


            for (var i = 0; i < inputs.length; i++) {
                inputs[i].disabled = true;
            }

        } else {
            $(checkbox).closest('.receipe_main').find('.category').prop('disabled', false);
            $(checkbox).closest('.receipe_main').find('.receip_id').prop('disabled', false);
            $(checkbox).closest('.receipe_main').find('.start_date').addClass('requiredField', true);
            $(checkbox).closest('.receipe_main').find('.delivery_date').addClass('requiredField', true);
            // If checkbox is unchecked, disable the row
            row.classList.remove('disabled');
            for (var i = 0; i < inputs.length; i++) {
                inputs[i].disabled = false;
            }
        }
    }
    function removes(count) {
        console.log(count);
        $('#remove' + count).remove();
        counter--;
    }

    var counter = 1;
    var option = '';
    function addRow() {
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



    function getStock1(datas) {
        let ids = $(datas).closest('.main').find('.item').val();
        var warehouse_id = $(datas).closest('.main').find('.warehouse_id').val();
        $.ajax({
            url: '<?php echo url('/')?>/selling/getStockForProduction',
            type: 'Get',
            data: { id: ids, warehouse_id: warehouse_id },
            success: function (data) {
                $(datas).closest('.main').find('.in_stock').val(data);
            }
        });
    }
    // var abc = '';
    function getReceipeData(instnace, count, class_name) {
        // abc = instnace;
        let id = instnace.value;
        $(instnace).closest('#row_of_data' + count).find('.receipe' + count).empty();
        $.ajax({
            url: '<?php echo url('/')?>/selling/getReceipeData',
            type: 'Get',
            data: { id: id, class_name: class_name },
            success: function (responsedata) {
                console.log(responsedata, instnace, count, 'instnace');
                $(instnace).closest('#row_of_data' + count).find('.receipe' + count).append(responsedata);
                //    var order_qty = $('.order_qty').val();
                var order_qty = $(instnace).closest('#row_of_data' + count).find('.order_qty').val();
                var uom_name = $(instnace).closest('#row_of_data' + count).find('.uom_name').val();

                $(instnace).closest('#row_of_data' + count).find('.row_recipe').each(function (key, value) {
                    var raw_material_quantity = $(this).closest('.row_recipe').find('.reqired_qty').val();
                    var recipe_qty = $(this).closest('.row_recipe').find('.recipe_qty').val();
                    var total = Number(parseFloat(raw_material_quantity * (order_qty / recipe_qty)));
                    //var total = (Number(parseFloat(order_qty) / division)) * parseFloat(required_qty);
                    //var total = (Number(parseFloat(order_qty))) ;
                    $(this).closest('.row_recipe').find('.requested_qty').val(total);
                });

            }
        });

    }
    // function getReceipeData(instance , count) {
    //     let abc = instance;
    //     let id = instance.value;
    //     let parentRecipeDetails = instance.closest('#row_of_data'+count);
    //     let receipe1 = parentRecipeDetails.querySelector('.receipe'+count);
    //     receipe1.innerHTML = ''; // Clear previous content

    //     fetch('<?php echo url('/')?>/selling/getReceipeData?id=' + id)
    //         .then(response => {
    //             if (!response.ok) {
    //                 throw new Error('Network response was not ok');
    //             }
    //             return response.text();
    //         })
    //         .then(responsedata => {
    //             receipe1.innerHTML = responsedata;

    //             let order_qty = parentRecipeDetails.closest('#row_of_data'+count).querySelector('.order_qty').value;
    //             parentRecipeDetails.querySelectorAll('.row_recipe').forEach(row => {
    //                 let required_qty = row.querySelector('.reqired_qty').value;
    //                 let total = (parseFloat(order_qty) / 1000) * parseFloat(required_qty);
    //                 row.querySelector('.requested_qty').value = total;
    //             });
    //         })
    //         .catch(error => {
    //             console.error('Error fetching recipe data:', error);
    //         });
    // }

    // var abc = '';

    function get_sub_item_by_id(instance, num, value) {
        console.log(instance, num, value, 'get_sub_item_by_id');
        var category = instance.value;
        $(instance).closest('.row_recipe').find('.item_id').empty();
        console.log('check123');
        $.ajax({
            url: '{{ url("/getSubItemByCategory") }}',
            type: 'Get',
            data: { category: category, item_id: value },
            success: function (response) {
                $(instance).closest('.row_recipe').find('.item_id').append(response);
                console.log(response, 'item description');

                if (value != 0) {
                    console.log(value)
                    setTimeout(() => {
                        $(instance).closest('.row_recipe').find('.item_id').val(value);
                        //    $('#item_id'+num).val(value);
                    }, 2000);

                }
            }
        });
    }


</script>



@endsection