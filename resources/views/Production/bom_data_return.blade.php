<?php
use App\Helpers\ProductionHelper;
use App\Helpers\CommonHelper;


$count=1;
$bom_data=ProductionHelper::get_bom_for_direct($row->finish_goods_id);

$net_issued_qty=0;
?>


@foreach($bom_data as  $row1)
    <?php
    $key=rand();
    $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->id);
    $issue_qty=0;
    $issue_id=0;
    $previous_return=0;
    if ($data_count->count()>0):
        $issue_qty=$data_count->first()->issue_qty;
        $issue_id=$data_count->first()->id;
        $previous_return=ProductionHelper::plan_issue_qty($data_count->first()->id);
        $net_issued_qty=$issue_qty-$previous_return;
    endif;
    ?>
    <tr class="text-center">

        <td> {{$count++}} </td>
        <td>{{CommonHelper::get_item_name($row1->item_id)}}</td>
        <td>{{$row1->qty_ft*$row->planned_qty}}</td>
        <td @if($issue_qty==0) style="color: red" @endif>@if($issue_qty==0) Not Issue @else {{$issue_qty}} @endif</td>
        <td @if($issue_qty==0) style="color: red" @endif>{{$previous_return}}</td>
        <td @if($issue_qty==0) style="color: red" @endif><input onkeyup="calc('{{'direct'.$key}}')" onblur="calc('{{'direct'.$key}}')"  class="form-control" @if($issue_qty==0) disabled @endif type="text" name="return_qty" id="return_qty{{'direct'.$key}}" /> </td>
        <td @if($issue_qty==0) style="color: red" @endif><input onkeyup="calc('{{'direct'.$key}}')" onblur="calc('{{'direct'.$key}}')" class="form-control" disabled value="{{$net_issued_qty}}" type="text" name="net_issued_qty" id="net_issued_qty{{'direct'.$key}}" /> </td>
        <td id="td{{'direct'.$key}}" @if($issue_qty==0) style="color: red" @endif><input id="btn{{'direct'.$key}}" onclick="save('{{'direct'.$key}}')" type="button" class="form-control btn btn-success" value="Return" /> </td>

        <input type="hidden" value="{{$data->id}}" id="production_plan_id{{'direct'.$key}}" />
        <input type="hidden" value="{{$row->id}}" id="production_plan_data_id{{'direct'.$key}}" />
        <input type="hidden" value="{{$issue_id}}" id="production_plan_issuence_id{{'direct'.$key}}" />
        <input type="hidden" value="{{$issue_qty}}" id="issue_qty{{'direct'.$key}}" />
        <input type="hidden" value="{{$previous_return}}" id="previous_return{{'direct'.$key}}" />
        <input type="hidden" id="item_id{{'direct'.$key}}" value="{{$row1->item_id}}"/>
    </tr>

@endforeach
<?php $main_count++; ?>

<?php $bom_data=ProductionHelper::get_bom_for_indirect($row->finish_goods_id);
$net_issued_qty=0;;
?>


