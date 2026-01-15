<?php
use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;
//$id = $_GET['id'];
$m = $_GET['m'];
?>
@extends('layouts.default')

@section('content')


    <?php
    $data=   DB::Connection('mysql2')->select('SELECT j.`master_id`,j.`id`,j.`amount` AS jv_amount,SUM(d.`amount`) AS allocation_amoun,j.date FROM jv_data j
INNER JOIN
`department_allocation` d
ON
d.`master_id`=j.`id`
WHERE
j.`status`=1
and d.status=1
AND d.`type`=5
GROUP BY j.`id`
order by j.master_id');
    ?>


    <div class="well">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php

                ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center" style="width:100px;">Date</th>
                            <th class="text-center" style="width: 150px">Main Id</th>
                            <th class="text-center" style="width: 150px">Master Id</th>
                            <th class="text-center" style="width:150px;">Jv Amount </th>
                            <th class="text-center" style="width:150px;">Allocation Amount</th>
                            <th class="text-center" style="width:150px;">Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $counter=1; ?>
                        @foreach($data as $row)
                            <?php $jv_amount=round($row->jv_amount);
                            $allocation_amount= round($row->allocation_amoun);

                            if ($jv_amount!=$allocation_amount):
                            ?>
                            <tr >
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row->date);?></td>
                                <td><?php  echo $row->master_id?></td>
                                <td><?php  echo $row->id?></td>
                                <td class="debit_amount text-right">{{$row->jv_amount}}</td>
                                <td class="debit_amount text-right">{{$row->allocation_amoun}}</td>
                                <td><a href="<?php echo  URL::to('/finance/editJournalVoucherForm/'.$row->master_id.'?m=1'); ?>" type="button" class="btn btn-primary btn-xs">Edit</a></td>

                            </tr>
                            <?php  endif; ?>

                        @endforeach


                        </tbody>


                    </table>
                </div>


                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center" style="width:100px;">Date</th>
                            <th class="text-center" style="width: 150px">Main Id</th>
                            <th class="text-center" style="width: 150px">Main Id</th>
                            <th class="text-center" style="width:150px;">Jv Amount </th>
                            <th class="text-center" style="width:150px;">Allocation Amount</th>
                            <th class="text-center" style="width:150px;">Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $data=   DB::Connection('mysql2')->select('SELECT j.`master_id`,j.`id`,j.`amount` AS jv_amount,SUM(d.`amount`) AS allocation_amoun,j.date FROM jv_data j
INNER JOIN
`cost_center_department_allocation` d
ON
d.`master_id`=j.`id`
WHERE
j.`status`=1
and d.status=1
AND d.`type`=5
GROUP BY j.`id`
order by j.master_id');

                        $counter=1; ?>
                        @foreach($data as $row)
                            <?php $jv_amount=round($row->jv_amount);
                            $allocation_amount= round($row->allocation_amoun);

                            if ($jv_amount!=$allocation_amount):
                            ?>
                            <tr >
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row->date);?></td>
                                <td><?php  echo $row->master_id?></td>
                                <td><?php  echo $row->id?></td>
                                <td class="debit_amount text-right">{{$row->jv_amount}}</td>
                                <td class="debit_amount text-right">{{$row->allocation_amoun}}</td>
                                <td><a href="<?php echo  URL::to('/finance/editJournalVoucherForm/'.$row->master_id.'?m=1'); ?>" type="button" class="btn btn-primary btn-xs">Edit</a></td>

                            </tr>
                            <?php  endif; ?>

                        @endforeach


                        </tbody>


                    </table>
                </div>

                <?php

                ?>
                <?php

                //echo "<pre>";
                //print_r($jv_data);?>
            </div>
        </div>

    </div>



    <?php
    $data=   DB::Connection('mysql2')->select('SELECT j.`master_id`,j.`id`,j.`amount` AS jv_amount,SUM(d.`amount`) AS allocation_amoun,j.date FROM pv_data j
INNER JOIN
`department_allocation` d
ON
d.`master_id`=j.`id`
WHERE
j.`status`=1
and d.status=1
AND d.`type`in (4,3)
GROUP BY j.`id`
order by j.master_id');
    ?>


    <div class="well">

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <?php

                ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center" style="width:100px;">Date</th>
                            <th class="text-center" style="width: 150px">Main Id</th>
                            <th class="text-center" style="width: 150px">Master Id</th>
                            <th class="text-center" style="width:150px;">Jv Amount </th>
                            <th class="text-center" style="width:150px;">Allocation Amount</th>
                            <th class="text-center" style="width:150px;">Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php $counter=1; ?>
                        @foreach($data as $row)
                            <?php $jv_amount=round($row->jv_amount);
                            $allocation_amount= round($row->allocation_amoun);

                            if ($jv_amount!=$allocation_amount):
                            ?>
                            <tr >
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row->date);?></td>
                                <td><?php  echo $row->master_id?></td>
                                <td><?php  echo $row->id?></td>
                                <td class="debit_amount text-right">{{$row->jv_amount}}</td>
                                <td class="debit_amount text-right">{{$row->allocation_amoun}}</td>
                                <td><a href="<?php echo  URL::to('/finance/editJournalVoucherForm/'.$row->master_id.'?m=1'); ?>" type="button" class="btn btn-primary btn-xs">Edit</a></td>

                            </tr>
                            <?php  endif; ?>

                        @endforeach


                        </tbody>


                    </table>
                </div>


                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <table  class="table table-bordered table-striped table-condensed tableMargin">
                        <thead>
                        <tr>
                            <th class="text-center" style="width:50px;">S.No</th>
                            <th class="text-center" style="width:100px;">Date</th>
                            <th class="text-center" style="width: 150px">Main Id</th>
                            <th class="text-center" style="width: 150px">Main Id</th>
                            <th class="text-center" style="width:150px;">Jv Amount </th>
                            <th class="text-center" style="width:150px;">Allocation Amount</th>
                            <th class="text-center" style="width:150px;">Edit</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $data=   DB::Connection('mysql2')->select('SELECT j.`master_id`,j.`id`,j.`amount` AS jv_amount,SUM(d.`amount`) AS allocation_amoun,j.date FROM pv_data j
INNER JOIN
`cost_center_department_allocation` d
ON
d.`master_id`=j.`id`
WHERE
j.`status`=1
and d.status=1
AND d.`type` in (4,3)
GROUP BY j.`id`
order by j.master_id');

                        $counter=1; ?>
                        @foreach($data as $row)
                            <?php $jv_amount=round($row->jv_amount);
                            $allocation_amount= round($row->allocation_amoun);

                            if ($jv_amount!=$allocation_amount):
                            ?>
                            <tr >
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row->date);?></td>
                                <td><?php  echo $row->master_id?></td>
                                <td><?php  echo $row->id?></td>
                                <td class="debit_amount text-right">{{$row->jv_amount}}</td>
                                <td class="debit_amount text-right">{{$row->allocation_amoun}}</td>
                                <td><a href="<?php echo  URL::to('/finance/editJournalVoucherForm/'.$row->master_id.'?m=1'); ?>" type="button" class="btn btn-primary btn-xs">Edit</a></td>

                            </tr>
                            <?php  endif; ?>

                        @endforeach


                        </tbody>


                    </table>
                </div>

                <?php

                ?>
                <?php

                //echo "<pre>";
                //print_r($jv_data);?>
            </div>
        </div>

    </div>
@endsection