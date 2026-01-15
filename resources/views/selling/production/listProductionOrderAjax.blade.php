<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use Illuminate\Support\Facades\DB;
$i = 1;
?>
@foreach($productions as $production)
    <?php

        $customer = CommonHelper::byers_name($production->customer);

        $customer = (!empty($customer)) ? $customer->name : '';
        $product = CommonHelper::get_item_by_id($production->finish_goods_id)
    ?>
    <tr id="{{$i}}">
        <td class="text-center">{{ $i }}</td>
        <td class="text-center">{{ $production->pr_no }}</td>
        <td class="text-center">{{ $production->order_no }}</td>
        <td class="text-center">{{ $product->item_code.' -- '.$product->sub_ic }}</td>
        <td class="text-center">{{ $production->color }}</td>
        <td class="text-center">{{ $production->planned_qty }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($production->start_date) }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($production->delivery_date) }}</td>
        <td class="text-center">{{ ProductionHelper::getProductionRequestStatus($production->approval_status) }}</td>
        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i
                        class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>

                        <!-- <a onclick="showDetailModelTwoParamerter('selling/viewProductionOrder','{{ $production->pp_id }}','view Production Order','')">
                            View
                        </a> -->
                        <a onclick="showDetailModelTwoParamerter('selling/viewProductionOrderPrint','{{ $production->pp_id }}','View Production Order','')">
                            View
                        </a>

                        @if($production->approval_status != 2)
                            <a href="editProductionOrder?id={{ $production->pp_id }}">
                                Edit
                            </a>

                            <a onclick="deleteProduction({{$production->pp_id}},{{$i}},@if($production->type == 1) 'deleteProduction')"
                                @elseif($production->type == 2) 'deleteGeneralProduction')" @endif>Delete</a>

                            <a onclick="approveProductionPlanMr( '{{$production->pp_id}}' , '{{$production->p_p_d_id}}' , '{{$production->finish_goods_id}}' )">Approve
                            </a>
                        @endif
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    @php
        $i++;
    @endphp
@endforeach


<script>

function deleteProduction(pp_id, count, link) {
        console.log('/selling/' + link)
        $.ajax({
            url: '/selling/' + link,
            type: 'Get',
            data: {
                production_id: pp_id,
            },
            success: function (response) {

                if (response == 1) {
                    $('#' + count).remove();
                }
                else {
                    alert('record not found')
                }

            }
        });
    }

    function approveProductionPlanMr(production_id, production_plan_data_id, finish_good_id) {
        $.ajax({
            url: "{{url('/')}}/selling/approveProductionPlanMr",
            type: 'Get',
            data: {
                production_id: production_id,
                production_plan_data_id: production_plan_data_id,
                finish_good_id: finish_good_id
            },
            success: function (response) {
                if (response == "true") {
                    location.reload();
                }
            }
        });
    }

   
</script>