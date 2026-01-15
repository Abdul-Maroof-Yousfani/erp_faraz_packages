<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php ?>  in active" id="indirect<?php ?>">
    <thead>
    <tr>
        <th class="text-center">S.No</th>
        <th class="text-center">IDMT Cost                                        </th>
        <th class="text-center">IDMT Spoil Cost</th>
        <th class="text-center">IDMT Excess Cost</th>
        <th class="text-center">IDMT Total Cost	Mat</th>
    </tr>
    </thead>
    <?php
    $data=  DB::Connection('mysql2')->table('indirect_material_costing')->where('status',1)->where('master_id',$id)->orderBy('id','ASC')->get();
    $counter=1;
    $total=0;
    ?>
    <tbody>
    @foreach($data as  $row)

        <tr class="text-center">
            <td>{{$counter++}}</td>
            <td>{{$row->idmt_cost_formula}}</td>
            <td>{{$row->idmt_spoil_formula}}</td>
            <td>{{$row->idmt_excess_formula}}</td>
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

<script>
    $( document ).ready(function() {

        $('.ind_cost').each(function(i, obj) {
            var value= $(this).val();
            var id=  $(this).attr("id");
            var number=id.replace('total_cost_indirect','');
            $('#ind_cost_'+number).html(value);
            $('#db_in_direct_m_cost'+number).val(value);

        });
    })
</script>
