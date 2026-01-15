<?php
use App\Helpers\CommonHelper;
use App\Helpers\ProductionHelper;
use App\Helpers\ReuseableCode;
$approved = ReuseableCode::check_rights(8);
$counter = 1;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if($qc_test->approval_status == 1 && $qc_test->status == 1)
            @if($approved == true)
                <button class="btn btn-success btn-xs btn-abc hidden-print"
                    onclick="approveAndRejectProductionRequest('{{ $qc_test->id }}',2)">Approve</button>
                <button class="btn btn-danger-2 btn-xs btn-abc hidden-print"
                    onclick="approveAndRejectProductionRequest('{{ $qc_test->id }}',3)">Reject</button>
            @endif
        @endif
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail', '', '1');?>
    </div>
</div>
<div class="row">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'))?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <h3>Production QC Tests</h3>
                </div>
            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td>Finish Good.</td>
                                <td class="text-center">{{ $qc_test->finish_good_id }}</td>
                            </tr>
                            <tr>
                                <td>Production Date</td>
                                <td class="text-center">
                                    {{ CommonHelper::changeDateFormat($qc_test->order_date) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Batch No</td>
                                <td class="text-center">
                                
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td>PP NO.</td>
                                <td class="text-center">{{ $qc_test->order_no }}</td>
                            </tr>
                            <tr>
                                <td>Inspection Date</td>
                                <td class="text-center">
                                    {{ CommonHelper::changeDateFormat($qc_test->qc_packing_date) }}
                                </td>
                            </tr>
                            <tr>
                                <td>Quantity Checked</td>
                                <td class="text-center">
                                    {{ $qc_test->qty_checked ?? 0 }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-condensed tableMargin">
                            <thead>
                                <tr>
                                    <th class="text-center">S.No</th>
                                    <th class="text-center">Test</th>
                                    <th class="text-center">ZCI Standard Value</th>
                                    <th class="text-center">Test Value</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            @foreach($qc_test_data as $key => $val)
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $val->name }}</td>
                                    <td class="text-center">{{ $val->standard_value }}</td>
                                    <td class="text-center">{{ $val->test_value }}</td>
                                    <td class="text-center">{{ $val->test_status }}</td>
                                </tr>
                            @endforeach
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
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="margin-top:40px;">
                        <div class="container-fluid">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Tested By: </h6>
                                    <b>
                                        <p>{{ strtoupper($qc_test->qc_by) }}</p>
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
                                        <p></p>
                                    </b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        function approveAndRejectProductionRequest(id, approval_status) {
            var csrf = '{{ csrf_token() }}'; // Get the CSRF token
            $.ajax({
                url: '{{ url('/') }}/prad/approveAndRejectProductionRequest',
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': csrf // Send CSRF token in the headers
                },
                data: { id: id, approval_status: approval_status }, // Send other data as payload
                success: function (data) {
                    $('#showDetailModelOneParamerter').modal('toggle'); // Toggle modal
                    viewProductionRequestListDetail(); // Refresh details
                }
            });
        }
    </script>