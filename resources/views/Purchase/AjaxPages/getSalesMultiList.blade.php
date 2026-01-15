<?php
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\SalesHelper;
use App\Helpers\FinanceHelper;


if($tab == 'so'):?>

<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
        <thead>
            <th colspan="10" class="text-center"><strong>
                    <?php if($contion == 1): echo "Sales Order (Open)"; elseif($contion == 2): echo "Sales Order (Partial)"; elseif($contion == 3): echo "Sales Order (Complete)"; else: endif;?></strong>
            </th>
        </thead>
        <thead>
        <th class="text-center col-sm-1">S.No</th>
        <th class="text-center col-sm-1">SO No</th>
        <th class="text-center col-sm-1">SO Date</th>
        <th class="text-center col-sm-1">Mode / Terms Of Payment</th>
        <th class="text-center col-sm-1">Buyer's Order NO </th>
        <th class="text-center col-sm-1">Order Date</th>
        <th class="text-center">Customer</th>
        <th class="text-center">Total Amount</th>
        <th class="text-center">SO Status</th>
        <th class="text-center">Action</th>
        </thead>
        <tbody>
        <?php
        $view=ReuseableCode::check_rights(27);
        $edit=ReuseableCode::check_rights(28);
        $delete=ReuseableCode::check_rights(29);
        $counter = 1;$total=0;
        $open=0;
        $parttial=0;
        $complete=0;
        ?>

        @foreach($MultiData as $row)
            <?php $customer=CommonHelper::byers_name($row->buyers_id);
            $data=SalesHelper::get_total_amount_for_sales_order_by_id($row->id);
            $status='';
            $diffrence=round($data->amount-$data->dn_amount);
            $status='all';
            if ($data->dn_amount==''):
                $status='Open';
                $open++;
            elseif($data->dn_amount!='' && $diffrence!=0):
                $status='partial';
                $parttial++;
            elseif($diffrence==0):
                $status='Complete';
                $complete++;
            endif;

            $validation='all';
            if ($contion==1):
                if ($data->dn_amount==''):
                    $validation=$status;
                endif;
            endif;;
            if ($contion==2):
                if($data->dn_amount!='' && $diffrence!=0):
                    $validation=$status;
                endif; endif;;
            if ($contion==3):
                if($diffrence==0):
                    $validation=$status;

                endif; endif;;
            if ($contion==''):
                $validation=$status;
            endif;






            ?>
            @if ($validation==$status )
                <tr @if($status=='Open') style="background-color: #fdc8c8" @endif title="{{$row->id}}" id="{{$row->id}}">
                    <td class="text-center">{{$counter++}}</td>
                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->so_date);?></td>
                    <td class="text-center">{{$row->model_terms_of_payment}}</td>
                    <td class="text-center">{{$row->order_no}}</td>
                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
                    <td class="text-center">{{$customer->name}}</td>
                    <td class="text-right">{{number_format($data->amount+$data->sales_tax,2) }} <?php $total += $data->amount+$data->sales_tax;?></td>
                    <td>{{$status}}</td>

                    <td class="text-center">
                        @if ($view==true)
                            <button onclick="showDetailModelOneParamerter('sales/viewSalesOrderDetail','<?php echo $row->id ?>','View Sales Order')"
                                    type="button" class="btn btn-success btn-xs">View</button>
                        @endif
                        @if ($edit==true)
                            <a href="{{ URL::asset('sales/EditSalesOrder/'.$row->id.'?m='.$_GET['m']) }}" class="btn btn-warning btn-xs">Edit </a>
                        @endif
                        @if ($delete==true)
                            <button onclick="sale_order_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                                    type="button" class="btn btn-primery btn-xs">Delete</button>
                        @endif
                    </td>

                    {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
                </tr>
            @endif
            <?php
            // for open
            ?>

        @endforeach


        <tr>
            <td class="text-center" colspan="7" style="font-size: 20px;">Total</td>
            <td class="text-right" colspan="1" style="font-size: 20px;color: white">{{number_format($total,2)}}</td>
            <td class="text-center" colspan="2" style="font-size: 20px;"></td>

        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'dn'):?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
        <thead>
        <th colspan="12" class="text-center"><strong>
                <?php if($contion == 1): echo "Delivery Note (Open)"; elseif($contion == 2): echo "Delivery Note (Partial)"; elseif($contion == 3): echo "Delivery Note (Complete)"; else: endif;?></strong>
        </th>
        </thead>
        <thead>
        <th class="text-center col-sm-1">S.No</th>
        <th class="text-center col-sm-1">SO No</th>
        <th class="text-center col-sm-1">DN No</th>
        <th class="text-center col-sm-1">DN Date</th>
        <th class="text-center col-sm-1">Mode / Terms Of Payment</th>
        <th class="text-center col-sm-1">Order Date</th>
        <th class="text-center">Customer</th>
        <th class="text-center">Total Qty.</th>
        <th class="text-center">Total Amount.</th>
        <th class="text-center">DN Status</th>
        <th class="text-center">View</th>
        <th class="text-center">Action</th>
        </thead>
        <tbody id="data">
        <?php $counter = 1;$total=0;
        $open=0;
        $parttial=0;
        $complete=0;
        ?>

        @foreach($MultiData as $row)

            <?php

            $data1=SalesHelper::get_total_amount_for_dn_by_id($row->id);
            $status='';
            $diffrence=round($data1->amount-$data1->dn_amount);
            $status='all';
            if ($data1->dn_amount==''):
                $status='Open';
            //    $open++;
            elseif($data1->dn_amount!='' && $diffrence!=0):
                $status='partial';
            //   $parttial++;
            elseif($diffrence==0):
                $status='Complete';
                //  $complete++;
            endif;




            $validation='all';
            if ($contion==1):
                if ($data1->dn_amount==''):
                    $validation=$status;
                endif;
            endif;
            if ($contion==2):
                if($data1->dn_amount!='' && $diffrence!=0):
                    $validation=$status;
                endif; endif;
            if ($contion==3):
                if($diffrence==0):
                    $validation=$status;

                endif; endif;;
            if ($contion==''):
                $validation=$status;
            endif;


            $data=SalesHelper::get_total_amount_for_delivery_not_by_id($row->id);


            ?>



            <?php $customer=CommonHelper::byers_name($row->buyers_id); ?>
            @if ($validation==$status )
                <tr @if($status=='Open') style="background-color: #fdc8c8" @elseif($status=='partial') style="background-color: #c9d6ec" @endif title="{{$row->id}}" id="{{$row->id}}">
                    <td class="text-center">{{$counter++}}</td>
                    <td class="text-center"><?php echo  strtoupper($row->so_no) ?></td>
                    <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gd_no)}}</td>
                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->gd_date);?></td>
                    <td class="text-center">{{$row->model_terms_of_payment}}</td>
                    <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
                    <td class="text-center">{{$customer->name}}</td>
                    <td class="text-right">{{number_format($data->qty,3)}}</td>
                    <td class="text-right">{{number_format($data->amount+$row->sales_tax_amount,3)}}</td>
                    <td>{{$status}}</td>

                    <td class="text-center"><button
                                onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $row->id ?>','','View Delivery Note')"
                                type="button" class="btn btn-success btn-xs">View</button></td>
                    <td class="text-center">
                        <button onclick="delivery_note('<?php echo $row->id?>','<?php echo $m ?>')"
                                type="button" class="btn btn-primery btn-xs">Edit Delivery Note</button>
                        <button onclick="delivery_note_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                                type="button" class="btn btn-primery btn-xs">Delete</button>

                    </td>
                    {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
                    {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
                </tr>
            @endif


        @endforeach



        </tbody>
    </table>
</div>
<?php elseif($tab == 'sti'):?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
        <thead>
            <th colspan="12">Sales Tax Invoice</th>
        </thead>
        <thead>
        <th class="text-center col-sm-1">S.No</th>
        <th class="text-center col-sm-1">SO No</th>
        <th class="text-center col-sm-1">SI No</th>
        <th class="text-center col-sm-1">Buyer's Unit</th>
        <th class="text-center col-sm-1">Buyer's Order No</th>
        <th class="text-center col-sm-1">SI Date</th>
        <th class="text-center col-sm-1">Model Terms Of Payment</th>
        <th class="text-center col-sm-1">Order Date</th>
        <th class="text-center">Customer</th>
        <th class="text-center">Total Amount</th>
        <th class="text-center">View</th>
        <th class="text-center">Action</th>
        </thead>
        <tbody id="data">
        <?php
        $counter = 1;$total=0;
        $main_total=0; ?>
        @foreach($MultiData as $row)
            <?php $customer=CommonHelper::byers_name($row->buyers_id);
            $data=SalesHelper::get_total_amount_for_sales_tax_invoice_by_id($row->id);
            $freight=SalesHelper::get_freight($row->id);
            $BuyersUnit = '';
            $BuyerOrderNo = '';
            if($row->so_id != 0 ):
                $SoData = DB::Connection('mysql2')->table('sales_order')->where('id',$row->so_id)->select('order_no','buyers_unit')->first();
                $BuyersUnit = $SoData->buyers_unit;
                $BuyerOrderNo = $SoData->order_no;
            endif;

            ?>
            <tr title="{{$row->id}}" id="{{$row->id}}">
                <td class="text-center">{{$counter++}}</td>
                <td title="{{$row->id}}" class="text-center">{{strtoupper($row->so_no)}}</td>
                <td title="{{$row->id}}" class="text-center">{{strtoupper($row->gi_no)}}</td>
                <td class="text-center"><?php echo $BuyersUnit?></td>
                <td class="text-center"><?php echo $BuyerOrderNo?></td>
                <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->gi_date);?></td>
                <td class="text-center">{{$row->model_terms_of_payment}}</td>
                <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->order_date);?></td>
                <td class="text-center">{{$customer->name}}</td>
                <td class="text-right">{{number_format($data->amount+$row->sales_tax+$freight,2)}}</td>
                <?php $main_total+=$data->amount+$row->sales_tax+$freight; ?>

                <td class="text-center"><button
                            onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                            type="button" class="btn btn-success btn-xs">View</button></td>
                <td class="text-center">
                    <button onclick="sales_tax('<?php echo $row->id?>','<?php echo $m ?>')"
                            type="button" class="btn btn-primery btn-xs">Edit Sales Tax Invoice</button>
                    <button onclick="sales_tax_delete('<?php echo $row->id?>','<?php echo $m ?>')"
                            type="button" class="btn btn-primery btn-xs">delete</button>
                </td>
                {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
                {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
            </tr>


        @endforeach
        <tr style="font-size: large;font-weight: bold">
            <td colspan="7">Total</td>
            <td class="text-center">{{number_format($main_total,2)}}</td>
        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'srv'):?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
        <thead>
        <th colspan="10" class="text-center"><?php if($contion == 1):echo 'Sales Receipt Voucher (Pending)'; elseif($contion == 2): echo 'Sales Receipt Voucher (Approved)'; else: endif;?></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">R.V. No.</th>
        <th class="text-center">R.V. Date</th>
        <th class="text-center">Cheque No</th>
        <th class="text-center">Cheque Date</th>
        <th class="text-center">Received Amount</th>
        <th class="text-center">Tax Amount</th>
        <th class="text-center">Debit/Credit</th>
        <th class="text-center">Voucher Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody>
        <?php
        $counter = 1;
        $makeTotalAmount = 0;
        foreach ($MultiData as $row1) {

        $received_data=   SalesHelper::get_received_data($row1->id);
        ?>
        <tr class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>">
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
            <td class="text-center"><?php echo $row1->cheque_no;?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
            <td class="text-center">{{number_format($received_data->net_amount,2)}}</td>
            <td class="text-center">{{number_format($received_data->tax_amount,2)}}</td>
            <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_rv_data',$row1->id);?></td>

            <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
                <?php if($row1->rv_status == 1):?>
                <span class="badge badge-warning " style="background-color: #fb3 !important;">Pending</span>
                <?php else:?>
                <span class="badge badge-success" style="background-color: #00c851 !important">Approved</span>
                <?php endif;?>
            </td>

            <td class="text-center hidden-print">
                <a onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','<?php echo $row1->id;?>','View Bank Reciept Voucher Detail','<?php echo $m?>','')" class="btn btn-xs btn-success">View</a>
                <input class="btn btn-xs btn-danger BtnHide<?php echo $row1->rv_no?>" type="button"
                       onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data',$row1->id)?>')"
                       value="Delete" />
                @if($row1->rv_status==1)
                    <a href="<?php echo url('/sales/editVoucherList') ?>?id=<?php echo $row1->id;?>&&m=<?php echo $m?>"> Edit</a>
                @endif

            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="8" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'srt'):?>
