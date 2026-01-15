<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$supplier_id = $_GET['Supplier'];

?>
{{--3 = supplier | 0 = expense--}}
@if($_GET['paid_to_type'] == 2)
    @if($_GET['payment_for'] != null && $_GET['Supplier'] != null)
        @if($_GET['payment_for'] == 0)
            <div class="row">
                <div class="col-md-12">
                    <span class="subHeadingLabelClass">Invoice Detail</span>
                </div>
                    <?php  $NewPurchaseVoucher = CommonHelper::NewPurchaseVoucherBySupplierId($supplier_id);
                    $counter = 1;
                    $RemainingAmount = 0;

                    $total_remaining_amount=0;
                    $total_paid_amount=0;
                    ?>

                @if(count($NewPurchaseVoucher) != 0)
                    <div class="col-md-12 " >
                        <div class="row" id="data">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                        <input type="hidden" name="purchase_voucher_type" value="1" title="pi">

                                           
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <h5 style="text-align: center" id="h3"></h5>

                                                    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
                                                        <thead>
                                                        <th class="text-center"></th>
                                                        <th class="text-center">P.V. No.</th>
                                                        <th class="text-center">P.V. Date</th>
                                                        <th class="text-center">Bill Date.</th>
                                                        <th class="text-center">Slip No</th>
                                                        <th class="text-center">PO No</th>
                                                        <th class="text-center">Purchased Amount</th>
                                                        <th class="text-center">Return Amount</th>
                                                        <th class="text-center">Paid Amount</th>
                                                        <th class="text-center">Remaining</th>

                                                        </thead>
                                                        <tbody id="filterBankPaymentVoucherList">
                                                            <?php



                                                        foreach ($NewPurchaseVoucher as $row1) {
                                                            $PurchaseAmount = CommonHelper::PurchaseAmountCheck($row1->id);
                                                            $PaymentAmount = CommonHelper::PaymentPurchaseAmountCheck($row1->id);


                                                            $return_amount=  DB::Connection('mysql2')->table('purchase_return as a')
                                                                ->join('purchase_return_data as b','a.id','b.master_id')
                                                                ->where('a.status',1)
                                                                ->where('a.type',2)
                                                                ->where('grn_no',$row1->grn_no)
                                                                ->sum('b.net_amount');


                                                            $po_no='';
                                                            if ($row1->purchase_type==1):
                                                                $po_no=$row1->description;
                                                                $po_no=explode('||',$po_no);
                                                                $po_no=$po_no[1];
                                                            endif;

                                                            if($PaymentAmount != "")
                                                            {
                                                                $RemainingAmount = $PurchaseAmount-$PaymentAmount-$return_amount;
                                                            } else
                                                            {
                                                                $RemainingAmount = $PurchaseAmount-$return_amount;
                                                                $PaymentAmount = 0;
                                                            }

                                                            ?>
                                                            <?php if($RemainingAmount>0){ ?>

                                                        <tr id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
                                                            <td class="text-center">
                                                                    <?php if($RemainingAmount>0){ ?>
                                                                <input name="checkbox[]" class="checkbox1 requiredField" id="1chk<?php echo $counter?>" type="checkbox" value="<?php echo $row1->id ?>" onclick="pvAcountHeadPoPiChunk()" />
                                                                <?php } else {
                                                                    echo '<span class="label label-default">Clear</span>';
                                                                } ?>
                                                            </td>
                                                            <td class="text-center hidden-print">


                                                                <input readonly="" type="hidden" class="form-control requiredField" name="pv_no{{$row1->id}}" id="pv_no" value="{{strtoupper($row1->pv_no)}}">
                                                                <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row1->id;?>','View Bank P.V Detail','<?php echo $_GET['m']?>')" class="btn btn-xs btn-success"><?php echo strtoupper($row1->pv_no);?></a>
                                                            </td>
                                                            <td class="text-left">

                                                                <input readonly="" type="date" class="form-control requiredField" name="purchase_date{{$row1->id}}" id="demand_date_1" value="{{$row1->pv_date}}">
                                                                    <?php // echo FinanceHelper::changeDateFormat($row1->pv_date); ?></td>
                                                            <td class="text-left">

                                                                <input readonly="" type="date" class="form-control requiredField" name="bill_date{{$row1->id}}" id="bill_date" value="{{ $row1->bill_date}}">


                                                                    <?php // echo FinanceHelper::changeDateFormat($row1->bill_date); ?>

                                                            </td>
                                                            <td class="text-left">
                                                                <input readonly="" type="text" class="form-control requiredField" name="slip_no{{$row1->id}}" id="slip_no_1" value="{{ $row1->slip_no}}">
                                                            </td>
                                                            <td class="text-left"><?php echo $po_no; ?></td>
                                                            <td class="text-left">


                                                                <input readonly="" type="text" class="form-control requiredField" name="amount{{$row1->id}}" id="demand_date_1" value="{{number_format($PurchaseAmount,2)}}">

                                                                    <?php // echo number_format($PurchaseAmount,2); ?></td>
                                                            <td class="text-left"><?php echo number_format($return_amount,2); ?></td>
                                                            <td class="text-left"><?php echo number_format($PaymentAmount,2); ?></td>
                                                            <td class="text-left"><?php echo number_format($RemainingAmount,2); ?></td>
                                                                <?php

                                                                $total_remaining_amount+=$RemainingAmount;
                                                                $total_paid_amount+=$PaymentAmount;
                                                                ?>
                                                            {{--<td class="text-center">< ?php echo CommonHelper::get_supplier_name($row1->supplier); ?></td>--}}
                                                            {{--<td class="text-center">< ?php echo $row1->description; ?></td>--}}
                                                            {{--<td class="text-center">< ?php if($row1->pv_status == 2){echo "Approved";} else{echo "Pending";}?></td>--}}

                                                        </tr>


                                                            <?php
                                                        }
                                                        }
                                                            ?>
                                                        <tr class="text-center" style="font-size: large;font-weight: bold">
                                                            <td colspan="8">Total</td>
                                                            <td colspan="1">{{number_format($total_paid_amount,2)}}</td>
                                                            <td colspan="1">{{number_format($total_remaining_amount,2)}}</td>
                                                            <td></td>
                                                        </tr>

                                                            <?php


                                                            $data=CommonHelper::get_advancee_from_outstanding($supplier_id);

                                                        foreach($data as $row):


                                                            $diffrence=CommonHelper::get_debit_credit_from_outstanding($supplier_id,$row->new_pv_no);

                                                        if ($diffrence<0):?>


                                                        <tr style="background-color: darkgrey" id="" title="" id="">
                                                            <td class="text-center">

                                                            </td>
                                                            <td class="text-center"><?php echo $counter++;?></td>
                                                            <td class="text-center"><?php echo $row->new_pv_no ?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                            <td class="text-center"><?php echo  $diffrence; ?></td>
                                                            <td  class="text-center"><b style="font-size: larger;font-weight: bolder"><?php echo $diffrence; ?></b></td>

                                                            <td class="text-center"><?php echo 'Advance'?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                        </tr>
                                                        <?php endif;endforeach ?>
                                                            <?php
                                                            ?>

                                                        <tr>
                                                            <th colspan="10" class="text-center">xxxxx</th>
                                                        </tr>
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
                @else
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                            <strong class="text-uppercase">All Clear! No Purchase invoice found</strong>
                        </div>
                    </div>
                @endif
            </div>
            <script>
                $(document).ready(function(){

                });

                function checking()
                {
                    $('.checkbox1').each(function()
                    {
                        if ($(this).is(':checked'))
                        {
                            $('#BtnPayment').prop('disabled',false);
                        }
                        else
                        {
                            $('#BtnPayment').prop('disabled',false);
                        }
                    });
                }

            </script>
        @else
            {{--        <div class="alert alert-warning" role="alert">--}}
            {{--            PO Work are in-progress below ||  (Choose Payment For <strong>Invoice</strong> for now)--}}
            {{--        </div>--}}
            <div class="row">
                <div class="col-md-12">
                    <span class="subHeadingLabelClass">Invoice Detail</span>
                </div>


                <input type="hidden" name="purchase_voucher_type" value="2" title="po">
                    <?php  $NewPurchaseVoucher = CommonHelper::PurchaseOrdersBySupplierId($supplier_id);
                    $counter = 1;
                    $RemainingAmount = 0;

                    $total_remaining_amount=0;
                    $total_paid_amount=0;
                    ?>

                @if(count($NewPurchaseVoucher) != 0)
                    <div class="col-md-12 " >
                        <div class="row" id="data">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="panel">
                                    <div class="panel-body">
                                            <?php 
                                            //echo CommonHelper::headerPrintSectionInPrintView($m); 
                                            ?>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <h5 style="text-align: center" id="h3"></h5>

                                                    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
                                                        <thead>
                                                        <th class="text-center"></th>
                                                        <th class="text-center">P.V. No.</th>
                                                        <th class="text-center">P.V. Date</th>
                                                        <th class="text-center">Bill Date.</th>
                                                        <th class="text-center">Slip No</th>
                                                        <th class="text-center">PO No</th>
                                                        <th class="text-center">Purchased Amount</th>
                                                        <th class="text-center">Tax Amount</th>
                                                        <th class="text-center">Total Amount</th>
                                                        <th class="text-center">Paid Amount</th>
                                                        <th class="text-center">Remaining</th>
                                                        </thead>
                                                        <tbody id="filterBankPaymentVoucherList">
                                                            <?php


                                                        foreach ($NewPurchaseVoucher as $row1) {
                                                            $PurchaseAmount= ReuseableCode::get_po_total_amount($row1->id);

                                                            // echo $row1->id;
                                                            // exit();
//                                                        dd($PurchaseAmount);
//                                                        $PurchaseAmount = CommonHelper::PurchaseAmountCheck($row1->id);
                                                            $PaymentAmount = CommonHelper::PaymentPurchaseAmountCheck($row1->id,2);

                                                            $sales_tax_amount = DB::connection('mysql2')->table('purchase_request')->whereIn('id', [$row1->id])->sum('sales_tax_amount');
//                                                        $return_amount=  DB::Connection('mysql2')->table('purchase_return as a')
//                                                            ->join('purchase_return_data as b','a.id','b.master_id')
//                                                            ->where('a.status',1)
//                                                            ->where('a.type',2)
//                                                            ->where('grn_no',$row1->grn_no)
//                                                            ->sum('b.net_amount');


                                                            $po_no='';
                                                            if ($row1->purchase_type==1):
                                                                $po_no=$row1->description;
                                                                $po_no=explode('||',$po_no);
                                                                $po_no=$po_no[1];
                                                            endif;

                                                            
                                                            if($PaymentAmount != "")
                                                            {
                                                                $RemainingAmount = ($PurchaseAmount + $sales_tax_amount ) - $PaymentAmount ;
                                                            } else
                                                            {
                                                                $RemainingAmount = $PurchaseAmount  + $sales_tax_amount ;
                                                                $PaymentAmount = 0;
                                                            }

                                                           

                                                            ?>

                                                        <tr id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->pv_status == 1):?>  onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
                                                            <td class="text-center">
                                                                    <?php if($RemainingAmount>0){ ?>
                                                                <input name="checkbox[]" class="checkbox1 requiredField" id="1chk<?php echo $counter?>" type="checkbox" value="<?php echo $row1->id ?>" onclick="pvAcountHeadPoPiChunk()" />
                                                                <?php } else {
                                                                    echo '<span class="label label-default">Clear</span>';
                                                                } ?>
                                                            </td>
                                                            <td class="text-center hidden-print">


                                                                <input readonly="" type="hidden" class="form-control requiredField" name="pv_no{{$row1->id}}" id="pv_no" value="{{strtoupper($row1->purchase_request_no)}}">
                                                                <a onclick="showDetailModelOneParamerter('fdc/viewPurchaseVoucherDetail','<?php echo $row1->id;?>','View Bank P.V Detail','<?php echo $_GET['m']?>')" class="btn btn-xs btn-success"><?php echo strtoupper($row1->purchase_request_no);?></a>
                                                            </td>
                                                            <td class="text-left">

                                                                <input readonly="" type="date" class="form-control requiredField" name="purchase_date{{$row1->id}}" id="demand_date_1" value="{{$row1->purchase_request_date}}">
                                                                    <?php // echo FinanceHelper::changeDateFormat($row1->pv_date); ?></td>
                                                            <td class="text-left">

                                                                <input readonly="" type="date" class="form-control requiredField" name="bill_date{{$row1->id}}" id="bill_date" value="{{ $row1->bill_date}}">


                                                                    <?php // echo FinanceHelper::changeDateFormat($row1->bill_date); ?>

                                                            </td>
                                                            <td class="text-left">
                                                                <input readonly="" type="text" class="form-control requiredField" name="slip_no{{$row1->id}}" id="slip_no_1" value="{{ $row1->slip_no}}">
                                                            </td>
                                                            <td class="text-left"><?php echo $po_no; ?></td>
                                                            <td class="text-left">
                                                                
                                                                
                                                                <input readonly="" type="text" class="form-control requiredField" name="amount{{$row1->id}}" id="demand_date_1" value="{{number_format($PurchaseAmount,2)}}">
                                                                
                                                                <?php // echo number_format($PurchaseAmount,2); ?></td>
                                                            <td class="text-left"> {{ $sales_tax_amount }}</td>
                                                            <td class="text-left"> {{ $sales_tax_amount + $PurchaseAmount }}</td>
                                                            <td class="text-left"><?php echo number_format($PaymentAmount,2); ?></td>
                                                            <td class="text-left"><?php echo number_format($RemainingAmount,2); ?></td>
                                                                <?php

                                                                $total_remaining_amount+=$RemainingAmount;
                                                                $total_paid_amount+=$PaymentAmount;
                                                                ?>
                                                            {{--<td class="text-center">< ?php echo CommonHelper::get_supplier_name($row1->supplier); ?></td>--}}
                                                            {{--<td class="text-center">< ?php echo $row1->description; ?></td>--}}
                                                            {{--<td class="text-center">< ?php if($row1->pv_status == 2){echo "Approved";} else{echo "Pending";}?></td>--}}

                                                        </tr>


                                                            <?php

                                                        }
                                                            ?>
                                                        <tr class="text-center" style="font-size: large;font-weight: bold">
                                                            <td colspan="7">Total</td>
                                                            <td colspan="1">{{number_format($total_paid_amount,2)}}</td>
                                                            <td colspan="1">{{number_format($total_remaining_amount,2)}}</td>
                                                            <td></td>
                                                        </tr>

                                                            <?php


                                                            $data=CommonHelper::get_advancee_from_outstanding($supplier_id);

                                                        foreach($data as $row):


                                                            $diffrence=CommonHelper::get_debit_credit_from_outstanding($supplier_id,$row->new_pv_no);

                                                        if ($diffrence<0):?>


                                                        <tr style="background-color: darkgrey" id="" title="" id="">
                                                            <td class="text-center">

                                                            </td>
                                                            <td class="text-center"><?php echo $counter++;?></td>
                                                            <td class="text-center"><?php echo $row->new_pv_no ?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                            <td class="text-center"><?php echo  $diffrence; ?></td>
                                                            <td  class="text-center"><b style="font-size: larger;font-weight: bolder"><?php echo $diffrence; ?></b></td>

                                                            <td class="text-center"><?php echo 'Advance'?></td>
                                                            <td class="text-center"><?php echo 'Advance' ?></td>
                                                        </tr>
                                                        <?php endif;endforeach ?>
                                                            <?php
                                                            ?>

                                                        <tr>
                                                            <th colspan="10" class="text-center">xxxxx</th>
                                                        </tr>
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
                @else
                    <div class="col-md-12">
                        <div class="alert alert-success" role="alert">
                            <strong class="text-uppercase">All Clear! No Purchase invoice found</strong>
                        </div>
                    </div>
                @endif
            </div>
            <script>
                $(document).ready(function(){

                });

                function checking()
                {
                    $('.checkbox1').each(function()
                    {
                        if ($(this).is(':checked'))
                        {
                            $('#BtnPayment').prop('disabled',false);
                        }
                        else
                        {
                            $('#BtnPayment').prop('disabled',false);
                        }
                    });
                }

            </script>

        @endif
    @else
        <div class="alert alert-warning" role="alert">
            Please ensure that you have correctly selected both the Payment and Party Detail fields.
        </div>
    @endif



