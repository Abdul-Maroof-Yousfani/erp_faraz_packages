<?php use  App\Helpers\CommonHelper ?>

<table id="data_table" class="table table-bordered">
    <thead>
    <th class="text-center">S.No</th>

    <th class="text-center">Production No</th>
    <th class="text-center">Production Completion Date</th>
    <th class="text-center">Finish Goods</th>
    <th class="text-center">Plan Qty</th>
    <th class="text-center">Produce Qty</th>
    <th class="text-center">Spoilage Qty</th>
    <th class="text-center">Cost per Piece</th>
    </thead>
    <tbody>
    <?php
    $count=1;

      $finish=Request::get('finish');
      $clause='';
      if ($finish!=''):
        $clause='and a.finish_goods_id="'.$finish.'"';
        endif;

    $data=DB::Connection('mysql2')->select('select a.order_no,b.v_date,a.finish_goods_id,a.planned_qty,d.spoilage
    ,e.direct_material_cost,e.indirect_material_cost,e.direct_labour,e.die_mould,e.machine_cost,e.foh
    from production_plane_data as a
    inner join
    production_plane c
    on
    a.master_id=c.id
    inner join
    transactions b
    on
    a.order_no=b.voucher_no
    inner join
    prouction_conversion_data d
    on
    d.production_plan_data_id=a.id
    inner join
    costing_data e
    on
    a.id=e.production_plan_data_id
    where a.status=1
    and b.status=1
    and b.voucher_type=19
    and c.order_date between "'.$from.'" and "'.$to.'"
    '.$clause.'
    group by a.id');


   

    $total_planned=0;
    $total_produce=0;
    $total_spoilage=0;

    ?>

    @foreach($data as $row)

        <?php  $total_foh= $row->direct_material_cost + $row->indirect_material_cost + $row->direct_labour + $row->die_mould +$row->machine_cost*$row->planned_qty + $row->foh;
               $produce_qty=$row->planned_qty - $row->spoilage;
        ?>
        <tr class="text-center">
            <td>{{ $count++ }}</td>
            <td><?php echo  $row->order_no ?></td>
            <td><?php echo  CommonHelper::changeDateFormat($row->v_date)?></td>
            <td><?php echo  CommonHelper::get_item_name($row->finish_goods_id)?></td>
            <td><?php echo  $row->planned_qty?></td>
            <td><?php echo  $row->planned_qty - $row->spoilage?></td>
            <td><?php echo  $row->spoilage?></td>
            <td><?php echo  number_format($total_foh/$produce_qty,2) ?></td>

        </tr>

        <?php


        $total_planned+=$row->planned_qty;
        $total_produce+=$row->planned_qty - $row->spoilage;
        $total_spoilage+=$row->spoilage;
        ?>
    @endforeach
    <tr class="text-center" style="font-size: large;font-weight: bold">
        <td colspan="4"> Total</td>
        <td><?php echo number_format($total_planned,2) ?></td>
        <td><?php echo number_format($total_produce,2) ?></td>
        <td><?php echo number_format($total_spoilage,2) ?></td>
        <td></td>
    </tr>
    </tbody>
</table>