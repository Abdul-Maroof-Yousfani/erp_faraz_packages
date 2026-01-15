<?php
use App\Helpers\ProductionHelper;
?>

<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php echo $counter?>  in active" id="die_mouldd<?php echo $counter?>">
    <thead>
    <tr>
        <th class="text-center">S.No</th>
        <th class="text-center">Machine Name</th>
        <th class="text-center">Die</th>
        <th class="text-center">Mould</th>


    </tr>
    </thead>
    <tbody>
    <?php
    $die_mould_count=1;

    $data=   DB::Connection('mysql2')->select('select a.machine_name,dai_id,mold_id,a.id as machine_id
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


     $total_die_cost=0;
     $total_mould_cost=0;
    ?>
    @foreach($data as $key=> $die_mould_row)
        <tr>
            <?php
                $die=explode(',',$die_mould_row->dai_id);
                $mould=explode(',',$die_mould_row->mold_id);
            ?>
            <td>{{$die_mould_count++}}</td>
            <td>{{$die_mould_row->machine_name}}

                <input type="hidden" name="machine_id_for_die_mould{{$keys}}[]" value="{{$die_mould_row->machine_id}}"/>
            </td>
            <td style="">
                @php $die_counter=1; @endphp
                <?php

                $die_formula='';
                $die_cost='';
                ?>
                @foreach($die as $die_data)
                    <?php $die= ProductionHelper::get_die_detail($die_data); ?>
                    @if($die[0]!='' && $die!=null)
                    <?php $cost=ProductionHelper::get_die_cost($die_data);
                            $total_die_cost+=$cost*$row->planned_qty;
                            ?>
                            <?php

                            $die_formula .=$die[0].' '.$cost.' * '.$row->planned_qty.' = '.$cost*$row->planned_qty.'@';
                            $die_cost.=   $cost*$row->planned_qty.'@';


                            ?>
                    {{$die_counter++.') '.$die[0].' '}}<b>{{$cost.' * '.$row->planned_qty.' = '.$cost*$row->planned_qty}}</b>


                     @endif
                    <br>
                @endforeach

                <input type="hidden" name="die_formula{{$keys}}[]" value="{{$die_formula}}"/>
                <input type="hidden" name="die_cost{{$keys}}[]" value="{{$die_cost}}"/>
            </td>


                <td>
                    @php $mould_counter=1; @endphp
                    <?php
                    $mould_formula='';
                    $mould_cost='';
                    ?>
                    @foreach($mould as $mould_data)
                        <?php $mould= ProductionHelper::get_mould_detail($mould_data); ?>
                        @if($mould[0]!='' && $mould!=null)
                           <?php $mould_batch_cost=ProductionHelper::get_mould_cost($mould_data);
                                $total_mould_cost+=$mould_batch_cost*$row->planned_qty;
                                ?>

                            <?php

                                $mould_formula .=$mould[0].$mould_batch_cost.' * '.$row->planned_qty.' = '.$mould_batch_cost*$row->planned_qty.'@';
                                $mould_cost.=   $mould_batch_cost*$row->planned_qty.'@';
                                ?>
                            {{$mould_counter++.') '.$mould[0]}}<b>{{$mould_batch_cost.' * '.$row->planned_qty.' = '.$mould_batch_cost*$row->planned_qty}}</b>
                        @endif
                        <br>
                    @endforeach
                    <input type="hidden" name="mould_formula{{$keys}}[]" value="{{$mould_formula}}"/>
                    <input type="hidden" name="mould_cost{{$keys}}[]" value="{{$mould_cost}}"/>
                </td>



        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td colspan="2" class="text-center">Total</td>
        <td class="text-right" colspan="">{{$total_die_cost}}</td>
        <td class="text-right" colspan="">{{$total_mould_cost}}</td>
    </tr>
    <tr style="font-weight: bold">
        <td colspan="2" class="text-center">Grand Total</td>
        <td  class="text-right" colspan="2">{{$total_die_cost+$total_mould_cost}}</td>

    </tr>

    <input type="hidden" class="die_mould_cost" id="die_mould{{$die_mould_counter}}" value="{{$total_mould_cost+$total_die_cost}}"/>
    <?php  $die_mould_counter++?>

    </tbody>
</table>
<script>
    $( document ).ready(function() {

        $('.die_mould_cost').each(function(i, obj) {
            var value= $(this).val();
            var id=  $(this).attr("id");
        
            var number=id.replace('die_mould','');
            $('#die_mould_'+number).html(value);
            $('#db_die_mould'+number).val(value);

        });
    })
</script>

