
<?php use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$view=ReuseableCode::check_rights(37);
$edit=ReuseableCode::check_rights(211);
$delete=ReuseableCode::check_rights(38);
?>

<?php $counter = 1;$total=0;?>


@foreach($purchase_voucher as $row)
    @php
        $connection = DB::connection('mysql2');
        
        $details = $connection->table('new_purchase_voucher_data')
            ->where('master_id', $row->id)
            ->get();
            
        $net_amount = $details->sum('net_amount') ?? 0;
        
        $grn_net_amount = $connection->table('grn_data')
            ->where('master_id', $row->grn_id)
            ->sum('net_amount') ?? 0;
            
        $grn_date = $connection->table('goods_receipt_note')
            ->where('id', $row->grn_id)
            ->value('grn_date') ?? '-';
            
        $detail_count = $details->count() ?: 1; // avoid rowspan=0
        
        $t_amount = $connection->table('transactions')
            ->where('voucher_no', $row->pv_no)
            ->where('debit_credit', 1)
            ->sum('amount') ?? 0;

        $has_mismatch = ($t_amount != $net_amount) || ($net_amount != $grn_net_amount);
        
        $total += $net_amount;
    @endphp

    @forelse($details as $index => $detail)
        <tr @if($has_mismatch) @endif id="{{ $row->id }}">
            @if($index === 0)
                <td rowspan="{{ $detail_count }}" class="text-center">{{ $counter++ }}</td>
                <td rowspan="{{ $detail_count }}" title="{{ $row->id }}" class="text-center">{{ strtoupper($row->pv_no ?? '-') }}</td>
                <td rowspan="{{ $detail_count }}" class="text-center">{{ CommonHelper::changeDateFormat($row->pv_date ?? null) }}</td>
                <td rowspan="{{ $detail_count }}" title="{{ $row->id }}" class="text-center">
                    {{ strtoupper($row->grn_no ?? '-') }}<br>{{ $grn_date }}
                </td>
                <td rowspan="{{ $detail_count }}" class="text-center">{{ $row->slip_no ?? '-' }}</td>
                <td rowspan="{{ $detail_count }}" class="text-center">{{ CommonHelper::changeDateFormat($row->bill_date ?? null) }}</td>
                <td rowspan="{{ $detail_count }}" id="app{{ $row->id }}" class="text-center text-danger">
                    @if($row->pv_status == 1) Pending
                    @elseif($row->pv_status == 3) 1st Approve
                    @else Approved @endif
                </td>
                <td rowspan="{{ $detail_count }}" class="text-center">
                    {{ CommonHelper::get_supplier_name($row->supplier ?? null) ?? '-' }}
                </td>
            @endif

            <!-- Detail columns – repeated for each item -->
           

            @if($index === 0)
                <!-- Net Amount – shown once with rowspan -->
                <td rowspan="{{ $detail_count }}" class="text-right">
                    PKR {{ number_format($net_amount, 2) }}
                </td>
                <!-- Hidden GRN comparison amount -->
                <td rowspan="{{ $detail_count }}" class="text-right hide">
                    {{ number_format($grn_net_amount, 2) }}
                </td>
            @endif
             <td class="text-right">{{ CommonHelper::get_item_name($detail->sub_item ?? null) ?? '-' }}</td>
            <td class="text-right">{{ number_format($detail->rate ?? 0, 2) }}</td>
            <td class="text-right">{{ number_format($detail->qty ?? 0, 2) }}</td>
            <td class="text-right">{{ number_format($detail->amount ?? 0, 2) }}</td>
            <!-- Action column – shown on every row or only first? (your original shows on every) -->
            @if($index === 0)

            <td rowspan="{{ $detail_count }}" class="text-center hidden-print">
                <div class="dropdown">
                    <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            @if($view ?? false)
                                @if(empty($row->grn_no))
                                    <a onclick="showDetailModelOneParamerter('fdc/viewDirectPurchaseVoucherDetail','{{ $row->id }}','View Purchase Voucher','{{ $m ?? '' }}')" 
                                       class="dropdown-item_sale_order_list dropdown-item">View</a>
                                @else
                                    <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','{{ $row->id }}','View Purchase Voucher','{{ $m ?? '' }}')" 
                                       class="dropdown-item_sale_order_list dropdown-item">View</a>
                                @endif
                            @endif

                            @if($row->pv_status == 1 && ($edit ?? false))
                                @if($row->grn_no == '0' || empty($row->grn_no))
                                    <a href="{{ URL::asset('finance/editDirectPurchaseVoucherForm/'.$row->id.'?m='.($m ?? '')) }}" 
                                       class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                                @else
                                    <a href="{{ URL::asset('finance/editPurchaseVoucherFormNew/'.$row->id.'?m='.($m ?? '')) }}" 
                                       class="dropdown-item_sale_order_list dropdown-item">Edit</a>
                                @endif
                            @endif

                            @if(($delete ?? false) && $row->pv_status != 2)
                                <a onclick="delete_record('{{ $row->id }}','{{ $row->grn_no ?? '' }}','{{ $row->pv_no ?? '' }}')" 
                                   class="dropdown-item_sale_order_list dropdown-item">Delete</a>
                            @endif
                        </li>
                    </ul>
                </div>
            </td>
            @endif

        </tr>
    @empty
        <tr id="{{ $row->id }}">
            <td>{{ $counter++ }}</td>
            <td colspan="13" class="text-center text-danger">No items found for this voucher</td>
        </tr>
    @endforelse
@endforeach

<!-- Grand Total Row -->
<tr class="font-weight-bold">
    <td colspan="8" class="text-right">Total</td>
    <td class="text-right">PKR {{ number_format($total, 2) }}</td>
    <td colspan="4"></td> <!-- adjust colspan to match remaining columns -->
</tr>