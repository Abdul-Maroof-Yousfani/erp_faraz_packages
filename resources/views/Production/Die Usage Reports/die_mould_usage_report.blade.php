
<?php use App\Helpers\ProductionHelper; ?>
<table id="data_table" class="table table-bordered">
    <thead>
    <th class="text-center">S.No</th>

    <th class="text-center">Die</th>
    <th class="text-center">Size</th>
    <th class="text-center">Batch Code</th>
    <th class="text-center">Life in Pieces</th>
    <th class="text-center">Depreciable Cost</th>
    <th class="text-center">Produce Qty</th>
    <th class="text-center">Total Depreciation Amount</th>
    </thead>
    <tbody>
    <?php
    $count=1;
  //  $data=DB::Connection('mysql2')->select('select * from die_usage_report where status=1 group by die_id,batch_code_id');
    $data=DB::Connection('mysql2')->select('select * from die_usage_report as a
    inner join
    production_plane b
    on
    a.production_plan_id=b.id
    where a.status=1
    and b.order_date between "'.$from.'" and "'.$to.'"
    group by a.die_id,a.batch_code_id');
    $total_dep=0;
    $total_produce=0;
    $total_dep_amount=0;
    $total_life=0;
    ?>

    @foreach($data as $row)

        <?php
        $die=ProductionHelper::get_die($row->die_id);
        $die_name=$die->dai_name;
        $size=$die->size;

        $detail=  ProductionHelper::get_die_bacth_code($row->batch_code_id);
        $batch_code=$detail->batch_code;
        $life=$detail->life;
        $value=$detail->value;


  //      $ppc_id=  DB::Connection('mysql2')->table('die_usage_report')->where('die_id',$row->die_id)
      //      ->where('batch_code_id',$row->batch_code_id)->select('production_plan_data_id')->get();

        $ppc_id=  DB::Connection('mysql2')->table('die_usage_report as a')
            ->join('production_plane as b','a.production_plan_id','=','b.id')
            ->join('transactions as c','b.order_no','=','c.voucher_no')
            ->where('a.die_id',$row->die_id)
            ->where('c.voucher_type',19)
            ->where('c.status',1)
            ->where('b.status',1)
            ->where('a.batch_code_id',$row->batch_code_id)
            ->select(DB::raw('DISTINCT(a.production_plan_data_id)'))
            ->get();

        $total_amount=0;
        foreach ($ppc_id as $row1):
          //  $total_amount+=$ppc_id=  DB::Connection('mysql2')->table('production_plane_data')->
          //  where('id',$row1->production_plan_data_id)->value('planned_qty');

            $total_amount+=$ppc_id=  DB::Connection('mysql2')->table('production_plane_data as a')
                ->join('production_plane as b','a.master_id','=','b.id')
                ->where('a.id',$row1->production_plan_data_id)
                ->whereBetween('b.order_date', [$from, $to])
                ->value('a.planned_qty');
        endforeach;

        ?>
        <tr class="text-center">
            <td>{{ $count++ }}</td>

            <td><?php echo  $die_name ?></td>
            <td><?php echo  $size ?></td>
            <td><?php echo  $batch_code?></td>
            <td><?php echo  $life?></td>
            <td><?php echo  number_format($value,2) ?></td>
            <td><?php echo  number_format($total_amount,2) ?></td>
            <td><?php echo  number_format(($value / $life)*$total_amount,2) ?></td>
        </tr>

        <?php

        $total=  ($value / $life)*$total_amount;

        $total_dep+=$value;
        $total_produce+=$total_amount;
        $total_dep_amount+=$total;
        $total_life+=$life;

        ?>
    @endforeach
    <tr class="text-center" style="font-size: large;font-weight: bold">
        <td colspan="4"> Total</td>
        <td><?php echo number_format($total_life,2) ?></td>
        <td><?php echo number_format($total_dep,2) ?></td>
        <td><?php echo number_format($total_produce,2) ?></td>
        <td><?php echo number_format($total_dep_amount,2) ?></td>
    </tr>
    </tbody>
</table>