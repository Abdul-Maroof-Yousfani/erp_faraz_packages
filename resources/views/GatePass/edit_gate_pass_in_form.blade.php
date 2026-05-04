@extends('layouts.default')

@section('content')
<div class="well_N">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title text-info"><b>Edit Gate Pass IN</b></h3>
        </div>
        <div class="panel-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="post" action="{{ url('/pdc/updateGatePassIn/' . $gatePass->id . '?m=' . $m) }}">
                {{ csrf_field() }}
                <input type="hidden" name="m" value="{{ $m }}">
                <input type="hidden" name="pageType" value="{{ $pageType ?? '' }}">
                <input type="hidden" name="parentCode" value="{{ $parentCode ?? '' }}">

                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Gate Pass ID</label>
                        <input type="text" class="form-control" value="{{ $gatePass->gate_pass_no }}" readonly>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Date</label>
                        <input type="text" class="form-control" value="{{ $gatePass->gate_pass_date }}" readonly>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Time</label>
                        <input type="text" class="form-control" value="{{ $gatePass->gate_pass_time }}" readonly>
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                        <label class="control-label small">Vehicle No</label>
                        <input type="text" class="form-control" value="{{ $gatePass->vehicle_no }}" readonly>
                    </div>
                </div>

                <div class="row" style="margin-top:14px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table class="table table-bordered sf-table-list">
                                <thead>
                                    <tr>
                                        <th class="text-center" style="width:60px;">S.No</th>
                                        <th class="text-center">Description</th>
                                        <th class="text-center">Customer / Party Name</th>
                                        <th class="text-center" style="width:130px;">Quantity</th>
                                        <th class="text-center" style="width:130px;">UOM</th>
                                        <th class="text-center" style="width:130px;">Bag Qty</th>
                                        <th class="text-center">Purpose</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $index => $item)
                                        <?php
                                            $partyName = '';
                                            if (!empty($item->party_id)) {
                                                $partyName = DB::connection('mysql2')->table('customers')->where('id', $item->party_id)->value('name') ?? '';
                                            }
                                        ?>
                                        <tr>
                                            <td class="text-center">{{ $index + 1 }}</td>
                                            <td><input type="text" class="form-control" value="{{ $item->item_name }}" readonly></td>
                                            <td><input type="text" class="form-control" value="{{ $partyName }}" readonly></td>
                                            <td><input type="text" class="form-control text-right" value="{{ number_format((float) ($item->qty ?? 0), 2) }}" readonly></td>
                                            <td><input type="text" class="form-control" value="{{ $item->uom ?? '' }}" readonly></td>
                                            <td><input type="text" class="form-control text-right" value="{{ isset($item->bag_qty) ? number_format((float) $item->bag_qty, 2) : '' }}" readonly></td>
                                            <td><input type="text" class="form-control" value="{{ $item->purpose ?? '' }}" readonly></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No detail found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-top:12px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label small">Description</label>
                        <textarea class="form-control" rows="3" readonly>{{ $gatePass->description }}</textarea>
                    </div>
                </div>

                <div class="row" style="margin-top:12px;">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label small">Gate Pass In Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" required name="gate_pass_in_description" rows="3">{{ $gatePass->gate_pass_in_description }}</textarea>
                    </div>
                </div>

                <div class="text-right" style="margin-top:15px;">
                    <button type="submit" class="btn btn-primary">Update Gate Pass IN</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
