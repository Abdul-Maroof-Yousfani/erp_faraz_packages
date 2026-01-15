<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

// $approved=ReuseableCode::check_rights(212);

// $m = $_GET['m'];
// $Checking = $_GET['id'];
// $Checking = explode(',',$Checking);
// if($Checking[0] == 'other')
// {
//     FinanceHelper::companyDatabaseConnection($m);
//     $jvs = DB::table('new_purchase_voucher')->where('pv_no','=',$Checking[1])->first();
//     FinanceHelper::reconnectMasterDatabase();
//     $id = $jvs->id;
// }
// else{
//     $id = $Checking[0];
// }
// $currentDate = date('Y-m-d');
// FinanceHelper::companyDatabaseConnection($m);
// $PurchaseVoucher = DB::table('new_purchase_voucher')->where('id','=',$id)->get();
// FinanceHelper::reconnectMasterDatabase();
// foreach ($PurchaseVoucher as $row) {
// $username=$row->username;
// $approve_1=$row->approved_user;
// $approve_2=$row->approve_user_2;
// $exp_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',1)->sum('net_amount');
// $item_amount= DB::Connection('mysql2')->table('new_purchase_voucher_data')->where('master_id','=',$id)->where('additional_exp',0)->sum('net_amount');

// $Supplier = CommonHelper::get_single_row('supplier','id',$row->supplier);

?>
<div class="row headquid">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">

        <button class="btn btn-sm btn-primary" onclick="printViewTwo('printPurchaseVoucherDetail','','1')" style="">
            <span class="glyphicon glyphicon-print"> Print</span>
        </button>

    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well" id="printPurchaseVoucherDetail">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">REFUND SECURITY DEPOSIT/ PORT CHARGES</h3>
                        </div>
                    </div>
                    <div style="line-height:5px;">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div style="width:45%; float:left;">
                                <table class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>

                                        <tr>
                                            <td style="width:40%;">File Reference No</td>
                                            <td style="width:60%;">{{ $shipping_detail->refrence_no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">PO Number</td>
                                            <td style="width:60%;">{{ $shipping_detail->purchase_request_no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">LC #/ Contract Number</td>
                                            <td style="width:60%;">{{ $shipping_detail->lc_no }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Supplier</td>
                                            <td style="width:60%;">
                                                {{ CommonHelper::get_supplier_name($shipping_detail->beneficiary_id) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Description</td>
                                            <td style="width:60%;"> {{ $shipping_detail->description }} </td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">BL number</td>
                                            <td style="width:60%;"> {{ $shipping_detail->bl_no }} </td>
                                        </tr>
                                        @php
                                            $containers = explode(",", $shipping_detail->container);
                                            $counter  = 1;
                                        @endphp
                                        @foreach ($containers as $container)
                                            <tr>
                                                <td style="width:40%;">Container # {{$counter++}}</td>
                                                <td style="width:60%;"> {{ $container }} </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>

                            <div style="width:45%; float:right;">
                                <table  class="table table-bordered table-striped table-condensed tableMargin">
                                    <tbody>
                                        <tr>
                                            <td>Lot #</td>
                                            <td>{{$shipping_detail->lot_no}}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">C. Agent :</td>
                                            <td style="width:60%;">{{CommonHelper::getAgentName($shipping_detail->clearing_agent_no)}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div style="width:100%; float:left;">
                                <table class="table table-bordered table-striped table-condensed tableMargin">
                                    <thead>
                                        <tr>
                                            <th style="width:40%;"></th>
                                            <th style="width:30%;">Security Deposit</th>
                                            <th style="width:30%;">Port Charges</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td style="width:40%;">Total Amount</td>
                                            <td style="width:30%;">{{ $shipping_detail->amount }}</td>
                                            <td style="width:30%;">{{ $shipping_detail->port_charges }}</td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Refund Amount</td>
                                            <td style="width:30%;">{{ $shipping_detail->refund_amount }}</td>
                                            <td style="width:30%;"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Deduction</td>
                                            <td style="width:30%;">{{ $shipping_detail->deduction }}</td>
                                            <td style="width:30%;"></td>
                                        </tr>
                                        <tr>
                                            <td style="width:40%;">Refund Chq / PO #</td>
                                            <td style="width:30%;">{{$shipping_detail->cheque_no}} <br> Dt : {{$shipping_detail->cheque_date}} <br> Remarks : {{$shipping_detail->remarks}}</td>
                                            <td style="width:30%;"></td>
                                        </tr>
                                        
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div style="line-height:8px;">&nbsp;</div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Import Department: </h6>
                                            <b>   <p><?php echo strtoupper($shipping_detail->username);  ?></p></b>
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center"> 
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center"> 
                                        </div>
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-center">
                                            <h6 class="signature_bor">Recieved By</h6>

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
