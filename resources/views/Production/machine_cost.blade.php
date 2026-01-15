<?php
use App\Helpers\ProductionHelper;
?>

<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php echo $counter?>  in active" id="machine<?php echo $counter?>">
    <thead>
    <tr>
        <th class="text-center">S.No</th>
        <th class="text-center">Machine Name</th>
        <th class="text-center">Total Annual Cost </th>



    </tr>
    </thead>
    <tbody>
    <?php
    $machine_count=1;

    $data=   DB::Connection('mysql2')->select('select a.machine_name,a.yearly_cost,a.id as machine_id
    from production_machine a
    inner join
    production_machine_data b
    ON a.id=b.master_id
    inner JOIN production_plane_data c
    ON
    c.finish_goods_id=b.finish_good
    inner join
    production_route d
    ON
    c.route=d.id
    inner join
    production_route_data e
    ON
    e.master_id=d.id
    and e.machine_id=a.id
    and e.orderby!=0
    inner join
    production_work_order f
    on
    f.id=d.operation_id
    inner join
    production_work_order_data g
    on
    f.id=g.master_id
    and g.machine_id=a.id
    where a.status=1
    and c.master_id="'.$row->master_id.'"
    and c.finish_goods_id="'.$row->finish_goods_id.'"
    and c.id="'.$row->id.'"
    group by b.id');

    $machine_count=1;
    $total_equi_cost=0;
    ?>
    @foreach($data as $key=> $machine_data)
        <tr>

            <td>{{$machine_count++}}</td>
            <td>{{$machine_data->machine_name}}
            <input type="hidden" name="machine_for_machine{{$keys}}[]" value="{{$machine_data->machine_id}}"/>
            </td>
            <td class="text-right">{{number_format($machine_data->yearly_cost,2)}}</td>
            <input type="hidden" name="annual_dep{{$keys}}[]" value="{{$machine_data->yearly_cost}}"/>
            <?php $total_equi_cost+=$machine_data->yearly_cost  ?>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td colspan="2">Machine Annual Depreciation</td>
        <td class="text-right">

            {{number_format($total_equi_cost,2)}}

        </td>

    </tr>


    <tr class="hide" style="font-weight: bold">
        <td colspan="2">Yearly Wages</td>
        <?php
        $labour_data=ProductionHelper::get_labour_rate_rows();
        $yearly_wages=$labour_data->wages;


        ?>
        <td id="yearly_wages{{$machine_counter}}" class="text-right">{{number_format($yearly_wages,2)}}</td>
    </tr>






    <tr class="hide" style="font-weight: bold">
        <td colspan="2">Direct Labour Cost</td>
        <td id="direct_labour_cost_for_machibe{{$machine_counter}}" class="text-right"><?php  ?></td>
    </tr>


    <tr class="hide" style="font-weight: bold">
        <td colspan="2">FOH Applied Machine Cost</td>
        <td id="foh_applied_amount{{$machine_counter}}" class="text-right"><?php  ?></td>
    </tr>


    <input type="hidden" class="machine_cost" id="machine_cost{{$machine_counter}}" value="{{$total_equi_cost}}"/>
    <?php  $machine_counter++?>
    </tbody>
</table>
<script>
    $( document ).ready(function() {

        $('.machine_cost').each(function(i, obj) {
            var value= $(this).val();
            var id=  $(this).attr("id");

            var number=id.replace('machine_cost','');
            $('#machine_'+number).html(value);
            $('#db_machine'+number).val(value);

            /*

            // start



            // for direct labour

          var direct_labour_cost=  $('#direct_lab_cost'+number).html();
            $('#direct_labour_cost_for_machibe'+number).html(direct_labour_cost);
            //end


            // foh applied amount

            var total_machine_cost=parseFloat($('#machine_'+number).html());

            // yearly wages
            var yearly_wages=$('#yearly_wages'+number).text().replace(/,/g, '');
          
            yearly_wages=parseFloat(yearly_wages);
            //


            var direct_labour_for_machine=parseFloat($('#direct_labour_cost_for_machibe'+number).html());



            var total=((total_machine_cost / yearly_wages ) * direct_labour_for_machine).toFixed(2);
            $('#foh_applied_amount'+number).html('(Machine Annual Depreciation / Yearly Wages) * Direct Labour Cost ='+total);

            // end

            $('#machine_'+number).html(total);



            */
        });
    })
</script>

