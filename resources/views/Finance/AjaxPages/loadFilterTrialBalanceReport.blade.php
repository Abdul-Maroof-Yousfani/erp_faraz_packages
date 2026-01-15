<?php
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\Account;
use App\Models\Transactions;
$m = $_GET['m'];
$fromDate = date("Y-m-d", strtotime($_GET['fromDate']));
$toDate = date("Y-m-d", strtotime($_GET['toDate']));
$currentDate = date('Y-m-d');
$d_total = 0;
$c_total = 0;
CommonHelper::companyDatabaseConnection($m);
$accounts = Account::where('status','=','1')->orderBy('level1', 'ASC')->orderBy('level2', 'ASC')->orderBy('level3', 'ASC')->orderBy('level4', 'ASC')->orderBy('level5', 'ASC')->orderBy('level6', 'ASC')->orderBy('level7', 'ASC')->get();
CommonHelper::reconnectMasterDatabase();
?>
<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
                <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($currentDate);?></label>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                         style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                        <?php echo CommonHelper::getCompanyName($m);?>
                    </div>
                    <br />
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                         style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                        <?php //echo CommonHelper::getCompanyDatabaseTableValueById($m,'accounts','name',$_GET['accountName']);?> Trial Balance Report
                    </div>
                    <br />
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                         style="font-size: 16px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                        <?php echo CommonHelper::changeDateFormat($_GET['fromDate']);?> ---- <?php echo CommonHelper::changeDateFormat($_GET['toDate']);?>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
                <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
                <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

            </div>
        </div>
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-responsive">
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Account Code</th>
                            <th class="text-center">Account Name</th>
                            <th class="text-center">Debit Amount</th>
                            <th class="text-center">Credit Amount</th>
                            </thead>
                            <tbody>
                            <?php
                            $counter = 1;
                            foreach($accounts as $row){
                            $array = explode('-',$row->code);
                            $level = count($array);
                            $nature = $array[0];
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $counter++;?></td>
                                <td><?php echo $row->code;?></td>
                                <td>
                                    <?php
                                    if($level == '1'){
                                    echo $row->name;
                                    }else if($level == '2'){
                                    echo '&emsp;&emsp;'. $row->name;
                                    }else if($level == '3'){
                                    echo '&emsp;&emsp;&emsp;&emsp;'. $row->name;
                                    }else if($level == '4'){
                                    echo '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name;
                                    }else if($level == '5'){
                                    echo '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name;
                                    }else if($level == '6'){
                                    echo '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name;
                                    }else if($level == '7'){
                                    echo '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $row->name;
                                    }
                                    ?>
                                </td>
                                <td class="text-right"><?php $debitAmount = FinanceHelper::RangeWiseTotalAmountForTrialBalance($m,$fromDate,$toDate,$row->id,$row->code,'1');
                                    $d_total += $debitAmount;
                                    echo number_format($debitAmount);?>
                                </td>
                                <td class="text-right"><?php $creditAmount = FinanceHelper::RangeWiseTotalAmountForTrialBalance($m,$fromDate,$toDate,$row->id,$row->code,'0');
                                    $c_total += $creditAmount;
                                    echo number_format($creditAmount);?>
                                </td>
                            </tr>
                            <?php }?>
                            <tr>
                                <th colspan="3">Total</th>
                                <th class="text-right"><?php echo number_format($d_total);?></th>
                                <th class="text-right"><?php echo number_format($c_total);?></th>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>