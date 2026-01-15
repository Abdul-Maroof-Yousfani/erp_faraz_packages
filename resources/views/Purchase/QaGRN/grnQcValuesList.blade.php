<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$export = ReuseableCode::check_rights(232);
$accType = Auth::user()->acc_type;
$m = Session::get('run_company');
$counter = 1;
?>
@extends('layouts.default')
@section('content')
@include('select2')
<div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="headquid">
                    <div class="row">
                        <div class="col-md-6">
                            <h2 class="subHeadingLabelClass">GRN QC Values List</h2>
                        </div>
                        <div class="col-md-6 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('printDemandVoucherList', '', '1');?>
                            <?php if ($export == true):?>
                            <?php    echo CommonHelper::displayExportButton('demandVoucherList', '', '1')?>
                            <?php endif;?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <input type="hidden" name="m" id="m" value="{{ $m }}" readonly class="form-control" />
                            <input type="hidden" name="pageType" id="pageType" value="0" readonly="readonly"
                                class="form-control" />

                            <div class="lineHeight">&nbsp;</div>
                            <div id="printDemandVoucherList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="userlittab table table-bordered sf-table-list"
                                                                id="demandVoucherList">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">S.No</th>
                                                                        <th class="text-center">Finish Goods</th>
                                                                        <th class="text-center">Actions</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @forelse ($qcValues as $index => $qcValue)
                                                                        <tr id="row-{{ $counter }}">
                                                                            <td class="text-center">
                                                                                {{ $counter }}
                                                                            </td>
                                                                            <td class="text-center">
                                                                                {{ $qcValue->item_code . ' -- ' . $qcValue->item_name }}
                                                                            </td>
                                                                            <td class="text-center hidden-print">
                                                                                <div class="dropdown">
                                                                                    <button class="drop-bt dropdown-toggle"
                                                                                        type="button" data-toggle="dropdown"
                                                                                        aria-expanded="false"><i
                                                                                            class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                                    <ul class="dropdown-menu">
                                                                                        <li>
                                                                                            <a onclick="showDetailModelOneParamerter('purchase/grnViewQcValuesDetail?m={{ $m }}','{{ $qcValue->id }}','View GRN QC Values')"
                                                                                                type="button"
                                                                                                class="dropdown-item_sale_order_list dropdown-item">View</a>

                                                                                            <a href="{{ route('purchase.grnEditQcValues', $qcValue->id) }}"
                                                                                                type="button"
                                                                                                class="dropdown-item_sale_order_list dropdown-item">
                                                                                                Edit</a>

                                                                                            <a onclick="grnDeleteQcValue('{{ $qcValue->id }}', '{{ $counter++ }}')"
                                                                                                type="button"
                                                                                                class="dropdown-item_sale_order_list dropdown-item ">
                                                                                                Delete</a>
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    @empty
                                                                        <tr>
                                                                            <td colspan="6" class="text-center">No GRN QC Values
                                                                                Found</td>
                                                                        </tr>
                                                                    @endforelse
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function grnDeleteQcValue(id, row) {
        if (confirm("Are you sure you want to deactivate this QC Value?")) {
            $.ajax({
                url: '{{ url('/') }}/purchase/grnDeleteQcValue',
                type: 'POST',
                data: {
                    id: id,
                    _token: '{{ csrf_token() }}',
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    if (response.success) {
                        $('#row-' + row).remove();
                    } else {
                        alert(response.message);
                    }
                },
                error: function (xhr) {
                    alert('An error occurred: ' + xhr.responseText);
                }
            });
        }
    }

</script>

@endsection