<div class="table-responsive">
    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
        <thead>
            <th colspan="7" class="text-center">Sales Return</th>
        </thead>
        <thead>
        <th class="text-center col-sm-1">S.No</th>
        <th class="text-center col-sm-1">SO No</th>
        <th class="text-center col-sm-1">Type</th>
        <th class="text-center col-sm-1">CR No</th>
        <th class="text-center col-sm-1">CR Date</th>
        <th class="text-center col-sm-2">Buyer</th>
        <th class="text-center col-sm-1">View</th>

        </thead>
        <tbody id="data">
        <?php $counter = 1;$total=0;

        ?>

        @foreach($MultiData as $row)
            <?php $customer=CommonHelper::byers_name($row->buyer_id);
            $data=SalesHelper::get_total_amount_for_sales_order_by_id($row->so_id);
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
                <td class="text-center">@if($row->type==1) DN @else SI @endif</td>
                <td title="{{$row->id}}" class="text-center">{{strtoupper($row->cr_no)}}</td>
                <td class="text-center"><?php  echo CommonHelper::changeDateFormat($row->cr_date);?></td>
                <td class="text-center"><?php   $customer=CommonHelper::byers_name($row->buyer_id);
                    echo   $customer->name;
                    ?></td>



                <td class="text-center"><button
                            onclick="showDetailModelOneParamerter('sales/viewCreditNoteDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                            type="button" class="btn btn-success btn-xs">View</button>
                    <a href="{{ URL::asset('sales/editSalesReturn/'.$row->id.'?m='.$m) }}" class="btn btn-primary btn-xs">Edit </a>
                </td>

                {{--<td class="text-center"><button onclick="DeleteSalesReturn('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
            </tr>


        @endforeach



        </tbody>
    </table>
</div>
<?php else:?>
<?php endif;?>
