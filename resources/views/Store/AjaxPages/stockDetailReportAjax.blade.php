<?php
use App\Helpers\CommonHelper;
//print_r($stock); die;
if($region!=""):
    $region =  'AND region_id='.$region.'';
else:
    $region = '';
endif;
$amount=0;
foreach($stock as $val){
$stock = DB::Connection('mysql2')->select('SELECT * FROM stock WHERE status=1 AND sub_item_id = '.$val->sub_item_id.' AND voucher_date between "'.$from_date.'" and "'.$to_date.'" '.$region.' ');
?>
<h3>{{$val->name}}</h3>
<table class="table table-bordered table-responsive">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>
    <th class="text-center">Supplier Name</th>
    <th class="text-center">Voucher No</th>
    <th class="text-center">Voucher Date</th>
    <th class="text-center">Region</th>
    <th class="text-center">Qty</th>
    <th class="text-center">Amount</th>
    </thead>
    <tbody id="filterDemandVoucherList">
    <?php $counter=1; $item_amt=0; $purchase_qty=0; $iss_qty=0; $ret_qty=0; ?>
    @foreach($stock as $stock_data)
        <?php
        $amount += $stock_data->amount;
        $item_amt += $stock_data->amount;
        $Region = CommonHelper::get_rgion_name_by_id($stock_data->region_id);

        if($stock_data->voucher_type==1):
           $purchase_qty += $stock_data->qty;
            if($stock_data->supplier_id!=""):
                $supplier = CommonHelper::get_single_row('supplier','id',$stock_data->supplier_id);
                $supplier = $supplier->name;
            elseif($stock_data->voucher_no!=""):
                $supplier = "Transfer";
            else:
                $supplier = "Opening";
            endif;
        elseif($stock_data->voucher_type==2):
            $iss_qty += $stock_data->qty;
            $supplier = "Issuance";
        elseif($stock_data->voucher_type==3):
            $ret_qty += $stock_data->qty;
            $supplier = "Return";
        endif;

        ?>
        <tr>
            <td class="text-center">{{$counter++}}</td>
            <td class="text-center" style="width: 30%">{{ $val->name }}</td>
            <td class="text-center" style="width: 30%">{{ $supplier }}</td>
            <td class="text-center">{{ ($stock_data->voucher_no!="")?$stock_data->voucher_no:"opening" }}</td>
            <td class="text-center">{{ date('m-d-Y',strtotime($stock_data->voucher_date)) }}</td>
            <td class="text-center">{{ $Region->region_name }}</td>
            <td class="text-center">{{$stock_data->qty }}</td>
            <td class="text-center" style="width: 15%">{{ $stock_data->amount }}</td>
        </tr>
    @endforeach

        <tr style="background:grey;">
            <td colspan="6">Total Amount</td>
            <td>{{ $purchase_qty-$iss_qty+$ret_qty }}</td>
            <td class="text-center"><strong> {{ number_format($item_amt,2) }} </strong></td>
        </tr>
    </tbody>
</table>

<?php
}

?>
<table class="table table-bordered table-responsive">
    <thead>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
    </thead>
    <tbody id="">
        <tr>
            <td colspan="7">Sub Total Amount</td>
            <td>{{ number_format($amount,2) }}</td>
        </tr>
    </tbody>
</table>

