<?php

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

use App\Models\JobOrder;


$counter = 0;

?>
@foreach($Branch as $Fil):
<?php

CommonHelper::companyDatabaseConnection($m);
$joborder = DB::table('job_order')->where('status', 1)->where('jo_status', 2)->where('invoice_created', 0)->where('branch_id',$Fil->id)->get();
CommonHelper::reconnectMasterDatabase();
if(count($joborder)>0){
?>
<?php echo Form::open(array('url' => 'sales/createInvoiceForm?m='.$m.'','id'=>'addInvoiceDetail', 'enctype' => 'multipart/form-data'));?>
<input type="hidden" name="_token" value="{{ csrf_token() }}">
<table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Job Order No</th>
    <th class="text-center">Ordered Date</th>
    <th class="text-center">Client Name</th>
    <th class="text-center">Branch Name</th>
    <th class="text-center">Client Job</th>

    <th class="text-center">Contact Person</th>

    <th class="text-center">Client Address</th>
    <th class="text-center">View</th>

    </thead>
    <tbody id="data">
    <?php

    $total=0;
    $paramOne = "pdc/viewJobOrderDetail?m=".$m;
    $Dcount = 1;
    ?>


    @foreach($joborder as $row)
        <?php $counter++;?>
        <tr title="{{$row->job_order_id}}">
            <td class="text-center">
                <input type="checkbox" value="{{$row->job_order_id}}" name="job_order_id[]"
                       onchange="CheckUncheck('<?php echo $counter?>','<?php echo $row->branch_id?>')" class="form-control AddRemoveClass<?php echo $row->branch_id?> AllCheckbox" id="<?php echo $row->branch_id?>" style="height: 30px;">{{$counter}}
                <input type="hidden" id="GetVal<?php echo $counter?>" value="<?php echo $row->branch_id?>">
            </td>
            <td class="text-center">{{ $row->job_order_no }}</td>
            <td title="{{$row->job_order_id}}" class="text-center"><?php  echo CommonHelper::changeDateFormat($row->date_ordered);?></td>
            <td class="text-center">
                <?php $Client = CommonHelper::get_single_row('client','id',$row->client_name);
                echo $Client->client_name;
                ?>
            </td>
            <td class="text-center">
                <?php $Branch = CommonHelper::get_single_row('branch','id',$row->branch_id);
                echo $Branch->branch_name;
                ?>
            </td>
            <td class="text-center">
                <?php $ClientJob = CommonHelper::get_single_row('client_job','id',$row->client_job);
                echo $ClientJob->client_job;
                ?>
            </td>
            <td class="text-center">{{ $row->contact_person }}</td>
            <td class="text-center">{{ $row->job_location }}</td>
            <td class="text-center">{{ $row->client_address }}</td>

            <td class="text-center">  <button onclick="showDetailModelOneParamerter('<?= $paramOne ?>','<?= $row->job_order_id ?>','Job Order')" type="button" class="btn btn-success btn-xs">View</button>  </td>

        </tr>

    @endforeach
    </tbody>
</table>


<?php //echo Form::close();
}
?>
@endforeach
<button type="submit" id="BtnCreateInvoice" class="btn btn-sm BtnEnDs BtnSub<?php echo $Branch->id?> btn-primary">Create Invoice</button>
<?php echo Form::close(); ?>
<script !src="">

    $(document).ready(function(){
      //  $('.BtnEnDs').prop('disabled',true);
    });

    var  temp = [];
    function CheckUncheck(Counter,Id)
    {
//        if($("input:checkbox:checked").length > 0)
//        {
//
//        }
//        else
//        {
//            temp = [];
//        }
//        $('.AllCheckbox').each(function()
//        {
//
//            if ($(this).is(':checked'))
//            {
//                $('.BtnSub'+Id).prop('disabled',false);
//
//            }
//            else
//            {
//                $('.BtnSub'+Id).prop('disabled',true);
//                //temp =[];
//            }
//
//        });
//
//
//        $(".AddRemoveClass"+Id).each(function() {
//            if ($(this).is(':checked')) {
//                var checked = ($(this).attr('id'));
//                temp.push(checked);
//
//                if(temp.indexOf(checked))
//                {
//                    if ($(this).is(':checked')) {
//                        alert('Please Checked Same Branch and then Create Invoice...!');
//                        $(this).prop("checked", false);
//                        $('.BtnSub'+Id).prop("disabled", true);
//                    }
//                }
//                else
//                {
//                    $('.BtnSub'+Id).prop("disabled", false);
//                }
//            }
//            else
//            {
//
//            }
//        });



    }


</script>