@foreach($bom_data as  $row1)
    <?php
     $key=rand();
    $data_count=ProductionHelper::check_issue_data($row1->bom_data_id,$row->id);
    $issue_qty=0;
    $issue_id=0;
    $previous_return=0;
    if ($data_count->count()>0):
        $issue_qty=$data_count->first()->issue_qty;
        $issue_id=$data_count->first()->id;
        $previous_return=ProductionHelper::plan_issue_qty($data_count->first()->id);
        $net_issued_qty=$issue_qty-$previous_return;
    endif;
    ?>
    <tr class="text-center">

        <td> {{$count++}} </td>
        <td>{{CommonHelper::get_item_name($row1->item_id)}}</td>
        <td>{{$row1->qty*$row->planned_qty}}</td>
        <td @if($issue_qty==0) style="color: red" @endif>@if($issue_qty==0) Not Issue @else {{$issue_qty}} @endif</td>
        <td @if($issue_qty==0) style="color: red" @endif>{{$previous_return}}</td>
        <td @if($issue_qty==0) style="color: red" @endif><input onkeyup="calc('{{'indirect'.$key}}')" onblur="calc('{{'indirect'.$key}}')" class="form-control" @if($issue_qty==0) disabled @endif type="text" name="return_qty" id="return_qty{{'indirect'.$key}}" /> </td>
        <td @if($issue_qty==0) style="color: red" @endif><input disabled onkeyup="calc('{{'indirect'.$key}}')" onblur="calc('{{'indirect'.$key}}')" class="form-control" @if($issue_qty==0) disabled @endif value="{{$net_issued_qty}}" type="text" name="net_issued_qty" id="net_issued_qty{{'indirect'.$key}}" /> </td>
        <td id="td{{'indirect'.$key}}" @if($issue_qty==0) style="color: red" @endif><input id="btn{{'indirect'.$key}}" onclick="save('{{'indirect'.$key}}')"  type="button" class="form-control btn btn-success" value="Return" /> </td>

        <input type="hidden" value="{{$data->id}}" id="production_plan_id{{'indirect'.$key}}" />
        <input type="hidden" value="{{$row->id}}" id="production_plan_data_id{{'indirect'.$key}}" />
        <input type="hidden" value="{{$issue_id}}" id="production_plan_issuence_id{{'indirect'.$key}}" />
        <input type="hidden" value="{{$issue_qty}}" id="issue_qty{{'indirect'.$key}}" />
        <input type="hidden" value="{{$previous_return}}" id="previous_return{{'indirect'.$key}}" />
        <input type="hidden" id="item_id{{'indirect'.$key}}" value="{{$row1->item_id}}"/>
    </tr>

@endforeach
<?php $main_count++ ?>
<script>
    function save(number)
    {
        $('#btn'+number).prop("disabled", true);
        var production_plan_id=$('#production_plan_id'+number).val();
        var production_plan_data_id=$('#production_plan_data_id'+number).val();
        var production_plan_issuence_id=$('#production_plan_issuence_id'+number).val();
        var return_qty=parseFloat($('#return_qty'+number).val());
        var issue_qty=parseFloat($('#issue_qty'+number).val());
        var previous_qty=parseFloat($('#previous_qty'+number).val());
        var item_id=parseFloat($('#item_id'+number).val());
        issue_qty=issue_qty-previous_qty;

        var criteria=validation(return_qty,issue_qty);
        if (criteria==false)
        {
            $('#btn'+number).prop("disabled", false);
            return false;
        }


        $.ajax({
            url:'{{url('/production/return_material')}}',
            data:{production_plan_id:production_plan_id,production_plan_data_id:production_plan_data_id,production_plan_issuence_id:production_plan_issuence_id,
                return_qty:return_qty,item_id:item_id},
            type:'GET',
            success:function(response)
            {


                $('#btn'+number).css("display", 'none');
                $('#td'+number).html('&#9989;');
            }
        });
    }
    function validation(return_qty,issue_qty)
    {
        var criteria=false;
        if (issue_qty==0)
        {
            criteria=false;
        }

        if (issue_qty!=0)
        {
            if (return_qty>issue_qty)
            {
                alert('Retrun qty can not greater than issue qty');
                criteria =false;
            }
            else
            {
                criteria=true;
            }
        }
        return criteria;
    }

    function calc(number)
    {
        var issue_qty=parseFloat($('#issue_qty'+number).val());
        var return_qty=parseFloat($('#return_qty'+number).val());
        var previous_return=parseFloat($('#previous_return'+number).val());

        issue_qty=issue_qty-previous_return;

        if (isNaN(return_qty))
        {
            return_qty=0;
        }

            if (return_qty>issue_qty)
            {
                alert('Retrun qty can not greater than issue qty');
                $('#return_qty'+number).val(0);

            }
        var total=issue_qty-return_qty;
        $('#net_issued_qty'+number).val(total);


    }
</script>