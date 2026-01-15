<?php
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;


$count=1;
$index=0;
$bom_data=ProductionHelper::get_bom_for_direct($row->finish_goods_id); ?>


@foreach($bom_data as $row1)
    <?php

    $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->id);



    $issue_qty=0;
    $return_qty=0;
    $net_issue_qty=0;
    $issue_id=0;
    if ($data_count->count()>0):
        $issue_qty=$data_count->first()->issue_qty;
        $return_qty=ProductionHelper::plan_issue_qty($data_count->first()->id);
        $net_issue_qty=$issue_qty-$return_qty;
        $issue_id=$data_count->first()->id;
        $request_qty=$data_count->first()->request_qty;
    endif;
    ?>
    <tr class="text-center">
        <td> {{$count++}} </td>
        <td>{{CommonHelper::get_item_name($row1->item_id)}}</td>
        <td>{{$request_qty}}</td>
        <td @if($issue_qty==0) style="color: red" @endif>{{$net_issue_qty}}</td>
        <input type="hidden" name="bom_data[]"  value="{{$row->id}}"/>
        <input type="hidden" name="issuence_id[]"  value="{{$issue_id}}"/>
        <td><input type="text" name="wastage[]" required value=""/> </td>
        <?php

        $requested_qty=$request_qty;
        $remining_qty=$net_issue_qty;

            $chip='Not Applicable';
            $turning_scrap='Not Applicable';
         if ($remining_qty >0):

            $chip=($remining_qty * $row1->recover_sreacp)/100;
            $chip=($chip*$row1->recover_chip)/100;



            $turning_scrap=($remining_qty * $row1->recover_sreacp)/100;
            $turning_scrap=($turning_scrap * $row1->turning_scrap)/100;

            endif;
        ?>
        <td>{{$chip}}</td>
        <td>{{$turning_scrap}}</td>



        <input type="hidden" value="{{$issue_id}}" name="production_plan_issuence_id{{$key}}[]" id="production_plan_issuence_id{{$count}}" />
        <input type="hidden" value="{{$row1->bom_data_id}}" name="bom_data_id{{$key}}[]" id="" />
        <input type="hidden" value="{{0}}" name="type{{$key}}[]" id="type" />
        <input type="hidden" value="{{$chip}}" name="chip{{$key}}[]" id="" />
        <input type="hidden" value="{{$turning_scrap}}" name="turning{{$key}}[]" id="" />
        <input type="hidden" class="issue" value="{{$net_issue_qty}}" name="" id="net_issue{{$count}}" />


    </tr>

@endforeach

<?php $bom_data=ProductionHelper::get_bom_for_indirect($row->finish_goods_id);


?>


@foreach($bom_data as $row1)
    <?php
    $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->id);
    $issue_qty=0;
    $return_qty=0;
    $net_issue_qty=0;
     $issue_id=0;
    if ($data_count->count()>0):
        $issue_qty=$data_count->first()->issue_qty;
        $return_qty=ProductionHelper::plan_issue_qty($data_count->first()->id);
        $net_issue_qty=$issue_qty-$return_qty;
        $issue_id=$data_count->first()->id;
        $request_qty=$data_count->first()->request_qty;
    endif;
    ?>
    <tr class="text-center">
        <td> {{$count++}} </td>
        <td>{{CommonHelper::get_item_name($row1->item_id)}}</td>
        <td>{{$request_qty}}</td>
        <td @if($issue_qty==0) style="color: red" @endif>{{$net_issue_qty}}</td>
        <td> </td>
        <?php

        $chip='Not Applicable';
        $turning_scrap='Not Applicable';

        ?>
        <td>{{$chip}}</td>
        <td>{{$turning_scrap}}</td>


        <input type="hidden" value="{{$issue_id}}" name="production_plan_issuence_id{{$key}}[]" id="production_plan_issuence_id{{$count}}" />
        <input type="hidden" value="{{$row1->bom_data_id}}" name="bom_data_id{{$key}}[]" id="" />
        <input type="hidden" value="{{1}}" name="type{{$key}}[]" id="type" />
        <input type="hidden" value="{{$chip}}" name="chip{{$key}}[]" id="" />
        <input type="hidden" value="{{$turning_scrap}}" name="turning{{$key}}[]" id="" />
        <input type="hidden" class="issue" value="{{$net_issue_qty}}" name="" id="net_issue{{$count}}" />




    </tr>

@endforeach
<?php $index++ ?>

<script>
    $( document ).ready(function() {
        $('.issue').each(function(i, obj) {
      
           if ($(this).val()==0)
           {
               $('.main').css('display','none');
               $('#main').css('display','block');
           }
        });
    });
</script>
