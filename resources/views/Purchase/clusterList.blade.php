<?php

use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    <div class="well">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        @include('Purchase.'.$accType.'purchaseMenu')
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">View Cluster List</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Cluster Name</th>
                                                            <th class="text-center">Created Date</th>
                                                            <th class="text-center">Created User</th>
                                                            <th class="text-center">Action</th>

                                                            </thead>
                                                            <tbody id="">
                                                            <?php
                                                                    $Counter = 1;
                                                            foreach($Cluster as $Fil):?>
                                                                <tr class="text-center">
                                                                    <td><?php echo $Counter++;?></td>
                                                                    <td><?php echo $Fil->cluster_name;?></td>
                                                                    <td><?php echo CommonHelper::changeDateFormat($Fil->created_date);?></td>
                                                                    <td><?php echo $Fil->username;?></td>

                                                                </tr>
                                                            <?php endforeach;?>
                                                            </tbody>
                                                        </table>


                                                        <?php

                                                        $data=DB::Connection('mysql2')->table('TABLE 188')->select('COL_2','COL_1')
                                                                ->groupBy('COL_2')
                                                                ->get();

                                                        ?>

                                                        <table class="table table-bordered">
                                                            <thead>
                                                            <th class="text-center">S.No</th>
                                                            <th class="text-center">Date</th>
                                                            <th class="text-center">SO No</th>
                                                            <th class="text-center">Customer Name</th>
                                                            <th class="text-center">Po No</th>
                                                            <th class="text-center">Item Code</th>
                                                            <th class="text-center">Item Description</th>
                                                            <th class="text-center">Quantity</th>
                                                            <th class="text-center">Price</th>
                                                            <th class="text-center">Amount</th>
                                                            <th class="text-center">Sales Tax </th>
                                                            <th class="text-center">Net Amount </th>

                                                            </thead>
                                                            <tbody id="">
                                                            <?php
                                                            $Counter = 1;
                                                            foreach($data as $row):
                                                            if ($Counter>1):
                                                            ?>
                                                            <tr class="text-center">
                                                                <td><?php ?></td>
                                                                <td><?php echo $row->COL_1;?></td>

                                                                <?php // ReuseableCode::insert_old_so($row->COL_2); ?>

                                                            </tr>
                                                            <?php endif; $Counter++; endforeach;?>
                                                            </tbody>
                                                        </table>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">

    </script>
@endsection