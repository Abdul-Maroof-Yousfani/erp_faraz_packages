<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;
$approved = ReuseableCode::check_rights(8);

$m = $_GET['m'];
$counter = 1;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail', '', '1');?>
    </div>
</div>
<div class="row">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company')); ?>

                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <h3 style="text-align: center;">Production Tracking</h3>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td>Prod. Order No.</td>
                                <td class="text-center">{{ $order->pr_no }}</td>
                            </tr>
                            <tr>
                                <td>Prod. Order Date</td>
                                <td class="text-center">
                                    {{ CommonHelper::changeDateFormat($order->request_date) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td>Ref No.</td>
                                <td class="text-center">{{ $order->ref_no }}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td class="text-center">{{ $order->description }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed tableMargin">

                            {{-- ================= MIXING ================= --}}
                            <tr style="background:#f2f2f2;">
                                <th colspan="6">MIXING</th>
                            </tr>

                            <tr>
                                <th>Mixture No</th>
                                <th>Produced Item</th>
                                <th>Qty</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                            </tr>

                            @php $totalMix = 0; @endphp
                            @foreach($order->productionMixings as $mix)
                                @php $totalMix += $mix->qty; @endphp
                                <tr>
                                    <td>{{ $mix->pm_no }}</td>
                                    <td>{{ CommonHelper::get_item_name($mix->produced_item_id) }}</td>
                                    <td class="text-center">{{ number_format($mix->qty, 2) }}</td>
                                    <td class="text-center">{{ $mix->date }}</td>
                                    <td class="text-center">{{ $mix->status }}</td>
                                    <td>{{ $mix->description }}</td>
                                </tr>
                            @endforeach

                            <tr style="font-weight:bold;">
                                <td colspan="2" class="text-right">Total Mixing</td>
                                <td class="text-center">{{ number_format($totalMix, 2) }}</td>
                                <td colspan="3"></td>
                            </tr>


                            {{-- ================= ROLLING ================= --}}
                            <tr style="background:#f2f2f2;">
                                <th colspan="6">ROLLING</th>
                            </tr>

                            <tr>
                                <th>Item</th>
                                <th>Mixture Qty</th>
                                <th>NO. of Roll</th>
                                <th>Date</th>
                                <th>Machine</th>
                                <th>Operator</th>
                                {{-- <th>Shift</th> --}}
                            </tr>

                            @php $totalRoll = 0; @endphp
                            @foreach($order->productionRollings as $roll)
                                @php $totalRoll += $roll->roll_qty; @endphp
                                <tr>
                                    <td>{{ $roll->subItem->sub_ic ?? '' }}</td>
                                    <td class="text-center">{{ number_format($roll->mixture_qty, 2) }}</td>
                                    <td class="text-center">{{ number_format($roll->roll_qty, 2) }}</td>
                                    <td class="text-center">{{ $roll->date }}</td>
                                    <td>{{ $roll->machine->name }}</td>
                                    <td class="text-center">{{ $roll->operator->name }}</td>
                                    {{-- <td class="text-center">{{ $roll->shift->shift_type_name }}</td> --}}
                                </tr>
                            @endforeach

                            <tr style="font-weight:bold;">
                                <td colspan="2" class="text-right">Total Rolling</td>
                                <td class="text-center">{{ number_format($totalRoll, 2) }}</td>
                                <td colspan="3"></td>
                            </tr>


                            {{-- ================= PRINTING ================= --}}
                            <tr style="background:#f2f2f2;">
                                <th colspan="6">PRINTING</th>
                            </tr>

                            <tr>
                                <th>Roll ID</th>
                                <th>Printed Item</th>
                                <th>Printed Qty</th>
                                <th>Date</th>
                                <th>Machine</th>
                                <th>Operator</th>
                            </tr>

                            @php $totalPrint = 0; @endphp
                            @foreach($order->productionRollings as $roll)
                                @foreach($roll->printings as $print)
                                    @php $totalPrint += $print->no_of_roll; @endphp
                                    <tr>
                                        <td>{{ $roll->id }}</td>
                                        <td class="text-center">{{ CommonHelper::get_item_name($print->item_id) }}</td>

                                        <td class="text-center">{{ number_format($print->no_of_roll, 2) }}</td>
                                        <td class="text-center">{{ $print->date }}</td>
                                        <td>{{ $roll->machine->name }}</td>
                                        <td class="text-center">{{ $roll->operator->name }}</td>
                                    </tr>
                                @endforeach
                            @endforeach

                            <tr style="font-weight:bold;">
                                <td class="text-right">Total Printing</td>
                                <td class="text-right"></td>
                                <td class="text-center">{{ number_format($totalPrint, 2) }}</td>
                                <td colspan="4"></td>
                            </tr>


                            {{-- ================= CUTTING & PACKING ================= --}}
                            <tr style="background:#f2f2f2;">
                                <th colspan="6">CUTTING & PACKING</th>
                            </tr>

                            <tr>
                                <th>Item</th>
                                <th>Bags Qty</th>
                                <th>Date</th>
                                <th>Roll Used</th>
                                <th>Machine</th>
                                <th>Operator</th>
                            </tr>

                            @php $totalCut = 0; @endphp
                            @foreach($order->productionRollings as $roll)
                                @foreach($roll->printings as $print)
                                    @foreach($print->cuttingAndPackings as $cut)
                                        @php $totalCut += $cut->bags_qty; @endphp
                                        <tr>
                                            <td>{{ $cut->subItem->sub_ic ?? '' }}</td>
                                            <td class="text-center">{{ number_format($cut->bags_qty, 2) }}</td>
                                            <td class="text-center">{{ $cut->date }}</td>
                                            <td class="text-center">{{ number_format($cut->printed_roll_qty, 2) }}</td>
                                            <td>{{ $roll->machine->name }}</td>
                                            <td class="text-center">{{ $roll->operator->name }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            @endforeach

                            <tr style="font-weight:bold;">
                                <td class="text-right">Total Cutting</td>
                                <td class="text-center">{{ number_format($totalCut, 2) }}</td>
                                <td colspan="4"></td>
                            </tr>

                        </table>
                    </div>

                </div>
                <div style="line-height:8px;">&nbsp;</div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <style>
                        .signature_bor {
                            border-top: solid 1px #CCC;
                            padding-top: 7px;
                        }
                    </style>
                    {{-- <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                        <div class="container-fluid">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Prepared By: </h6>
                                    <b>
                                        <p>{{ strtoupper($order->username) }}</p>
                                    </b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Checked By:</h6>
                                    <b>
                                        <p></p>
                                    </b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Approved By:</h6>
                                    <b>
                                        <p>{{ strtoupper($order->approved_by) }}</p>
                                    </b>
                                </div>

                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function approveAndRejectProductionOrder(id, approval_status) {
            var csrf = '{{ csrf_token() }}'; // Get the CSRF token
            $.ajax({
                url: '{{ url('/') }}/far_prod/approveAndRejectProductionOrder',
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrf // Send CSRF token in the headers
                },
                data: { id: id, approval_status: approval_status }, // Send other data as payload
                success: function (data) {
                    $('#showDetailModelOneParamerter').modal('toggle'); // Toggle modal
                    viewProductionOrderListDetail(); // Refresh details
                }
            });
        }
    </script>