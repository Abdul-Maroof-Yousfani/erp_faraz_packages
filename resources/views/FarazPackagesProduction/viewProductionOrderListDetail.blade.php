<?php 
$counter = 1;
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
?>
@foreach($production_request as $key => $val)
    <tr id="{{ $val->id }}">
        <td class="text-center">{{ $counter++ }}</td>
        <td class="text-center">{{ $val->pr_no }}</td>
        <td class="text-center">{{ CommonHelper::changeDateFormat($val->request_date) }}</td>
        <td class="text-center">{{ $val->username }}</td>
        <td class="text-center">{{ ProductionHelper::getProductionRequestStatus($val->approval_status) }}</td>
        <td class="text-center hidden-print">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                <ul class="dropdown-menu">
                    <li>
                        <a onclick="showDetailModelOneParamerter('far_production/viewProductionOrderDetail?m={{ $m }}','{{ $val->id }}','View Production Order')" type="button" class="dropdown-item_sale_order_list dropdown-item "><i class="fa-regular fa-eye"></i> View</a>
                        <a onclick="showDetailModelOneParamerter('far_production/viewProductionOrderDetailTrack?m={{ $m }}','{{ $val->id }}','View Production Tracking')" type="button" class="dropdown-item_sale_order_list dropdown-item "><i class="fa-regular fa-eye"></i> Track</a>
                        @if($val->approval_status == 1)
                            <a href="{{url('far_production/editProductionOrderForm/'.$val->id.'?m='.$m) }}" type="button" class="dropdown-item_sale_order_list dropdown-item "><i class="fa-solid fa-pencil"></i> Edit</a>
                        @endif
                        <a id="{{ $val->id }}" type="button" onclick="deleteProductionOrder('{{ $val->id }}')" class="dropdown-item_sale_order_list dropdown-item"><i class="fa-solid fa-trash"></i> Delete</a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>

    <script>
        function deleteProductionOrder(id) {
			if (confirm('Are you sure you want to delete this request')) {
				$.ajax({
					url: '<?php echo url('/') ?>/far_prod/deleteProductionOrder',
					type: 'GET',
					data: { id: id },
					success: function (response) {
                        if(response == 'true') {
                            $('#' + id).fadeOut();
                        }
					}
				});
			}
		}
    </script>
@endforeach