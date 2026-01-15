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
                <th class="text-center">Completion Date</th>
                <th class="text-center">Finish Goods</th>
                <th class="text-center">Plan Qty</th>
                <th class="text-center">Produce Qty</th>
                <th class="text-center">Spoilage</th>
                <th class="text-center">Direct Material</th>
                <th style="width: 100px!important;" class="text-center">DMT Excess Qty</th>
                <th class="text-center">Indirect Material</th>
                <th class="text-center">IDMT Excess Qty</th>
                <th class="text-center">Direct Labour </th>
                <th class="text-center">Die & Mould</th>
                <th class="text-center">Machine Cost</th>
                <th class="text-center">FOH</th>
                <th class="text-center">Total Cost</th>
                <th class="text-center">Cost per Piece</th>
                <th class="text-center">Spoilage Cost per Piece</th>
            </tr>
            </thead>

            <tbody>
           <?php
           $count=1;
           $total_direct_material=0;
           $total_indirect_material=0;
           $total_direct_labour=0;
           $total_die_mould=0;
           $total_machine_cost=0;
           $total_foh=0;
           $grand_total_cost=0;

           ?>
            @foreach($master_data as $row)
             <?php
          $tr_data=   ProductionHelper::get_completion_date($row->order_no);
          $completion_date=$tr_data->v_date;

          $conversion_data=ProductionHelper::get_conversion_data_row($row->id);
          $produce_qty=$conversion_data->produce_qty;
          $spoilage=$conversion_data->spoilage;


        $costing_data=  ProductionHelper::get_costing_data($row->id);
        $direct_material_cost=$costing_data->direct_material_cost;
        $total_direct_material+=$direct_material_cost;

                // for excess cost
        $direct_material_excess_cost_data=ProductionHelper::get_costing_direct_material($costing_data->id);
        $excess_cost=$direct_material_excess_cost_data->dmt_excess_formula;
        $excess_cost=explode('*',$excess_cost);
        $excess_cost=(float)$excess_cost[0];
        // end


        $indirect_material_cost=$costing_data->indirect_material_cost;
        $total_indirect_material+=$indirect_material_cost;
        // for indirect material

             $indirect_material_excess_cost_data=ProductionHelper::get_costing_direct_indirect_material($costing_data->id);
             $indirect_excess_cost=$indirect_material_excess_cost_data->idmt_excess_formula;
             $indirect_excess_cost=explode('*',$indirect_excess_cost);
             $indirect_excess_cost=(float)$indirect_excess_cost[0];
        //end
        $direct_labour=$costing_data->direct_labour;
        $total_direct_labour+=$direct_labour;
        $die_mould=$costing_data->die_mould;
        $total_die_mould+=$die_mould;
        $machine_cost=$costing_data->machine_cost;
        $machine_cost=$machine_cost*$row->planned_qty;
        $total_machine_cost+=$machine_cost;
        $foh=$costing_data->foh;
        $total_foh+=$foh;
        $total_cost=$direct_material_cost+$indirect_material_cost+$direct_labour+$die_mould+$machine_cost+$foh;
        $grand_total_cost+=$total_cost;
        $cost_per_piece=$total_cost / $produce_qty;
        $sppilage_cost_per_piece=($total_cost / $row->planned_qty ) * $spoilage;


             ?>

    <tr class="text-center">
        <td><?php echo $count++ ?></td>
        <td><?php echo strtoupper($row->order_no) ?></td>
        <td><?php echo CommonHelper::new_date_formate($completion_date) ?></td>
        <td><?php echo CommonHelper::get_item_name($row->finish_goods_id) ?></td>
        <td><?php echo $row->planned_qty ?></td>
        <td><?php echo $produce_qty ?></td>
        <td><?php echo $spoilage ?></td>
        <td><?php echo number_format($direct_material_cost,2) ?></td>
        <td><?php echo number_format($excess_cost,2) ?></td>
        <td><?php echo number_format($indirect_material_cost,2) ?></td>
        <td><?php echo number_format($indirect_excess_cost,2) ?></td>
        <td><?php echo number_format($direct_labour,2) ?></td>
        <td><?php echo number_format($die_mould,2) ?></td>
        <td><?php echo number_format($machine_cost,2) ?></td>
        <td><?php echo number_format($foh,2) ?></td>
        <td><?php echo number_format($total_cost,2) ?></td>
        <td><?php echo number_format($cost_per_piece,2) ?></td>
        <td><?php echo number_format($sppilage_cost_per_piece,2) ?></td>
    </tr>
            @endforeach
            <tr class="text-center" style="font-size: large;font-weight: bold">
                <td colspan="7">Total</td>
                <td><?php echo  number_format($total_direct_material,2) ?></td>
                <td></td>
                <td><?php echo number_format($total_indirect_material,2) ?></td>
                <td></td>
                <td><?php echo number_format($total_direct_labour,2) ?></td>
                <td><?php echo number_format($total_die_mould,2) ?></td>
                <td><?php echo number_format($total_machine_cost,2) ?></td>
                <td><?php echo number_format($total_foh,2) ?></td>
                <td><?php echo number_format($grand_total_cost,2) ?></td>
            </tr>
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


