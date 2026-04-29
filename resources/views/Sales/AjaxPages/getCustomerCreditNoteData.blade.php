<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
use App\Helpers\ReuseableCode;


$view=ReuseableCode::check_rights(124);
$edit=ReuseableCode::check_rights(125);
$export=ReuseableCode::check_rights(258);
$counter = 1;$total=0;
$OverAllTotal = 0;

?>

@foreach($credit_note as $row)
    <?php $customer=CommonHelper::byers_name($row->buyers_id);
    $data=SalesHelper::get_total_amount_for_sales_order_by_id($row->so_id);
    $SoNo = SalesHelper::get_credit_note_so_no($row);
    $SiNo = SalesHelper::get_credit_note_source_no($row);
    ?>
    <tr title="{{$row->id}}" id="{{$row->id}}">
        <td class="text-center">{{$counter++}}</td>
        <td class="text-center"><?php echo strtoupper($SoNo)?></td>
        <td class="text-center"><?php echo strtoupper($SiNo)?></td>
        <td class="text-center">@if($row->type==1) DN @else SI @endif</td>
        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->cr_no)}}</td>
        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->cr_date);?></td>
        <td class="text-center"><?php   $customer=CommonHelper::byers_name($row->buyer_id);
            echo   $customer->name;
            ?></td>



        <td class="text-center">
            <div class="dropdown">
                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis-vertical"></i>
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <?php if($view == true):?>
                            <a onclick="showDetailModelOneParamerter('sales/viewCreditNoteDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                               type="button" class="dropdown-item_sale_order_list dropdown-item">
                                <i class="fa-regular fa-eye"></i> View
                            </a>
                            <a href="{{ route('sales.salereturn.edit', ['id'=> $row->id]) }}"
                               type="button" class="dropdown-item_sale_order_list dropdown-item">
                                <i class="fa-solid fa-pencil"></i> Edit
                            </a>
                        <?php endif;?>
                        <?php if($edit == true):?>
                            <a onclick="delete_sales_return('{{$row->id}}','{{$row->cr_no}}')"
                               type="button" class="dropdown-item_sale_order_list dropdown-item">
                                <i class="fa-solid fa-trash"></i> Delete
                            </a>
                        <?php endif;?>
                    </li>
                </ul>
            </div>
        </td>

        {{--<td class="text-center"><button onclick="DeleteSalesReturn('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
    </tr>


@endforeach
