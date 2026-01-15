<?php
use App\Helpers\CommonHelper;


$counter = 1;?>

<?php
$count=1;
foreach($jvs as $row):
//$paid_amount=CommonHelper::check_payment($row->id);
 $supplier_id=$row->supplier_id;?>
<tr title="<?php echo $row->id?>" class="colour" id="{{$row->id}}">
    <?php
    $jv_amount=CommonHelper::get_breakup_amount($row->main_id,0,$supplier_id);
    $paid_amount=   CommonHelper::get_breakup_amount($row->main_id,1,$supplier_id);
    $remaining_amount=$jv_amount-$paid_amount;
    ?>

    <td class="text-center">
        <?php if ($remaining_amount>0): ?>
        <input  name="checkbox[]" onclick="check(),supplier_check('<?php echo $row->supplier_id?>',this.id)" class="checkbox1 <?php echo $row->supplier_id?>" id="1chk<?php echo $counter?>" type="checkbox"
               value='<?php echo $row->main_id ?>'>
        <?php endif; ?>

    </td>
    <td  class="text-center">{{$count++}}</td>
   <td style="cursor: pointer" onclick="get_ledger_refrence_wise('<?php echo $row->main_id ?>','<?php echo  $supplier_id ?>')">{{$row->main_id}}</td>
    <td class="text-center"><?php echo strtoupper($row->voucher_no)?></td>
    <td class="text-center"><?php echo  CommonHelper::changeDateFormat($row->voucher_date)?></td>
    <td class="text-center"><?php echo strtoupper($row->slip_no)?></td>
    <td class="text-center"></td>
    <td class="text-center"></td>

    <td class="text-right"><?php echo number_format($jv_amount,2) ?></td>
    <td class="text-right"><?php echo number_format($paid_amount,2);
        //  $remaining_amount=$row['total_net_amount']-$paid_amount;
        ?></td>
    <td class="text-right"><?php  echo number_format($remaining_amount,2) ?></td>
    <input type="submit" value="CREATE Payment" class="btn btn-xs btn-success pull-left" id="add" disabled="">
        <input type="hidden" name="supplier" value="<?php echo $supplier_id ?>">
</tr>


<?php endforeach;?>








<script>
    var supplier='';
    var counter=1;
    function supplier_check(supp,id)
    {

        if(!$(".checkbox1").is(':checked'))
        {

            counter=1;
            $('#'+id).closest("tr").css("background-color","white");
        }


        if ($("#"+id).is(':checked'))
        {
            $('#'+id).closest("tr").css("background-color","#39CCCC");
            var supplier_id = supp;
            if (supplier_id != supplier && counter > 1)
            {

                alert('SUPPLIER SHOULD BE SAME');
                $('#'+id).prop('checked', false);
                $('#'+id).closest("tr").css("background-color","white");
                return false;
            }
            supplier = supplier_id;

            counter++;
        }

    }


    function check()
    {





        if($(".checkbox1").is(':checked'))
        {
            $("#add").prop('disabled', false);
        }
        else
        {
            $("#add").prop('disabled', true);
        }
    }










    function check2()
    {

        if($(".checkbox2").is(':checked'))
        {

            $("#add1").prop('disabled', false);

        }
        else
        {
            $("#add1").prop('disabled', true);
        }
    }

    function change(type)
    {
        var url='<?php echo url('/')?>/finance/createPaymentForOutstanding/'+type;
        var action = $('form').attr('action');
        $('form').attr('action',url);
    }
</script>
<script>
    function myFunction()
    {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("myInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td")[7];
            if (td) {
                txtValue = td.textContent || td.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    tr[i].style.display = "";
                } else {
                    tr[i].style.display = "none";
                }
            }
        }
    }

    $("td").click(function(e) {

        var chk = $(this).closest("tr").find("input:checkbox").get(0);
        if(e.target != chk)
        {
            chk.checked = !chk.checked;
            var id=chk.id;

            var cls=chk.className;
            cls=cls.split(' ');
            cls=cls[1];
            $(this).closest("tr").css("background-color","#39CCCC");
            $("#add").prop('disabled', false);
            if(chk.checked == false)
            {
                $(this).closest("tr").css("background-color","white");
                //$("#add").prop('disabled', true);
            }


        }

        supplier_check(cls,id);
    });
    function get_ledger_refrence_wise(breakup_id,supplier_id)
    {
       

        $(".colour").css("background-color","");
      //  $("#"+breakup_id).css("background-color","lightblue");
        $.ajax({
            url: '<?php echo url('/')?>/pdc/get_ledger_refrence_wise',
            type: "GET",
            data: { breakup_id:breakup_id,supplier_id:supplier_id},
            success:function(data)
            {

                $('#data1').html(data);

            }
        });
    }
</script>
