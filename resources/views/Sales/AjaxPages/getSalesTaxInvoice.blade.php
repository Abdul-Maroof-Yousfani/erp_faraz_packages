<?php
use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

$counter=1;
if ($type==1):
$voucher_no='DN No';
$voucher_date='DN Date';

else:
    $voucher_no='SI No';
    $voucher_date='SI Date';
endif;


?>
        @foreach($dataa as $row)

        <?php
        

        ?>
    <tr>


        <td colspan="12">
            <b>{{$voucher_no}} : <?php echo $row->gi_no; echo '</br>';?>
            {{$voucher_date}} : <?php echo CommonHelper::changeDateFormat($row->gi_date); ?></b>

        </td>

    


    @foreach(SalesHelper::get_data_from_invoice_data($row->id,$type) as $data)




        <?php if ($data->qty>0):  ?>
    <tr title="{{$row->id}}" id="{{$row->id}}">

        <td class="text-center">


            @if ($data->sales_tax_invoice_id=='' || $data->sales_tax_invoice_id==0)
            <input  name="checkbox[]" onclick="check()" class="checkbox1 <?php ?>" id="1chk<?php echo $counter?>" type="checkbox"  value='<?php echo $data->id ?>'>
                    @else {{'Clear'}}
                @endif

       </td>
        <td class="text-center">{{$counter++}}</td>
        <?php
        $sub_ic_data=CommonHelper::get_subitem_detail($data->item_id);
        $sub_ic_data=explode(',',$sub_ic_data);
        ?>
        <td title="{{$row->id}}" class="text-center">{{$sub_ic_data[4]}}</td>

        <td class="text-center">{{CommonHelper::get_uom_name($sub_ic_data[0])}}</td>
        <td class="text-center"><?php  echo number_format($data->qty,2);?></td>
        <td class="text-center">{{number_format($data->rate,2)}}</td>
        <td class="text-right">{{$data->tax}}</td>
        <td class="text-center">{{number_format($data->amount,2)}}</td>
      

    </tr>
        <?php endif; ?>
        @endforeach
</tr>
<input type="hidden" name="buyer_id" value="{{$row->buyers_id}}"/>
<input type="hidden" name="type" value="{{$type}}"/>
@endforeach

    <script>
        function check()
        {

            if($(".checkbox1").is(':checked'))
            {
                $("#add").prop('disabled', false);
            }
            else
            {
                $("#add").prop('disabled', true);
            }
        }

    </script>