
<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
?>
                    <div style="display: none" id="other_fomrate" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <table  id="tablee" class="table " style="border: solid 1px black;">
                                <thead>
                                <tr>
                                    <th class="text-center" style="border:1px solid black;">S.NO</th>
                                    <th class="text-center" style="border:1px solid black;">Item</th>
                                    <th class="text-center" style="border:1px solid black;">Uom</th>
                                    <th class="text-center" style="border:1px solid black;">QTY. <span class="rflabelsteric"><strong>*</strong></span></th>
                                    <th class="text-center" style="border:1px solid black;">Rate</th>
                                    <th class="text-center" style="border:1px solid black;">Amount</th>
                                    <th class="text-center" style="border:1px solid black;">Discount%</th>
                                   <th class="text-center" style="border:1px solid black;">Discount Amount</th>
                                   <th class="text-center" style="border:1px solid black;">Sales Tax 17%</th>
                                   <th class="text-center" style="border:1px solid black;">Sales Tax 3%</th>
                                    <th class="text-center" style="border:1px solid black;">Amount</th>


                                </tr>
                                </thead>

                                    <tbody>
                                    <?php
                                    $count=1;
                                    $total_sub_total=0;
                                    $total_discount_amount=0;
                                    $total_net_price=0;
                                    $total_sales_tax=0;
                                    $total_additional=0;
                                    ?>
                                    @foreach($sale_order_dataa as $row)
                                    <tr>

                                        <?php
                                        $sales_tax=0;
                                        if ($sales_order->sales_tax>0):
                                        $sales_tax=(($row->sub_total - $row->discount_amount ) / 100)  * 17;
                                        endif;

                                        $sales_additional=0;
                                        if ($sales_order->sales_tax_further>0):
                                            $sales_additional=(($row->sub_total - $row->discount_amount)  / 100 ) * 3;
                                        endif;

                                        $total_sub_total+=$row->sub_total;
                                        $total_discount_amount+=$row->discount_amount;
                                        $total_sales_tax+=$sales_tax;
                                        $total_additional+=$sales_additional;
                                        $net_price=$row->sub_total - $row->discount_amount + $sales_tax + $sales_additional ;
                                        $total_net_price+=$net_price;
                                        ?>
                                        <td style="border:1px solid black !important" class="text-center border">{{ $count++ }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ $row->desc }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ CommonHelper::only_uom_nam_by_item_id($row->item_id) }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ $row->qty }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ number_format($row->rate,2) }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ number_format($row->sub_total,2) }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ number_format($row->discount,2) }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ number_format($row->discount_amount,2) }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ number_format($sales_tax,2) }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ number_format($sales_additional,2) }}</td>
                                        <td style="border:1px solid black !important" class="text-center border">{{ number_format($net_price,2) }}</td>

                                    </tr>

                                    @endforeach
                                <tr class="text-center" style="background-color: lightgray;font-size: large;font-weight: bold">
                                    <td style="border:1px solid black !important" colspan="5">Total</td>
                                    <td style="border:1px solid black !important" colspan="">{{ number_format($total_sub_total,2) }}</td>
                                    <td style="border:1px solid black !important"></td>
                                    <td style="border:1px solid black !important">{{ number_format($total_discount_amount,2) }}</td>
                                    <td style="border:1px solid black !important">{{ number_format($total_sales_tax,2) }}</td>
                                    <td style="border:1px solid black !important">{{ number_format($total_additional,2) }}</td>
                                    <td style="border:1px solid black !important">{{ number_format($total_net_price,2) }}</td>
                                </tr>
                                    </tbody>




                            </table>
                        </div>
                    </div>




