<?php
use App\Helpers\CommonHelper;
$from = $_GET['from'];
$to = $_GET['to'];
//die;

    $data = DB::Connection('mysql2')->select('SELECT subitem.id as id, subitem.sub_ic as sub_ic, subitem.rate as rate FROM subitem
                                    INNER JOIN stock ON stock.sub_item_id = subitem.id
                                    WHERE stock.voucher_date BETWEEN "'.$from.'" AND "'.$to.'" AND opening=0 AND stock.status=1 AND subitem.status=1 group by subitem.id');
$sumQty=0;
$sumAmount=0;
foreach($data as $datas):
$id = $datas->id;
$rate = $datas->rate;

$stock_grn = DB::Connection('mysql2')->select('select sum(qty) as qty from stock where voucher_date BETWEEN "'.$from.'" AND "'.$to.'"
                                       and voucher_type=1 and status=1 and opening=0 AND sub_item_id = '.$datas->id.' group by sub_item_id ');

$stock_iss = DB::Connection('mysql2')->select('select sum(qty) as qty from stock where voucher_date BETWEEN "'.$from.'" AND "'.$to.'"
                                       and voucher_type=2 and status=1 and opening=0 AND sub_item_id = '.$datas->id.' group by sub_item_id ');

$stock_ret = DB::Connection('mysql2')->select('select sum(qty) as qty from stock where voucher_date BETWEEN "'.$from.'" AND "'.$to.'"
                                       and voucher_type=3 and status=1 and opening=0 AND sub_item_id = '.$datas->id.' group by sub_item_id ');
//$qty=0;
$grn=0;     $grn_amt=0;
$iss=0;     $iss_amt=0;
$ret=0;     $ret_amt=0;
foreach ($stock_grn as $row):
    $grn = $row->qty;
    //$grn_rate = $row->rate;
endforeach;

foreach ($stock_iss as $row):
    $iss = $row->qty;
   // $iss_amt = $row->rate;
endforeach;

foreach ($stock_ret as $row):
    $ret = $row->qty;
    //$ret_amt = $row->rate;
endforeach;

//$stock_grn = DB::Connection('mysql2')->table('stock')->select('id', DB::raw('SUM(qty) as qty'), DB::raw('SUM(amount) as amount'))
//    ->where('status', 1)
//    ->where('sub_item_id', $id)
//    ->groupBy('sub_item_id')
//    ->first();

//print_r($stock_grn);
?>

<tr>
    <td><?php echo $datas->sub_ic ?></td>
    <td><?php echo $qty = $grn-$iss+$ret ?></td>
    <td><?php echo $qty*$rate ?></td>
</tr>
<?php $sumQty += $grn-$iss+$ret ?>
<?php $sumAmount += $qty*$rate ?>

<?php endforeach;?>
<tr>
    <td>Total </td>
    <td><?php echo number_format($sumQty,2) ?></td>
    <td><?php echo number_format($sumAmount,2) ?></td>
</tr>
