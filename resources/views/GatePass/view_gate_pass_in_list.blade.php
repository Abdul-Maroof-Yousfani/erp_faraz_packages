<?php
$currentDate = date('Y-m-d');
$defaultFromDate = date('Y-m-d', strtotime('-3 days'));
?>

@extends('layouts.default')

@section('content')
    <div class="container-fluid">
        <div class="row well_N">
            <div class="col-md-12">
                <div class="panel panel-dark">
                    <div class="panel-heading clearfix">
                        <div class="pull-right">
                            <a href="{{ url('/pdc/gatePassInForm?m=' . $m . '&pageType=' . ($pageType ?? '') . '&parentCode=' . ($parentCode ?? '')) }}" class="btn btn-sm btn-primary">Create Gate Pass IN</a>
                        </div>
                    </div>

                    <div class="panel-body">
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">Gate Pass No</label>
                                        <input type="text" id="gate_pass_no" class="form-control gatepassin-filter" value="{{ $gatePassNo ?? '' }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">Vehicle No</label>
                                        <input type="text" id="vehicle_no" class="form-control gatepassin-filter" value="{{ $vehicleNo ?? '' }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">From Date</label>
                                        <input type="date" id="from_date" class="form-control gatepassin-filter" value="{{ $fromDate ?? $defaultFromDate }}" max="{{ $currentDate }}">
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                                        <label class="control-label small">To Date</label>
                                        <input type="date" id="to_date" class="form-control gatepassin-filter" value="{{ $toDate ?? $currentDate }}" max="{{ $currentDate }}">
                                    </div>
                                </div>
                                <div class="row" style="margin-top:12px;">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <button type="button" class="btn btn-primary" id="refreshGatePassInList">Filter</button>
                                        <button type="button" class="btn btn-default" id="resetGatePassInList">Reset</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <strong class="small text-uppercase">Gate Pass IN Listing</strong>
                            </div>
                            <div id="gatePassInTableWrap">
                                @include('GatePass.ajax_gate_pass_in_table', ['gatePassInList' => $gatePassInList, 'm' => $m])
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var ajaxUrl = '{{ url('/pdc/viewGatePassInListAjax') }}';
            var debounceTimer = null;

            function buildParams(nextPage) {
                return {
                    m: '{{ $m }}',
                    page: nextPage || 1,
                    gate_pass_no: $('#gate_pass_no').val(),
                    vehicle_no: $('#vehicle_no').val(),
                    from_date: $('#from_date').val(),
                    to_date: $('#to_date').val()
                };
            }

            function loadGatePassInList(nextPage) {
                $('#gatePassInTableWrap').css('opacity', '0.5');
                $.ajax({
                    url: ajaxUrl,
                    type: 'GET',
                    dataType: 'json',
                    data: buildParams(nextPage),
                    success: function (response) {
                        $('#gatePassInTableWrap').html(response.table_html);
                    },
                    complete: function () {
                        $('#gatePassInTableWrap').css('opacity', '1');
                    }
                });
            }

            function debounceLoad() {
                window.clearTimeout(debounceTimer);
                debounceTimer = window.setTimeout(function () {
                    loadGatePassInList(1);
                }, 300);
            }

            $('.gatepassin-filter').on('keyup change', debounceLoad);
            $('#refreshGatePassInList').on('click', function () { loadGatePassInList(1); });
            $('#resetGatePassInList').on('click', function () {
                $('#gate_pass_no').val('');
                $('#vehicle_no').val('');
                $('#from_date').val('{{ $defaultFromDate }}');
                $('#to_date').val('{{ $currentDate }}');
                loadGatePassInList(1);
            });
            $(document).on('click', '#gatePassInTableWrap .pagination a', function (e) {
                e.preventDefault();
                var href = $(this).attr('href');
                var match = href.match(/[?&]page=(\d+)/);
                loadGatePassInList(match ? parseInt(match[1], 10) : 1);
            });
            loadGatePassInList(1);
        });
    </script>
@endsection
