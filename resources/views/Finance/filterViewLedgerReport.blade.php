<?php 
	$counter = 1;
?>
<tr>
	<td></td>
</tr>
<?php 
	foreach ($transactions as $row1) {
?>
	<tr>
		<td class="text-center"><?php echo $counter++;?></td>
		<td><?php echo Helpers::checkLedgerVoucherType($row1['voucher_type']);?></td>
		<td class="text-center"><?php echo $row1['voucher_no'].'<br />'.$row1['v_date'];?></td>
		<td><?php echo $row1['particulars'];?></td>
		<td class="text-right"><?php echo $row1['particulars'];?></td>
		<td class="text-right"><?php echo $row1['particulars'];?></td>
		<td class="text-right"><?php echo $row1['particulars'];?></td>
		<td class="text-center">Action</td>	
	</tr>
<?php
	}
?>