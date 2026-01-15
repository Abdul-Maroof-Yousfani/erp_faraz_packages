<?php
use App\Helpers\ProductionHelper;
?>

<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php echo $counter?>  in active" id="labour_id<?php echo $counter?>">
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
    $dl_count=1;

    $data=   DB::Connection('mysql2')->select('select a.machine_name,b.qty_per_hour,a.setup_time,g.wait_time,move_time,g.que_time,g.capacity,a.id as machine_id
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


    
        $total_setup_time=0;
        $total_work_time=0;
        $total_operating_time=0;
        $grnad_total_time_capicity=0;
    ?>
    @foreach($data as $key=> $dl_row)
        <tr>
        <?php
                // start
            $in_minuts=($dl_row->qty_per_hour)/60;

                // end


             // start

           $setup_time= ProductionHelper::hours_to_minuts($dl_row->setup_time);
            $total_setup_time+= $setup_time;
             //end

            // start
            $wait_minut= ProductionHelper::hours_to_minutss($dl_row->wait_time);
            $move_time= ProductionHelper::hours_to_minutss($dl_row->move_time);
            $que_time= ProductionHelper::hours_to_minutss($dl_row->que_time);

            $planned_qty=$row->planned_qty;

           $operating_time=($wait_minut+$move_time+$que_time)*$planned_qty;
            $total_operating_time+=$operating_time;
            //end


                //start
            $planned_qty=$row->planned_qty;
            $work_time=$planned_qty*$in_minuts;
            $total_work_time+=$work_time;
                //end


                // start
          $total_time=  $setup_time+$operating_time+$work_time;
                //end

                //start
                $total_time_capicity=($total_time / $dl_row->capacity)*100;
                $grnad_total_time_capicity+=$total_time_capicity;
                //end

            ?>
       <td>{{$dl_count++}}</td>
       <td>{{$dl_row->machine_name}}

        <input type="hidden" name="machine_id{{$keys}}[]" value="{{$dl_row->machine_id}}"/>

       </td>
       <td>

           {{$setup_time}}
           <input type="hidden" name="setup_time{{$keys}}[]" value="{{$setup_time}}"/>

       </td>
       <td>{{ $dl_row->qty_per_hour.' / 60 ='.  number_format($in_minuts,2).'*'.$planned_qty.'='.number_format($work_time,2)}}

           <input type="hidden" name="working_time_formula{{$keys}}[]" value="{{$dl_row->qty_per_hour.' / 60 ='.  number_format($in_minuts,2).'*'.$planned_qty.'='.number_format($work_time,2)}}"/>
           <input type="hidden" name="working_time{{$keys}}[]" value="{{$work_time}}"/>
       </td>
       <td>{{$wait_minut.'+'.$move_time.'+'.$que_time.'='.$operating_time}}

           <input type="hidden" name="operation_time_formula{{$keys}}[]" value="{{$wait_minut.'+'.$move_time.'+'.$que_time.'='.$operating_time}}"/>
           <input type="hidden" name="operation_time{{$keys}}[]" value="{{$operating_time}}"/>
       </td>
       <td>
           {{$dl_row->capacity}}
           <input type="hidden" name="capacity{{$keys}}[]" value="{{$dl_row->capacity}}"/>

       </td>
       <td>{{number_format($total_time,2).'/'.$dl_row->capacity.'* 100 ='.number_format($total_time_capicity,2)}}

           <input type="hidden" name="total_time_mint_formula{{$keys}}[]" value="{{number_format($total_time,2).'/'.$dl_row->capacity.'* 100 ='.number_format($total_time_capicity,2)}}"/>
           <input type="hidden" name="total_time_mint{{$keys}}[]" value="{{$total_time_capicity}}"/>

       </td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td colspan="2" class="text-center">Total(min)</td>
        <td class="text-right">{{number_format($total_setup_time,2)}}</td>
        <td class="text-right">{{number_format($total_work_time,2)}}</td>
        <td class="text-right">{{number_format($total_operating_time,2)}}</td>
        <td></td>
        <td class="text-right">{{number_format($grnad_total_time_capicity,2)}}</td>

    </tr>


    <?php  ?>
    <tr style="font-weight: bold">
        <td colspan="6" class="text-center">Total(Hours)</td>

        <?php

        $grnad_total_time_capicity_hour=  $grnad_total_time_capicity/60;
        ?>

        <td class="text-right">{{number_format($grnad_total_time_capicity_hour,2)}}</td>

    </tr>

    <tr style="font-weight: bold">
        <td colspan="6" class="text-center">DL Rate</td>

            <?php

                    // start
                  $labour_rate=  ProductionHelper::get_labour_rate();
                    //end
            ?>

        <td class="text-right">{{number_format($labour_rate,2)}}</td>

    </tr>


    <tr style="font-weight: bold">
        <td colspan="6" class="text-center">DL Amount</td>

        <?php

        // start
        $dl_amount=$labour_rate*$grnad_total_time_capicity_hour;
        //end

            $direct_labour[]=$dl_amount;
        ?>

        <td class="text-right">{{number_format($dl_amount,2)}}</td>

    </tr>


    <input type="hidden" class="direct_labour" id="labour_cost{{$direct_labour_counter}}" value="{{$dl_amount}}"/>
    <?php  $direct_labour_counter++?>

    </tbody>
</table>
<script>
    $( document ).ready(function() {

        $('.direct_labour').each(function(i, obj) {
            var value= parseFloat($(this).val());
            value=(value).toFixed(2);


            var id=  $(this).attr("id");
            var number=id.replace('labour_cost','');
            $('#direct_lab_cost'+number).html(value);
            $('#db_direct_labour'+number).val(value);

        });
    })
</script>

