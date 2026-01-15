<?php
use App\Helpers\CommonHelper;
use App\Models\Account;

$Accounts=new Account();
$Accounts = $Accounts->SetConnection('mysql2');


$from = $_GET['from_date'];
$to = $_GET['to_date'];

?>
<script>

</script>
<div id="content1">
    <h5 class="text-center topp"><b>Income Statment</b></h5>
    <b>	<h5 class="text-center"><?php echo  "For The Period Ended".' '.date_format(date_create($from),'d - M -Y').' '."-".' '.date_format(date_create($to),'y- M -Y') ?></h5></b>
    <table class="" id="table_export1" style="background:#FFF !important;">
        <thead>
        <tr>
            <th
                    class=""><b>Revenue</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>




        </tr>
        </thead>
        <tbody id="tbl_id">
        <?php

        $data = $Accounts->where('status',1)->where('code', 'like', '4-%')->get();

        //$data=$this->db->query('select id,name,code from accounts where status="active" and code IN ("5-1","5-2","5-3","5-4","5-5","5-6","5-9","5-10")');
        //$num_rows= $data->num_rows();
        $counter=1;
        $total_revenues=0;
        foreach($data as $row):
        $counter++;
        ?>
        <tr>

            <td class="col-sm-3"><?php echo  ucwords($row['name']); ?></td>
            <?php $amount= CommonHelper::income_statment($from,$to,$row->id,$_GET['m']);
            $total_revenues+=$amount;
            ?>
            <td class="text-right col-sm-3"><?php
                if ($amount >0):
                    echo  number_format($amount,2);
                endif;

                if ($amount<0):
                    echo number_format($amount*-1);
                    $total_revenues=$total_revenues*-1;
                endif;


                ?>   </td>
        </tr>
        <?php  endforeach;
        die();
        ?>

                <!--
	<tr>
	<td class="text-center col-sm-4" >Total</td>
	<td  class="text-center col-sm-6" >< ?php

	 if ($total_amount >0):
	 echo number_format($total_amount,2);
	 $total_revenue=$total_amount;
	 endif;

	 if ($total_amount <0):
	  echo number_format($total_amount*-1,2);
	  $total_revenue=$total_amount*-1;
	 endif;
	 ?></td>
