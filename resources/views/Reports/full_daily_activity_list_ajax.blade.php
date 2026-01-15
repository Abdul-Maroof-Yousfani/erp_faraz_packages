<?php
use App\Helpers\CommonHelper;
$Counter = 1;
     $m = $_GET['m'];
?>

<button type="button" class="btn btn-primary" onclick="oneJobModel('1')">
    Pending <span class="badge badge-light"><?= $pending[0]->pending ?></span>
    <span class="sr-only">Pending</span>
</button>

<button type="button" class="btn btn-success" onclick="oneJobModel('2')">
    Job Done <span class="badge badge-light"><?= $jobdone[0]->jobdone; ?></span>
    <span class="sr-only">Job Done</span>
</button>

<button type="button" class="btn btn-danger" onclick="oneJobModel('3')">
    Job Hold <span class="badge badge-light"><?= $hold[0]->hold; ?></span>
    <span class="sr-only">Job Hold</span>
</button>

<button type="button" class="btn btn-danger" onclick="oneJobModel('4')">
    Job Delay <span class="badge badge-light"><?= $delay[0]->delay; ?></span>
    <span class="sr-only">unread messages</span>
</button>

<table class="table table-bordered sf-table-list">
    <thead>
    <th class="text-center">S.No</th>
    <th class="text-center">Task Date</th>
    <th class="text-center">Client</th>
    <th class="text-center">Description</th>
    <th class="text-center">Account Officer</th>
    <th class="text-center">Vendor</th>
    <th class="text-center">Region</th>
    <th class="text-center">Remarks</th>
    <th class="text-center hidden-print">Status</th>
    <th class="text-center hidden-print">Created User</th>
    <th class="text-center hidden-print">Action</th>
    </thead>
    <tbody id="GetData">

    <?php
    // print_r($DailyTask); die;
    foreach($DailyTask as $key => $value):
    $region_name = CommonHelper::get_rgion_name_by_id($value->region);
    $client = CommonHelper::get_client_name_by_id($value->client);
    $acc_officer = CommonHelper::get_paid_to_name($value->acc_officer,1);
    if($value->action==1) { $s="Pending"; } elseif ($value->action==2) { $s="Job Done"; } elseif ($value->action==3) { $s="Job Hold"; } elseif ($value->action==4) { $s="Job Delay"; }

    ?>
    <tr class="text-center tr<?= $value->action ?>">
        <td> <?php echo $Counter++; ?> </td>
        <td> <?php echo date('d-m-Y',strtotime($value->task_date)); ?> </td>
        <td> <?php echo $client; ?> </td>
        <td> <?php echo $value->description; ?> </td>
        <td> <?php echo $acc_officer; ?> </td>
        <td> <?php echo $value->vendor; ?> </td>
        <td> <?php echo $region_name->region_name; ?> </td>
        <td id="remarks<?php echo $value->id; ?>"> <?php echo $value->remarks; ?> </td>
        <td id="change<?php echo $value->id; ?>"> <?php echo $s; ?> </td>
        <td><?php echo $value->username?></td>
        <td><div class="btn-group">
                <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"> ACTION <span class="caret"></span></button>
                <ul class="dropdown-menu dropdown-default pull-right sf-dropdown-ul" role="menu">
                    <li>
                        <a onclick="job_Done('<?php echo $value->id; ?>')">Job Done</a>
                        <a onclick="job_Delay('<?php echo $value->id; ?>')">Job Delay</a>
                        <a onclick="job_Hold('<?php echo $value->id; ?>')">Job Hold</a>
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
                    alert("Job Done");
                    $("#change"+id).html('Job Done');
                }
            });
        } else {
            alert('Cancel');
        }
    }

    function job_Delay(id)
    {
        if(confirm("Want To Submit...? Press ok")) {
            var m = '<?php echo $_GET['m'] ?>';
            $.ajax({
                url: '<?php echo url('/')?>/reports/job_Delay',
                type: "GET",
                data: {id:id, m:m },
                success: function (data) {
                    alert("Job Delayed");
                    $("#change"+id).html('Job Delay');
                }
            });
            showDetailModelOneParamerter('reports/get_remarks?m=<?php echo $m; ?>',id,'Remarks');
        } else {
            alert('Cancel');
        }
    }

    function job_Hold(id)
    {
        if(confirm("Want To Submit...? Press ok")) {
            var m = '<?php echo $_GET['m'] ?>';
            $.ajax({
                url: '<?php echo url('/')?>/reports/job_Hold',
                type: "GET",
                data: {id:id, m:m },
                success: function (data) {
                    alert("Job Hold");
                    $("#change"+id).html('Job Hold');
                }
            });
            showDetailModelOneParamerter('reports/get_remarks?m=<?php echo $m; ?>',id,'Remarks');
        } else {
            alert('Cancel');
        }
    }

    function oneJobModel(par1){

        if(par1==1){
            $(".tr1").show();
            $(".tr2").hide();
            $(".tr3").hide();
            $(".tr4").hide();
        } else if(par1==2){
            $(".tr1").hide();
            $(".tr2").show();
            $(".tr3").hide();
            $(".tr4").hide();
        } else if(par1==3){
            $(".tr1").hide();
            $(".tr2").hide();
            $(".tr3").show();
            $(".tr4").hide();
        } else if(par1==4){
            $(".tr1").hide();
            $(".tr2").hide();
            $(".tr3").hide();
            $(".tr4").show();
        } else {
            $(".tr1").show();
            $(".tr2").show();
            $(".tr3").show();
            $(".tr4").show();
        }
    }


</script>