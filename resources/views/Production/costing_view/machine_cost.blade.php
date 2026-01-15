<?php
use App\Helpers\ProductionHelper;
?>

<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php ?>  in active" id="machine<?php ?>">
    <thead>
    <tr>
        <th class="text-center">S.No</th>
        <th class="text-center">Machine Name</th>
        <th class="text-center">Total Annual Cost </th>



    </tr>
    </thead>
    <tbody>
    <?php
    $data=  DB::Connection('mysql2')->table('machine_costing')->where('status',1)->where('master_id',$id)->orderBy('id','ASC')->get();
    $counter=1;
    $total=0;
    ?>
    @foreach($data as  $row)

        <tr class="text-center">
            <td>{{$counter++}}</td>
            <td>{{ProductionHelper::get_machine_name($row->machine_id)}}</td>
            <td>{{number_format($row->annual_dep,2)}}</td>

            <?php $total+=$row->annual_dep ?>
        </tr>
    @endforeach
    <tr class="text-center" style="font-weight: bold">
        <td colspan="2">Total</td>
        <td colspan="">{{number_format($total,2)}}</td>
    </tr>
    <tr class="text-center" style="font-weight: bold">
        <td colspan="2"> Planned Qty</td>
        <td colspan="">{{  $planned_qty }}</td>
    </tr>

    <tr class="text-center" style="font-weight: bold">
        <td colspan="2"> Net Total</td>
        <td colspan="">{{  number_format($total*$planned_qty,2) }}</td>
    </tr>


    </tbody>
</table>


