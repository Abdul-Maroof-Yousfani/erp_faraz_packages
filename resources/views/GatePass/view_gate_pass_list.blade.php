<?php
use App\Helpers\CommonHelper;

$currentDate = date('Y-m-d');
$defaultFromDate = date('Y-m-d', strtotime('-3 days'));
$currentFromDate = $fromDate ?? $defaultFromDate;
$currentToDate = $currentDate;
?>

@extends('layouts.default')

@section('content')
    @include('select2')

    <div class="container-fluid">
        <div class="row well_N">
            <div class="col-md-12">
                <div class="panel panel-dark">
                    <div class="panel-heading clearfix">
                        
                        <div class="pull-right">
                            <a href="{{ url('/pdc/createGatePassForm?m=' . $m) }}" class="btn btn-sm btn-primary">Create Gate Pass</a>
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="row hide">
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-body text-center">
                                        <div class="text-muted small">Total Gate Passes</div>
                                        <h3 id="total_gate_passes" style="margin:6px 0 0;">{{ (int) ($summary->total_gate_passes ?? 0) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-body text-center">
                                        <div class="text-muted small">Direct Sale</div>
                                        <h3 id="direct_sale_count" style="margin:6px 0 0;">{{ (int) ($summary->direct_sale_count ?? 0) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-body text-center">
                                        <div class="text-muted small">Delivery Note</div>
                                        <h3 id="delivery_note_count" style="margin:6px 0 0;">{{ (int) ($summary->delivery_note_count ?? 0) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                <div class="panel panel-default">
                                    <div class="panel-body text-center">
                                        <div class="text-muted small">Manual</div>
                                        <h3 id="manual_count" style="margin:6px 0 0;">{{ (int) ($summary->manual_count ?? 0) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">Gate Pass No</label>
                                        <input type="text" id="gate_pass_no" class="form-control gatepass-filter" value="{{ $gatePassNo ?? '' }}" placeholder="GP-...">
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">Type</label>
                                        <select id="gate_pass_type" class="form-control select2 gatepass-filter">
                                            <option value="">All Types</option>
                                            <option value="1" @if(($gatePassType ?? '') === '1') selected @endif>Direct Sale Invoice</option>
                                            <option value="2" @if(($gatePassType ?? '') === '2') selected @endif>Delivery Note</option>
                                            <option value="3" @if(($gatePassType ?? '') === '3') selected @endif>Manual</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">Source No</label>
                                        <input type="text" id="source_no" class="form-control gatepass-filter" value="{{ $sourceNo ?? '' }}" placeholder="Invoice / note no">
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">Vehicle No</label>
                                        <input type="text" id="vehicle_no" class="form-control gatepass-filter" value="{{ $vehicleNo ?? '' }}" placeholder="Vehicle no">
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">From Date</label>
                                        <input type="date" id="from_date" class="form-control gatepass-filter" value="{{ $currentFromDate }}" max="{{ $currentDate }}">
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">To Date</label>
                                        <input type="date" id="to_date" class="form-control gatepass-filter" value="{{ $currentToDate }}" max="{{ $currentDate }}">
                                    </div>
                                </div>
                                <div class="row" style="margin-top:12px;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="button" class="btn btn-primary" id="refreshGatePassList">Filter</button>
                                        <button type="button" class="btn btn-default" id="resetGatePassList">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong class="small text-uppercase">All Gate Passes</strong>
                            </div>
                            <div id="gatePassTableWrap">
                                @include('GatePass.ajax_gate_pass_table', ['gatePasses' => $gatePasses])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('.select2').select2();

            var ajaxUrl = '{{ url('/pdc/viewGatePassListAjax') }}';
            var page = 1;
            var debounceTimer = null;

            function buildParams(nextPage) {
                return {
                    m: '{{ $m }}',
                    page: nextPage || 1,
                    gate_pass_no: $('#gate_pass_no').val(),
                    gate_pass_type: $('#gate_pass_type').val(),
                    source_no: $('#source_no').val(),
                    vehicle_no: $('#vehicle_no').val(),
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val()
                };
            }

            function loadGatePassList(nextPage) {
                page = nextPage || page || 1;
                $('#gatePassTableWrap').css('opacity', '0.5');

                $.ajax({
                    url: ajaxUrl,
                    type: 'GET',
                    dataType: 'json',
                    data: buildParams(page),
                    success: function (response) {
                        $('#total_gate_passes').text(response.summary.total_gate_passes || 0);
                        $('#direct_sale_count').text(response.summary.direct_sale_count || 0);
                        $('#delivery_note_count').text(response.summary.delivery_note_count || 0);
                        $('#manual_count').text(response.summary.manual_count || 0);
                        $('#gatePassTableWrap').html(response.table_html);
                    },
                    complete: function () {
                        $('#gatePassTableWrap').css('opacity', '1');
                    }
                });
            }

            function debounceLoad() {
                window.clearTimeout(debounceTimer);
                debounceTimer = window.setTimeout(function () {
                    loadGatePassList(1);
                }, 300);
            }

            $('.gatepass-filter').on('keyup change', function () {
                debounceLoad();
            });

            $('#refreshGatePassList').on('click', function () {
                loadGatePassList(1);
            });

            $('#resetGatePassList').on('click', function () {
                $('#gate_pass_no').val('');
                $('#gate_pass_type').val('').trigger('change');
                $('#source_no').val('');
                $('#vehicle_no').val('');
                $('#from_date').val('{{ $defaultFromDate }}');
                $('#to_date').val('{{ $currentDate }}');
                loadGatePassList(1);
            });

            $(document).on('click', '#gatePassTableWrap .pagination a', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var match = href.match(/[?&]page=(\d+)/);
                var nextPage = match ? parseInt(match[1], 10) : 1;
                loadGatePassList(nextPage);
            });

            loadGatePassList(1);
        });
    </script>
@endsection
