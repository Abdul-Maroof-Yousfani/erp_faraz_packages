<?php
use App\Helpers\CommonHelper;
?>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-condensed">
        <thead>
            <tr>
                <th class="text-center">#</th>
                <th class="text-center">Gate Pass No</th>
                <th class="text-center">Date</th>
                <th class="text-center">Time</th>
                <th class="text-center">Type</th>
                <th class="text-center">Source No</th>
                <th class="text-center">Description</th>
                <th class="text-center">Vehicle No</th>
                <th class="text-center">Action</th>
                <th class="text-center hide">Items</th>
                <th class="text-center hide">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gatePasses as $index => $gatePass)
                <tr>
                    <td class="text-center">{{ $gatePasses->firstItem() + $index }}</td>
                    <td>{{ $gatePass->gate_pass_no }}</td>
                    <td class="text-center">{{ !empty($gatePass->gate_pass_date) ? CommonHelper::changeDateFormat($gatePass->gate_pass_date) : '-' }}</td>
                    <td class="text-center">{{ Carbon\Carbon::parse($gatePass->gate_pass_time)->format('h:i A') ?? '-' }}</td>
                    <td class="text-center">
                        @if((int) $gatePass->gate_pass_type === 1)
                            <span class="label label-success">Direct Sale</span>
                        @elseif((int) $gatePass->gate_pass_type === 2)
                            <span class="label label-primary">Delivery Note</span>
                        @else
                            <span class="label label-default">Manual</span>
                        @endif
                    </td>
                    <td>{{ $gatePass->source_no ?: '-' }}</td>
                    <td>{{ $gatePass->description ?: '-' }}</td>
                    <td>{{ $gatePass->vehicle_no ?: '-' }}</td>
                    <td class="text-center">
                        
                        <div class="dropdown" style="display:inline-block;">
                            <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" title="More Actions">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="javascript:void(0)" onclick="showDetailModelOneParamerter('pdc/viewGatePassDetailAjax','{{ $gatePass->id }}','View Gate Pass Detail','{{ $m }}')" title="View">
                                        <i class="fa-regular fa-eye"></i> View
                                    </a>
                                    <a href="{{ url('/pdc/editGatePassForm/' . $gatePass->id . '?m=' . $m) }}" type="button" class="dropdown-item_sale_order_list dropdown-item">
                                        <i class="fa-solid fa-pencil"></i> Edit
                                    </a>
                                    <a href="{{ url('/pdc/deleteGatePass/' . $gatePass->id . '?m=' . $m) }}" class="dropdown-item_sale_order_list dropdown-item" onclick="return confirm('Delete this gate pass?')">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </a>
                                 
                                </li>
                            </ul>
                        </div>
                    </td>
                    <td class="text-center hide">{{ (int) ($gatePass->items_count ?? 0) }}</td>
                    <td class="text-right hide">{{ number_format((float) ($gatePass->total_amount ?? 0), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="12" class="text-center text-muted">No gate passes found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="clearfix">
    <div class="pull-left text-muted small">
        Showing {{ $gatePasses->firstItem() ?: 0 }} to {{ $gatePasses->lastItem() ?: 0 }} of {{ $gatePasses->total() }} records
    </div>
    <div class="pull-right">
        {{ $gatePasses->links() }}
    </div>
</div>
