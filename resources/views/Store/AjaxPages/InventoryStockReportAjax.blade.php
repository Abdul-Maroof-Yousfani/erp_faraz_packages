<?php
use App\Helpers\CommonHelper;
//print_r($stock); die;

//foreach($stock as $val){
//$stock = DB::Connection('mysql2')->select('SELECT * FROM stock WHERE status=1 AND sub_item_id = '.$val->sub_item_id.' AND voucher_date between "'.$from_date.'" and "'.$to_date.'" '.$region.' ');
?>

    <h5 style="text-align: center"> From : {{CommonHelper::changeDateFormat($from_date)}}  To: {{CommonHelper::changeDateFormat($to_date)}}</h5>
<h5 style="text-align: center"> Goods Received</h5>
<table style="font-size: small;"  class="table table-bordered table-responsive" >
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>
    <th class="text-center">Supplier Name</th>
    <th class="text-center">Voucher No</th>
    <th class="text-center">Voucher Date</th>
    <th class="text-center">Region</th>
    <th class="text-center">Grn Type</th>
    <th class="text-center">Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>
    </thead>
    <tbody id="filterDemandVoucherList">
    <?php $counter=1; $purchase_qty=0; $amount=0; $total=0; ?>
    @foreach($stock as $stock_data)
        <?php
        $amount += $stock_data->amount;
        $item = CommonHelper::get_item_name($stock_data->sub_item_id);
        $Region = CommonHelper::get_rgion_name_by_id($stock_data->region);

        $purchase_qty += $stock_data->purchase_recived_qty;
        if($stock_data->supplier_id!=""):
            $supplier = CommonHelper::get_single_row('supplier','id',$stock_data->supplier_id);
            $supplier = $supplier->name;
        else:
            $supplier = "";
        endif;

        ?>
        <tr>
            <td class="text-center">{{$counter++}}</td>
            <td class="text-center" style="width: 30%">{{ $item }}</td>
            <td class="text-center" style="width: 30%">{{ $supplier }}</td>
            <td class="text-center">{{ ($stock_data->grn_no!="")?$stock_data->grn_no:"opening" }}</td>
            <td class="text-center">{{ date('d-M-Y',strtotime($stock_data->grn_date)) }}</td>
            <td class="text-center">{{ $Region->region_name }}</td>
            <td class="text-center text-danger" style="width: 140px;">
                <?php if($stock_data->type == 0){echo "Through Purchase Order";}
                elseif($stock_data->type == 2){echo "Direct Grn";}
                elseif($stock_data->type == 3){echo "With Issuance";}
                else{echo "";}
                ?>
            </td>
            <td class="text-right">{{number_format($stock_data->purchase_recived_qty,2) }}</td>
            <td class="text-right">{{number_format($stock_data->rate,2) }}</td>
            <td class="<?php if($stock_data->amount > 0){echo "text-right";}?>" style="width: 15%">{{ number_format($stock_data->amount,2) }}</td>
        </tr>
    @endforeach
    <tr style="background-color: darkgray;font-size: larger;font-weight: bold">
        <td colspan="7">  </td>
        <td class="text-right">{{number_format($purchase_qty,2)}}</td>
        <td></td>
        <?php $total+=$amount; ?>
        <td class="text-right">{{number_format($amount,2)}}</td>
    </tr>

    </tbody>
</table>


<h5 style="text-align: center"> Goods Isuued</h5>

