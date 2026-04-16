<?php
use App\Helpers\CommonHelper;
?>

<div class="panel panel-default" style="margin-bottom:0;">
    <div class="panel-heading clearfix">
        <div class="pull-left">
            <strong>Gate Pass Details</strong>
        </div>
        <div class="pull-right">
            <span class="label label-info">{{ $gatePass->gate_pass_no }}</span>
        </div>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4 col-sm-6">
                <div class="small text-muted">Type</div>
                <div><strong>{{ $sourceTypeLabel }}</strong></div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="small text-muted">Source No</div>
                <div><strong>{{ $gatePass->source_no ?: '-' }}</strong></div>
            </div>
            <div class="col-md-4 col-sm-6">
                <div class="small text-muted">Date / Time</div>
                <div><strong>{{ !empty($gatePass->gate_pass_date) ? CommonHelper::changeDateFormat($gatePass->gate_pass_date) : '-' }}</strong> {{ !empty($gatePass->gate_pass_time) ? ' | ' . date('h:i A', strtotime($gatePass->gate_pass_time)) : '' }}</div>
            </div>
          
            <div class="col-md-3 col-sm-6" style="margin-top:10px;">
                <div class="small text-muted">Vehicle No</div>
                <div>{{ $gatePass->vehicle_no ?: '-' }}</div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-top:10px;">
                <div class="small text-muted">Vehicle Type</div>
                <div>{{ $gatePass->vehicle_type ?: '-' }}</div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-top:10px;">
                <div class="small text-muted">Driver</div>
                <div>{{ $gatePass->driver_name ?: '-' }}</div>
            </div>
            <div class="col-md-3 col-sm-6" style="margin-top:10px;">
                <div class="small text-muted">Contact</div>
                <div>{{ $gatePass->vehicle_contact ?: '-' }}</div>
            </div>
            <div class="col-md-6 hide" style="margin-top:10px;">
                <div class="small text-muted">Transporter / Company</div>
                <div>{{ $gatePass->transporter_name ?: '-' }}</div>
            </div>
        </div>
        <div class="row">
              <div class="col-md-12" style="margin-top:10px;">
                <div class="small text-muted">Description</div>
                <div>{{ $gatePass->description ?: '-' }}</div>
            </div>
        </div>

        <hr>

        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Item Name</th>
                        <th class="text-center">Qty</th>
                        <th class="text-center hide">Rate</th>
                        <th class="text-center hide">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($items as $index => $item)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $item->item_name ?: '-' }}</td>
                            <td class="text-right">{{ number_format((float) $item->qty, 2) }}</td>
                            <td class="text-right hide">{{ number_format((float) $item->rate, 2) }}</td>
                            <td class="text-right hide">{{ number_format((float) $item->amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No items found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
