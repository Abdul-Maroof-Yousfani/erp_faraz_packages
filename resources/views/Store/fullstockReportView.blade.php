<?php use App\Helpers\CommonHelper;
$TotalQty = 0;
$OpeningQty=0;
$GrnQty = 0;
$IssQty = 0;
$GrnAmount=0;
$PurchaseReturnAmount=0;
?>
@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <button type="button" class="btn btn-sm btn-info" onclick="PrintDetail()" style="">
            <span class="glyphicon glyphicon-print"> Print</span>
        </button>
    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N" id="PrintArea">
            <h2 style="text-align: center">Stock Summary Report</h2>
            <?php

            $item_name=CommonHelper::get_item_name($_GET['sub_item_id']);

            ?>

            <?php $OpeningData=DB::Connection('mysql2')->Select('select * from stock where sub_item_id="'.$_GET['sub_item_id'].'"  and opening = 1
            and warehouse_id="'.$_GET['warehouse_id'].'" group by sub_item_id');
            if(count($OpeningData) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="7" class="text-center" style="font-size: 25px;">Opening</th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Opening Date.</th>
                <th class="text-center">Supplier Name</th>
                <th class="text-center">UOM</th>
                <th class="text-center">QTY.</th>
                <th class="text-center">Amount</th>
                </thead>
                <tbody id="filterDemandVoucherList">

                <?php

                $counter=1;
                ?>
                @foreach($OpeningData as $data)
                    <?php
                    if($data->supplier_id != ""){
                        $supplier_name = CommonHelper::get_supplier_name($data->supplier_id);
                    } else {
                        $supplier_name = "";
                    }

                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $OpeningQty += $data->qty;
                    $TotalQty +=$OpeningQty;
                    $issue_amount= CommonHelper::get_amount_from_stock(2,1,$data->sub_item_id);
                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->created_date)) }}</td>
                        <td class="text-center"> {{$supplier_name}} </td>
                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="5"></td>
                    <td class="text-center"> {{$OpeningQty}}  </td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>









            <?php

            $GrnData=DB::Connection('mysql2')->Select('select * from stock where sub_item_id="'.$_GET['sub_item_id'].'" and voucher_type = 1 and status=1 and opening = 0 and transfer_status=0
                and warehouse_id="'.$_GET['warehouse_id'].'"');
            if(count($GrnData) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                    <th colspan="11" class="text-center" style="font-size: 25px;">Goods Receipt Note</th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">Item</th>
                <th class="text-center">Grn No.</th>
                <th class="text-center">Grn Date.</th>
                <th class="text-center">Supplier Name</th>
                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Batch Code</th>
                <th class="text-center">Purchase QTY.</th>
                <th class="text-center">Rate.</th>
                <th class="text-center">Amount.</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($GrnData as $data)
                    <?php
                    if($data->supplier_id != ""){
                        $supplier_name = CommonHelper::get_supplier_name($data->supplier_id);
                    } else {
                        $supplier_name = "";
                    }

                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $GrnQty += $data->qty;
                            $GrnAmount+=$data->amount;
                     $TotalQty +=$GrnQty;
                    $issue_amount= CommonHelper::get_amount_from_stock(2,1,$data->sub_item_id);
                            $Warehouse = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id)
                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center">
                            <?php if($data->description !=""):
                                echo $data->description;
                            else:
                                echo $item_name;
                            endif;?>
                        </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        <td class="text-center"> {{$supplier_name}} </td>
                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Warehouse->name ?? ''}}</td>
                        <td class="text-center">{{$data->batch_code}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8"></td>
                    <td class="text-center"> {{$GrnQty}}  </td>
                    <td></td>
                    <td class="text-center">{{$GrnAmount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>





            <?php

            $GrnData=DB::Connection('mysql2')->Select('select * from stock where sub_item_id="'.$_GET['sub_item_id'].'" and voucher_type = 1 and status=1 and opening = 0 and transfer_status in (1,2)
                and warehouse_id="'.$_GET['warehouse_id'].'"');

                $transfer_recived_qty=0;
                 $transfer_recived_amount=0;
            if(count($GrnData) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="10" class="text-center" style="font-size: 25px;">Stock Received Qty</th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>
                <th class="text-center">Item</th>
                <th class="text-center">Grn No.</th>
                <th class="text-center">Grn Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Received From</th>
                <th class="text-center">Purchase QTY.</th>
                <th class="text-center">Rate.</th>
                <th class="text-center">Amount.</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($GrnData as $data)
                    <?php
                    if($data->supplier_id != ""){
                        $supplier_name = CommonHelper::get_supplier_name($data->supplier_id);
                    } else {
                        $supplier_name = "";
                    }

                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $transfer_recived_qty += $data->qty;
                    $transfer_recived_amount+=$data->amount;
                    $TotalQty +=$GrnQty;
                    $issue_amount= CommonHelper::get_amount_from_stock(2,1,$data->sub_item_id);
                    $Warehouse = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id);
                            if ($data->transfer_status==1):
                    $warehouse_from = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id_from);
                            $ware_house_from_name=$warehouse_from->name;
                            else:
                                $ware_house_from_name='';
                            endif;
                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>

                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Warehouse->name}}</td>
                        <td class="text-center">{{$ware_house_from_name}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7"></td>
                    <td class="text-center"> {{$transfer_recived_qty}}  </td>
                    <td></td>
                    <td class="text-center">{{$transfer_recived_amount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>






            <?php $IssData=DB::Connection('mysql2')->Select('select a.pr_no,c.* from purchase_return a
                                            INNER JOIN stock c ON c.voucher_no =  a.pr_no
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.opening = 0
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"');
            $purchase_return_qty=0;
            $purchase_return_amount=0;
            if(count($IssData) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                    <th colspan="11" class="text-center" style="font-size: 25px;">Purchase Return </th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Purchase Return No.</th>
                <th class="text-center">Purchase Return Date.</th>
                <th class="text-center">Supplier Name</th>
                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Batch Code</th>
                <th class="text-center">Purchase Return QTY.</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;

                ?>
                @foreach($IssData as $data)
                    <?php
                    if($data->supplier_id != ""){
                        $supplier_name = CommonHelper::get_supplier_name($data->supplier_id);
                    } else {
                        $supplier_name = "";
                    }

                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $purchase_return_qty += $data->qty;
                    $purchase_return_amount += $data->amount;
                    $TotalQty +=$IssQty;
                    $issue_amount= CommonHelper::get_amount_from_stock(2,1,$data->sub_item_id);
                    $Wareho = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id)
                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        <td class="text-center"> {{$supplier_name}} </td>
                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Wareho->name}}</td>
                        <td class="text-center">{{$data->batch_code}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8"></td>
                    <td class="text-center"> {{$purchase_return_qty}}  </td>
                    <td></td>
                    <td class="text-center">{{$purchase_return_amount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>



            <?php
            $transfer_data=DB::Connection('mysql2')->Select('select a.tr_no,c.* from stock_transfer a
                                            INNER JOIN stock c ON c.voucher_no =  a.tr_no
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.opening = 0
                                            and c.voucher_type=3
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"');
            $stock_transfer=0;
            $stock_transfer_amount=0;
            if(count($transfer_data) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="10" class="text-center" style="font-size: 25px;">Stock Transferred </th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Stock Transfer No.</th>
                <th class="text-center">Stock Transfer Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Location Send To</th>
                <th class="text-center">Stock Transfer QTY.</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($transfer_data as $data)
                    <?php


                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $stock_transfer += $data->qty;
                    $stock_transfer_amount += $data->amount;
                    $TotalQty +=$IssQty;
                    $issue_amount= CommonHelper::get_amount_from_stock(2,1,$data->sub_item_id);
                    $Wareho = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id);
                    $ware_house = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id_to)
                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>

                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Wareho->name}}</td>
                        <td class="text-center">{{$ware_house->name}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7"></td>
                    <td class="text-center"> {{$stock_transfer}}  </td>
                    <td></td>
                    <td class="text-center">{{$stock_transfer_amount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>




            <?php
            $internal_con=DB::Connection('mysql2')->Select('select a.voucher_no,c.* from internal_consumtion a
                                            INNER JOIN stock c ON c.voucher_no =  a.voucher_no
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.opening = 0
                                            and c.voucher_type=8
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"');
            $total_internal_con_qty=0;
            $total_interal_cons_amount=0;
            if(count($internal_con) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="10" class="text-center" style="font-size: 25px;">Internal Consumption </th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Voucher No.</th>
                <th class="text-center">Voucher Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>

                <th class="text-center">Stock Transfer QTY.</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($internal_con as $data)
                    <?php



                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{CommonHelper::get_item_name($data->sub_item_id)}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        <td class="text-center">{{CommonHelper::get_uom($data->sub_item_id)}}</td>
                        <td class="text-center">{{CommonHelper::get_name_warehouse($data->warehouse_id)}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>

                        <?php
                        $total_internal_con_qty+=$data->qty;
                        $total_interal_cons_amount+= $data->amount;
                        ?>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6"></td>
                    <td class="text-center"> {{number_format($total_internal_con_qty,2)}}  </td>
                    <td></td>
                    <td class="text-center">{{number_format($total_interal_cons_amount,2)}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>



                <?php // issuence plan ?>

            <?php
            $total_issuence_qty_plan=0;
            $total_issuence_amount_plan=0;
                if (Session::get('run_company')==3):
            $issuence_plan=DB::Connection('mysql2')->Select('select c.* from production_plane_issuence a
                                            INNER JOIN stock c ON c.master_id =  a.id
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.opening = 0
                                            and c.voucher_type=9
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"');
            $total_issuence_qty_plan=0;
            $total_issuence_amount_plan=0;
            if(count($issuence_plan) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="10" class="text-center" style="font-size: 25px;">Issuence Against Production Plan  </th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Voucher No.</th>
                <th class="text-center">Voucher Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>

                <th class="text-center">Issued QTY.</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($issuence_plan as $data)
                    <?php



                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{CommonHelper::get_item_name($data->sub_item_id)}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        <td class="text-center">{{CommonHelper::get_uom($data->sub_item_id)}}</td>
                        <td class="text-center">{{CommonHelper::get_name_warehouse($data->warehouse_id)}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>

                        <?php
                        $total_issuence_qty_plan+=$data->qty;
                        $total_issuence_amount_plan+= $data->amount;
                        ?>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6"></td>
                    <td class="text-center"> {{number_format($total_issuence_qty_plan,2)}}  </td>
                    <td></td>
                    <td class="text-center">{{number_format($total_issuence_amount_plan,2)}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif; endif;?>





            <?php // return plan ?>

            <?php

            $total_return_qty_plan=0;
            $total_return_amount_plan=0;
            if (Session::get('run_company')==3):
            $return_plan=DB::Connection('mysql2')->Select('select c.* from production_plane_return a
                                            INNER JOIN stock c ON c.master_id =  a.id
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.opening = 0
                                            and c.voucher_type=10
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"');
            $total_return_qty_plan=0;
            $total_return_amount_plan=0;
            if(count($issuence_plan) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="10" class="text-center" style="font-size: 25px;">Return Against Production Plan  </th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Voucher No.</th>
                <th class="text-center">Voucher Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>

                <th class="text-center">Return QTY.</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($return_plan as $data)
                    <?php



                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{CommonHelper::get_item_name($data->sub_item_id)}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        <td class="text-center">{{CommonHelper::get_uom($data->sub_item_id)}}</td>
                        <td class="text-center">{{CommonHelper::get_name_warehouse($data->warehouse_id)}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>

                        <?php
                        $total_return_qty_plan+=$data->qty;
                        $total_return_amount_plan+= $data->amount;
                        ?>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6"></td>
                    <td class="text-center"> {{number_format($total_return_qty_plan,2)}}  </td>
                    <td></td>
                    <td class="text-center">{{number_format($total_return_amount_plan,2)}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif; endif;?>






            <?php // produce?>

            <?php

            $total_produce_qty=0;
            $total_produce_amount=0;
            if (Session::get('run_company')==3):
            $produce_data=DB::Connection('mysql2')->Select('select * from stock c

                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and c.status=1
                                            and c.opening = 0
                                            and c.voucher_type=11
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"');
            $total_produce_qty=0;
            $total_produce_amount=0;
            if(count($produce_data) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="10" class="text-center" style="font-size: 25px;">Produce QTY By Production  </th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Voucher No.</th>
                <th class="text-center">Voucher Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>

                <th class="text-center">Produce QTY.</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($produce_data as $data)
                    <?php



                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{CommonHelper::get_item_name($data->sub_item_id)}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        <td class="text-center">{{CommonHelper::get_uom($data->sub_item_id)}}</td>
                        <td class="text-center">{{CommonHelper::get_name_warehouse($data->warehouse_id)}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>

                        <?php
                        $total_produce_qty+=$data->qty;
                        $total_produce_amount+= $data->amount;
                        ?>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="6"></td>
                    <td class="text-center"> {{number_format($total_produce_qty,2)}}  </td>
                    <td></td>
                    <td class="text-center">{{number_format($total_produce_amount,2)}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif; endif;?>




            <?php
            $sales_data=DB::Connection('mysql2')->Select('select a.gd_no,c.* from delivery_note_data a
                                            INNER JOIN stock c ON c.voucher_no =  a.gd_no
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.status=1
                                            and c.opening = 0
                                            and c.voucher_type=5
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"
                                            group by c.master_id');

                                            $sales_data=DB::Connection('mysql2')->Select('select c.*  from stock as c

                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"

                                            and c.status=1
                                            and c.opening = 0
                                            and c.voucher_type=5
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"');
            $sales_qtyr=0;
            $sales_amount=0;
            if(count($sales_data) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="11" class="text-center" style="font-size: 25px;">Sales Data</th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">GD No.</th>
                <th class="text-center">GD Date.</th>
                <th class="text-center">Customer</th>
                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Batch Code</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>
                <th class="text-center">COGS</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                $sales_total_qty=0;
                ?>
                @foreach($sales_data as $data)
                    <?php
                    if ($data->qty>0):

                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                //    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $sales_qtyr += $data->qty;
                    $sales_amount += $data->amount;


                    $Wareho = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id);

                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        <td class="text-center">{{CommonHelper::byers_name($data->customer_id)->name ?? ''}}</td>
                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Wareho->name}}</td>
                        <td class="text-center">{{$data->batch_code}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->qty*$data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>

                    <?php endif; ?>
                @endforeach
                <tr>
                    <td colspan="8"></td>
                    <td class="text-center"> {{$sales_qtyr}}  </td>
                    <td></td>
                    <td class="text-center">{{$sales_amount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>




            <?php
            $pos_data=DB::Connection('mysql2')->Select('select a.pos_no,c.* from pos a
                                            INNER JOIN stock c ON c.voucher_no =  a.pos_no
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.status=1
                                            and c.opening = 0
                                            and c.voucher_type=5

                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"
                                            group by c.master_id');
            $pos_qty=0;
            $pos_amount=0;
            if(count($pos_data) > 0):

            ?>

            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="11" class="text-center" style="font-size: 25px;">POS Data</th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">POS No.</th>
                <th class="text-center">POS Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Batch Code</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>
                <th class="text-center">COGS</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;

                ?>
                @foreach($pos_data as $data)
                    <?php
                    if ($data->qty>0):

                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    //    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $pos_qty += $data->qty;
                    $pos_amount += $data->amount;


                    $Wareho = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id);

                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>

                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Wareho->name}}</td>
                        <td class="text-center">{{$data->batch_code}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->qty*$data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>

                    <?php endif; ?>
                @endforeach
                <tr>
                    <td colspan="8"></td>
                    <td class="text-center"> {{$pos_qty}}  </td>
                    <td></td>
                    <td class="text-center">{{$pos_amount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif; ?>















            <?php

                // production
            $production=DB::Connection('mysql2')->Select('select a.voucher_no,c.* from issuence_for_production a
                                            INNER JOIN stock c ON c.voucher_no =  a.voucher_no
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.status=1
                                            and c.opening = 0
                                            and c.voucher_type=5
                                            and c.pos_status=2

                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"
                                            group by c.master_id');
            $issu_prod_qty=0;
            $issue_prod_amount=0;
            if(count($production) > 0):

            ?>

            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="11" class="text-center" style="font-size: 25px;">Issuence Against Production</th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">Voucher  No.</th>
                <th class="text-center">Voucher Date.</th>

                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Batch Code</th>
                <th class="text-center">QTY</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>
                <th class="text-center">COGS</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                $issu_prod_qty
                ?>
                @foreach($production as $data)
                    <?php
                    if ($data->qty>0):

                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    //    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $issu_prod_qty += $data->qty;
                    $issue_prod_amount += $data->amount;


                    $Wareho = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id);

                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>

                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Wareho->name}}</td>
                        <td class="text-center">{{$data->batch_code}}</td>
                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->qty*$data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>

                    <?php endif; ?>
                @endforeach
                <tr>
                    <td colspan="8"></td>
                    <td class="text-center"> {{$issu_prod_qty}}  </td>
                    <td></td>
                    <td class="text-center">{{$issue_prod_amount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif; ?>


            <?php
                //Sales Return
            $sales_return_data=DB::Connection('mysql2')->Select('select a.voucher_no,c.* from credit_note_data a
                                            INNER JOIN stock c ON c.master_id =  a.id
                                            where c.sub_item_id="'.$_GET['sub_item_id'].'"
                                            and a.status=1
                                            and c.status=1
                                            and c.opening = 0
                                            and c.voucher_type=6
                                            and c.warehouse_id="'.$_GET['warehouse_id'].'"
                                            Group by a.id');
            $sales_return_qtyr=0;
            $sales_return_amount=0;
            if(count($sales_return_data) > 0):

            ?>
            <table class="table table-bordered table-responsive">
                <thead>
                <th colspan="10" class="text-center" style="font-size: 25px;">Sales Return Data</th>
                </thead>
                <thead>
                <th class="text-center">S.No</th>

                <th class="text-center">Item</th>
                <th class="text-center">CR No.</th>
                <th class="text-center">CR Date.</th>
                {{--<th class="text-center">Buyer</th>--}}
                <th class="text-center">UOM</th>
                <th class="text-center">Location</th>
                <th class="text-center">Batch Code</th>
                <th class="text-center">Qty</th>
                <th class="text-center">Rate</th>
                <th class="text-center">Amount</th>

                </thead>
                <tbody id="filterDemandVoucherList">
                <?php

                $counter=1;
                ?>
                @foreach($sales_return_data as $data)
                    <?php


                    $sub_ic_data=CommonHelper::get_subitem_detail($data->sub_item_id);
                    $sub_ic_data=explode(',',$sub_ic_data);
                    $uom=$sub_ic_data[0];
                    $sub_item_id=$sub_ic_data[4];

                    //    $purchase_amount= CommonHelper::get_amount_from_stock(1,1,$data->sub_item_id);
                    $sales_return_qtyr += $data->qty;
                    $sales_return_amount += $data->amount;


                    $Wareho = CommonHelper::get_single_row('warehouse','id',$data->warehouse_id);

                    ?>
                    <tr>
                        <td class="text-center">{{$counter++}}</td>

                        <td class="text-center"> {{$item_name}} </td>
                        <td class="text-center">{{ strtoupper($data->voucher_no) }}</td>
                        <td class="text-center">{{ date('d-m-Y',strtotime($data->voucher_date)) }}</td>
                        {{--<td class="text-center">{{$data->customer_id}}</td>--}}
                        <td class="text-center">{{CommonHelper::get_uom_name($uom)}}</td>
                        <td class="text-center">{{$Wareho->name}}</td>
                        <td class="text-center">{{$data->batch_code}}</td>

                        <td class="text-center">{{number_format($data->qty,2)}}</td>
                        <td class="text-center">{{number_format($data->rate,2)}}</td>
                        <td class="text-center">{{number_format($data->amount,2)}}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7"></td>
                    <td class="text-center"> {{$sales_return_qtyr}}  </td>
                    <td></td>
                    <td class="text-center">{{$sales_return_amount}}</td>
                </tr>

                </tbody>
            </table>
            <?php endif;?>



            <table class="table table-bordered table-responsive " style="width: 20%">

                <tbody>
                <tr>
                    <td>Opening Quantity</td>
                    <td><?php echo $OpeningQty?></td>
                </tr>
                <tr>
                    <td>Grn Quantity</td>
                    <td><?php echo $GrnQty?></td>
                </tr>


                <tr>
                    <td>Produce QTY By Production</td>
                    <td><?php echo $total_produce_qty?></td>
                </tr>

                <tr>
                    <td>Stock Received QTY</td>
                    <td><?php echo $transfer_recived_qty?></td>
                </tr>
                <tr>
                    <td>Purchase Return Quantity</td>
                    <td><?php echo $purchase_return_qty?></td>
                </tr>

                <tr>
                    <td>Transferd QTY</td>
                    <td><?php echo $stock_transfer?></td>
                </tr>


                <tr>
                    <td>Internal Consumption</td>
                    <td><?php echo $total_internal_con_qty?></td>
                </tr>

                <tr>
                    <td>Issuence Against Production Plan</td>
                    <td><?php echo $total_issuence_qty_plan?></td>
                </tr>

                <tr>
                    <td>Return Against Production Plan</td>
                    <td><?php echo $total_return_qty_plan?></td>
                </tr>

                <tr>
                    <td>Sales QTY</td>
                    <td><?php echo $sales_qtyr?></td>
                </tr>

                <tr>
                    <td>POS QTY</td>
                    <td><?php echo $pos_qty?></td>
                </tr>
                <tr>
                    <td>Production Issuence</td>
                    <td><?php echo $issu_prod_qty?></td>
                </tr>

                <tr>
                    <td>Sales Return QTY</td>
                    <td><?php echo $sales_return_qtyr?></td>
                </tr>

                <tr>
                    <td>Stock In Hand</td>
                    <td><?php echo number_format($GrnQty+$OpeningQty+$transfer_recived_qty-$total_issuence_qty_plan-
                                $purchase_return_qty-$stock_transfer-$total_internal_con_qty-$sales_qtyr-$pos_qty-$issu_prod_qty+$sales_return_qtyr+$total_return_qty_plan+$total_produce_qty,2)?></td>
                </tr>
                </tbody>
            </table>
            <?php //REturn Stock Damage?>




        <table class="table table-bordered table-responsive">

            <thead>
            <th class="text-center">S.No</th>

            <th class="text-center">Voucher</th>
            <th class="text-center">Voucher Type</th>
            <th class="text-center">Voucher Date</th>
            <th class="text-center">QTY</th>
            <th class="text-center">Rate.</th>
            <th class="text-center">Amount</th>
            <th class="text-center">Balance QTY</th>
            <th class="text-center">Balance Amount</th>
            <th class="text-center">Cost</th>
            <th class="text-center">Update</th>
            </thead>
            <tbody id="filterDemandVoucherList">

            <?php

            $counter=1;

         $data=   DB::Connection('mysql2')->table('stock')->where('status',1)->where('sub_item_id',$_GET['sub_item_id'])
            ->where('warehouse_id',$_GET['warehouse_id'])
            ->orderBy('voucher_date','ASC')->get();
                    $total_qty=0;
                    $total_amount=0;

            ?>
            @foreach($data as $row)


                <?php
                $dn_data_id='';
                $voucher_type='';
                $dn_no='';
                        if ($row->voucher_type==1 && $row->opening==1):
                        $voucher_type='Opening';
                        $total_qty+=$row->qty;
                        $total_amount+=$row->amount;
                        elseif($row->voucher_type==1 && $row->opening==0):
                        $voucher_type='Goods Receipt Note';
                $total_qty+=$row->qty;
                $total_amount+=$row->amount;
                       elseif($row->voucher_type==5 && $row->pos_status==0):
                       $voucher_type='Delivery Note';
                $total_qty-=$row->qty;
                $total_amount-=$row->amount;

                elseif($row->voucher_type==5 && $row->pos_status==1):
                $voucher_type='POS';
                $total_qty-=$row->qty;
                $total_amount-=$row->amount;

                elseif($row->voucher_type==5 && $row->pos_status==2):
                $voucher_type='Production Issuence';
                $total_qty-=$row->qty;
                $total_amount-=$row->amount;


                elseif($row->voucher_type==2):
                $voucher_type='Purchase Return';
                $total_qty-=$row->qty;
                $total_amount-=$row->amount;

                elseif($row->voucher_type==8):
                $voucher_type='Internal Consumption';
                $total_qty-=$row->qty;
                $total_amount-=$row->amount;

                elseif($row->voucher_type==9):
                $voucher_type='Issuence Against Production';
                $total_qty-=$row->qty;
                $total_amount-=$row->amount;

                elseif($row->voucher_type==10):
                $voucher_type='Return Against Production';
                $total_qty+=$row->qty;
                $total_amount+=$row->amount;

                       elseif($row->voucher_type==6):
                       $voucher_type='Credit Note';
                $total_qty+=$row->qty;
                $total_amount+=$row->amount;
                    //   $so_id= DB::Connection('mysql2')->table('credit_note')->where('id',$row->main_id)->where('status',1)->value('so_id');
                    //  $so_no= DB::Connection('mysql2')->table('sales_order')->where('id',$so_id)->where('status',1)->value('so_no');
                    //   $dn_no= DB::Connection('mysql2')->table('delivery_note')->where('so_no',$so_no)->where('status',1)->value('gd_no');


                 $dn_data_id= DB::Connection('mysql2')->table('credit_note_data')->where('id',$row->master_id)->where('status',1)->value('so_data_id');
                $dn_no= DB::Connection('mysql2')->table('delivery_note_data')->where('so_data_id',$dn_data_id)->where('status',1)->value('gd_no');
                        endif;
                ?>

                <tr>
                    <td class="text-center">{{$counter++}}</td>
                    <td class="text-center"> {{strtoupper($row->voucher_no).' '.strtoupper($dn_no).''}} </td>
                    <td style="color: red" class="text-center"> {{$voucher_type}}  </td>
                    <td class="text-center">{{date('d-m-Y',strtotime($row->voucher_date)) }}</td>
                    <td class="text-center">{{$row->qty}}</td>
                    <td class="text-center">{{$row->rate}}</td>
                    <td class="text-center">{{number_format($row->amount,2)}}</td>
                    <td style="font-weight: bold" class="text-center">{{number_format($total_qty,2)}}</td>
                    <td @if ($total_qty>0) title="{{number_format($total_amount/$total_qty,2)}}" @endif style="font-weight: bold" class="text-center">{{number_format($total_amount,2)}}</td>
                    <td class=""><input class="form-control" type="number" id="val{{$row->id}}" value="{{$row->amount}}"></td>
                    <td class=""><input   type="button" onclick="update_cost('{{$row->id}}')" value="Update"/> </td>
                </tr>
            @endforeach
        <tr style="font-size: large;font-weight: bold">
            <td colspan="4">Total</td>
            <td class="text-center">{{number_format($total_qty,2)}}</td>
            <td ></td>
            <td class="text-center">{{number_format($total_amount,2)}}</td>
        </tr>

            </tbody>
        </table>
        </div>
</div>


    <script !src="">
        function PrintDetail() {

            var printContents = document.getElementById('PrintArea').innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            //if(param3 == 0){
            location.reload();

            //}
        }


        function update_cost(id)
        {
            var value=$('#val'+id).val();
            $.ajax({
                url: '{{url('sad/update_cost')}}',
                type: 'GET',
                data: {id: id,value:value},
                success: function (response)
                {
                    alert('Successfully Update');
                    location.reload();
                }
            });
        }
    </script>
@endsection
