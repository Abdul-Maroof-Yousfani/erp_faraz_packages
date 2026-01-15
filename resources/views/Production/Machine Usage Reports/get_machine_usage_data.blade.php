<table id="data_table" class="table table-bordered">
    <thead>
    <th class="text-center">S.No</th>

    <th class="text-center">Machine Name</th>
    <th class="text-center">Machine Code</th>
    <th class="text-center">Life in Pieces</th>
    <th class="text-center">Depreciable Cost</th>
    <th class="text-center">Produce Qty</th>
    <th class="text-center">Total Depreciation Amount</th>
    </thead>
    <tbody>
    <?php
    $count=1;
    $data=DB::Connection('mysql2')->table('production_machine as a')
        ->join('machine_costing as b','a.id','=','b.machine_id')
        ->join('costing_data as c','b.master_id','=','c.id')
        ->join('production_plane_data as d','c.production_plan_data_id','=','d.id')
        ->join('production_plane as e','e.id','=','d.master_id')
        ->join('transactions as f','d.order_no','=','f.voucher_no')
        ->where('a.status',1)
        ->where('b.status',1)
        ->where('f.voucher_type',19)
        ->where('f.status',1)
        ->whereBetween('e.order_date', [$from, $to])
        ->select('a.id','a.machine_name','a.code','a.life','a.dep_cost','b.master_id', DB::raw('SUM(d.planned_qty) As total_planned'))
        ->groupBy('a.id')
        ->get();


    $total_dep=0;
    $total_produce=0;
    $total_dep_amount=0;
    $total_life=0;
    ?>

    @foreach($data as $row)
        <?php $plan=DB::Connection('mysql2')->select('select distinct (c.id) as data_id
from  machine_costing as a
inner join
costing_data b
on
a.master_id=b.id
inner join
production_plane_data c
on
c.id=b.production_plan_data_id
inner join
production_plane d
on
c.master_id=d.id
inner join
transactions e
on
e.voucher_no=d.order_no
where c.status=1
and e.voucher_type=19
and e.status=1
and d.status=1
and a.machine_id="'.$row->id.'"
and d.order_date between "'.$from.'" and "'.$to.'"
');

        ?>


        <?php



        $total_plane=0;
        foreach ($plan as $row1):
            $total_plane+=$ppc_id=  DB::Connection('mysql2')->table('production_plane_data')->
            where('id',$row1->data_id)->value('planned_qty');
        endforeach;
        ?>
        <tr class="text-center">
            <td>{{ $count++ }}</td>

            <td><?php echo  $row->machine_name ?></td>
            <td><?php echo  $row->code ?></td>
            <td><?php echo  number_format($row->life,2) ?></td>
            <td><?php echo  number_format($row->dep_cost,2)?></td>
            <td> <?php echo number_format($total_plane,2) ?></td>
            <td>
                <?php
                $dep_amount=($row->dep_cost / $row->life) * $total_plane;
                echo number_format($dep_amount,2);

                ?> </td>
        </tr>

        <?php
        $total_life+=$row->life;
        $total_dep+=$row->dep_cost;
        $total_produce+=$total_plane;
        $total_dep_amount+=$dep_amount;

        ?>

    @endforeach
    <tr class="text-center" style="font-size: large;font-weight: bold">
        <td colspan="3"> Total</td>
        <td><?php echo number_format($total_life,2) ?></td>
        <td><?php echo number_format($total_dep,2) ?></td>
        <td><?php echo number_format($total_produce,2) ?></td>
        <td><?php echo number_format($total_dep_amount,2) ?></td>

    </tr>
    </tbody>
</table>
