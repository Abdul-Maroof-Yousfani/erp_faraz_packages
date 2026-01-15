<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php echo $counter?>  in active" id="direct<?php echo $counter?>">
    <thead>
    <tr>
        <th class="text-center">S.No</th>
        <th class="text-center">DMT Cost                                        </th>
        <th class="text-center">DMT Spoil Cost</th>
        <th class="text-center">DMT Excess Cost</th>
        <th class="text-center">DMT Total Cost	Mat</th>
    </tr>
    </thead>
    <tbody>
    <?php
            $total_indirect_material_cost=0;
    $data=   DB::Connection('mysql2')->select('select sum(a.cost)cost,sum(c.return_qty)returned,b.qty_ft,d.spoilage,d.produce_qty,sum(a.issue_qty)isuued,a.request_qty
                                                                 from production_plane_issuence a
                                                                inner join
                                                                production_bom_data_direct_material  b
                                                                on
                                                                a.bom_data_id=b.id
                                                                inner join
                                                                prouction_conversion_data d
                                                                on
                                                                a.master_id=d.production_plan_data_id
                                                                left join
                                                                production_plane_return c
                                                                on
                                                                a.id=c.production_plan_issuence_id
                                                                where a.master_id="'.$row->id.'"
                                                                and a.type=0
                                                                and d.status=1
                                                                group by a.id')
    ?>
    @foreach($data as $key=> $dm_row)
        <?php
        // start
        $net_issued= $dm_row->isuued-$dm_row->returned;
        // end

        $cost=$dm_row->cost;
        $planned_qty= $dm_row->produce_qty+$dm_row->spoilage;
        $requested_qty=$dm_row->request_qty / $planned_qty;
        $net_issue_qty=$dm_row->isuued-$dm_row->returned;
        $net_issue_qty=$net_issue_qty-($requested_qty*$planned_qty);

        $excess_qty=$net_issue_qty*$cost;


        //
        $dm_cost=$requested_qty*($planned_qty-$dm_row->spoilage)*$dm_row->cost;
        $spoilage_cost=  $requested_qty*($dm_row->spoilage)*$dm_row->cost;
        $total_cost=$dm_cost+$spoilage_cost+$excess_qty;
        ?>

        <tr  class="text-center">
            <td><?php echo $dm_row->cost*$net_issued;?></td>
            <td><?php echo $requested_qty.'('.$planned_qty.'-'.$dm_row->spoilage.')'.'*'.$dm_row->cost.'='.$dm_cost;   ?>

           <input type="hidden" name="dm_cost_formula{{$keys}}[]" value="{{$requested_qty.'('.$planned_qty.'-'.$dm_row->spoilage.')'.'*'.$dm_row->cost.'='.$dm_cost}}" />
            <input type="hidden" name="dm_cost{{$keys}}[]" value="{{$dm_cost}}" />
            </td>
            <td><?php

                echo $requested_qty.'('.$dm_row->spoilage.')'.'*'.$dm_row->cost.'='.$spoilage_cost;  ?>
                <input type="hidden" name="dmt_spoil_cost_formula{{$keys}}[]" value="{{$requested_qty.'('.$dm_row->spoilage.')'.'*'.$dm_row->cost.'='.$spoilage_cost}}" />
                <input type="hidden" name="dmt_spoil_cost{{$keys}}[]" value="{{$spoilage_cost}}" />

            </td>
            <td><?php echo  $net_issue_qty.' *'.$cost.'=';echo  $excess_qty ?>

                <input type="hidden" name="dmt_excess_formula{{$keys}}[]" value="{{$net_issue_qty.' *'.$cost.'='.$excess_qty}}" />
                <input type="hidden" name="dmt_excess{{$keys}}[]" value="{{$excess_qty}}" />

            </td>
            <td><?php echo number_format($total_cost,2) ?>

                <input type="hidden" name="dmt_total_cost{{$keys}}[]" value="{{$total_cost}}" />
            </td>
            @php $total_indirect_material_cost+=$total_cost; @endphp
            <input type="hidden" id="total_cost{{$counter}}" value="{{$total_cost}}"/>
        </tr>
    @endforeach
      <tr style="font-weight: bold">
          <td colspan="4">Total</td>
          <td class="text-center">{{$total_indirect_material_cost}}</td>

      </tr>
    <input type="hidden" class="d_cost" id="total_cost{{$direct_material_counter}}" value="{{$total_indirect_material_cost}}"/>
    <?php  $direct_material_counter++?>
    </tbody>
</table>

<script>
    $( document ).ready(function() {
     
        $('.d_cost').each(function(i, obj) {
           var value= $(this).val();
           var id=  $(this).attr("id");
           var number=id.replace('total_cost','');
           $('#d_cost_'+number).html(value);
           $('#db_direct_m_cost'+number).val(value);

        });
    })
</script>
