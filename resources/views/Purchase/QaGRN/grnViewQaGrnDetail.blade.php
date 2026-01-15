<?php
use App\Helpers\CommonHelper;
$counter = 1;
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printQcGrnDetail', '', '1');?>
    </div>
</div>
<div class="row">&nbsp;</div>
<div class="row" id="printQcGrnDetail">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company'))?>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <h3>QC GRN Tests</h3>
                </div>
            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td>GRN No.</td>
                                <td class="text-center">{{ $qc_grn->grn_no ?? '' }}</td>
                            </tr>
                            <tr>
                                <td>GRN Date</td>
                                <td class="text-center">
                                    {{ CommonHelper::changeDateFormat($qc_grn->grn_date ?? '') }}
                                </td>
                            </tr>
                            <tr>
                                <td>PO No.</td>
                                <td class="text-center">
                                    {{ $qc_grn->po_no ?? '' }}
                                </td>
                            </tr>
                            <tr>
                                <td>Supplier</td>
                                <td class="text-center">
                                    {{ $qc_grn->supplier_name ?? '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td>QC Date</td>
                                <td class="text-center">
                                    {{ CommonHelper::changeDateFormat($qc_grn->qc_grn_date ?? '') }}
                                </td>
                            </tr>
                            <tr>
                                <td>QC By</td>
                                <td class="text-center">
                                    {{ $qc_grn->qc_by ?? '' }}
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
                                    <th class="text-center">Standard Value</th>
                                    <th class="text-center">Test Value</th>
                                    <th class="text-center">Test Status</th>
                                    <th class="text-center">Test Type</th>
                                    <th class="text-center">Remarks</th>
                                </tr>
                            </thead>
                            @foreach($qc_grn_data as $key => $val)
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">{{ $val->test_name ?? $val->name ?? '' }}</td>
                                    <td class="text-center">{{ $val->standard_value ?? '' }}</td>
                                    <td class="text-center">{{ $val->test_value ?? '' }}</td>
                                    <td class="text-center">{{ $val->test_status ?? '' }}</td>
                                    <td class="text-center">{{ $val->test_type ?? '' }}</td>
                                    <td class="text-center">{{ $val->remarks ?? '' }}</td>
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
                                        <p>{{ strtoupper($qc_grn->qc_by ?? '') }}</p>
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
</div>
