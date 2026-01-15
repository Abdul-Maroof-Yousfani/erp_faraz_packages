<table  class="table table-bordered table-striped table-condensed tableMargin">
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
      $data=  DB::Connection('mysql2')->table('direct_material_costing')->where('status',1)->where('master_id',$id)->orderBy('id','ASC')->get();
            $counter=1;
            $total=0;
    ?>
    @foreach($data as  $row)

    <tr class="text-center">
        <td>{{$counter++}}</td>
        <td>{{$row->dmt_cost_formula}}</td>
        <td>{{$row->dmt_spoil_formula}}</td>
        <td>{{$row->dmt_excess_formula}}</td>
        <td>{{$row->total_cost}}</td>
        <?php $total+=$row->total_cost ?>
    </tr>
    @endforeach
    <tr class="text-center" style="font-weight: bold">
        <td colspan="4">Total</td>
        <td colspan="">{{number_format($total,2)}}</td>
    </tr>

    </tbody>
</table>


