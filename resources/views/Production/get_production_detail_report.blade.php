<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;

use App\Helpers\SalesHelper;

?>
<style>
    .modalWidth{
        width: 100%;
    }
    .bold {
        font-size: large;
        font-weight: bold;
    }
</style>





            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table id="data_table"  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center">PPC NO</th>
                            <th class="text-center">Finish Goods</th>
                            <th class="text-center">Planned Quantity</th>
                            <th class="text-center">Spoilage</th>
                            <th class="text-center">Produce Qty</th>
                        </tr>
                        </thead>

                        <tbody>
                        <?php


                        $counter = 1;
                        foreach ($master_data as $row):
                        $conversion_data=ProductionHelper::get_conversion_data_row($row->id);
                        $spoilage=$conversion_data->spoilage;
                        ?>
                        <tr class="tex-center">
                            <td class="text-center bold"><?php echo $counter++;?></td>
                            <td class="text-center bold">{{ strtoupper($row->order_no) }}</td>
                            <td  class="text-center bold"><?php echo CommonHelper::get_item_name($row->finish_goods_id);?></td>
                            <td class="text-center bold"><?php echo $row->planned_qty?></td>
                            <td class="text-center bold"><?php echo $spoilage?></td>
                            <td class="text-center bold"><?php echo $row->planned_qty - $spoilage?></td>

                        </tr>

                        <tr class="InnerDetail" style="; background: radial-gradient(black, transparent)">
                            <td colspan="7">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                    <tr>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">BOM Item</th>
                                        <th class="text-center">Requested QTY</th>
                                        <th class="text-center">Issue QTY</th>
                                        <th class="text-center">Return QTY</th>
                                        <th class="text-center">Net QTY</th>
                                        <th class="text-center">Variance</th>

                                    </tr>
                                    </thead>
                                    <tbody>

                                    @include('Production.production_detail_report_bom_data')

                                    </tbody>
                                </table>
                            </td>
                        </tr>

                        <?php
                        endforeach
                        ?>
                        </tbody>
                    </table>



                </div>
            </div>




<script>
    function get_vouchers(voucher_no)
    {

        if($('#vouchers').is(':checked'))
        {
            var id=0;
            $('#data').html('<div class="loader"></div>');
            $.ajax({

                url:'{{url('production/get_ledger_data')}}',
                type:'GET',
                data:{voucher_no:voucher_no,id:id},
                success:function(response){
                    $('#data').html(response);
                },
                err:function(err){
                    $('#data').html(err);
                }
            })
        }

    }
</script>


