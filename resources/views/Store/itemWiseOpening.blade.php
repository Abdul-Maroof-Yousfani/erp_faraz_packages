<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;

$export=ReuseableCode::check_rights(242);
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');
?>

@extends('layouts.default')

@section('content')
    <div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('printGoodsReceiptNoteList','','1');?>
                            <?php if($export == true):?>
                        <?php echo CommonHelper::displayExportButton('goodsReceiptNoteList','','1')?>
                        <?php endif;?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Item Wise Opening Report</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div id="printGoodsReceiptNoteList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="panel">
                                            <div class="panel-body">
                                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                                <div class="row">
                                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered sf-table-list" id="goodsReceiptNoteList">
                                                                <thead>
                                                                <th class="text-center">S.No</th>
                                                                <th class="text-center">Item Name</th>
                                                                <th class="text-center">Location</th>
                                                                <th class="text-center">Qty</th>
                                                                <th class="text-center">Amount</th>
                                                                </thead>
                                                                <tbody id="GetDataAjax">
                                                                <?php $Counter =1;
                                                                        $total=0;
                                                                   foreach($OpeningItemWise as $Fil):
                                                                      //  $SubItem = CommonHelper::get_single_row('subitem','id',$Fil->sub_item_id);
                                                                        $Warehouse = CommonHelper::get_single_row('warehouse','id',$Fil->warehouse_id);
                                                                ?>
                                                                    <tr>
                                                                        <td><?php echo $Counter++;?></td>
                                                                        <td><?php echo CommonHelper::get_item_name($Fil->sub_item_id)?></td>
                                                                        <td><?php echo strtoupper($Warehouse->name)?></td>
                                                                        <td><?php echo number_format($Fil->qty,2)?></td>
                                                                        <td><?php echo number_format($Fil->amount,2);$total+=$Fil->amount?></td>
                                                                    </tr>
                                                                <?php endforeach;?>
                                                                <tr>
                                                                    <td colspan="4">Total</td>
                                                                    <td>{{number_format($total,2)}}</td>
                                                                </tr>
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
    </div>
    </div>

@endsection
