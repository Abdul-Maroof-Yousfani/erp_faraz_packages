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
                <th class="text-center">Vehicle No</th>
                <th class="text-center">Description</th>
                <th class="text-center">Gate Pass IN Description</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gatePassInList as $index => $gatePass)
                <tr>
                    <td class="text-center">{{ $gatePassInList->firstItem() + $index }}</td>
                    <td>{{ $gatePass->gate_pass_no }}</td>
                    <td class="text-center">{{ !empty($gatePass->gate_pass_date) ? CommonHelper::changeDateFormat($gatePass->gate_pass_date) : '-' }}</td>
                    <td class="text-center">{{ !empty($gatePass->gate_pass_time) ? Carbon\Carbon::parse($gatePass->gate_pass_time)->format('h:i A') : '-' }}</td>
                    <td>{{ $gatePass->vehicle_no ?: '-' }}</td>
                    <td>{{ $gatePass->description ?: '-' }}</td>
                    <td>{{ $gatePass->gate_pass_in_description ?: '-' }}</td>
                    <td class="text-center">
                        <div class="dropdown" style="display:inline-block;">
                            <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false" title="More Actions">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="javascript:void(0)" onclick="showDetailModelOneParamerter('pdc/viewGatePassInDetailAjax','{{ $gatePass->id }}','View Gate Pass IN Detail','{{ $m }}')" title="View">
                                        <i class="fa-regular fa-eye"></i> View
                                    </a>
                                    <a href="{{ url('/pdc/editGatePassInForm/' . $gatePass->id . '?m=' . $m) }}" class="dropdown-item_sale_order_list dropdown-item">
                                        <i class="fa-solid fa-pencil"></i> Edit
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-muted">No gate pass in records found for the selected filters.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="clearfix">
    <div class="pull-left text-muted small">
        Showing {{ $gatePassInList->firstItem() ?: 0 }} to {{ $gatePassInList->lastItem() ?: 0 }} of {{ $gatePassInList->total() }} records
    </div>
    <div class="pull-right">
        {{ $gatePassInList->links() }}
    </div>
</div>
