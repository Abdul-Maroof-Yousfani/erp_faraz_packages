<?php
use App\Helpers\CommonHelper;
$Counter = 1;
?>
<table class="table table-bordered sf-table-list">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Task Date</th>
    <th class="text-center">Client</th>
    <th class="text-center">Description</th>
    <th class="text-center">Account Officer</th>
    <th class="text-center">Vendor</th>
    <th class="text-center">Region</th>
    <th class="text-center hidden-print">Status</th>
    <th class="text-center hidden-print">Action</th>
    </thead>
    <tbody id="GetData">

    <?php
    // print_r($DailyTask); die;
    foreach($DailyTask as $key => $value):
    $region_name = CommonHelper::get_rgion_name_by_id($value->region);
    $client = CommonHelper::get_client_name_by_id($value->client);
    $acc_officer = CommonHelper::get_paid_to_name($value->acc_officer,1);
    if($value->action==1) { $s="Pending"; } elseif ($value->action==2) { $s="Job Done"; } elseif ($value->action==3) { $s="Hold"; }

    ?>
    <tr class="text-center">
        <td> <?php echo $Counter++; ?> </td>
        <td> <?php echo date('d-m-Y',strtotime($value->task_date)); ?> </td>
        <td> <?php echo $client; ?> </td>
        <td> <?php echo $value->description; ?> </td>
        <td> <?php echo $acc_officer; ?> </td>
        <td> <?php echo $value->vendor; ?> </td>
        <td> <?php echo $region_name->region_name; ?> </td>
        <td id="change<?php echo $value->id; ?>"> <?php echo $s; ?> </td>
        <td><div class="btn-group">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"> ACTION <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-default pull-right sf-dropdown-ul" role="menu">
                    <li>
                        <a onclick="job_Done('<?php echo $value->id; ?>')">Job Done</a>
                    </li>
                </ul>
            </div>
        </td>
    </tr>
    <?php endforeach; ?>

    </tbody>
</table>

<script>
    function job_Done(id)
    {
        if(confirm("Want To Submit...? Press ok")) {
            var m = '<?php echo $_GET['m'] ?>';
            $.ajax({
                url: '<?php echo url('/')?>/reports/job_Done',
                type: "GET",
                data: {id:id, m:m },
                success: function (data) {
                    alert("Successfully Deleted");
                    $("#change"+id).html('Job Done');
                }
            });
        } else {
            alert('JVC Cancel');
        }
    }
</script>