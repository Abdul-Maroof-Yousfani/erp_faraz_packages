<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>
@include('includes._normalUserNavigation')
<br xmlns="http://www.w3.org/1999/html"/>
<style>
    .find-job {
        background: #fff;
    }

    .section {
        padding: 60px 0;
    }

    .section-title {
        font-size: 36px;
        padding: 0px 0px 30px;
        font-weight: 900;
    }

    .job-list {
        border: 1px solid #f1f1f1;
        padding: 15px;
        display: inline-block;
        margin-bottom: 15px;
    }

    .job-list .thumb {
        float: left;
    }

    .job-list .job-list-content {
        display: block;
        margin-left: 125px;
        position: relative;
        width: 1000px;
    }



    .job-list .job-list-content p {
        margin-bottom: 20px;
    }

    .job-list .job-tag {
        padding: 15px 0;
        line-height: 35px;
    }

    .pull-left {
        float: left !important;
    }

    .job-list .job-tag .meta-tag span {
        font-size: 14px;
        color: black;
        margin-right: 10px;
    }

    .job-list .job-tag .meta-tag span a {
        color: black;
    }

    .job-list .job-tag .meta-tag span i {
        margin-right: 5px;
    }

    .job-list .job-list-content h4 {
        font-size: 22px;
        margin-bottom: 10px;
    }

    .full-time {
        font-size: 12px;
        color: #fff;
        background: #2c3e50 !important;
        border-radius: 4px;
        margin-left: 10px;
        padding: 4px 18px;
    }



    .btn:hover, .btn:focus {
        color: #fff;

    }

.career{
    color:#f1592a;
    font-size: 20px;
}

.dept-name
{
    font-weight: bold;
    color:#3071a9;
    text-decoration: underline;
}
</style>
<?php
$companyList1 = DB::select('select `name`,`dbName`,`id` from `company` where `status` = 1');


?>
<div class="container-fluid">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <h2 class="career">It's more than just a Job. It,s Your Dream!</h2>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 text-center">
                            <h2><b>Latest Jobs</b></h2>
                        </div>
                        <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12"><h5 style="font-weight: bold;">Filter Wise </h5> </div>
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <select class="form-control requiredField" name="department_id" id="department_id" style="padding: 5px;">
                                <option value="">All</option>
                        <?php

                            foreach($companyList1 as $value)
                            {
                            CommonHelper::companyDatabaseConnection($value->id);
                            $department_wise2 = DB::select('select `department_id`  from `employee_requisition` where `approval_status` = 2 and `status` = 1 GROUP BY `department_id` order by `id` desc');
                            $counter2 = 1;
                                foreach($department_wise2 as $value2)
                                {?>
                                    <option <?php if(Request::segment(3) == $value2->department_id): echo 'selected'; endif; ?> value="{{ $value2->department_id}}">
                                        {{ HrHelper::getMasterTableValueById($value->id,'department','department_name',$value2->department_id) }}
                                    </option><?php
                                }
                                CommonHelper::reconnectMasterDatabase();
                                }
                                ?>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <section class="find-job section">
                                <div class="container">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <?php
                                            $counter = 1;
                                            $companyList = DB::select('select `name`,`dbName`,`id` from `company` where `status` = 1');
                                            foreach($companyList as $row1){
                                            CommonHelper::companyDatabaseConnection($row1->id);
                                            $department_wise = DB::select('select `department_id`  from `employee_requisition` where '.(Request::segment(3) !=''? 'department_id='.Request::segment(3)." and": '').' `approval_status` = 2 and `status` = 1 GROUP BY `department_id` order by `id` desc');

                                            $counter2 = 1;
                                            foreach($department_wise as $row4){?>
                                            <div class='row text-center'>
                                                <h3 class="dept-name"><?= HrHelper::getMasterTableValueById($row1->id,'department','department_name',$row4->department_id)?></h3>
                                            </div>
                                            <?php
                                              CommonHelper::companyDatabaseConnection($row1->id);
                                            $viewJobsList = DB::select('select *  from employee_requisition where  approval_status = 2 and status = 1 and department_id= '.$row4->department_id.' order by id desc');
                                            foreach($viewJobsList as $row3){ ?>
                                            <div class="job-list">
                                                <div class="thumb">
                                                    <a href="{{ url('visitor/ViewandApplyDetail/'.$row1->id."/".$row3->id) }}">
                                                        <img height="100" width="100"src="{{ URL::asset('assets/img/zamzamalogo.png ')}}" alt="Company">
                                                    </a>
                                                </div>
                                                <div class="job-list-content">
                                                    <h4><a href="{{ url('visitor/ViewandApplyDetail/'.$row1->id."/".$row3->id) }}" style="color:black;"><?= $row3->job_title ?></a>
                                                        <span class="full-time">&nbsp;Full-Time</span>
                                                    </h4>
                                                    <p><?= $row3->other_requirment?></p>
                                                    <div class="job-tag">
                                                        <div class="pull-left">
                                                            <div class="meta-tag">
                                                                <span><span class="glyphicon glyphicon-map-marker"></span><?= $row3->location ?></span>
                                                            </div>
                                                        </div>
                                                        <div class="pull-right">
                                                            <a href="{{ url('visitor/ViewandApplyDetail/'.$row1->id."/".$row3->id) }}"  class="btn btn-primary btn-sm">More Detail</a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php }
                                            }
                                                CommonHelper::reconnectMasterDatabase();
                                            }?>

                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
       </div>
</div>
<script>

    $( document ).ready(function() {
        $("#department_id").change(function(){

            var dept_id = $("#department_id").val();
            if(dept_id != '')
            {
                window.location = $('#url').val()+'/visitor/careers/'+dept_id;
            }
            else
            {
                window.location = $('#url').val()+'/visitor/careers';
            }

        })
    })


</script>
