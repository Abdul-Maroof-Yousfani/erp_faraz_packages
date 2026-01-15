<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
//        print_r($_GET);die();
        $from = $_GET['FromDate'];
$to = $_GET['ToDate'];

?>



<?php
        /*
$supp=$this->db->query('select s.id,s.name from supplier s
      inner join goods_receipt_note d on s.id=d.supplier_id
      where s.status = 1
      and g.grn_date between "'.$from.'" and "'.$to.'"
    group  by s.id')->result_array();
        */

$supp = DB::Connection('mysql2')->select('select s.id,s.name from supplier s
      inner join goods_receipt_note d on s.id=d.supplier_id
      where s.status = 1
      and d.grn_date between "'.$from.'" and "'.$to.'"
    group  by s.id');
?>

<?php $main_index=0;
$alltotal = 0;
        $total = 0;
?>
<?php foreach ($supp as $supplier) : ?>


    <tr class="text-center">
        <td style="font-size: 25px;" colspan="7"><strong><?php echo 'SUPPLIER'.' : '.$supplier->name; $cars[$main_index]=$supplier->name; ?></strong></td>
    </tr>
    <b style="text-transform: uppercase !important;"></b>
    <?php
    $grn_data1 =DB::Connection('mysql2')->select('select g.grn_date as date,g.supplier_invoice_no as no,d.* from grn_data as d
      inner join goods_receipt_note g on g.grn_no=d.grn_no
      where g.supplier_id="'.$supplier->id.'"
       and  g.status=1
       and d.status=1
       and d.grn_status in (2)
        and g.grn_date between "'.$from.'" and "'.$to.'"');
            /*
    $grn_data1=	$this->db->query('select g.grn_date as date,g.supplier_invoice_no as no,d.* from grn_data as d
      inner join goods_receipt_note g on g.grn_no=d.grn_no
      where d.supplierId="'.$supplier->id.'"
       and  g.status=1
       and d.status=1
       and g.grn_status in (2)
        and g.grn_date between "'.$from.'" and "'.$to.'"')->result_array();
            */

    /*		$grn_data1=$this->db->query('select   g.grn_date,d.item,
    d.expiryDate,
    g.grn_no,
    g.invoice_no,
    d.companyId,
    d.supplierId,
    d.netAmount as amount,
    d.goodReceivedQuantity
     from
    goods_receipt_note g
    inner join
    grn_data d
    ON
    d.grn_no=d.grn_no
    where g.status=1
    and g.grn_date between "'.$from.'" and "'.$to.'"
    ')->result_array();*/

    $count=1;

    foreach ($grn_data1 as $grn_data):?>


    <tr>
        <td class="text-center"><?php echo $count; ?></td>

        <td class="text-left"><?php echo CommonHelper::get_item_name($grn_data->sub_item_id); ?></td>

        <td class="text-center"><?php echo CommonHelper::changeDateFormat($grn_data->date);?></td>

        <td class="text-center"><?php echo strtoupper($grn_data->grn_no)?></td>

        <td class="text-center"><?php echo $grn_data->no;?></td>

        <td class="text-center"> <?php echo $grn_data->purchase_recived_qty;?></td>

        <td class="text-center"> <?php echo $amount= $grn_data->amount;?></td>
    </tr>

    <?php
    $alltotal +=$amount;
    $total +=$amount;

    $count++;
    endforeach;?>


    <?php  ?>
    <?php if ($total>0): ?>
    <tr style="color: black;" class="sf-table-total">
        <td colspan="6" class="text-center"><b style="font-size: 15px; color: #000;">Total</b></td>

        <?php $total1[]=$total; ?>
        </td>


        <td class="text-center" colspan=""><b style="font-size: 15px; color: #000;"><?php echo  $total;  $total=0; ?></b></td>
    </tr> <?php endif;?>



    <?php  $main_index++; endforeach; ?>


