<?php

use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;
use App\Models\NewJvs;
use App\Helpers\ReuseableCode;
use App\Models\NewPv;
use App\Models\NewRvs;


$view=ReuseableCode::check_rights(153);
$edit=ReuseableCode::check_rights(154);
$delete=ReuseableCode::check_rights(155);

$counter = 1;
$makeTotalAmount = 0;
        ?>
<?php

$from = new DateTime($FromDate);
$to = new DateTime($ToDate);
$interval = new DateInterval('P1D'); // 1 day interval

$current = clone $from;

while ($current <= $to) {
    $date= $current->format('Y-m-d');
    $Clause1 = '';
    $NewJvs=new NewJvs();
    $NewJvs=$NewJvs->SetConnection('mysql2');
    if($VoucherStatus == ''){$Clause1 = '';}
    else{$Clause1 = 'AND a.jv_status = '.$VoucherStatus;}

    if($AccountId !=""):

        $NewJvs= DB::Connection('mysql2')->select('select a.* from new_jvs a
        inner join new_jv_data b ON a.id=b.master_id
        inner join accounts c ON b.acc_id=c.id
        where a.status=1
        and c.id="'.$AccountId.'"
        '.$Clause1.'
        and a.jv_date = "'.$date.'"
        ');
    else:
    if($VoucherStatus !="")
    {
        $NewJvs=$NewJvs->where('status',1)->where('jv_status',$VoucherStatus)->where('jv_date',$date)->get();
    }
    else
    {
        $NewJvs=$NewJvs->where('status',1)->where('jv_date',$date)->get();
    }

    endif;


    foreach ($NewJvs as $row1) {
        ?>
        
        <tr class="tr<?php echo $row1->id ?>" id="tr<?php echo $row1->id ?>" title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" <?php if($row1->jv_status == 1):?>
        onclick="checkUncheck('1chk<?php echo $counter ?>','1row<?php echo $counter ?>')"<?php endif;?>>
            
            <td class="text-center"><?php echo $counter++;?></td>
            <td class="text-center"><?php echo strtoupper($row1->jv_no);?></td>
            <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->jv_date);?></td>
            <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_jv_data',$row1->id);?></td>
            <td class="text-center"><?php echo $row1->description;?></td>
            <td class="text-center">-</td>
            <td class="text-center">-</td>
            
            <?php //die();?>
        
            <td class="text-center status{{$row1->jv_no}}"><?php if($row1->jv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?></td>
            <?php   $count=CommonHelper::check_amount_in_ledger($row1->jv_no,$row1->id,2) ?>
           
        </tr>
        <?php
        }
        
        $Clause1 = '';
        $pvs=new NewPv();
        $pvs=$pvs->SetConnection('mysql2');
        if($VoucherStatus == ''){$Clause1 = '';}
        else{$Clause1 = 'AND a.pv_status = '.$VoucherStatus;}

        if($AccountId !=""):

           $pvs= DB::Connection('mysql2')->select('select a.* from new_pv a
            inner join new_pv_data b ON a.id=b.master_id
            inner join accounts c ON b.acc_id=c.id
            where a.status=1
            '.$Clause1.'
            and c.id="'.$AccountId.'"
            and a.pv_date = "'.$date.'"
            ');
        else:
        if($VoucherStatus !="")
        {
            $pvs=$pvs->where('status',1)->where('pv_status',$VoucherStatus)->where('pv_date',$date)->get();
        }
        else
        {
            $pvs=$pvs->where('status',1)->where('pv_date',$date)->get();
        }

        endif;

        foreach ($pvs as $row1) {
            ?>
            <tr title="<?php echo $row1->id ?>" id="1row<?php echo $counter ?>" >
            
                <td class="text-center"><?php echo $counter++;?></td>
                <td class="text-center"><?php echo strtoupper($row1->pv_no);?></td>
                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->pv_date);?></td>
                <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?></td>
                <td class="text-center"><?php echo $row1->description;?></td>
                <td class="text-center"><?php echo $row1->cheque_no;?></td>
                <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
                <td class="text-center status{{$row1->pv_no}}"><?php if($row1->pv_status == 2){echo "<span style='color:green;'>Approved</span>";} else{echo "<span style='color:red;'>Pending</span>";}?></td>
                <?php   $count=CommonHelper::check_amount_in_ledger($row1->pv_no,$row1->id,2) ?>
                
            </tr>
            <?php
            }
            $NewRvs=new NewRvs();
            $NewRvs=$NewRvs->SetConnection('mysql2');

            
            $Clause1 = '';
            if($VoucherStatus == ''){$Clause1 = '';}
            else{$Clause1 = 'AND a.rv_status = '.$VoucherStatus;}
            if ($AccountId != "") {
                $NewRvs = DB::Connection('mysql2')->select('
                    SELECT a.*
                    FROM new_rvs a
                    INNER JOIN new_rv_data b ON a.id = b.master_id
                    INNER JOIN accounts c ON b.acc_id = c.id
                    WHERE a.status = 1
                    '.$Clause1.'
                    AND c.id = "'.$AccountId.'"
                    AND a.rv_date = "'.$date.'"
                ');
            } else {
                if ($VoucherStatus != "") {
                    $NewRvs = $NewRvs->where('status', 1)
                        ->where('rv_status', $VoucherStatus)
                        ->where('rv_date', $date)
                        ->get();
                } else {
                    $NewRvs = $NewRvs->where('status', 1)
                            ->where('rv_date', $date)
                            ->get();
                }
            }  
            
            
            foreach ($NewRvs as $row1) {
                ?>
                <tr id="tr{{$row1->id}}">
                    {{--<td class="text-center">--}}
                    {{--< ?php if($row1->pv_status ==1):?>--}}
                    {{--<input name="checkbox[]" class="checkbox1" id="1chk< ?php echo $counter?>" type="checkbox" value="< ?php echo $row1->id?>" />--}}
                    {{--< ?php endif;?>--}}
                    {{--</td>--}}
                    <td class="text-center"><?php echo $counter++;?></td>
                    <td class="text-center"><?php echo strtoupper($row1->rv_no);?></td>
                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->rv_date);?></td>
                    <td class="text-center"><?php echo $Account = CommonHelper::debit_credit_amount('new_pv_data',$row1->id);?></td>
                    <td class="text-center"><?php echo $row1->description;?></td>
                    <td class="text-center"><?php echo $row1->cheque_no;?></td>
                    <td class="text-center"><?php echo FinanceHelper::changeDateFormat($row1->cheque_date);?></td>
                   
                    {{--<td class="text-center">< ?php echo $row1->description;?></td>--}}
                    <?php //die();?>
                
                    <td id="Append{{$row1->id}}" class="text-center status<?php echo $row1->rv_no?>">
                        <?php if($row1->rv_status == 1):?>
                        <span class="text-danger">Pending</span>
                        <?php else:?>
                        <span class="text-success">Approved</span>
                        <?php endif;?>
                    </td>
                    
                </tr>
                <?php
                }



    $current->add($interval);
}




?>
<tr>
    <th colspan="8" class="text-center">xxxxx</th>
</tr>