<?php
use App\Helpers\CommonHelper;

//print_r($stock); die;

//foreach($stock as $val){
//$stock = DB::Connection('mysql2')->select('SELECT * FROM stock WHERE status=1 AND sub_item_id = '.$val->sub_item_id.' AND voucher_date between "'.$from_date.'" and "'.$to_date.'" '.$region.' ');
?>

<h5 style="text-align: center"> From : {{CommonHelper::changeDateFormat($from_date)}}  To: {{CommonHelper::changeDateFormat($to_date)}}</h5>
<?php $to= date('Y-m-d', strtotime('-1 day', strtotime($from_date)));
        $from='2020-06-30';
?>
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
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>
    <th class="text-center">Action</th>
    <td></td>
    </thead>
    <tbody id="filterDemandVoucherList">
    <?php $counter=1; $purchase_qty=0; $amount=0;  ?>
    @foreach($stock as $stock_data)


        <?php
      $rate_data=  DB::Connection('mysql2')->select('select rate from stock where status=1 and
        voucher_date BETWEEN "'.$from.'" and "'.$to.'"
        and rate>0 and sub_item_id="'.$stock_data->sub_item_id.'"');
         $count=count($rate_data);

       //$rate_data1=  DB::Connection('mysql2')->select('select (sum(amount)/sum(qty)) as ratee from stock where status=1 and--}}
     //   voucher_date BETWEEN "'.$from.'" and "'.$to_date.'"--}}
      // and rate>0 and amount>0 and sub_item_id="'.$stock_data->sub_item_id.'"');


        $rate_data1=  DB::Connection('mysql2')->select('select (sum(amount)/sum(qty)) as ratee from stock where status=1 and
        voucher_date BETWEEN "'.$from.'" and "'.$stock_data->grn_date.'"
        and voucher_type=1
        and transfer=0
        and rate>0 and amount>0 and sub_item_id="'.$stock_data->sub_item_id.'"');


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
            <td class="text-center">{{ $item }}</td>
            <td class="text-center">{{ $supplier }}</td>
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
            <td class="text-center">{{$stock_data->purchase_recived_qty }}</td>

            <?php $stock_rate=DB::Connection('mysql2')->table('stock')->where('master_id',$stock_data->id)->where('voucher_type',1)->select('rate')->first(); ?>
            <td @if($stock_rate->rate!=$stock_data->rate) style="color: red" @endif class="text-center">{{number_format($stock_data->rate,2)}}</td>
            <td class="<?php if($stock_data->amount > 0){echo "text-right";}?>" >{{ number_format($stock_data->amount,2) }}</td>
            <td style="width: 150px;">
                <input type="hidden" value="<?php echo $stock_data->purchase_recived_qty?>" id="GrnQty<?php echo $stock_data->id?>">
                <input type="number" class="form-control" id="GrnRate<?php echo $stock_data->id?>" onkeyup="calcGrn('<?php echo $stock_data->id?>')" value="<?php echo $stock_data->rate;?>">
            </td>
            <td style="width: 150px;"><input type="number" class="form-control" id="GrnAmount<?php echo $stock_data->id?>" value="<?php echo $stock_data->amount;?>" readonly></td>
            <td>
                <button type="button" class="btn btn-xs btn-primary" id="BtnSubmit" onclick="UpdateRateAmountGrn('<?php echo $stock_data->id?>')">Update</button>
            </td>
            <td>
                <?php  if (!empty($rate_data)): echo end($rate_data)->rate; endif;

                if (!empty($rate_data1)): echo '('.number_format(end($rate_data1)->ratee,2).')'; endif
                ?></td>

        </tr>
        <script !src="">
            calcGrn('<?php echo $stock_data->id?>')
        </script>
    @endforeach
    <tr style="background-color: darkgray;font-size: larger;font-weight: bold">
        <td colspan="7">  </td>
        <td class="text-right">{{number_format($purchase_qty,2)}}</td>
        <td></td>
        <td class="text-center">{{number_format($amount,2)}}</td>
        <td colspan="2"></td>
    </tr>

    </tbody>
</table>


<h5 style="text-align: center"> Goods Isuued</h5>

<table style="font-size: small"  class="table table-bordered table-responsive">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>

    <th class="text-center">Voucher No</th>
    <th class="text-center">Voucher Date</th>
    <th class="text-center">Qty</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>
    <th class="text-center">Action</th>
    <th class="text-center">Auto Rate</th>
    </thead>
    <tbody id="filterDemandVoucherList">
    <?php
    $counter=1;
    $issue_qty=0;
    $iss_amount = 0;
    ?>
    @foreach($issuence as $row)

        <?php

        $rate_data=  DB::Connection('mysql2')->select('select rate from stock where status=1 and
        voucher_date BETWEEN "'.$from.'" and "'.$row->iss_date.'"
        and rate>0 and sub_item_id="'.$row->sub_item_id.'"  and voucher_type=1 order by voucher_date ASC');





        $rate_data1=  DB::Connection('mysql2')->select('select (sum(amount)/sum(qty)) as ratee from stock where status=1 and
        voucher_date BETWEEN "'.$from.'" and "'.$row->iss_date.'"
        and voucher_type=1
    and transfer=0
        and rate>0 and amount>0 and sub_item_id="'.$row->sub_item_id.'"');

      $average=  round(end($rate_data1)->ratee);
      $database_set=  round($row->rate);
        ?>
        <tr @if($average!=$database_set) style="background-color: antiquewhite" @endif @if($row->rate==0) style="background-color: red" @endif title="{{$row->sub_item_id}}">
            <td>{{$counter++}}</td>
            <td><small>{{CommonHelper::get_item_name($row->sub_item_id)}}</small></td>
            <td>{{$row->iss_no}}</td>

            <td>{{date('d-M-Y',strtotime($row->iss_date))}}</td>

            <td>{{number_format($row->qty,2)}}</td>
            <td>
                <?php echo number_format($row->rate,2)?>
            </td>
            <td class="<?php if($row->amount > 0){echo "text-right";}?>">
                <?php echo number_format($row->amount,2)?>
            </td>
            <td>
                <input type="hidden" value="<?php echo $row->qty?>" id="Qty<?php echo $row->id?>">
                <input type="number" class="form-control" id="Rate<?php echo $row->id?>" onkeyup="calc('<?php echo $row->id?>')" value="<?php echo $row->rate;?>">
            </td>
            <td><input type="number" class="form-control" id="Amount<?php echo $row->id?>" value="<?php echo $row->amount;?>" readonly></td>
            <td>
                <button type="button" class="btn btn-xs btn-primary" id="BtnSubmit" onclick="UpdateRateAmount('<?php echo $row->id?>')">Update</button>
            </td>
            <td><?php  if (!empty($rate_data)): echo end($rate_data)->rate; endif;


                if (!empty($rate_data1)): echo '('.number_format(end($rate_data1)->ratee,2).')'; endif
                ?></td>
            <?php $issue_qty+=$row->qty;  ?>
            <?php $iss_amount+=$row->amount;  ?>
        </tr>
    @endforeach
    <tr style="background-color: darkgray;font-size: larger;font-weight: bold">
        <td colspan="4">  </td>
        <td class="text-center">{{number_format($issue_qty,2)}}</td>
        <td class="text-center"></td>
        <td class="text-right"><?php echo number_format($iss_amount,2)?></td>
        <td colspan="2"></td>
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
    <th class="text-center">Rate</th>
    <th class="text-center">Amount</th>
    <th class="text-center">Action</th>
    </thead>
    <tbody id="filterDemandVoucherList">
    <?php
    $counter=1;
    $return_qty=0;
    $return_amount=0;
    ?>
    @foreach($return as $row)

        <?php

        $rate_data=  DB::Connection('mysql2')->select('select rate from stock where status=1 and
        voucher_date BETWEEN "'.$from.'" and "'.$to_date.'"
        and rate>0 and sub_item_id="'.$row->sub_item_id.'" order by voucher_date ASC');


        $rate_data1=  DB::Connection('mysql2')->select('select (sum(amount)/sum(qty)) as ratee from stock where status=1 and
        voucher_date BETWEEN "'.$from.'" and "'.$row->iss_date.'"
        and voucher_type=1
        and transfer=0
        and rate>0 and amount>0 and sub_item_id="'.$row->sub_item_id.'"');

        $average=  number_format(end($rate_data1)->ratee,2);
        $database_set=  number_format($row->rate,2);

        ?>
        <tr @if($row->rate==0) style="background-color: red" @endif @if($average!=$database_set) style="background-color: antiquewhite" @endif>
            <td>{{$counter++}}</td>
            <td><small>{{CommonHelper::get_item_name($row->sub_item_id)}}</small></td>
            <td style="width: 120px;">{{$row->description}}</td>
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
            <td>
                <input type="hidden" value="<?php echo $row->qty?>" id="ReturnQty<?php echo $row->stock_return_data_id?>">
                <input type="number" class="form-control" id="ReturnRate<?php echo $row->stock_return_data_id?>" onkeyup="calcReturn('<?php echo $row->stock_return_data_id?>')" value="<?php echo $row->rate;?>">
            </td>
            <td><input type="number" class="form-control" id="ReturnAmount<?php echo $row->stock_return_data_id?>" value="<?php echo $row->amount;?>" readonly></td>
            <td>
                <button type="button" class="btn btn-xs btn-primary" id="BtnSubmit" onclick="UpdateRateAmountReturn('<?php echo $row->stock_return_data_id?>')">Update</button>
            </td>

            <td><?php  if (!empty($rate_data)): echo end($rate_data)->rate; endif;


                if (!empty($rate_data1)): echo '('.number_format(end($rate_data1)->ratee,2).')'; endif
                ?></td>

            <?php $return_qty+=$row->qty;  ?>
            <?php $return_amount+=$row->amount;  ?>
        </tr>
    @endforeach
    <tr style="background-color: darkgray;font-size: larger;font-weight: bold">
        <td colspan="7">  </td>
        <td class="text-center">{{number_format($return_qty,2)}}</td>
        <td class="text-center"></td>
        <td class="text-right"><?php echo number_format($return_amount,2)?></td>
    </tr>

    </tbody>
</table>

<h4>Good received {{$purchase_qty}} - Goods Issued {{$issue_qty}} + Goods Return {{$return_qty}} =  {{number_format($purchase_qty-$issue_qty+$return_qty,2)}}</h4>
<?php
//}

?>


