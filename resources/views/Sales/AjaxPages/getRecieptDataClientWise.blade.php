
<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;
echo Form::open(array('url' => 'finance/CreateReceiptVoucherForSales?m='.$m,'id'=>'cashPaymentVoucherForm'));?>
<table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
    <thead>

    <th class="text-center col-sm-1">S.No</th>
    <th class="text-center col-sm-1">SO No.</th>
    <th class="text-center col-sm-1">SI No</th>
    <th class="text-center col-sm-1">ST No</th>
    <th class="text-center col-sm-1">SI Date</th>
    <th class="text-center">Terms Of Payment</th>
    <th class="text-center">Customer</th>
    <th class="text-center">Invoice Amount</th>
    <th class="text-center">Return Amount</th>
    <th class="text-center">Received Amount</th>
    <th class="text-center">Remaining Amount</th>
    <th class="text-center">view</th>
    </thead>
    <tbody id="data">
    <?php $counter = 1;
    $total=0;
    $received=0;
   $remaining=0;
   
    ?>

    @foreach($Invoice as $row)
        <?php
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $data = SalesHelper::getTotalAmountSalesTaxInvoice($row->id);
        $get_freight = SalesHelper::get_freight($row->id);
        $customer = CommonHelper::byers_name($row->buyers_id);
        $rece = SalesHelper::get_received_payment($row->id);
        $return_amount = SalesHelper::get_sales_return_from_sales_tax_invoice($row->id);
        CommonHelper::reconnectMasterDatabase();

        $rema = $data->total + $get_freight + $row->advance_tax_amount + $row->cartage_amount - $return_amount - $rece;
        if($rema > 0):
        ?>
        <tr @if($rema==0) style="background-color: #bdefbd" @endif title="{{$row->id}}" id="{{$row->id}}">

            <td class="text-center">
                @if($rema>0)
                    <input name="checkbox[]" onclick="check(),supplier_check('',this.id)"
                    class="checkbox1 form-control AllCheckbox AddRemoveClass<?php echo $row->buyers_id?>"
                    id="<?php echo $row->buyers_id?>" type="checkbox" value="{{$row->id}}"
                    onchange="CheckUncheck('<?php echo $counter?>','<?php echo $row->buyers_id?>')" style="height: 30px;">
                @else 
                    <p> Clear</p>
                @endif
            </td>

            <td title="{{$row->id}}" class="text-center">{{ strtoupper($row->so_no)}}</td>
            <td class="text-center">{{ strtoupper($row->gi_no) }}</td>
            <td class="text-center">{{ strtoupper($row->sc_no) }}</td>
            <td class="text-center">{{ CommonHelper::changeDateFormat($row->gi_date) }}</td>
            <td class="text-center">{{ $row->model_terms_of_payment }}</td>
            <td class="text-center">{{ $customer->name }}</td>

            <?php

            $inv = $data->total+$get_freight + $row->advance_tax_amount + $row->cartage_amount; ?>

            <td class="text-right">{{number_format($inv,2)}}</td>
            <td class="text-right">{{number_format($return_amount,2)}}</td>
            <?php
            $rema = ($data->total + $get_freight + $row->advance_tax_amount + $row->cartage_amount) - $rece - $return_amount;?>
            <td class="text-right">{{number_format($rece,2)}}</td>
            <td class="text-right">{{number_format($rema,2)}}</td>

            <?php

            $total += $inv;
            $received += $rece;
            $remaining += $rema;
            ?>
            <td class="text-center"><button
                        onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->id ?>','View Sales Tax Invoice')"
                        type="button" class="btn btn-success btn-xs">View</button></td>




            {{--<td class="text-center"><a href="{{ URL::asset('purchase/editPurchaseVoucherForm/'.$row->id) }}" class="btn btn-success btn-xs">Edit </a></td>--}}
            {{--<td class="text-center"><button onclick="delete_record('{{$row->id}}')" type="button" class="btn btn-danger btn-xs">DELETE</button></td>--}}
        </tr>
        <?php   endif; ?>

    @endforeach

    <tr>
        <td class="text-center" colspan="7">Total</td>
        <td class="text-right" colspan="1">{{number_format($total,2)}}</td>
        <td class="text-right" colspan="1">{{number_format($received,2)}}</td>
        <td class="text-right" colspan="2">{{number_format($remaining,2)}}</td>
    </tr>
    <tr>
        <td colspan="10">
            <input type="submit" value="Create Receipt" class="btn btn-sm btn-primary BtnEnDs BtnSub" id="add">
        </td>
    </tr>
    </tbody>
</table>
<?php Form::close(); ?>
