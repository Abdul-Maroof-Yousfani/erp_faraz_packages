<?php
$MasterId = $_GET['MultiValues'];
$m = $_GET['m'];
//$Detail = DB::Connection('mysql2')->table('commision_data')->where('master_id',$MasterId)->where('status',1)->get();

$data=DB::connection('mysql2')->select('select a.id,a.rv_no,d.gi_no,d.id as si_id,c.received_amount,d.model_terms_of_payment,d.gi_date,
        a.rv_date,e.name as customer_name,c.id as brigde_id,f.percent,f.commision_amount as com_amount from new_rvs a
		inner join
		brige_table_sales_receipt c
		on
		a.id=c.rv_id
		inner join
		sales_tax_invoice d
		on
		c.si_id=d.id
		inner join
		customers e
		on
		e.id=d.buyers_id
		inner join
		commision_data f
		on
		c.id=f.brigde_id
		where a.status=1
 		and a.sales=1
 		and f.status=1
 		and f.master_id="'.$MasterId.'"
		and a.rv_status=2');
?>
<table class="table table-bordered sf-table-list AutoCounter " >
    <thead>
        <th class="text-center">Sr No</th>
        <th class="text-center">RV No</th>
        <th class="text-center">SI No</th>
        <th class="text-center">Cutomer Name</th>
        <th class="text-center">SI Amount</th>
        <th class="text-center">Return Amount</th>
        <th class="text-center">Net Amount</th>
        <th class="text-center">Perent</th>
        <th class="text-center">Commision</th>
    </thead>
    <tbody id="{{$MasterId}}">
    <?php
            $count = 1;
            $total_com=0;
    foreach($data  as $row):?>

    <?php  $si_amount=DB::Connection('mysql2')->table('sales_tax_invoice_data')->where('status',1)->where('master_id',$row->si_id)->sum('amount'); ?>
        <tr  class="text-center">
            <td><?php echo $count++;?></td>
            <td style="cursor: pointer" onclick="showDetailModelOneParamerter('sdc/viewReceiptVoucher','{{$row->id}}','View Bank Reciept Voucher Detail','1','')" class="text-center">{{strtoupper($row->rv_no)}}</td>
            <td style="cursor: pointer" onclick="showDetailModelOneParamerter('sales/viewSalesTaxInvoiceDetail','<?php echo $row->si_id ?>','View Sales Tax Invoice')" class="text-center">{{strtoupper($row->gi_no)}}</td>
            <td>{{$row->customer_name}}</td>
            <?php
            $return_data=DB::Connection('mysql2')->table('credit_note as a')->
            join('credit_note_data as b','a.id','=','b.master_id')
                    ->select(DB::raw('SUM(b.net_amount) As return_amount'))
                    ->where('a.status',1)
                    ->where('a.si_id',$row->si_id)
                    ->first();

            $return_amount= $return_data->return_amount;
            ?>
            <td  class="text-center">{{number_format($si_amount,2)}}</td>
            <td class="text-center">{{number_format($return_amount,2)}}</td>
            <?php $net_amount=$si_amount-$return_amount; ?>
            <td class="text-center">{{number_format($net_amount,2)}}</td>

            <td><?php echo $row->percent?></td>
            <td><?php echo number_format($row->com_amount,2);$total_com+=$row->com_amount?></td>
        </tr>
    <?php endforeach;?>
    <tr style="background-color: lavender;font-size: large;font-weight: bold">
        <td class="text-center"  colspan="8">Total</td>
        <td class="text-center" colspan="1">{{number_format($total_com,2)}}</td>
    </tr>
    </tbody>
</table>
<div class="row">
    <div  class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <input onclick="com_delete('{{$MasterId}}')" style="width: 100%"  class="btn btn-danger" value="Delete">

    </div>
</div>
<script>
    function com_delete(id)
    {


        if (confirm('Are you sure you want to delete this request')) {

            $.ajax({
                url: '{{url('fdc/com_delete')}}',
                type: 'get',
                data: {id: id},
                success: function (response) {
                    $('#' + id).remove();
                }

            })
        }
        else
        {

        }
    }
</script>