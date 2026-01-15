<?php
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;
use App\Helpers\SalesHelper;
use App\Helpers\FinanceHelper;


if($tab == 'jv'):?>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="bankPaymentVoucherList">
        <thead>
            <th colspan="6" class="text-center"><?php echo $FinanceLable;?></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">J.V. No.</th>
        <th class="text-center">J.V. Date</th>
        <th class="text-center">Debit/Credit</th>
        <th class="text-center">Voucher Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody id="data">
        <?php
        $counter = 1;
        $makeTotalAmount = 0;
        foreach ($MultiData as $row1) {
        ?>
        <tr class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>">
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->jv_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->jv_date);?></td>
            <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_jv_data',$row1->id);?></td>

            <td class="text-center status{{$row1->jv_no}}"><?php if($row1->jv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?></td>
            <?php   $count=CommonHelper::check_amount_in_ledger($row1->jv_no,$row1->id,2) ?>
            <td class="text-center hidden-print">
                <a href="<?php echo  URL::to('/finance/editJv/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs BtnHide<?php echo $row1->jv_no?>">Edit</a>
                <input class="btn btn-xs btn-danger BtnHide<?php echo $row1->jv_no?>" type="button"
                       onclick="DeleteJvActivity('<?php echo $row1->id;?>','<?php echo $row1->jv_no?>','<?php echo $row1->jv_date?>','<?php echo CommonHelper::GetAmount('new_jv_data',$row1->id)?>')"
                       value="Delete" />
                <a onclick="showDetailModelOneParamerter('fdc/viewJournalVoucherDetail','<?php echo $row1->id;?>','View Journal Voucher Detail','<?php echo $m?>','')" class="btn btn-xs btn-success">View</a>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="8" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'bpv'):?>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="TableExportToCsv">
        <thead>
            <th colspan="9" class="text-center"><?php echo $FinanceLable;?></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">P.V. No.</th>
        <th class="text-center">P.V. Date</th>
        <th class="text-center">Debit/Credit</th>
        <th class="text-center">Ref / Bill No.</th>
        <th class="text-center">Cheque No</th>
        <th class="text-center">Cheque Date</th>
        <th class="text-center">Voucher Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody id="data">
        <?php
        $counter = 1;
        $makeTotalAmount = 0;
        foreach ($MultiData as $row1) {
        ?>
        <tr @if ($row1->type==2) style="background-color: darkgray" @endif
        class="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>">
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
            <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?></td>
            <td class="text-center"><?php echo $row1->bill_no;?></td>
            <td class="text-center"><?php echo $row1->cheque_no;?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
            <td class="text-center status{{$row1->pv_no}}"><?php if($row1->pv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?></td>
            <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
            <td class="text-center hidden-print">

                <a  href="<?php echo  URL::to('/finance/editBankPaymentNew/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs BtnHide<?php echo $row1->pv_no?>">
                    <span class="glyphicon glyphicon-eye-pencil">Edit</span>
                </a>
                <input class="btn btn-xs btn-danger BtnHide<?php echo $row1->pv_no?>" type="button"
                       onclick="DeletePvActivity('<?php echo $row1->id;?>','<?php echo $row1->pv_no?>','<?php echo $row1->pv_date?>','<?php echo CommonHelper::GetAmount('new_pv_data',$row1->id)?>')"
                       value="Delete" />

                <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetail','<?php echo $row1->id;?>','View Bank P.V Detail','<?php echo $_GET['m']?>','')" class="btn btn-xs btn-success">
                    <span class="glyphicon glyphicon-eye-open"> V</span>
                </a>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="10" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'cpv'):?>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="TableExportToCsv">
        <thead>
        <th colspan="7" class="text-center"><?php echo $FinanceLable;?></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">P.V. No.</th>
        <th class="text-center">P.V. Date</th>
        <th class="text-center">Debit/Credit</th>
        <th class="text-center">Ref / Bill No.</th>
        <th class="text-center">Voucher Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody id="data">
        <?php
        $counter = 1;
        $makeTotalAmount = 0;

        foreach ($MultiData as $row1) {
        ?>
        <tr @if ($row1->type==2) style="background-color: darkgray" @endif class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" >
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
            <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?></td>
            <td class="text-center"><?php echo $row1->bill_no;?></td>

            <td class="text-center status{{$row1->pv_no}}"><?php if($row1->pv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?></td>
            <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
            <td class="text-center hidden-print">
                <a onclick="showDetailModelOneParamerter('fdc/viewBankPaymentVoucherDetail','<?php echo $row1->id;?>','View Cash P.V Detail','<?php echo $_GET['m']?>')" class="btn btn-xs btn-success"><span class="glyphicon glyphicon-eye-open"> V</span></a>

                <a href="<?php echo url('finance/editCashPVForm/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs BtnHide<?php echo $row1->pv_no?>">Edit</a>

                <input class="btn btn-xs btn-danger BtnHide<?php echo $row1->pv_no?>" type="button" onclick="DeletePvActivity('<?php echo $row1->id;?>','<?php echo $row1->pv_no?>','<?php echo $row1->pv_date?>','<?php echo CommonHelper::GetAmount('new_pv_data',$row1->id)?>')" value="Delete" />

            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="8" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'brv'):?>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="TableExportToCsv">
        <thead>
            <th colspan="9" class="text-center"><?php echo $FinanceLable?></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">R.V. No.</th>
        <th class="text-center">R.V. Date</th>
        <th class="text-center">Ref / Bill No.</th>
        <th class="text-center">Cheque No</th>
        <th class="text-center">Cheque Date</th>
        <th class="text-center">Debit/Credit</th>
        <th class="text-center">Voucher Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody id="data">
        <?php
        $counter = 1;
        $makeTotalAmount = 0;
        foreach ($MultiData as $row1) {
        ?>
        <tr class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" >
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
            <td class="text-center"><?php echo $row1->ref_bill_no;?></td>
            <td class="text-center"><?php echo $row1->cheque_no;?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
            <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_rv_data',$row1->id);?></td>

            <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
                <?php if($row1->rv_status == 1):?>
                <span class="badge badge-warning " style="background-color: #fb3 !important;">Pending</span>
                <?php else:?>
                <span class="badge badge-success" style="background-color: #00c851 !important">Approved</span>
                <?php endif;?>
            </td>
            <td class="text-center hidden-print">
                <?php if($row1->rv_status ==1):?>
                <a href="<?php echo  URL::to('/finance/editBankRv/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs BtnHide<?php echo $row1->rv_no?>">Edit</a>
                <input class="btn btn-xs btn-danger BtnHide<?php echo $row1->rv_no?>" type="button"
                       onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data',$row1->id)?>')"
                       value="Delete" />
                <?php endif;?>
                <a onclick="showDetailModelOneParamerter('fdc/viewBankRvDetailNew','<?php echo $row1->id;?>','View Bank Reciept Voucher Detail','<?php echo $m?>','')" class="btn btn-xs btn-success">View</a>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="8" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php elseif($tab == 'crv'):?>
<div class="table-responsive">
    <h5 style="text-align: center" id="h3"></h5>
    <table class="table table-bordered sf-table-list" id="TableExportToCsv">
        <thead>
            <th colspan="7" class="text-center"><?php echo $FinanceLable?></th>
        </thead>
        <thead>
        <th class="text-center">S.No</th>
        <th class="text-center">R.V. No.</th>
        <th class="text-center">R.V. Date</th>
        <th class="text-center">Ref / Bill No.</th>
        <th class="text-center">Debit/Credit</th>
        <th class="text-center">Voucher Status</th>
        <th class="text-center hidden-print">Action</th>
        </thead>
        <tbody id="data">
        <?php
        $counter = 1;
        $makeTotalAmount = 0;
        foreach ($MultiData as $row1) {
        ?>
        <tr>
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
            <td class="text-center"><?php echo $row1->ref_bill_no;?></td>
            <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_rv_data',$row1->id);?></td>

            <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
                <?php if($row1->rv_status == 1):?>
                <span class="badge badge-warning " style="background-color: #fb3 !important;">Pending</span>
                <?php else:?>
                <span class="badge badge-success" style="background-color: #00c851 !important">Approved</span>
                <?php endif;?>
            </td>
            <td class="text-center hidden-print">
                <?php if($row1->rv_status ==1):?>
                <a href="<?php echo  URL::to('/finance/editCashRv/'.$row1->id.'?m='.$m); ?>" type="button" class="btn btn-primary btn-xs BtnHide<?php echo $row1->rv_no?>">Edit</a>
                <input class="btn btn-xs btn-danger BtnHide<?php echo $row1->rv_no?>" type="button"
                       onclick="DeleteRvActivity('<?php echo $row1->id;?>','<?php echo $row1->rv_no?>','<?php echo $row1->rv_date?>','<?php echo CommonHelper::GetAmount('new_rv_data',$row1->id)?>')"
                       value="Delete" />
                <?php endif;?>
                <a onclick="showDetailModelOneParamerter('fdc/viewCashRvDetailNew','<?php echo $row1->id;?>','View Cash Reciept Voucher Detail','<?php echo $m?>','')" class="btn btn-xs btn-success">View</a>
            </td>
        </tr>
        <?php
        }
        ?>
        <tr>
            <th colspan="8" class="text-center">xxxxx</th>
        </tr>
        </tbody>
    </table>
</div>
<?php else:?>

<?php endif;?>
