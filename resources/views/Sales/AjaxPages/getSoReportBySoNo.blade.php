<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
        <div class="table-responsive">
            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                <thead>
                <tr>
                    <th colspan="9" class="text-center"><strong style="font-size: 25px !important;">Sales Order</strong></th>
                </tr>
                <th class="text-center col-sm-1">S.No</th>
                <th class="text-center col-sm-1">SO No</th>
                <th class="text-center col-sm-1">SO Date</th>
                <th class="text-center col-sm-1">Mode / Terms Of Payment</th>
                <th class="text-center col-sm-1">Buyer's Order NO </th>
                <th class="text-center col-sm-1">Order Date</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Total Amount</th>
                <th class="text-center">Username</th>
                <th class="text-center">View</th>

                </thead>
                <tbody>
                <?php $counter = 1;$total=0;?>

                @foreach($SalesOrder as $row)
                    <?php $customer=CommonHelper::byers_name($row->buyers_id);
                    $data=SalesHelper::get_so_amount($row->id);
                    ?>
                    <tr title="{{$row->id}}" id="{{$row->id}}">
                        <td class="text-center">{{$counter++}}</td>
                        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->so_date);?></td>
                        <td class="text-center">{{$row->model_terms_of_payment}}</td>
                        <td class="text-center">{{$row->order_no}}</td>
                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
                        <td class="text-center">{{$customer->name}}</td>
                        <td class="text-right">{{number_format($data->amount+$row->sales_tax,2)}}</td>
                        <td class="text-center"><?php echo $row->username?></td>
                        <td class="text-center">
                            <button onclick="showDetailModelOneParamerter('sales/viewSalesOrderDetail','<?php echo $row->id ?>','View Sales Order','<?php echo $m?>')"
                                    type="button" class="btn btn-success btn-xs">View</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <?php if($DeliveryNote->count() > 0):?>
                <thead>
                <tr>
                    <th colspan="10" class="text-center"><strong style="font-size: 25px !important;">Delivery Note</strong></th>
                </tr>
                <th class="text-center col-sm-1">S.No</th>
                <th class="text-center col-sm-1">SO No</th>
                <th class="text-center col-sm-1">DN No</th>
                <th class="text-center col-sm-1">DN Date</th>
                <th class="text-center col-sm-1">Mode / Terms Of Payment</th>
                <th class="text-center col-sm-1">Order Date</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Total Qty.</th>
                <th class="text-center">Total Amount.</th>
                <th class="text-center">Username</th>
                <th class="text-center">View</th>
                </thead>
                <tbody>
                <?php $counter = 1;$total=0;?>

                @foreach($DeliveryNote->get() as $row)

                    <?php $data=SalesHelper::get_total_amount_for_delivery_not_by_id($row->id); ?>
                    <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
                    <tr title="{{$row->id}}" id="{{$row->id}}">
                        <td class="text-center">{{$counter++}}</td>
                        <td class="text-center"><?php echo  strtoupper($row->so_no) ?></td>
                        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gd_no)}}</td>
                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->gd_date);?></td>
                        <td class="text-center">{{$row->model_terms_of_payment}}</td>
                        <td class="text-center"><?php echo CommonHelper::changeDateFormat($row->order_date);?></td>
                        <td class="text-center">{{$customer->name}}</td>
                        <td class="text-right">{{number_format($data->qty,3)}}</td>
                        <td class="text-right">{{number_format($data->amount+$row->sales_tax_amount,3)}} <?php $total+=$data->amount+$row->sales_tax_amount;?></td>
                        <td class="text-center"><?php echo $row->username?></td>
                        <td class="text-center"><button
                                    onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $row->id ?>','','View Delivery Note','<?php echo $m?>')"
                                    type="button" class="btn btn-success btn-xs">View</button></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="text-center"><strong style="font-size: 20px !important;">TOTAL</strong></td>
                    <td style="font-size: 20px !important;" class="text-right"><?php echo number_format($total,2)?></td>
                    <td></td>
                </tr>
                </tbody>
                <?php endif;?>
                <?php if($SalesTaxInvoice->count() > 0):?>
                <thead>
                <tr>
                    <th colspan="9" class="text-center"><strong style="font-size: 25px !important;">Sales Tax Invoice</strong></th>
                </tr>
                <th class="text-center col-sm-1">S.No</th>
                <th class="text-center col-sm-1">SO No</th>
                <th class="text-center col-sm-1">SI No</th>
                <th class="text-center col-sm-1">SI Date</th>
                <th class="text-center col-sm-1">Multi DN No</th>
                <th class="text-center col-sm-1">Model Terms Of Payment</th>
                <th class="text-center col-sm-1">Order Date</th>
                <th class="text-center">Customer</th>
                <th class="text-center">Total Amount</th>
                <th class="text-center">Username</th>
                <th class="text-center">View</th>
                </thead>
                <tbody>
                <?php $counter = 1;$total=0;

                ?>

                @foreach($SalesTaxInvoice->get() as $row)
                    <?php $customer=CommonHelper::byers_name($row->buyers_id);
                    $data=SalesHelper::get_total_amount_for_sales_tax_invoice_by_id($row->id);
                    $DnDataId = DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('master_id',$row->id)->select('dn_data_ids')->groupBy('master_id')->first();
                    $InArray = explode(',',$DnDataId->dn_data_ids);
                    $DnNoData = DB::Connection('mysql2')->table('delivery_note')->whereIn('id',$InArray)->select('gd_no')->get();
                    $MultiDnNO = "";
                    foreach($DnNoData as $Fil):
                        $MultiDnNO .=$Fil->gd_no.'<br>';
                    endforeach;
                    ?>
                    <tr title="{{$row->id}}" id="{{$row->id}}">
                        <td class="text-center">{{$counter++}}</td>
                        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
                        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gi_no)}}</td>
                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->gi_date);?></td>
                        <td class="text-center text-success"><?php echo strtoupper($MultiDnNO);?></td>
                        <td class="text-center">{{$row->model_terms_of_payment}}</td>
                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
                        <td class="text-center">{{$customer->name}}</td>
                        <td class="text-right">{{number_format($data->amount+$row->sales_tax,2)}}<?php $total+=$data->amount+$row->sales_tax;?></td>
                        <td class="text-center"><?php echo $row->username?></td>
                        <td class="text-center"><button
                                    onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice','<?php echo $m?>')"
                                    type="button" class="btn btn-success btn-xs">View</button></td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="8" class="text-center"><strong style="font-size: 20px !important;">TOTAL</strong></td>
                    <td style="font-size: 20px !important;" class="text-right"><?php echo number_format($total,2)?></td>
                    <td></td>
                </tr>
                </tbody>
                <?php endif;?>
                <?php if($SalesReturn->count() > 0):?>
                <thead>
                <tr>
                    <th colspan="7" class="text-center"><strong style="font-size: 25px !important;">Sales Return</strong></th>
                </tr>
                <th class="text-center col-sm-1">S.No</th>
                <th class="text-center col-sm-1">SO No</th>
                <th class="text-center col-sm-1">CR No</th>
                <th class="text-center col-sm-1">CR Date</th>
                <th class="text-center col-sm-2">Buyer</th>
                <th class="text-center col-sm-1">Total Amount</th>
                <th class="text-center col-sm-1">View</th>
                </thead>
                <tbody id="data">
                <?php $counter = 1;$total=0;

                ?>

                @foreach($SalesReturn->get() as $row)
                    <?php
                    $data=SalesHelper::get_total_amount_for_credit_note_by_id($row->id);
                    $SoNo = "";
                    if($row->so_id > 0):
                        $SoData = CommonHelper::get_single_row('sales_order','id',$row->so_id);
                        $SoNo = $SoData->so_no;
                    else:
                        $SoNo = "";
                    endif;
                    ?>
                    <tr title="{{$row->id}}" id="">
                        <td class="text-center">{{$counter++}}</td>
                        <td class="text-center"><?php echo strtoupper($SoNo)?></td>
                        <td title="{{$row->id}}" class="text-center">{{strtoupper($row->cr_no)}}</td>
                        <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->cr_date);?></td>
                        <td class="text-center"><?php $customer=CommonHelper::byers_name($row->buyer_id);
                            echo   $customer->name;
                            ?></td>
                        <td class="text-right">{{number_format($data->amount+$row->sales_tax,2)}}<?php $total+=$data->amount+$row->sales_tax;?></td>
                        <td class="text-center"><button
                                    onclick="showDetailModelOneParamerter('sales/viewCreditNoteDetail','<?php echo $row->id ?>','View Sales Tax Invoice','<?php echo $m?>')"
                                    type="button" class="btn btn-success btn-xs">View</button>
                        </td>
                    </tr>


                @endforeach
                <tr>
                    <td colspan="5" class="text-center"><strong style="font-size: 20px !important;">TOTAL</strong></td>
                    <td style="font-size: 20px !important;" class="text-right"><?php echo number_format($total,2)?></td>
                    <td></td>
                </tr>

                </tbody>
                <?php endif;?>

            </table>
        </div>
    </div>
</div>







