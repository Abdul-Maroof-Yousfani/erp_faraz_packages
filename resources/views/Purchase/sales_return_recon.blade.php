<?php

use App\Helpers\CommonHelper;
?>


<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    <div class="panel">
        <div class="panel-body">
            <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">

                        <?php

                        $data = DB::Connection('mysql2')->select('select sum(b.net_amount)cr_amount,a.cr_no,a.cr_date,a.id as cr_id,a.type
                                                      from credit_note a
                                                      inner  join
                                                       credit_note_data b
                                                       ON
                                                       b.master_id = a.id
                                                      where a.status = 1
                                                      group by a.id');
                        ?>

                        
                        <table class="table table-bordered sf-table-list" id="goodsReceiptNoteList">
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">CR NO</th>
                            <th class="text-center">CR Date</th>
                            <th class="text-center">Amount Through DN</th>
                            <th class="text-center">Amount Through SI</th>

                            </thead>
                            <tbody id="GetDataAjax">
                            <?php $count=1;
                            $total_cr_amount_dn=0;
                            $total_cr_amount_si=0;
                            ?>
                            @foreach($data as $row)


                                <?php

                                $stock_amount_grn= DB::Connection('mysql2')->table('stock')->where('status',1)->where('voucher_no',$row->cr_no)->sum('amount');



                                ?>
                                <tr @if($stock_amount_grn!=$row->cr_amount) style="background-color: red" @endif>
                                    <td class="text-center">{{$count++}}</td>
                                    <td class="text-center">{{strtoupper($row->cr_no)}}</td>
                                    <td class="text-center">{{date_format(date_create($row->cr_date), 'd-m-Y')}}</td>
                                    <td class="text-right"> @if ($row->type==1){{number_format($row->cr_amount,2)}} <?php    $total_cr_amount_dn+=$row->cr_amount; ?> @endif</td>
                                    <td class="text-right"> @if ($row->type==2){{number_format($row->cr_amount,2)}} <?php    $total_cr_amount_si+=$row->cr_amount; ?> @endif</td>

                                </tr>
                            @endforeach


                            <tr style="font-size: larger;font-weight: bolder">

                                <td colspan="3">Total</td>
                                <td class="text-right" colspan="">{{number_format($total_cr_amount_dn,2)}}</td>
                                <td class="text-right" colspan="">{{number_format($total_cr_amount_si,2)}}</td>
                            </tr>

                            <?php  ?>
                            </tbody>


                        <?php echo  $total_in_stock=$total_grn_amount+$total_cr_amount_dn+$total_cr_amount_si; ?>

                        </table>

                            <p>Roconcile Formula :  Total In Stock -Return Amount Thorugh GRN - GRN Amount Which Invoice Not Created -Sales Return Through DN</p>
                            <p>{{number_format($total_in_stock,2).'-'.number_format($total_return_amount_grn,2).'-'.number_format($total,2).'+'.$total_cr_amount_dn.'='}}
                            <b style="font-weight: bold">{{number_format($total_in_stock-$total_return_amount_grn-$total-$total_cr_amount_dn,2)}}</b></p>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>