@else
    <div class='jhed headquid'>
        <div class="row">
            <div class="col-md-6">
                <span class="subHeadingLabelClass">Cash Payment Voucher Detail</span>
            </div>

            <div class="col-md-6 text-right">
                <input  type="button" class="btn btn-sm btn-primary" onclick="AddMorePvs()" value="Add More PV's Rows" />
                <span class="badge badge-success" id="span">2</span>
            </div>
        </div>

    </div>
    <div class="table-responsive">
        <table id="buildyourform" class="userlittab table table-bordered sf-table-list">
            <thead>

            <tr>
                <th class="text-center hidden-print"><a href="#" onclick="showDetailModelOneParamerter('fdc/createAccountFormAjax')" class="">Account Head</a>

                <th class="text-center hide" style="width:450px;">Description <span class="rflabelsteric"><strong>*</strong></span></th>
                <th class="text-center" style="width:150px;">Debit <span class="rflabelsteric"><strong>*</strong></span></th>
                <th class="text-center" style="width:150px;">Credit <span class="rflabelsteric"><strong>*</strong></span></th>
                <th class="text-center" style="width:150px;">Action</th>
            </tr>
            </thead>
            <tbody class="addMorePvsDetailRows_1" id="addMorePvsDetailRows_1">
                <?php for($j = 1 ; $j <= 2 ; $j++){?>
            <input type="hidden" name="rvsDataSection_1[]" class="form-control" id="rvsDataSection_1" value="<?php echo $j?>" />
            <tr class="AutoNo">
                <td>
                    <select style="width: 100%" class="form-control requiredField select2 acccccctex" name="account_id[]" id="account_id{{$j}}">
                        <option value="">Select Account</option>
                        @foreach(CommonHelper::get_all_account_operat() as $key => $y)
                            <option value="{{ $y->id.',0'}}">{{ $y->code .' ---- '. $y->name}}</option>
                        @endforeach
                    </select>
                </td>

                <td class="hide">
                    <textarea class="form-control" name="desc[]" id="desc_1_{{$j}}"/></textarea>
                </td>
                <td>
                    <input onfocus="mainDisable('c_amount_1_<?php echo $j ?>','d_amount_1_<?php echo $j ?>');" placeholder="Debit" class="form-control d_amount_1 requiredField number_format" maxlength="15" min="0" type="text" name="d_amount[]" id="d_amount_1_{{$j}}" onkeyup="sum('1')" value="" required="required"/>
                </td>
                <td>
                    <input onfocus="mainDisable('d_amount_1_<?php echo $j ?>','c_amount_1_<?php echo $j ?>');" placeholder="Credit" class="form-control c_amount_1 requiredField number_format" maxlength="15" min="0" type="text" name="c_amount[]" id="c_amount_1_{{$j}}" onkeyup="sum('1')" value="" required="required"/>
                </td>
                <td class="text-center">---</td>
            </tr>
            <?php }?>
            </tbody>
        </table>
        <table class="table table-bordered">
            <tbody>
            <tr>
                <td></td>
                <td style="width:150px;">
                    <input
                            type="number"
                            readonly="readonly"
                            id="d_t_amount_1"
                            maxlength="15"
                            min="0"
                            name="d_t_amount_1"
                            class="form-control requiredField text-right number_format"
                            value=""/>
                </td>
                <td style="width:150px;">
                    <input
                            type="number"
                            readonly="readonly"
                            id="c_t_amount_1"
                            maxlength="15"
                            min="0"
                            name="c_t_amount_1"
                            class="form-control requiredField text-right number_format"
                            value=""/>
                </td>
                <td class="diff" style="width:150px;font-size: 20px;">
                    <input readonly style="color: blue;font-weight: 600" class="form-control" type="text" id="diff" value=""/>
                </td>
            </tr>
            </tbody>
        </table>

    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
            <label class="sf-label">Description</label>
            <span style="font-size:17px !important; color:#F00 !important;"><strong>*</strong></span>
            <textarea  name="description_1" id="description_1" style="resize:none;" class="form-control requiredField"></textarea>
        </div>
    </div>
@endif