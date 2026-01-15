<?php
use App\Helpers\CommonHelper;
    //print_r($supplier);

foreach($supplier as $val){
        $grn_datas =	DB::Connection('mysql2')->select('select g.id,g.grn_date, g.supplier_invoice_no, d.* from grn_data as d inner join goods_receipt_note g on g.grn_no=d.grn_no
                      where g.supplier_id="'.$val->id.'" and g.status=1 and d.status=1 and g.grn_date between "'.$from_date.'" and "'.$to_date.'" ');
   // print_r($grn_datas);
    ?>
<h3>{{$val->name}}</h3>
<table class="table table-bordered table-responsive">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Item</th>
    <th class="text-center">COMPANY</th>
    <th class="text-center">PURCHASE DATE</th>
    <th class="text-center">EXPIRY DATE</th>
    <th class="text-center">GRN #</th>
    <th class="text-center">INVOICE</th>
    <th class="text-center">PACK SIZE</th>
    <th class="text-center">PUR QTY</th>

    </thead>
    <tbody id="filterDemandVoucherList">
    <?php
    $counter=1;
    ?>
    @foreach($grn_datas as $grn_data)
        <?php
        $sub_ic_data=CommonHelper::get_subitem_detail($grn_data->sub_item_id);
        $sub_ic_data=explode(',',$sub_ic_data);
        //$uom=$sub_ic_data[0];
        ?>
        <tr>
            <td class="text-center">{{$counter++}}</td>
            <td class="text-center" style="width: 30%">{{$sub_item_id=$sub_ic_data[4] }}</td>
            <td class="text-center" style="width: 20%">{{$val->company_name }}</td>
            <td class="text-center">{{$grn_data->grn_date }}</td>
            <td class="text-center">{{$grn_data->expiry_date }}</td>
            <td class="text-center">{{$grn_data->grn_no }}</td>
            <td class="text-center" style="width: 15%">{{$grn_data->supplier_invoice_no }}</td>
            <td class="text-center">{{$grn_data->packunit }}</td>
            <td class="text-center">{{$grn_data->purchase_recived_qty }}</td>

        </tr>
    @endforeach

    </tbody>
</table>

<?php
    }
?>