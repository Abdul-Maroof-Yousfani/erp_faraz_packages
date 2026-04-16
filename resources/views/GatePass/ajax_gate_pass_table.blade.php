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
                <th class="text-center">Items</th>
                <th class="text-center">Amount</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gatePasses as $index => $gatePass)
                <tr>
                    <td class="text-center">{{ $gatePasses->firstItem() + $index }}</td>
                    <td>{{ $gatePass->gate_pass_no }}</td>
                    <td class="text-center">{{ !empty($gatePass->gate_pass_date) ? CommonHelper::changeDateFormat($gatePass->gate_pass_date) : '-' }}</td>
                    <td class="text-center">{{ $gatePass->gate_pass_time ?? '-' }}</td>
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
                    <td class="text-center">{{ (int) ($gatePass->items_count ?? 0) }}</td>
                    <td class="text-right">{{ number_format((float) ($gatePass->total_amount ?? 0), 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center text-muted">No gate passes found for the selected filters.</td>
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
