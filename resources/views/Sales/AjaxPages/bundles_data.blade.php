
<?php use App\Helpers\CommonHelper;
$counter=1;
?>


    @foreach($data as $row)


    <tr @if ($counter>1) class="remove" @endif title="1">
        <td>
            <input  type="text" value="{{$row->desc}}" class="form-control sami string clear" name="bsub_ic_des[]" id="bitem_{{$counter}}" placeholder="ITEM">
            <input type="hidden" class="string clear" value="{{$row->item_id}}" name="bitem_id[]" id="bsub_{{$counter}}">
        </td>

        <?php $uom_id=CommonHelper::generic('subitem',array('id'=>$row->item_id),array('uom'))->first()->uom;
        $uom_name=CommonHelper::get_uom_name($uom_id);

        ?>

        <td>
            <input readonly value="{{$uom_name}}" type="text" class="form-control clear" name="buom_id[]" id="buom_id{{$counter}}" >
        </td>
        <td>
            <input type="text"   onkeyup="bclaculation('{{$counter}}');bnet_calculation()" onblur="bclaculation('{{$counter}}');bnet_calculation()" class="form-control bqty clear int" name="bactual_qty[]" id="bactual_qty{{$counter}}"
                   placeholder="ACTUAL QTY" min="1" value="{{$row->qty}}">
        </td>
        <td class="">
            <input onkeyup="bclaculation('{{$counter}}');bnet_calculation()" onblur="bclaculation('{{$counter}}');bnet_calculation()"
                   type="text" class="form-control  clear int brate" name="brate[]" id="brate{{$counter}}" placeholder="RATE" min="1" value="{{$row->rate}}">
        </td>
        <td class="">
            <input type="text" class="form-control clear bamount" name="bamount[]" id="bamount{{$counter}}" placeholder="AMOUNT" min="1" value="{{$row->amount}}" readonly>
        </td>
        <td class="">
            <input type="text" onkeyup="bdiscount_percent(this.id);bnet_calculation()" onblur="bnet_calculation()" class="form-control  clear bdiscount_percent"
                   name="bdiscount_percent[]" id="bdiscount_percent{{$counter}}" placeholder="DISCOUNT" min="1" value="{{$row->discount_percent}}">
        </td>
        <td class="">
            <input type="text" onkeyup="bdiscount_amount(this.id);bnet_calculation()" onblur="bnet_calculation()" class="form-control  clear bdiscount_amount"
                   name="bdiscount_amount[]" id="bdiscount_amount{{$counter}}" placeholder="DISCOUNT" min="1" value="{{$row->discount_amount}}">
        </td>
        <td class="">
            <input type="text" class="form-control bnet_amount_dis" name="bafter_dis_amount[]" id="bafter_dis_amount{{$counter}}"
                   placeholder="NET AMOUNT" min="1" value="{{$row->net_amount}}" readonly>


        </td>
        <td style="background-color: #ccc"></td>
    </tr>
        <?php $counter++; ?>
    @endforeach
<input type="hidden" id="bundle_uom" value="{{$row->bundle_unit}}">
<input type="hidden" class="bundle_qty" value="{{$row->bundle_qty}}">
<input type="hidden" class="bundle_rate" value="{{$row->bundle_rate}}">
<input type="hidden" class="bundle_amount" value="{{$row->bundle_amount}}">
<input type="hidden" class="bundle_discount_percent" value="{{$row->bundle_percent}}">
<input type="hidden" class="bundle_discount_amount" value="{{$row->discount_bunle_amount}}">
<input type="hidden" class="bundle_net_amount" value="{{$row->bundle_amount}}">