<table style="font-size: small"  class="table table-bordered table-responsive">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>

    <th class="text-center">Description</th>
    <th class="text-center">Voucher No</th>
    <th class="text-center">Voucher Date</th>
    <th class="text-center">Region</th>
    <th class="text-center">Issuance Type</th>
    <th class="text-center">Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>

    </thead>
    <tbody id="filterDemandVoucherList">
    <?php
    $counter=1;
    $issue_qty=0;
    $iss_amount = 0;
    ?>
    @foreach($issuence as $row)
       <tr>
           <td>{{$counter++}}</td>
           <td>{{CommonHelper::get_item_name($row->sub_item_id)}}</td>
           <td>{{$row->description}}</td>
           <td>{{$row->iss_no}}</td>

           <td>{{date('d-M-Y',strtotime($row->iss_date))}}</td>
           <td>{{CommonHelper::get_rgion_name_by_id($row->region)->region_name}}</td>
           <td style="width: 140px;" class="text-danger">
               <?php if($row->issuance_type == 1){echo "Issue With Job Order";}
               elseif($row->issuance_type == 2){echo "Issue With Delivery Challan";}
               elseif($row->issuance_type == 3){echo "Issue With Out Job Order";}
               elseif($row->issuance_type == 4){echo "Issue Damage Stock";}
                   else{echo "Issue Delivery Challan Damage";}
               ?>
           </td>
           <td>{{number_format($row->qty,2)}}</td>
           <td>
            <?php echo number_format($row->rate,2)?>
           </td>
           <td class="<?php if($row->amount > 0){echo "text-right";}?>">
               <?php echo number_format($row->amount,2)?>
           </td>

           <?php $issue_qty+=$row->qty;  ?>
           <?php $iss_amount+=$row->amount;  ?>
       </tr>
    @endforeach
    <tr style="background-color: darkgray;font-size: larger;font-weight: bold">
        <td colspan="7">  </td>
        <td class="text-center">{{number_format($issue_qty,2)}}</td>
        <td class="text-center"></td>

        <?php  ?>
        <td class="text-right"><?php echo number_format($iss_amount,2)?></td>
    </tr>

    </tbody>
</table>


<h5 style="text-align: center"> Goods Return</h5>

<table style="font-size: small"  class="table table-bordered table-responsive">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>

    <th class="text-center">Description</th>
    <th class="text-center">Voucher No</th>
    <th class="text-center">Voucher Date</th>
    <th class="text-center">Region</th>
    <th class="text-center">Return Type</th>
    <th class="text-center">Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>
    </thead>
    <tbody id="filterDemandVoucherList">
    <?php
    $counter=1;
    $return_qty=0;
    $return_amount=0;
    ?>
    @foreach($return as $row)
        <tr title="">
            <td>{{$counter++}}</td>
            <td>{{CommonHelper::get_item_name($row->sub_item_id)}}</td>
            <td>{{$row->description}}</td>
            <td>{{$row->iss_no}}</td>

            <td>{{date('d-M-Y',strtotime($row->iss_date))}}</td>
            <td>{{CommonHelper::get_rgion_name_by_id($row->region)->region_name}}</td>
            <td style=" width: 140px;" class="text-danger">
                <?php if($row->issuance_type == 1){echo "Return Without Job Order";}
                elseif($row->issuance_type == 3){echo "Return Without Job Order";}
                elseif($row->issuance_type == 4){echo "Return Damage Stock";}
                else{echo "";}
                ?>
            </td>
            <td><?php echo number_format($row->qty ,2)?></td>
            <td>
                <?php echo number_format($row->rate,2)?>
            </td>
            <td class="<?php if($row->amount > 0){echo "text-right";}?>"><?php echo number_format($row->amount,2);?></td>

            <?php $return_qty+=$row->qty;  ?>
            <?php $return_amount+=$row->amount;  ?>
        </tr>
    @endforeach
    <tr style="background-color: darkgray;font-size: larger;font-weight: bold">
        <td colspan="7">  </td>
        <td class="text-center">{{number_format($return_qty,2)}}</td>
        <td class="text-center"></td>
        <?php $total+=$return_amount; ?>
        <td class="text-right"><?php echo number_format($return_amount,2)?></td>
    </tr>

    <tr style="background-color: yellow;font-size: larger;font-weight: bold">
        <td colspan="9"> Net Amount </td>

        <td class="text-right"><?php echo number_format($total-$iss_amount,2)?></td>
    </tr>
    </tbody>
</table>

<h4>Good received {{$purchase_qty}} - Goods Issued {{$issue_qty}} + Goods Return {{$return_qty}} =  {{number_format($purchase_qty-$issue_qty+$return_qty,2)}}</h4>
<?php
//}

?>


