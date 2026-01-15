<?php

use App\Helpers\CommonHelper;
use App\Helpers\SalesHelper;

?>

    <?php
    $dataa= DB::Connection('mysql2')->select('SELECT t1.id FROM delivery_note t1
                                              LEFT JOIN sales_tax_invoice t2 ON t1.so_no = t2.so_no
                                              WHERE t2.id IS NULL
                                              and t1.gd_date BETWEEN "'.$FromDate.'" and "'.$ToDate.'"
                                              and t1.status=1');

    ?>
                <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                    <thead>
                    <th class="text-center col-sm-1">S.No</th>
                    <th class="text-center col-sm-1">SO No</th>
                    <th class="text-center col-sm-1">DN No</th>
                    <th class="text-center col-sm-1">DN Date</th>
                    <th class="text-center col-sm-1">Mode / Terms Of Payment</th>
                    <th class="text-center col-sm-1">Order No</th>
                    <th class="text-center col-sm-1">Order Date</th>
                    <th class="text-center">Customer</th>

                    <th class="text-center">Total Qty.</th>
                    <th class="text-center">Total Amount.</th>

                    <th class="text-center">Username</th>
                    <th class="text-center">View</th>
                    <th class="text-center">Days</th>
                    {{--<th class="text-center">Delete</th>--}}
                    </thead>
                    <tbody id="data">
                    <?php $counter = 1;$total=0;
                    $open=0;
                    $parttial=0;
                    $complete=0;
                    ?>

                    @foreach($dataa as $row)

                        <?php


                        $dn=   DB::Connection('mysql2')->table('delivery_note')->where('id',$row->id)->first();
                        $now = time(); // or your date as well
                        $your_date = strtotime($dn->gd_date);
                        $datediff = $now - $your_date;

                        $days=round($datediff / (60 * 60 * 24));

                        $data=SalesHelper::get_total_amount_for_delivery_not_by_id($dn->id); ?>
                        <?php $customer=CommonHelper::byers_name($dn->buyers_id); ?>
                        <tr  title="{{$row->id}}" id="{{$row->id}}">
                            <td class="text-center">{{$counter++}}</td>
                            <td class="text-center"><?php echo  strtoupper($dn->so_no) ?></td>
                            <td title="{{$row->id}}" class="text-center">{{strtoupper($dn->gd_no)}}</td>
                            <td class="text-center"><?php  echo CommonHelper::changeDateFormat($dn->gd_date);?></td>
                            <td class="text-center">{{$dn->model_terms_of_payment}}</td>
                            <td class="text-center">{{$dn->order_no}}</td>
                            <td class="text-center"><?php  echo CommonHelper::changeDateFormat($dn->order_date);?></td>
                            <td class="text-center">{{$customer->name}}</td>
                            <td class="text-right">{{number_format($data->qty,3)}}</td>
                            <td class="text-right">{{number_format($data->amount+$dn->sales_tax_amount,3)}}</td>

                            <td class="text-center"><?php echo $dn->username?></td>

                            <td class="text-center">

                                <button onclick="showDetailModelOneParamerter('sales/viewDeliveryNoteDetail/<?php echo $row->id ?>','','View Delivery Note')"
                                        type="button" class="btn btn-success btn-xs">View</button>


                            </td>
                            <td><span class="badge badge-danger">{{$days}}</span></td>


                        </tr>


                    @endforeach



                    </tbody>
                </table>
