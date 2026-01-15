
<?php
use App\Helpers\CommonHelper;
?>
<table  class="table table-bordered table-striped table-condensed tableMargin">

    <tr>
        <th class="text-center">Sr No</th>
        <th class="text-center">Voucher No</th>
        <th class="text-center">Voucher Date</th>
        <th class="text-center">Supplier</th>
        <th class="text-center">Quantity</th>
        <th class="text-center">Rate</th>
        <th class="text-center">Amount</th>
  
    </tr>

    <tbody>
        <?php
            $count=1;
              
        ?>
    @foreach($grn_data as $row)
    <?php
    $qty = $row->purchase_recived_qty - $row->qc_qty;
    $rate = $row->rate;
    $amount = $qty * $rate;
    ?>
    <tr>
        <td class="text-center">{{$count++}}</td>
        <td class="text-center">{{strtoupper($row->grn_no)}}</td>
        <td class="text-center">{{commonHelper::changeDateFormat($row->grn_date)}}</td>
        <td class="text-center">{{commonHelper::get_supplier_name($row->supplier_id)}}</td>
        <td class="text-center">{{number_format($qty,2)}}</td>
        <td class="text-center">{{number_format($rate)}}</td>
        <td class="text-center">{{number_format($amount,2)}}</td>
       
    </tr>
        @endforeach
    </tbody>
    </table>