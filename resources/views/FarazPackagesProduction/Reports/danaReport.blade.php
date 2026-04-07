<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Session;

$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'] ?? ($m ?? '');
} else {
    $m = Auth::user()->company_id;
}

$dateValue = $date ?? date('Y-m-d');
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
                                    <h2 class="subHeadingLabelClass">Dana Report</h2>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('printDanaReport', '', '1'); ?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">
                                    <i class="fa fa-external-link" aria-hidden="true"></i> Export
                                </button>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">Date</label>
                                <input type="date" class="form-control" id="date" name="date" value="{{ $dateValue }}">
                            </div>
                            <div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
                                <label class="sf-label">Raw Item</label>
                                <select class="form-control select2" id="raw_item_id" name="raw_item_id">
                                    <option value="0">All Raw Items</option>
                                    @foreach(($rawItems ?? []) as $ri)
                                        <option value="{{ $ri->id }}" @if(($rawItemId ?? 0) == $ri->id) selected @endif>
                                            {{ ($ri->item_code ? $ri->item_code . ' - ' : '') . $ri->sub_ic }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 24px;">
                                <button type="button" class="btn btn-success" onclick="loadDanaReport()">Submit</button>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>

                        @if (Session::has('dataInsert'))
                            <div class="alert alert-success alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                {{ Session::get('dataInsert') }}
                            </div>
                        @endif
                        @if (Session::has('dataEdit'))
                            <div class="alert alert-danger alert-dismissible" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                                        aria-hidden="true">&times;</span></button>
                                {{ Session::get('dataEdit') }}
                            </div>
                        @endif

                        <div id="printDanaReport">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                    <h3 style="font-weight: bold;">FARAZ PACKAGES</h3>
                                    <h4 style="font-weight: bold; text-decoration: underline;">Dana Report</h4>
                                    <div style="margin-top: 6px;">
                                        <strong>Date:</strong> {{ \App\Helpers\CommonHelper::changeDateFormat($dateValue) }}
                                    </div>
                                </div>
                            </div>

                            <div class="lineHeight">&nbsp;</div>

                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="userlittab table table-bordered sf-table-list" id="DanaReportTable">
                                            <thead>
                                            <tr>
                                                <th class="text-center" style="width: 60px;">S#.</th>
                                                <th class="text-center">Discription</th>
                                                <th class="text-center" style="width: 120px;">Opening</th>
                                                <th class="text-center" style="width: 120px;">Purchase</th>
                                                <th class="text-center" style="width: 120px;">Closing</th>
                                                <th class="text-center" style="width: 120px;">Consume</th>
                                                <th class="text-center" style="width: 120px;">Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php
                                                $i = 1;
                                            @endphp
                                            @forelse($items as $row)
                                                <tr>
                                                    <td class="text-center">{{ $i++ }}</td>
                                                    <td>{{ $row->description }}</td>
                                                    <td class="text-right">{{ number_format($row->opening, 2) }}</td>
                                                    <td class="text-right">{{ number_format($row->purchase, 2) }}</td>
                                                    <td class="text-right">{{ number_format($row->closing, 2) }}</td>
                                                    <td class="text-right">{{ number_format($row->consume, 2) }}</td>
                                                    <td class="text-right">{{ number_format($row->balance, 2) }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">No data found.</td>
                                                </tr>
                                            @endforelse
                                            </tbody>
                                            <tfoot>
                                            <tr style="background: #f5f5f5; font-weight: bold;">
                                                <td colspan="2" class="text-right">Total</td>
                                                <td class="text-right">{{ number_format($totals['opening'] ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($totals['purchase'] ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($totals['closing'] ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($totals['consume'] ?? 0, 2) }}</td>
                                                <td class="text-right">{{ number_format($totals['closing'] ?? 0, 2) }}</td>
                                            </tr>
                                            </tfoot>
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

    <script>
        function loadDanaReport() {
            var date = $('#date').val();
            var rawItemId = $('#raw_item_id').val();
            var baseUrl = '<?php echo url('/') ?>';
            var m = '<?php echo $m ?>';
            window.location.href = baseUrl + '/far_production/danaReport?date=' + encodeURIComponent(date) + '&raw_item_id=' + encodeURIComponent(rawItemId) + '&m=' + encodeURIComponent(m);
        }
    </script>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function ExportToExcel(type, fn, dl) {
            var elt = document.getElementById('DanaReportTable');
            var wb = XLSX.utils.table_to_book(elt, {sheet: "sheet1"});
            return dl ? XLSX.write(wb, {bookType: type, bookSST: true, type: 'base64'}) : XLSX.writeFile(wb, fn || ('Dana Report <?php echo date('d-M-Y') ?>.' + (type || 'xlsx')));
        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection

