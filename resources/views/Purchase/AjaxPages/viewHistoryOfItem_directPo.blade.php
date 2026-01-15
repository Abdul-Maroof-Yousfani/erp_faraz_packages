
<?php
use App\Helpers\CommonHelper;

?>
<table  class="table table-bordered table-striped table-condensed tableMargin">

    <tr class="text-center">
        <th class="text-center">GRN No</th>
        <th class="text-center">GRN Date</th>
        <th class="text-center">Vendor</th>
        <th class="text-center">Rate</th>
    </tr>

    <tbody>
    @foreach($grn_data as $row)
        <?php $Supp = DB::Connection('mysql2')->table('goods_receipt_note')->where('grn_no',$row->grn_no)->select('supplier_id')->first();?>
        <tr>
            <td class="text-center">{{ucwords($row->grn_no)}}</td>
            <td class="text-center">{{commonHelper::changeDateFormat($row->grn_date)}}</td>
            <td class="text-center"><?php echo CommonHelper::get_supplier_name($Supp->supplier_id);?></td>
            {{--<td class="text-center">{{number_format($row->purchase_recived_qty)}}</td>--}}
            <td class="text-center">{{number_format($row->rate)}}</td>
        </tr>
    @endforeach
    </tbody>
</table>