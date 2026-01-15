<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
$countRemainingLeaves=0;
$countUsedLeavess=0;
$transferedleaveTotal = 0;

use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\LeaveApplicationData;
use App\Models\Employee;
use App\Models\TransferedLeaves;
?>


<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }
    tr td{
        padding: 2px !important;
    }
    tr th{
        padding: 2px !important;
    }
    .btn span.glyphicon {
        opacity: 0;
    }
    .btn.active span.glyphicon {
        opacity: 1;
    }
</style>

<div class="panel-body">
    <div class="row">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if($leaves_policy_validatity == 0)
                    <h4><div style="padding:0px;" class="policy_expire_mesg"> Leaves Policy Expire ,Please Update Leaves Policy !</div></h4>
                @endif
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <?php echo Form::open(array('url' => 'had/addTaxesDetail','id'=>'EOBIform'));?>
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Emo Code:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="" id="" value="{{ $emp_data->emp_code }}" disabled class="form-control requiredField" />
                    </div>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Employee Name:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="" id="" value="<?= $emp_data->emp_name ?>" disabled class="form-control requiredField" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Department:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="" id="" value="{{ HrHelper::getMasterTableValueById(Input::get('m'),'department','department_name',$emp_data->emp_department_id)}}" disabled class="form-control requiredField" />
                    </div>

                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                        <label>Emp Designation:</label>
                        <span class="rflabelsteric"><strong>*</strong></span>
                        <input type="text" name="" id="" value="{{ HrHelper::getMasterTableValueById(Input::get('m'),'designation','designation_name',$emp_data->designation_id)}}" disabled class="form-control requiredField" />
                    </div>
                </div>

                <div class="row">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <table class="table table-bordered sf-table-list">
                            <thead>
                            <tr>
                                <th style="background-color: rgb(42, 110, 207);padding: 2px;">
                                    <div class="text-center" style="margin-top: 7px;">
                                        <span style="color:white;">LEAVES BALANCE</span>
                                    </div>
                                </th>
                            </tr>
                            </thead>
                        </table>
                        <table class="table table-bordered sf-table-list">
                            <thead>
                            <tr>
                                <th class="text-center">S No#</th>
                                <th class="text-center">Leaves Name</th>
                                <th class="text-center">No of leaves (Current Year)+(Previous Year) </th>
                                <th class="text-center">Used</th>
                                <th class="text-center">Remaining</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            $count =0 ;
                            $count_leaves = 0;
                            ?>
                            @foreach($leaves_policy as $val)
                                <?php
                                $count_leaves+=$val->no_of_leaves ;
                                $count++;
                                ?>
                                <tr>
                                    <td class="text-center"><b>{{ $count }}</b></td>
                                    <td class="text-center"><b>{{ HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )}}</b></td>
                                    <td class="text-center">
                                        <b><?php

                                            $TransferedLeaves = TransferedLeaves::where([['emp_code','=',$emp_data->emp_code],['leaves_policy_id','=',$emp_data->leaves_policy_id],['status','=','1']]);

                                            if($TransferedLeaves->count() > 0):
                                                if($val->leave_type_id == 1):
                                                    $transferedleaveTotal+= $TransferedLeaves->value('annual_leaves');
                                                    echo $val->no_of_leaves." + ".$TransferedLeaves->value('annual_leaves').' = '.($val->no_of_leaves+$TransferedLeaves->value('annual_leaves'));
                                                elseif($val->leave_type_id == 3):
                                                    $transferedleaveTotal+= $TransferedLeaves->value('casual_leaves');
                                                    echo $val->no_of_leaves." + ".$TransferedLeaves->value('casual_leaves').' = '.($val->no_of_leaves+$TransferedLeaves->value('casual_leaves'));
                                                else:
                                                    echo $val->no_of_leaves." + ".$TransferedLeaves->value('sick_leaves').' = '.($val->no_of_leaves+$TransferedLeaves->value('sick_leaves'));
                                                endif;
                                            else:
                                                if($val->leave_type_id == 1):
                                                    $transferedleaveTotal+= $TransferedLeaves->value('annual_leaves');
                                                    echo $val->no_of_leaves.' + 0 = '.$val->no_of_leaves;
                                                elseif($val->leave_type_id == 3):
                                                    $transferedleaveTotal+= $TransferedLeaves->value('casual_leaves');
                                                    echo $val->no_of_leaves.' + 0 = '.$val->no_of_leaves;
                                                else:
                                                    echo $val->no_of_leaves.' + 0 = '.$val->no_of_leaves;
                                                endif;
                                            endif;

                                            ?>
                                        </b>
                                    </td>
                                    <td class="text-center">
                                        <?php

                                        if($val->leave_type_id == 1):
                                            $getUsedAnnualLeaves =DB::table('leave_application_data')
                                                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                ->where([['leave_application_data.leave_policy_id','=',$emp_data->leaves_policy_id],
                                                    ['leave_application.emp_code','=',$emp_data->emp_code],['leave_application.leave_type','=',$val->leave_type_id ],
                                                    ['leave_application.status', '=', '1'],
                                                    ['leave_application.approval_status', '=', '2']])
                                                ->sum('no_of_days');
                                            echo $getUsedAnnualLeaves;
                                            $countUsedLeavess+= $getUsedAnnualLeaves;

                                        elseif($val->leave_type_id == 3):
                                            $getUsedCasualLeaves =DB::table('leave_application_data')
                                                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                ->where([['leave_application_data.leave_policy_id','=',$emp_data->leaves_policy_id],
                                                    ['leave_application.emp_code','=',$emp_data->emp_code],['leave_application.leave_type','=',$val->leave_type_id ],
                                                    ['leave_application.status', '=', '1'],
                                                    ['leave_application.approval_status', '=', '2']])
                                                ->sum('no_of_days');
                                            echo $getUsedCasualLeaves;
                                            $countUsedLeavess+= $getUsedCasualLeaves;

                                        else:
                                            echo $getUsedSickLeaves =DB::table('leave_application_data')
                                                ->join('leave_application', 'leave_application.id', '=', 'leave_application_data.leave_application_id')
                                                ->where([['leave_application_data.leave_policy_id','=',$emp_data->leaves_policy_id],
                                                    ['leave_application.emp_code','=',$emp_data->emp_code],['leave_application.leave_type','=',$val->leave_type_id ],
                                                    ['leave_application.status', '=', '1'],
                                                    ['leave_application.approval_status', '=', '2']])
                                                ->sum('no_of_days');
                                            $countUsedLeavess+= $getUsedSickLeaves;
                                        endif;

                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if($val->leave_type_id == 1):
                                            $countRemainingLeaves+= $val->no_of_leaves+$TransferedLeaves->value('annual_leaves')-$getUsedAnnualLeaves;
                                            echo $val->no_of_leaves+$TransferedLeaves->value('annual_leaves')-$getUsedAnnualLeaves;

                                        elseif($val->leave_type_id == 3):
                                            $countRemainingLeaves+= $val->no_of_leaves+$TransferedLeaves->value('casual_leaves')-$getUsedCasualLeaves;
                                            echo $val->no_of_leaves+$TransferedLeaves->value('casual_leaves')-$getUsedCasualLeaves;
                                        else:
                                            $countRemainingLeaves+= $val->no_of_leaves-$getUsedSickLeaves;
                                            echo $val->no_of_leaves-$getUsedSickLeaves;
                                        endif;
                                        ?>
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th class="text-right"  style="color: #fff;background-color: #2a6ecf;" colspan="2"><b>Total</b></th>
                                <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf;"><b>{{ $count_leaves+$transferedleaveTotal }}</b></th>
                                <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf; "><?php print_r($countUsedLeavess)?></th>
                                <th class="text-center" style="text-decoration:underline;color: #fff;background-color: #2a6ecf; "><?=$countRemainingLeaves?></th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        <div style="color: #fff;background-color: #2a6ecf; ">
                            <b>SELECT LEAVE TYPE</b>
                            &ensp;
                            <span class="glyphicon glyphicon-arrow-down"></span>
                        </div>
                        <div class="btn-group" data-toggle="buttons" style="padding: 4px;">
                            @foreach($leaves_policy as $val)
                                <?php $leaveName = HrHelper::getMasterTableValueById('0','leave_type','leave_type_name',$val->leave_type_id )?>

                                <label style="border:1px solid #fff;" class="btn btn-success" onclick="viewEmployeeLeavesDetail('<?=$val->id?>','{{ $val->no_of_leaves }}','<?= $val->leave_type_id ?>')">
                                    <input required="required" autocomplete="off" type="radio" name="leave_type" id="leave_type" class="requiredField" value="<?=$val->leave_type_id?>">
                                    {{ $leaveName }}
                                    <span class="glyphicon glyphicon-ok"></span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="lineHeight">&nbsp;</div>
                <div class="lineHeight">&nbsp;</div>
                <div class="" id="leavesData"></div>
                <div class="" id="leave_days_area"></div>
                <div class="lineHeight">&nbsp;</div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Reason For Leave</label>
                        <textarea id="reason" class="form-control requiredField">-</textarea>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <label>Address While on Leaave</label>
                        <textarea id="leave_address" class="form-control requiredField">-</textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="leaveAppLoader"></div>
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                <span id="errorMesg" style="color:red"></span>
                <button type="button" id="submitBtn" onclick="check_days()" class="btn btn-success">Submit</button>
                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
            </div>
        </div>
        <?php echo Form::close();?>
    </div>