</tr>
<!-->

        <tr>
            <th><b>Evenet Income</b></th>
            <th></th>
            <th></th>
            <th></th>


        </tr>
        <?php
        $event_program=$this->db->query('select id,name,code from accounts where status="active" and code IN ("5-7-1","5-7-2","5-7-3")');

        $counter=1;
        $amount=0;
        //$total_amount=0;
        foreach($event_program->result_array() as $row):

        ?>

        <tr>

            <td class="col-sm-3"><?php echo  ucwords($row['name']); ?></td>

            <?php $amount= $this->crud_model->income_statment($from,$to,$row['id']);
            $total_eventt+=$amount;
            ?>
            <td  <?php  if ($event_program->num_rows()==$counter): ?>style="text-decoration:underline ";<?php endif; ?> class="col-sm-2"><?php
                if ($amount >=0):
                    echo number_format($amount,2);
                endif;

                if ($amount<0):
                    echo number_format($amount*-1);
                endif;

                ?>   </td>
        </tr>
        <?php    $counter++; endforeach;$total_eventt ?>

        <tr>
            <td  class="col-sm-3"><b>Total Event Income &nbsp;&nbsp;&nbsp;</b></td>
            <td style="text-decoration: underline" class="col-sm-3 text-right"><?php

                if ($total_eventt >=0):
                    echo '<b>'.number_format($total_eventt,2).'</b>';
                    $total_event_program=$total_amount;
                endif;

                if ($total_eventt <0):
                    echo '<b>'.number_format($total_eventt*-1,2).'</b>';
                    $total_event_program=$total_amount*-1;
                endif;
                ?></td>
        </tr>

        <?php $revenue_event=$total_eventt+$total_revenues; ?>
        <tr>
            <td  class="col-sm-3"><b>Total  Income &nbsp;&nbsp;&nbsp;</b></td>
            <td class="col-sm-3">&nbsp;</td>
            <td style="text-decoration: underline" class="col-sm-1 text-right"><?php

                if ($revenue_event >=0):
                    echo '<b>'.number_format($revenue_event,2).'</b>';

                endif;

                if ($revenue_event <0):
                    echo '<b>'.number_format($revenue_event*-1,2).'</b>';

                endif;
                ?></td>
        </tr>

        <tr>
            <th><b>Cost Of Goods Sold</b></th>
            <th></th>
            <th></th>
            <th></th>


        </tr>


        <?php
        $cogs=$this->db->query('select id,name,code from accounts where status="active" and code IN ("4-9","4-10","4-11")');

        $counter=1;
        $total_amount=0;
        foreach($cogs->result_array() as $row):
        ?>

        <tr>

            <td class="col-sm-3"><?php echo  ucwords($row['name']); ?></td>
            <?php $amount= $this->crud_model->income_statment($from,$to,$row['id']);
            $total_amount+=$amount;
            ?>
            <td <?php  if ($event_program->num_rows()==$counter): ?>style="text-decoration:underline ";<?php endif; ?> class="col-sm-2"><?php
                if ($amount >=0):
                    echo number_format($amount,2);
                endif;

                if ($amount<0):
                    echo '('.number_format($amount*-1).')';
                endif;

                ?>   </td>
        </tr>
        <?php $counter++; endforeach ?>
        <tr>
            <td class="col-sm-3"><b>Total Cost Of Goods Sold</b></td>
            <td  class="col-sm-3 text-right"><?php
                $total_cogs=$total_amount;
                if ($total_cogs >=0):
                    echo '<b>'.number_format($total_cogs,2).'</b>';
                endif;

                if ($total_cogs <0):
                    echo '<b>'.'('.number_format($total_cogs*-1,2).')'.'</b>';
                endif;
                ?></td>
        </tr>



        </tbody>



        <thead>
        <tr>
            <th><b>ADMIN & GENERAL EXPENSES</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <tr>
            <!--
                    <th class="text-center col-sm-1">Acc Name</th>
                    <th class="text-center col-sm-1">Amount</th>
            <!-->

        </tr>
        </thead>
        <tbody id="tbl_id">
        <?php
        $total_amount=0;
        $data=$this->db->query('select id,name,code from accounts where status="active" and parent_code="4-1" or code In ("4-3","4-4","4-5","4-7")');

        $counter=1;

        foreach($data->result_array() as $row):
        ?>
        <tr>

            <td class="col-sm-3"><?php echo  ucwords($row['name']); ?></td>
            <?php $amount= $this->crud_model->income_statment($from,$to,$row['id']);
            $total_amount+=$amount;
            ?>
            <td <?php  if ($data->num_rows()==$counter): ?>style="text-decoration:underline ";<?php endif; ?> class=""><?php
                if ($amount >=0):
                    echo number_format($amount,2);
                endif;

                if ($amount<0):
                    echo number_format($amount*-1);
                endif;

                ?>   </td>
        </tr>
        <?php $counter++; endforeach; ?>
        <tr>
            <td class="col-sm-3"><b>Total Admin & General Expense</b></td>
            <td  class="col-sm-3 text-right"><?php

                if ($total_amount >0):
                    echo '<b>'.number_format($total_amount,2).'</b>';
                    $total_admin_general_exp=$total_amount;
                endif;

                if ($total_amount <0):
                    echo '<b>'.number_format($total_amount*-1,2).'</b>';
                    $total_admin_general_exp=$total_amount*-1;
                endif;
                ?></td>
        </tr>


        <tr>
            <th><b>REPAIR AND MAINTENANCE</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <?php

        $total_amount=0;
        $data=$this->db->query('select id,name,code from accounts where status="active" and parent_code="4-2"');

        $counter=1;

        foreach($data->result_array() as $row):

        ?>
        <tr>

            <td class="col-sm-3"><?php echo  ucwords($row['name']); ?></td>
            <?php $amount= $this->crud_model->income_statment($from,$to,$row['id']);
            $total_amount+=$amount;
            ?>
            <td  <?php if ($data->num_rows()==$counter): ?>style="text-decoration: underline"<?php endif; ?> class="col-sm-2"><?php
                if ($amount >=0):
                    echo number_format($amount,2);
                endif;

                if ($amount<0):
                    echo number_format($amount*-1);
                endif;

                ?>   </td>
        </tr>
        <?php $counter++; endforeach; ?>
        <tr>
            <td class="col-sm-3"><b>Total Repair & Maintenance</b></td>
            <td style="text-decoration: underline"  class="col-sm-3 text-right"><?php

                if ($total_amount >=0):
                    echo '<b>'.number_format($total_amount,2).'</b>';
                    $total_RMP=$total_amount;
                endif;

                if ($total_amount <0):
                    echo '<b>'.number_format($total_amount*-1,2).'</b>';
                    $total_RMP=$total_amount*-1;
                endif;
                ?></td>
        </tr>


        <tr>
            <td><b>Total Expense</b></td>
            <td class="col-sm-3">&nbsp;&nbsp;</td>
            <td style="text-decoration: underline" class="col-sm-1 text-right"><?php $total_exp=$total_RMP+$total_admin_general_exp+$total_cogs;
                echo '<b>'.number_format($total_exp).'</b>';
                ?></td>
        </tr>


        <tr>
            <td class="col-sm-3"><b>Net Operating Income</b></td>
            <td class="col-sm-3">&nbsp;&nbsp;</td>
            <td class="col-sm-1 text-right"><?php $net_operating=$revenue_event-$total_exp;
                echo '<b>'.number_format($net_operating).'</b>';
                ?></td>
        </tr>

        <!--other income><!-->


        <tr>
            <th><b>Other Income</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <?php

        $total_amount=0;
        $data=$this->db->query('select id,name,code from accounts where status="active" and parent_code="5-8"');

        $counter=1;

        foreach($data->result_array() as $row):
        ?>
        <tr>

            <td class="col-sm-3"><?php echo  ucwords($row['name']); ?></td>
            <?php $amount= $this->crud_model->income_statment($from,$to,$row['id']);
            $total_amount+=$amount;
            ?>
            <td <?php if ($data->num_rows()==$counter): ?>style="text-decoration: underline"<?php endif; ?>  class="col-sm-2"><?php
                if ($amount >=0):
                    echo number_format($amount,2);
                endif;

                if ($amount<0):
                    echo number_format($amount*-1);
                endif;

                ?>   </td>
        </tr>
        <?php $counter++; endforeach; ?>
        <tr>
            <td class="col-sm-3"><b>Total Other Income</b></td>
            <td  class="col-sm-3 text-right"><?php

                if ($total_amount >=0):
                    echo '<b>'.number_format($total_amount,2).'</b>';
                    $other_income=$total_amount;
                endif;

                if ($total_amount <0):
                    echo '<b>'.number_format($total_amount*-1,2).'</b>';
                    $other_income=$total_amount*-1;
                endif;
                ?></td>
        </tr>
        <tr>





            <th><b>Bank Charges</b></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        <?php

        $total_amount=0;
        $data=$this->db->query('select id,name,code from accounts where status="active" and code="4-6"');

        $counter=1;

        foreach($data->result_array() as $row):
        ?>
        <tr>

            <td class="col-sm-3"><?php echo  ucwords($row['name']); ?></td>
            <?php $amount= $this->crud_model->income_statment($from,$to,$row['id']);
            $total_amount+=$amount;
            ?>
            <td style="text-decoration: underline" class="col-sm-2"><?php
                if ($amount >=0):
                    echo number_format($amount,2);
                endif;

                if ($amount<0):
                    echo number_format($amount*-1);
                endif;

                ?>   </td>
        </tr>
        <?php endforeach; ?>
        <tr>
            <td class="col-sm-3"><b>Total Bank Charges</b></td>
            <td style="text-decoration: underline;"  class="col-sm-3 text-right"><?php

                if ($total_amount >=0):
                    echo '<b>'.number_format($total_amount,2).'</b>';
                    $bank_charges=$total_amount;
                endif;

                if ($total_amount <0):
                    echo '<b>'.number_format($total_amount*-1,2).'</b>';
                    $bank_charges=$total_amount*-1;
                endif;
                ?></td>
        </tr>
        <tr>

        <tr>
            <td class="col-sm-3"><b>&nbsp;</b></td>
            <td class="col-sm-3"><b>&nbsp;</b></td>
            <td style="text-decoration: underline" class="col-sm-1 text-right"><?php $other_less_bank_charges=$other_income-$bank_charges;
                echo '<b>'.number_format($other_less_bank_charges,2).'</b>';
                ?></td>
        </tr>

        <tr>
            <th><b>SURPLUS</b></th>
            <td class="col-sm-3">&nbsp;</td>
            <td style="
     text-decoration-line: underline;
  text-decoration-style: double;" class="col-sm-1 text-right"><?php $total_income=$other_less_bank_charges+$net_operating;
                echo '<b>'.number_format($total_income).'</b>';
                ?></td>
        </tr>
        </tbody>
    </table>
</div>
