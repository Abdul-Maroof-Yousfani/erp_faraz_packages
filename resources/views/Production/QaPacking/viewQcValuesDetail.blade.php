<div?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$counter = 1;
?>

<div class="row" id="printDemandVoucherVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <h3 style="text-align: center;">QC Values</h3>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td class="text-center">Item</td>
                                <td class="text-center">
                                    {{ $qcValues[0]->item_code . ' -- ' . $qcValues[0]->item_name }}</td>

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
                                    <th class="text-center">Test Name</th>
                                    <th class="text-center">Standard Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($qcValues as $index => $value)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-center">{{ $value->test_name }}</td>
                                        <td class="text-center">{{ $value->standard_value }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">No QC
                                            Details Found</td>
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