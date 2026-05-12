<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
use Illuminate\Support\Facades\Session;

$export = ReuseableCode::check_rights(258);
$this->m = Session::get('run_company');
?>
@extends('layouts.default')
@section('content')
@include('select2')

<div class="panel-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">Production Wastage List</h2>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <div class="headquid">
                                <a href="{{ url('far_production/addProductionWastage?pageType='.request('pageType').'&&parentCode='.request('parentCode').'&&m='.request('m', $m)) }}" class="btn btn-primary">
                                    Add Wastage
                                </a>
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintProductionWastageList', '', '1'); ?>
                                <?php if ($export == true): ?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">
                                    <i class="fa fa-external-link" aria-hidden="true"></i> Export
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    @if(Session::has('dataInsert'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ Session::get('dataInsert') }}
                        </div>
                    @endif

                    <div class="lineHeight">&nbsp;</div>
                    <div class="panel">
                        <div class="panel-body" id="PrintProductionWastageList">
                            <?php echo CommonHelper::headerPrintSectionInPrintView($m); ?>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="userlittab table table-bordered sf-table-list" id="ProductionWastageList">
                                            <thead>
                                                <th class="text-center">S.No</th>
                                                <th class="text-center">Production Order</th>
                                                <th class="text-center">Process</th>
                                                <th class="text-center">Items</th>
                                                <th class="text-center">Total Qty (KG)</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center hidden-print">Action</th>
                                            </thead>
                                            <tbody>
                                                <?php $count = 0; ?>
                                                @foreach($wastageList as $row)
                                                    <tr id="wastageRow{{ $row->id }}">
                                                        <td class="text-center">{{ ++$count }}</td>
                                                        <td class="text-center">{{ $row->pr_no ?? '-' }}</td>
                                                        <td>{{ $processes[$row->process] ?? ucwords(str_replace('_', ' ', $row->process)) }}</td>
                                                        <td class="text-center">{{ $row->detail_count }}</td>
                                                        <td class="text-right">{{ number_format($row->detail_qty ?? $row->qty, 2) }}</td>
                                                        <td class="text-center">{{ $row->wastage_date }}</td>
                                                        <td class="text-center hidden-print">
                                                            <div class="dropdown">
                                                                <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                                    <i class="fa-solid fa-ellipsis-vertical"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a onclick="showDetailModelOneParamerter('far_production/viewProductionWastageDetail?m={{ $m }}','{{ $row->id }}','View Production Wastage')" type="button" class="dropdown-item_sale_order_list dropdown-item">
                                                                            <i class="fa-regular fa-eye"></i> View
                                                                        </a>
                                                                        <a href="{{ url('far_production/editProductionWastageForm/'.$row->id.'?pageType='.request('pageType').'&&parentCode='.request('parentCode').'&&m='.request('m', $m)) }}" type="button" class="dropdown-item_sale_order_list dropdown-item">
                                                                            <i class="fa-solid fa-pencil"></i> Edit
                                                                        </a>
                                                                        <a onclick="deleteProductionWastage('{{ $row->id }}')" type="button" class="dropdown-item_sale_order_list dropdown-item">
                                                                            <i class="fa-solid fa-trash"></i> Delete
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
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

<script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
<script>
    function ExportToExcel(type, fn, dl) {
        var elt = document.getElementById('ProductionWastageList');
        var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
        return dl ? XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }) :
            XLSX.writeFile(wb, fn || ('Production Wastage <?php echo date('d-M-Y') ?>.' + (type || 'xlsx')));
    }
</script>
<script>
    function deleteProductionWastage(id) {
        if (confirm('Are you sure you want to delete this wastage record?')) {
            $.ajax({
                url: '{{ url('/') }}/far_prod/deleteProductionWastage',
                type: 'GET',
                data: { id: id },
                success: function (response) {
                    if (response == 'true') {
                        $('#wastageRow' + id).fadeOut();
                    }
                }
            });
        }
    }
</script>
@endsection
