<?php
use App\Helpers\CommonHelper;

$type= Request::get('GetType');

?>

<div class="tb-report-wrap">
    <div class="tb-report-header">
        <h2 class="tb-company-name"><?php echo CommonHelper::get_company_name(Session::get('run_company'));?></h2>
        <h3 class="tb-report-title">Trial Balance</h3>
        <p class="tb-date-range"><?php echo date_format(date_create($from),'d-m-Y').' To '.date_format(date_create($to),'d-m-Y'); ?></p>
        <p class="tb-printed-on">Printed On: <?php echo date_format(date_create(date('Y-m-d')),'F d, Y'); ?></p>
    </div>

    <div class="tb-toolbar">
        <input type="button" class="btn btn-xs btn-danger" onclick="tableToExcel('table_export', 'Trial Balance')" value="Export to Excel">
    </div>

    <div class="tb-table-scroll">
    <table class="tb-table" id="table_export">
        <thead>
            <tr>
                <th>Sr.No</th>
                <th>Code</th>
                <th>Account Name</th>
                <th class="text-right">Open.Bal Dr</th>
                <th class="text-right">Open.Bal Cr</th>
                <th class="text-right">Dr During The Period</th>
                <th class="text-right">Cr During The Period</th>
                <th class="text-right">End.Dr</th>
                <th class="text-right">End.Cr</th>
            </tr>
        </thead>
        <tbody id="tbl_id">
<?php
        CommonHelper::companyDatabaseConnection($_GET['m']);
        $accounts =DB::select("SELECT a.id,a.name,a.code FROM accounts a where a.status = 1
         order by a.level1,a.level2,a.level3,a.level4,a.level5,a.level6,a.level7
         ");
        CommonHelper::reconnectMasterDatabase();

        $counter = 1;
        $paramOne = "fdc/getSummaryLedgerDetail?m=".$m;

        $opening_credit=0;
        $total_opening_debit=0;
        $total_opening_crdit=0;
        $total_tra_amount_debit=0;
        $total_tra_amount_credit=0;
        $total_closing_debit=0;
        $total_closing_credit=0;

        foreach($accounts as $row):

            $opening_debit=CommonHelper::get_opening_for_trial($from,$to,$m,$row->code,1);

            if ($opening_debit>0):
                if ($row->code=='1' || $row->code=='2' || $row->code=='3' || $row->code=='4' || $row->code=='5' || $row->code=='6'):
                    $total_opening_debit+=$opening_debit;
                endif;
            endif;

            $opening_credit=CommonHelper::get_opening_for_trial($from,$to,$m,$row->code,0);

            if ($opening_credit>0):
                if ($row->code=='1' || $row->code=='2' || $row->code=='3' || $row->code=='4' || $row->code=='5' || $row->code=='6'):
                    $total_opening_crdit+=$opening_credit;
                endif;
            endif;

            $opening=$opening_debit-$opening_credit;
            $tra_amount_debit=CommonHelper::get_amount($from,$to,$m,$row->code,1);

            if ($tra_amount_debit>0):
                if ($row->code=='1' || $row->code=='2' || $row->code=='3' || $row->code=='4' || $row->code=='5' || $row->code=='6'):
                    $total_tra_amount_debit+=$tra_amount_debit;
                endif;
            endif;

            $tra_amount_credit=CommonHelper::get_amount($from,$to,$m,$row->code,0);

            if ($tra_amount_credit>0):
                if ($row->code=='1' || $row->code=='2' || $row->code=='3' || $row->code=='4' || $row->code=='5' || $row->code=='6'):
                    $total_tra_amount_credit+=$tra_amount_credit;
                endif;
            endif;

            $tra_amount=$tra_amount_debit-$tra_amount_credit;

            $closing_debit=0;
            $closing_credit=0;
            $final_amount=$opening+$tra_amount;

            if ($final_amount>=0):
                $closing_debit=$final_amount;
            elseif($final_amount<0):
                $closing_credit=$final_amount;
                $closing_credit=$closing_credit*-1;
            else:
                $final_amount=0;
            endif;

            if ($closing_debit>=0):
                if ($row->code=='1' || $row->code=='2' || $row->code=='3' || $row->code=='4' || $row->code=='5' || $row->code=='6'):
                    $total_closing_debit+=$closing_debit;
                endif;
            endif;

            if ($closing_credit>0):
                if ($row->code=='1' || $row->code=='2' || $row->code=='3' || $row->code=='4' || $row->code=='5' || $row->code=='6'):
                    $total_closing_credit+=$closing_credit;
                endif;
            endif;
?>

@if($type=='active')
    @if ($opening_debit>0 || $opening_credit>0 || $tra_amount_debit >0 || $tra_amount_credit >0)
        <tr>
            <td class="text-center">{{$counter++}}</td>
            <td>{{'`'.$row->code}}</td>
            <td class="link_hide" onclick="showDetailModelOneParamerter('fdc/getSummaryLedgerDetail','<?php echo $from.','.$to.','.$row->code;?>','','<?php echo $_GET['m'] ?>')">{{CommonHelper::get_acc_name_space_wise($row->code,$row->name)}}</td>
            <td class="text-right">{{number_format($opening_debit,2)}}</td>
            <td class="text-right">{{number_format($opening_credit,2)}}</td>
            <td class="text-right">{{number_format($tra_amount_debit,2)}}</td>
            <td class="text-right">{{number_format($tra_amount_credit,2)}}</td>
            <td class="text-right">{{number_format($closing_debit,2)}}</td>
            <td class="text-right">{{number_format($closing_credit,2)}}</td>
        </tr>
    @endif
@else
    <tr>
        <td class="text-center">{{$counter++}}</td>
        <td>{{$row->code}}</td>
        <td>{{CommonHelper::get_acc_name_space_wise($row->code,$row->name)}}</td>
        <td class="text-right">{{number_format($opening_debit,2)}}</td>
        <td class="text-right">{{number_format($opening_credit,2)}}</td>
        <td class="text-right">{{number_format($tra_amount_debit,2)}}</td>
        <td class="text-right">{{number_format($tra_amount_credit,2)}}</td>
        <td class="text-right">{{number_format($closing_debit,2)}}</td>
        <td class="text-right">{{number_format($closing_credit,2)}}</td>
    </tr>
@endif

<?php endforeach;?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="3">Total</td>
                <td class="text-right">{{number_format($total_opening_debit,2)}}</td>
                <td class="text-right">{{number_format($total_opening_crdit,2)}}</td>
                <td class="text-right">{{number_format($total_tra_amount_debit,2)}}</td>
                <td class="text-right">{{number_format($total_tra_amount_credit,2)}}</td>
                <td class="text-right">{{number_format($total_closing_debit,2)}}</td>
                <td class="text-right">{{number_format($total_closing_credit,2)}}</td>
            </tr>
        </tfoot>
    </table>
    </div>
</div>