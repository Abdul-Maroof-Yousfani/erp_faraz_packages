<?php
use App\Helpers\CommonHelper;
$ppc = CommonHelper::generateUniquePosNo('production_plane', 'order_no', 'PPC');
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
        <div class="well_N" style="margin-left: unset !important;">
            <div class="dp_sdw">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-6 text-right hidden-print">
                        <h1><?php CommonHelper::displayPrintButtonInView('printReport', '', '1'); ?></h1>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printReport">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                        <?php echo CommonHelper::get_company_logo(Session::get('run_company')); ?>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6 text-center">
                                        <h2 class="subHeadingLabelClass">PRODUCTION PLAN</h2>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6"></div>
                                </div>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class="row qout-h">
                                            <div class="col-md-12">
                                                <div class="col-md-6" id="AppnedHtml">
                                                    <table class="userlittab table table-bordered">
                                                        <tr>
                                                            <td>Finished Item:</td>
                                                            <td>{{ $data->item_code.' -- '.$data->sub_ic }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Color :</td>
                                                            <td>{{ $data->color }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Production Request No. :</td>
                                                            <td>{{ $data->pr_no }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Work Order No. : </td>
                                                            <td>{{ $data->order_no }}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                <div class="col-md-6">
                                                    <table class="userlittab table table-bordered">
                                                        <tr>
                                                            <td>Start Date : </td>
                                                            <td>{{ CommonHelper::changeDateFormat($data->start_date) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>End Date : </td>
                                                            <td>{{ CommonHelper::changeDateFormat($data->delivery_date) }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>SPECIAL INSTRUCTION :</td>
                                                            <td>{{ $data->special_instructions }}</td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="m-form">
                                                        <table class="table table-bordered" style="width: 600px;">
                                                            <thead>
                                                                <tr>
                                                                    <td class="text-center" colspan="3"><h3>RAW MATERIALS</h3></td>
                                                                </tr>
                                                                <tr>
                                                                    <th class="text-center">Category</th>
                                                                    <th class="text-center">item</th>
                                                                    <th class="text-center">Quantity</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($rawMaterialList as $key => $value)
                                                                <?php 
                                                                $product = CommonHelper::get_item_by_id($value->item_id);
                                                                ?>
                                                                    <tr>
                                                                        <td class="text-center">{{ CommonHelper::get_category_name($value->bom_data_id) }} </td>
                                                                        <td class="text-center">{{ $product->item_code.' -- '.$product->sub_ic }} </td>
                                                                        <td class="text-center">{{ number_format($value->request_qty, 2) }} </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
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
    function getBomReceipe(datas) {
        $(datas).closest('.receipe_main').find('.recipe_qty').empty();
        var receipe_id = $(datas).closest('.receipe_main').find('.receip_id').val();
        let category_id = datas.value;
        $.ajax({
            url: '<?php echo url('/'); ?>/selling/getOverAllstock',
            type: 'Get',
            data: {
                category_id: category_id,
                receipe_id: receipe_id
            },
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

        let ppid = $('#ppid').val();
        if (id) {

            $.ajax({
                url: '<?php echo url('/'); ?>/selling/getWorkOrderDataForView',
                type: 'Get',
                data: {
                    id: id,
                    ppid: ppid
                },
                success: function (data) {

                    $('#more_details').empty();
                    $('#more_details').append(data);
                    var order_no = $('#order_no').val();
                    var customer_name = $('#customer_name').val();
                    $('#so_number').val(order_no);
                    $('#customer').val(customer_name);

                    setTimeout(() => {
                        $('.receip_id').trigger('change');
                    }, 2000);
                }
            });

        } else {
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
        @foreach (CommonHelper::get_all_warehouse() as $warehouse)
            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
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
            url: '<?php echo url('/'); ?>/selling/getStockForProduction',
            type: 'Get',
            data: {
                id: ids,
                warehouse_id: warehouse_id
            },
            success: function (data) {
                $(datas).closest('.main').find('.in_stock').val(data);
            }
        });
    }

    function getReceipeData(instnace) {
        let id = instnace.value;
        $(instnace).closest('.recipe_details').find('.receipe1').empty();
        $.ajax({
            url: '<?php echo url('/'); ?>/selling/getReceipeDataView',
            type: 'Get',
            data: {
                id: id
            },
            success: function (responsedata) {
                $(instnace).closest('.recipe_details').find('.receipe1').append(responsedata);
                //    var order_qty = $('.order_qty').val();
                var order_qty = $(instnace).closest('.receipe_main').find('.order_qty').val();
                $('.row_recipe').each(function (key, value) {
                    var required_qty = $(this).closest('.row_recipe').find('.reqired_qty').val();
                    var total = (Number(parseFloat(order_qty) / 1000)) * parseFloat(required_qty);
                    $(this).closest('.row_recipe').find('.requested_qty').val(total);
                });

            }
        });

    }
    $(document).ready(function () {
        $('#sales_order_id').trigger('change');
    })
</script>