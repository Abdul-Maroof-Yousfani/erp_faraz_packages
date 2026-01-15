<?php
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;


$count=1;
$bom_data=ProductionHelper::get_bom_for_direct($row->finish_goods_id); ?>


@foreach($bom_data as $row1)
     <?php
     $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->id);
    $issue_qty=0;
     $return_qty=0;
     $net_issue_qty=0;
     if ($data_count->count()>0):
     $issue_qty=$data_count->first()->issue_qty;
     $return_qty=ProductionHelper::plan_issue_qty($data_count->first()->id);
     $net_issue_qty=$issue_qty-$return_qty;
     $issue_id=$data_count->first()->id;
     $request_qty=$data_count->first()->request_qty;
     else:
         $issue_id=0;
         $request_qty=$row1->qty_ft*$row->planned_qt;

      endif;
     ?>
    <tr class="text-center">
    <td> {{$count++}} </td>
    <td>{{CommonHelper::get_item_name($row1->item_id)}}</td>
    <td>{{ $request_qty }}</td>
    <td onclick="get_tra_data('{{$issue_id}}','{{$data->order_no}}','16')" style="cursor: pointer" @if($issue_qty==0) style="color: red" @endif>@if($issue_qty==0) Not Issue @else {{$issue_qty}} @endif</td>
    <td onclick="get_tra_data('{{$issue_id}}','{{$data->order_no}}','17')" style="cursor: pointer" @if($issue_qty==0) style="color: red" @endif>{{$return_qty}}</td>
    <td @if($issue_qty==0) style="color: red" @endif>{{$net_issue_qty}}</td>
    </tr>

@endforeach

<?php $bom_data=ProductionHelper::get_bom_for_indirect($row->finish_goods_id); ?>


@foreach($bom_data as $row1)
    <?php
    $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->id);
    $issue_qty=0;
    $return_qty=0;
    $net_issue_qty=0;
     if ($data_count->count()>0):
        $issue_qty=$data_count->first()->issue_qty;
         $return_qty=ProductionHelper::plan_issue_qty($data_count->first()->id);
         $net_issue_qty=$issue_qty-$return_qty;
         $issue_id=$data_count->first()->id;
         $request_qty=$data_count->first()->request_qty;
     else:
         $issue_id=0;
         $request_qty=$row1->qty*$row->planned_qty;

     endif;
    ?>
    <tr class="text-center">
        <td> {{$count++}} </td>
        <td>{{CommonHelper::get_item_name($row1->item_id)}}</td>
        <td>{{ $request_qty }}</td>
        <td onclick="get_tra_data('{{$issue_id}}','{{$data->order_no}}','16')" style="cursor: pointer" @if($issue_qty==0) style="color: red" @endif>@if($issue_qty==0) Not Issue @else {{$issue_qty}} @endif</td>
        <td onclick="get_tra_data('{{$issue_id}}','{{$data->order_no}}','17')" style="cursor: pointer" @if($issue_qty==0) style="color: red" @endif>{{$return_qty}}</td>
        <td @if($issue_qty==0) style="color: red" @endif>{{$net_issue_qty}}</td>
    </tr>



    <script>
    function get_tra_data(id,voucher_no,type)
    {
        $('#data').html('<div class="loader"></div>');
        $.ajax({

            url:'{{url('production/get_ledger_data')}}',
            type:'GET',
            data:{voucher_no:voucher_no,id:id,type:type},
            success:function(response){
                $('#data').html(response);
            },
            err:function(err){
                $('#data').html(err);
            }
        })
    }

    </script>

@endforeach