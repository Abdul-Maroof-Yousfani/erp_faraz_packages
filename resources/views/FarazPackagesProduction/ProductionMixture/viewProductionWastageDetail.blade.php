<?php
$processLabel = $wastage ? ($processes[$wastage->process] ?? ucwords(str_replace('_', ' ', $wastage->process))) : '-';
?>
@if(!$wastage)
    <div class="alert alert-danger">Record not found.</div>
@else
    <div class="table-responsive">
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>Production Order</th>
                    <td>{{ $wastage->pr_no ?? '-' }}</td>
                    <th>PO Date</th>
                    <td>{{ $wastage->request_date ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Ref No.</th>
                    <td>{{ $wastage->ref_no ?? '-' }}</td>
                    <th>Current Status</th>
                    <td>{{ $wastage->curr_status ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Process</th>
                    <td>{{ $processLabel }}</td>
                    <th>Wastage Date</th>
                    <td>{{ $wastage->wastage_date }}</td>
                </tr>
                <tr>
                    <th>Total Qty (KG)</th>
                    <td>{{ number_format($wastage->qty, 2) }}</td>
                    <th></th>
                    <td></td>
                </tr>
            </tbody>
        </table>
    </div>

    <h4>Item Details</h4>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-center">S.No</th>
                    <th class="text-center">Item</th>
                    <th class="text-center">Qty (KG)</th>
                    <th class="text-center">Remarks</th>
                </tr>
            </thead>
            <tbody>
                @foreach($wastageDetails as $key => $detail)
                    <tr>
                        <td class="text-center">{{ $key + 1 }}</td>
                        <td>{{ $detail->item_code }} -- {{ $detail->sub_ic }} {{ $detail->uom_name ? '(' . $detail->uom_name . ')' : '' }}</td>
                        <td class="text-right">{{ number_format($detail->qty, 2) }}</td>
                        <td>{{ $detail->ppc ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
