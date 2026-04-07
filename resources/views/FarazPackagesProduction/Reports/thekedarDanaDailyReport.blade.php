<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\Session;

$accType = Auth::user()->acc_type;
if ($accType == 'client') {
    $m = $_GET['m'] ?? ($m ?? '');
} else {
    $m = Auth::user()->company_id;
}
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
                                    <h2 class="subHeadingLabelClass">Thekedar / Dana Daily Report</h2>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('printThekedarDanaReport', '', '1'); ?>
                                <a id="dlink" style="display:none;"></a>
                                <button type="button" class="btn btn-warning" onclick="ExportToExcel('xlsx')">
                                    <i class="fa fa-external-link" aria-hidden="true"></i> Export
                                </button>
                            </div>
                        </div>

                        <div class="lineHeight">&nbsp;</div>

                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">From Date</label>
                                <input type="date" class="form-control" id="from_date" value="{{ $from }}">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <label class="sf-label">To Date</label>
                                <input type="date" class="form-control" id="to_date" value="{{ $to }}">
                            </div>
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12" style="margin-top: 24px;">
                                <button type="button" class="btn btn-success" onclick="loadReport()">Submit</button>
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

                        <div id="printThekedarDanaReport">
                            @foreach(($dates ?? []) as $date)
                                @php
                                    $packRows = $packingByDate[$date] ?? [];
                                    $dRows = $danaByDate[$date] ?? [];
                                    $printMachines = $printingByDateMachine[$date] ?? [];

                                    $dayPackBags = 0; $dayPackKgs = 0;
                                    foreach ($packRows as $r) { $dayPackBags += (float)$r->bags; $dayPackKgs += (float)$r->kgs; }

                                    $dayDanaBags = 0; $dayDanaKgs = 0;
                                    foreach ($dRows as $r) { $dayDanaBags += (float)$r->bags; $dayDanaKgs += (float)$r->kgs; }

                                    $dayPrintRolls = 0; $dayPrintKgs = 0;
                                    foreach ($printMachines as $mid => $rows) {
                                        foreach ($rows as $r) { $dayPrintRolls += (float)$r->rolls; $dayPrintKgs += (float)$r->kgs; }
                                    }
                                @endphp

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                                        <h3 style="font-weight: bold; margin: 0;">FARAZ PACKAGES</h3>
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>

                                {{-- Printing Operator (machine wise) --}}
                                @php
                                    $printingTotalRolls = 0;
                                    $printingTotalKgs = 0;
                                @endphp

                                @foreach(($printMachines ?? []) as $machineId => $rows)
                                    @php
                                        $machineRolls = 0; $machineKgs = 0;
                                        foreach ($rows as $r) { $machineRolls += (float)$r->rolls; $machineKgs += (float)$r->kgs; }
                                        $printingTotalRolls += $machineRolls;
                                        $printingTotalKgs += $machineKgs;
                                        $operatorName = $rows[0]->operator_name ?? 'Printing Operator';
                                    @endphp

                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list reportTable">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="7" class="text-center">
                                                            Printing Operator {{ $operatorName }} &nbsp;&nbsp; Date:
                                                            {{ CommonHelper::changeDateFormat($date) }}
                                                        </th>
                                                    </tr>
                                                    <tr>
                                                        <th class="text-center" style="width:60px;">S#</th>
                                                        <th class="text-center" style="width:110px;">Date</th>
                                                        <th class="text-center">NAME</th>
                                                        <th class="text-center" style="width:80px;">MAC#</th>
                                                        <th class="text-center">SIZE</th>
                                                        <th class="text-center" style="width:90px;">Roll</th>
                                                        <th class="text-center" style="width:110px;">KGS</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php $k = 1; @endphp
                                                    @foreach($rows as $r)
                                                        <tr>
                                                            <td class="text-center">{{ $k++ }}</td>
                                                            <td class="text-center">{{ date('n/j/Y', strtotime($date)) }}</td>
                                                            <td class="text-center">{{ $r->operator_name ?? '-' }}</td>
                                                            <td class="text-center">{{ $r->machine_id ?? '-' }}</td>
                                                            <td class="text-center">{{ $r->size_name ?? '-' }}</td>
                                                            <td class="text-right">{{ number_format((float)$r->rolls, 0) }}</td>
                                                            <td class="text-right">{{ number_format((float)$r->kgs, 3) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                    <tfoot>
                                                    <tr style="font-weight: bold;">
                                                        <td colspan="5" class="text-right"></td>
                                                        <td class="text-right">{{ number_format($machineRolls, 0) }}</td>
                                                        <td class="text-right">{{ number_format($machineKgs, 3) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="5" class="text-right">Previous Balance</td>
                                                        <td class="text-right">-</td>
                                                        <td class="text-right">-</td>
                                                    </tr>
                                                    <tr style="font-weight: bold;">
                                                        <td colspan="5" class="text-right">Total</td>
                                                        <td class="text-right">{{ number_format($machineRolls, 0) }}</td>
                                                        <td class="text-right">{{ number_format($machineKgs, 3) }}</td>
                                                    </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="lineHeight">&nbsp;</div>
                                @endforeach

                                @if(count($printMachines ?? []) > 0)
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="table table-bordered sf-table-list reportTable">
                                                <tr style="font-weight: bold;">
                                                    <td class="text-right" style="width: 80%;">Printing 1&2 Total</td>
                                                    <td class="text-right" style="width: 10%;">{{ number_format($printingTotalRolls, 0) }}</td>
                                                    <td class="text-right" style="width: 10%;">{{ number_format($printingTotalKgs, 3) }}</td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="lineHeight">&nbsp;</div>
                                @endif

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list reportTable">
                                                <thead>
                                                <tr>
                                                    <th colspan="7" class="text-center">
                                                        Thekedar Ejaz(Cutting) Iqbal(Packing) &nbsp;&nbsp; Date:
                                                        {{ CommonHelper::changeDateFormat($date) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" style="width:60px;">S#</th>
                                                    <th class="text-center" style="width:110px;">Date</th>
                                                    <th class="text-center">NAME</th>
                                                    <th class="text-center" style="width:80px;">MAC#</th>
                                                    <th class="text-center">SIZE</th>
                                                    <th class="text-center" style="width:90px;">BAGS</th>
                                                    <th class="text-center" style="width:110px;">KGS</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $i = 1; @endphp
                                                @forelse($packRows as $r)
                                                    <tr>
                                                        <td class="text-center">{{ $i++ }}</td>
                                                        <td class="text-center">{{ date('n/j/Y', strtotime($date)) }}</td>
                                                        <td class="text-center">{{ $r->operator_name ?? '-' }}</td>
                                                        <td class="text-center">{{ $r->machine_id ?? '-' }}</td>
                                                        <td class="text-center">{{ $r->size_name ?? '-' }}</td>
                                                        <td class="text-right">{{ number_format((float)$r->bags, 0) }}</td>
                                                        <td class="text-right">{{ number_format((float)$r->kgs, 3) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">No packing data for this date.</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                                <tfoot>
                                                <tr style="font-weight: bold;">
                                                    <td colspan="5" class="text-right"></td>
                                                    <td class="text-right">{{ number_format($dayPackBags, 0) }}</td>
                                                    <td class="text-right">{{ number_format($dayPackKgs, 3) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right">Previous Balance</td>
                                                    <td class="text-right">
                                                        {{ number_format(($packingPrev->bags ?? 0) + 0, 0) }}
                                                    </td>
                                                    <td class="text-right">
                                                        {{ number_format(($packingPrev->kgs ?? 0) + 0, 3) }}
                                                    </td>
                                                </tr>
                                                <tr style="font-weight: bold;">
                                                    <td colspan="5" class="text-right">Total</td>
                                                    <td class="text-right">{{ number_format(($packingPrev->bags ?? 0) + $dayPackBags, 0) }}</td>
                                                    <td class="text-right">{{ number_format(($packingPrev->kgs ?? 0) + $dayPackKgs, 3) }}</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>

                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list reportTable">
                                                <thead>
                                                <tr>
                                                    <th colspan="7" class="text-center">
                                                        Dana Operator Iftekhar &nbsp;&nbsp; Date:
                                                        {{ CommonHelper::changeDateFormat($date) }}
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th class="text-center" style="width:60px;">S#</th>
                                                    <th class="text-center" style="width:110px;">Date</th>
                                                    <th class="text-center">NAME</th>
                                                    <th class="text-center" style="width:80px;">MAC#</th>
                                                    <th class="text-center">Dana</th>
                                                    <th class="text-center" style="width:90px;">BAGS</th>
                                                    <th class="text-center" style="width:110px;">KGS</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @php $j = 1; @endphp
                                                @forelse($dRows as $r)
                                                    <tr>
                                                        <td class="text-center">{{ $j++ }}</td>
                                                        <td class="text-center">{{ date('n/j/Y', strtotime($date)) }}</td>
                                                        <td class="text-center">{{ $r->operator_name ?? '-' }}</td>
                                                        <td class="text-center">{{ $r->machine_id ?? '-' }}</td>
                                                        <td class="text-center">{{ $r->dana_name ?? '-' }}</td>
                                                        <td class="text-right">{{ number_format((float)$r->bags, 0) }}</td>
                                                        <td class="text-right">{{ number_format((float)$r->kgs, 3) }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="7" class="text-center">No dana data for this date.</td>
                                                    </tr>
                                                @endforelse
                                                </tbody>
                                                <tfoot>
                                                <tr style="font-weight: bold;">
                                                    <td colspan="5" class="text-right"></td>
                                                    <td class="text-right">{{ number_format($dayDanaBags, 0) }}</td>
                                                    <td class="text-right">{{ number_format($dayDanaKgs, 3) }}</td>
                                                </tr>
                                                <tr>
                                                    <td colspan="5" class="text-right">Previous Balance</td>
                                                    <td class="text-right">{{ number_format(($danaPrev->bags ?? 0) + 0, 0) }}</td>
                                                    <td class="text-right">{{ number_format(($danaPrev->kgs ?? 0) + 0, 3) }}</td>
                                                </tr>
                                                <tr style="font-weight: bold;">
                                                    <td colspan="5" class="text-right">Total</td>
                                                    <td class="text-right">{{ number_format(($danaPrev->bags ?? 0) + $dayDanaBags, 0) }}</td>
                                                    <td class="text-right">{{ number_format(($danaPrev->kgs ?? 0) + $dayDanaKgs, 3) }}</td>
                                                </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="lineHeight">&nbsp;</div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ URL::asset('assets/custom/js/exportToExcelXlsx.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
        });

        function loadReport() {
            var from = $('#from_date').val();
            var to = $('#to_date').val();
            var baseUrl = '<?php echo url('/') ?>';
            var m = '<?php echo $m ?>';
            window.location.href = baseUrl + '/far_production/thekedarDanaDailyReport?from_date=' + encodeURIComponent(from) + '&to_date=' + encodeURIComponent(to) + '&m=' + encodeURIComponent(m);
        }

        function ExportToExcel(type, fn, dl) {
            // Export all tables by temporarily wrapping them
            var wrapper = document.createElement('table');
            wrapper.id = 'TmpExportTable';
            wrapper.className = 'table';

            document.querySelectorAll('#printThekedarDanaReport table.reportTable').forEach(function(tbl) {
                var clone = tbl.cloneNode(true);
                var spacerRow = document.createElement('tr');
                var spacerCell = document.createElement('td');
                spacerCell.colSpan = 7;
                spacerCell.innerHTML = '&nbsp;';
                spacerRow.appendChild(spacerCell);
                var tbody = document.createElement('tbody');
                tbody.appendChild(spacerRow);
                wrapper.appendChild(clone.tHead.cloneNode(true));
                wrapper.appendChild(clone.tBodies[0].cloneNode(true));
                if (clone.tFoot) wrapper.appendChild(clone.tFoot.cloneNode(true));
                wrapper.appendChild(tbody);
            });

            var wb = XLSX.utils.table_to_book(wrapper, {sheet: "sheet1"});
            return dl ? XLSX.write(wb, {bookType: type, bookSST: true, type: 'base64'}) : XLSX.writeFile(wb, fn || ('Thekedar Dana Report <?php echo date('d-M-Y') ?>.' + (type || 'xlsx')));
        }
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>
@endsection

