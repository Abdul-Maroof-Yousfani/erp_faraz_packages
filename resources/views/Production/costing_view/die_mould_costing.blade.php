<?php
use App\Helpers\ProductionHelper;
?>

<table class="table table-bordered table-striped table-condensed tableMargin tab-pane allTab fade routeTab<?php ?>  in active" id="die_mouldd<?php ?>">
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
    $data=  DB::Connection('mysql2')->table('die_mould_costing')->where('status',1)->where('master_id',$id)->orderBy('id','ASC')->get();
    $counter=1;
    $total_die=0;
    $total_mould=0;
    ?>


    @foreach($data as  $row)

        <tr class="text-center">
            <td>{{$counter++}}</td>
            <td>{{ProductionHelper::get_machine_name($row->machine_id)}}</td>
           <td>
            <?php
            $die_data=$row->die_formula;
            $die_data=explode('@',$die_data);
            $die_cost_data=$row->die_cost;
            $die_cost_data=explode('@',$die_cost_data);
                $die_count=1;
                 $total_die;
                foreach($die_data as $key =>$row1):
                   if ($row1!=''):
                    echo $die_count++.') '.$row1.'</br>';
                   $total_die+=$die_cost_data[$key];
                   endif;
                    endforeach
            ?>
           </td>

            <td>
                <?php
                $mould_data=$row->mould_formula;
                $mould_data=explode('@',$mould_data);

                $mould_cost_data=$row->mould_cost;
                $mould_cost_data=explode('@',$mould_cost_data);

                $mould_count=1;

                foreach($mould_data as $key =>$row2):
                if ($row2!=''):
                echo $mould_count++.') '.$row2.'</br>';
                $total_mould+=$mould_cost_data[$key];
                endif;
                endforeach
                ?>
            </td>
        </tr>
    @endforeach
    <tr style="font-weight: bold">
        <td colspan="2" class="text-center">Total</td>
        <td class="text-right">{{number_format($total_die,2)}}</td>
        <td class="text-right">{{number_format($total_mould,2)}}</td>

    </tr>
    </tbody>
</table>


