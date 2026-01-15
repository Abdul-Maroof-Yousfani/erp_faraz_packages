
<?php use App\Helpers\CommonHelper; ?>

       <?php
        $counter = 1;$total=0;
        $paramOne = "sdc/viewInvoiceDetail?m=".$m;
        $edit_url= url('/sales/editInvoice/');

     $total_amount=0;
?>

    @foreach($invoice as $row)
        <tr id="{{ $row->id }}">
            <td class="text-center">{{$counter++}}</td>
            <td class="text-center">{{ strtoupper($row->inv_no) }}</td>
            <td class="text-center">{{ CommonHelper::changeDateFormat($row->inv_date) }}</td>

            <td class="text-center">{{ strtoupper($row->job_order_no) }}</br>{{ $row->client_ref }}</td>
            <td class="text-center">{{ CommonHelper::get_client_name_by_id($row->bill_to_client_id)}}</td>

            <td class="text-center">{{ $row->ship_to }}</td>
            <td class="text-center">{{number_format($row->net_value,2)}}</td>
            <?php $total_amount+=$row->net_value; ?>
            <td class="text-center">{{ ucwords($row->username) }}</td>

            <td class="text-center">
                <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->id?>','View Invoice Detail')" type="button" class="btn btn-success btn-xs">View</button>
            </td>

            <?php
            $row->inv_status;
            if($row->type == 0):?>
            <td class="text-center hide{{$row->id}}">
                <a href='<?php echo  $edit_url.'/'.$row->id.'?m='.$m ?>' type="button" class="btn btn-primary btn-xs  hide{{$row->id}}">Edit</a>
            </td>
            <?php else:?>
            <td class="text-center">
                <i class="fa fa-ban" aria-hidden="true" style="color: red"></i>
            </td>
            <?php endif;?>

            <?php if($row->inv_status == 1):?>
            <td class="text-center">
                <button onclick="invoiceDelete('<?= $row->id ?>','smfal/invoiceDelete')" class="btn btn-success btn-xs  hide{{$row->id}}">Delete</button>
            </td>
            <?php else:?>
            <td class="text-center">
                <i class="fa fa-ban" aria-hidden="true" style="color: red"></i>
            </td>
            <?php endif;?>
        </tr>

    @endforeach

<tr style="background-color: darkgrey">
    <td colspan="6">Total</td>
    <td class="text-center">{{number_format($total_amount,2)}}</td>
    <td colspan="4"></td>
</tr>

<b>Total Amount Before Tax</b>
<b>Total Amount Of Tax</b>
<b>Total Amount After Tax</b>
