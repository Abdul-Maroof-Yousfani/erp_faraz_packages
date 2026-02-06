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
        @if($production_request->approval_status == 1 && $production_request->status == 1)
                <button class="btn btn-success btn-xs btn-abc hidden-print" onclick="approveAndRejectProductionOrder('{{ $production_request->id }}',2)">Approve</button>
                <button class="btn btn-danger-2 btn-xs btn-abc hidden-print" onclick="approveAndRejectProductionOrder('{{ $production_request->id }}',3)">Reject</button>
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
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <?php echo CommonHelper::get_company_logo(Session::get('run_company')); ?>
                    
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <h3 style="text-align: center;">Production Order</h3>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"></div>
            </div>

            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table class="table table-bordered table-condensed tableMargin">
                        <tbody>
                            <tr>
                                <td>PR NO.</td>
                                <td class="text-center">{{ $production_request->pr_no }}</td>
                            </tr>
                            <tr>
                                <td>PR Date</td>
                                <td class="text-center">
                                    {{ CommonHelper::changeDateFormat($production_request->request_date) }}
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
                                <td class="text-center">{{ $production_request->ref_no }}</td>
                            </tr>
                            <tr>
                                <td>Description</td>
                                <td class="text-center">{{ $production_request->description }}</td>
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
                                    <th class="text-center">Item</th>
                                    <th class="text-center">Color</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Reason</th>
                                    <th class="text-center">Required Date</th>
                                    {{-- <th class="text-center">View Recipe</th> --}}
                                </tr>
                            </thead>

                            @foreach($production_request_data as $key => $val)
                                <tr>
                                    <td class="text-center">{{ $counter++ }}</td>
                                    <td class="text-center">
                                        {{ CommonHelper::get_item_by_id($val->item_id)->item_code . ' -- ' . CommonHelper::get_item_by_id($val->item_id)->sub_ic }}
                                    </td>
                                    <td class="text-center">{{ $val->color }}</td>
                                    <td class="text-center">{{ number_format($val->quantity, 2) }}</td>
                                    <td class="text-center">{{ $val->purpose }}</td>
                                    <td class="text-center">{{ CommonHelper::changeDateFormat($val->required_date) }}</td>
                                    {{-- <td class="text-center">
                                        <a style="cursor: pointer" onclick="showDetailModelOneParamerter('recipe/viewRecipeInfo?m={{ $m }}','{{ $val->item_id }}','View Product Formulation')" type="button" class="dropdown-item_sale_order_list dropdown-item "> View</a>
                                    </td> --}}
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
                                    <h6 class="signature_bor">Prepared By: </h6>
                                    <b>
                                        <p>{{ strtoupper($production_request->username) }}</p>
                                    </b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Checked By:</h6>
                                    <b>
                                        <p><?php  ?></p>
                                    </b>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 text-center">
                                    <h6 class="signature_bor">Approved By:</h6>
                                    <b>
                                        <p>{{ strtoupper($production_request->approved_by) }}</p>
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