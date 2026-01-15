<?php
use App\Helpers\ProductionHelper;
?>


<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php ?>  in active" id="labour_id<?php ?>">
    <thead>
    <tr>
        <th class="text-center">S.No</th>
        <th class="text-center">Machine Name</th>
        <th class="text-center">Setup Time (min)</th>
        <th class="text-center">Working Time  (min)</th>
        <th class="text-center">Operating Time (min)</th>
        <th class="text-center">Capacity</th>
        <th class="text-center">Total Time(min)</th>

    </tr>
    </thead>
    <tbody>

    <?php
    $data=  DB::Connection('mysql2')->table('direct_labour_costing')->where('status',1)->where('master_id',$id)->orderBy('id','ASC')->get();
    $counter=1;
    $total=0;
    ?>
    @foreach($data as  $row)

        <tr class="text-center">
            <td>{{$counter++}}</td>
            <td>{{ProductionHelper::get_machine_name($row->machine_id)}}</td>
            <td>{{$row->setup_time}}</td>
            <td>{{$row->working_time_formula}}</td>
            <td>{{$row->operation_time_formula}}</td>
            <td>{{$row->capacity}}</td>
            <td>{{$row->total_time_mint_formula}}</td>
            <?php $total+=$row->total_time_mint ?>
        </tr>
    @endforeach
    <tr class="text-center" style="font-weight: bold">
        <td colspan="6">Total</td>
        <td colspan="">{{number_format($total,2)}}</td>
    </tr>
    <tr class="text-center" style="font-weight: bold">
        <td colspan="6">Total(Hours)</td>
        <?php $total=$total/60; ?>
        <td colspan="">{{number_format($total,2)}}</td>
    </tr>
    <?php $labour_data=ProductionHelper::get_labour_rate_from_costing_data($id); ?>
    <tr class="text-center" style="font-weight: bold">
        <td colspan="6">DL Amount {{ '('. $labour_data->dl_rate. ')' }}</td>
        <td colspan="">{{number_format($total*$labour_data->dl_rate,2)}}</td>
    </tr>
    </tbody>
</table>