</div>


<script type="text/javascript">

    @if($leaves_policy_validatity == 0)
    setInterval(function () {
        $(".policy_expire_mesg").css("color","#fff");
        $(".policy_expire_mesg").css("background-color","#a94442");
        $(".policy_expire_mesg").css("border-color","#a94442");
        setTimeout(function () {
            $(".policy_expire_mesg").removeAttr("style");

        },500)
    },900);
    @endif

    function leaves_day_type(type)
    {

        var current_date  = '<?= date("Y-m-d") ?>';
        var leave_type = $("input[id='leave_type']:checked").val();

        if(leave_type == 2)
        {
            if(type == 'full_day_leave')
            {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> No. of Days</label><input type="number" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                    '<span id="warning_message" style="color:red"></span></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> To </label><input type="date" class="form-control requiredField" name="to_date" id="to_date"></div></div>');

            }
            else if(type == 'half_day_leave')
            {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>');
            }
            else if(type == 'short_leave')
            {
                $("#leave_days_area").html('');
                $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>');

            }
        }
        else if(leave_type == 3)
        {
            if(type == 'full_day_leave')
            {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> No. of Days</label><input type="number" onclick="checkCasualLeave()" onkeyup="checkCasualLeave()" class="form-control requiredField" id="no_of_days" name="no_of_days">' +
                    '<span id="warning_message" style="color:red"></span></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Leave from </label><input type="date" class="form-control requiredField" name="from_date" id="from_date"> </div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> To </label><input type="date" class="form-control requiredField" name="to_date" id="to_date"></div></div>');


            }
            else if(type == 'half_day_leave')
            {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> (09:00 A.M to 02:00 P.M) &nbsp;&nbsp;&nbsp;First Half&nbsp;:&nbsp;<input checked type="radio" value="first_half" id="first_second_half" name="first_second_half"></label><br>' +
                    '<label> (01:00 A.M to 06:00 P.M) &nbsp;&nbsp;&nbsp;2nd Half&nbsp;:&nbsp;<input type="radio" value="second_half" id="first_second_half" name="first_second_half"></label></div>' +
                    '<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="first_second_half_date" name="first_second_half_date"> </div></div>');
            }
            else if(type == 'short_leave')
            {

                $("#leave_days_area").html('<div class="row"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> From (Time) </label><input type="time" class="form-control requiredField" id="short_leave_time_from" name="short_leave_time_from"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label> To (Time) </label><input class="form-control requiredField" type="time"  id="short_leave_time_to" name="short_leave_time_to"></div>' +
                    '<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
                    '<label> Date </label><input type="date" class="form-control requiredField" id="short_leave_date" name="short_leave_date"></div></div>');

            }
        }


    }
    function check_days()
    {
        var leave_type = $("input[id='leave_type']:checked").val();
        var leaves_day_type = $("input[id='leave_day_type']:checked").val();
        var leave_policy_id = '<?=$leaves_policy[0]->leaves_policy_id?>';

        jqueryValidationCustom();
        if(validate == 0){
            $('.leaveAppLoader').html('<div class="loading"></div>');


            if(leave_type == 4)
            {
                var emp_code = '<?= $emp_data->emp_code ?>';
                var company_id = '<?= Input::get('m') ?>';
                var no_of_days = $("#no_of_days").val();
                var from_date =  $("#from_date").val();
                var to_date   =  $("#to_date").val();
                var leave_type = $("input[id='leave_type']:checked").val();
                var leave_day_type = 1;
                var reason   = $("#reason").val();
                var leave_address =  $("#leave_address").val();
                var data = {
                    emp_code:emp_code,
                    leave_policy_id:leave_policy_id,
                    company_id:company_id,
                    leave_type:leave_type,
                    leave_day_type:leave_day_type,
                    no_of_days:no_of_days,
                    from_date:from_date,
                    to_date:to_date,
                    reason:reason,
                    leave_address:leave_address,
                };

                var from_date = $('#from_date').val();
                var to_date   = $("#to_date").val();
                var date1 = new Date(from_date);
                var date2 = new Date(to_date);
                var no_of_days = $("#no_of_days").val();
                var timeDiff = Math.abs(date2.getTime() - date1.getTime());
                var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
            }

            else if(leave_type == 1)
            {
                if(leaves_day_type == 'full_day_leave'){

                    var inform_days_two = '29';
                    var from_date = $('#from_date').val();
                    var to_date   = $("#to_date").val();
                    var no_of_days = $('#no_of_days').val();
                    var current_date = '<?= date("Y-m-d"); ?>';
                    var date1 = current_date;
                    var date2 = from_date;
                    date1 = date1.split('-');
                    date2 = date2.split('-');
                    date1 = new Date(date1[0], date1[1], date1[2]);
                    date2 = new Date(date2[0], date2[1], date2[2]);
                    date1_unixtime = parseInt(date1.getTime() / 1000);
                    date2_unixtime = parseInt(date2.getTime() / 1000);
                    var timeDifference = date2_unixtime - date1_unixtime;
                    var timeDifferenceInHours = timeDifference / 60 / 60;
                    var timeDifferenceInDays = timeDifferenceInHours  / 24;

                    var emp_code = '<?= $emp_data->emp_code ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                    var from_date =  $("#from_date").val();
                    var to_date   = $("#to_date").val();
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var leave_day_type = 1
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var data = {
                        emp_code:emp_code,
                        leave_policy_id:leave_policy_id,
                        company_id:company_id,
                        full_day_deduction_rate:full_day_deduction_rate,
                        leave_type:leave_type,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        from_date:from_date,
                        to_date:to_date,
                        reason:reason,
                        leave_address:leave_address,
                    };
                }

            }
            else if(leave_type == 2)
            {
                if(leaves_day_type == 'full_day_leave'){


                    var from_date = $('#from_date').val();
                    var no_of_days = $('#no_of_days').val();
                    var emp_code = '<?= $emp_data->emp_code ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                    var from_date =  $("#from_date").val();
                    var to_date   = $("#to_date").val();
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var leave_day_type = 1
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var data = {

                        leave_type:leave_type,
                        emp_code:emp_code,
                        leave_policy_id:leave_policy_id,
                        company_id:company_id,
                        full_day_deduction_rate:full_day_deduction_rate,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        from_date:from_date,
                        to_date:to_date,
                        reason:reason,
                        leave_address:leave_address,
                    };

                }
                else if(leaves_day_type == 'half_day_leave')
                {

                    var emp_code = '<?= $emp_data->emp_code ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                    var first_second_half = $("input[id='first_second_half']:checked").val();
                    var no_of_days = (1*half_day_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 2
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var data = {
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_code:emp_code,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        first_second_half:first_second_half,
                        first_second_half_date:first_second_half_date,
                        leave_address:leave_address,
                        reason:reason,
                        first_second_half_date:first_second_half_date,
                    };



                }
                else if(leaves_day_type == 'short_leave')
                {

                    var emp_code = '<?= $emp_data->emp_code ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                    var short_leave_time_from = $("#short_leave_time_from").val();
                    var short_leave_time_to = $("#short_leave_time_to").val();
                    var short_leave_date = $("#short_leave_date").val();
                    var no_of_days = (1*per_hour_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 3;
                    var leave_type = $("input[id='leave_type']:checked").val();

                    var data = {
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_code:emp_code,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        short_leave_time_from:short_leave_time_from,
                        short_leave_time_to:short_leave_time_to,
                        short_leave_date:short_leave_date,
                        leave_address:leave_address,
                        reason:reason
                    };

                }
                else
                {
                    alert('Error ! Select Full/Half/Short Leave Type !');
                    return false;
                }
            }
            else if(leave_type == 3)
            {
                if(leaves_day_type == 'full_day_leave'){

                    var from_date = $('#from_date').val();
                    var no_of_days = $('#no_of_days').val();
                    var emp_code = '<?= $emp_data->emp_code ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var full_day_deduction_rate  = '<?=$leaves_policy[0]->fullday_deduction_rate ?>';
                    var no_of_days = ($("#no_of_days").val()*full_day_deduction_rate);
                    var from_date =  $("#from_date").val();
                    var to_date   = $("#to_date").val();
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var leave_day_type = 1
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var data = {
                        leave_type:leave_type,
                        emp_code:emp_code,
                        leave_policy_id:leave_policy_id,
                        company_id:company_id,
                        full_day_deduction_rate:full_day_deduction_rate,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        from_date:from_date,
                        to_date:to_date,
                        reason:reason,
                        leave_address:leave_address,
                    };

                }
                else if(leaves_day_type == 'half_day_leave')
                {

                    var emp_code = '<?= $emp_data->emp_code ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var half_day_deduction_rate  = '<?=$leaves_policy[0]->halfday_deduction_rate ?>';
                    var first_second_half = $("input[id='first_second_half']:checked").val();
                    var no_of_days = (1*half_day_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 2
                    var leave_type = $("input[id='leave_type']:checked").val();
                    var data = {
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_code:emp_code,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        first_second_half:first_second_half,
                        first_second_half_date:first_second_half_date,
                        leave_address:leave_address,
                        reason:reason,
                        first_second_half_date:first_second_half_date,
                    };

                }
                else if(leaves_day_type == 'short_leave')
                {

                    var emp_code = '<?= $emp_data->emp_code ?>';
                    var company_id = '<?= Input::get('m') ?>';
                    var reason   = $("#reason").val();
                    var leave_address =  $("#leave_address").val();
                    var per_hour_deduction_rate  = '<?=$leaves_policy[0]->per_hour_deduction_rate ?>';
                    var short_leave_time_from = $("#short_leave_time_from").val();
                    var short_leave_time_to = $("#short_leave_time_to").val();
                    var short_leave_date = $("#short_leave_date").val();
                    var no_of_days = (1*per_hour_deduction_rate);
                    var first_second_half_date =  $("#first_second_half_date").val();
                    var leave_day_type = 3;
                    var leave_type = $("input[id='leave_type']:checked").val();

                    var data = {
                        leave_type:leave_type,
                        company_id:company_id,
                        emp_code:emp_code,
                        leave_policy_id:leave_policy_id,
                        leave_day_type:leave_day_type,
                        no_of_days:no_of_days,
                        short_leave_time_from:short_leave_time_from,
                        short_leave_time_to:short_leave_time_to,
                        short_leave_date:short_leave_date,
                        leave_address:leave_address,
                        reason:reason
                    };

                }
                else
                {
                    alert('Error ! Select Full/Half/Short Leave Type !');
                    return false;
                }
            }
            else
            {
                alert('Please Select Leaves Type !')
            }
            var company_id = '<?= Input::get('m') ?>';


            $.ajax({
                url: '<?php echo url('/')?>/hadbac/addLeaveApplicationDetail',
                type: "GET",
                data: data,
                success:function(data) {
                    //alert(data);
                    if(data == 1)
                    {
                        swalAdd();
                        location.reload();
                    }
                    else
                    {
                        $(".leaveAppLoader").html("");
                    }
                }
            });

        }

        else
        {
            //alert(jqueryValidationCustom());
        }
    }

    function viewEmployeeLeavesDetail(id,leavesCount,leaveType)
    {
        var current_date  = '<?= date("Y-m-d") ?>';
        $('#leavesData').append('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
        $("#leave_days_area").html('');

        if (leaveType == 4)
        {
            var data =  {
                m :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$emp_data->emp_code?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };
        }
        else if(leaveType == 1)
        {
            var data =  {
                m :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$emp_data->emp_code?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };
        }
        else if(leaveType == 2)
        {
            var data =  {
                m :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$emp_data->emp_code?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };
        }
        else if(leaveType == 3)
        {

            var data =  {
                m :'<?= Input::get('m') ?>' ,
                emp_id:'<?=$emp_data->emp_code?>',
                leave_id:id,
                leavesCount:leavesCount,
                leaveType:leaveType,
            };
        }

        else
        {
            $("#leave_days_area").html('');
            $('#leavesData').html('');
        }


        $.ajax({
            url: '<?php echo url('/')?>/hdc/viewEmployeeLeaveDetail',
            type: "GET",
            data: data,
            success:function(data) {
                $('#leavesData').html('');
                $('#leavesData').html(data);
            }
        });

    }

</script>
