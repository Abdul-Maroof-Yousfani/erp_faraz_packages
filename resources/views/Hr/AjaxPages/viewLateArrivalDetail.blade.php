<?php
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
use App\Models\UserQuery;
?>

<style>
    hr{border-top: 1px solid cadetblue}

    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>

<div class="well">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <h3 class="text-center" style="color:green">
                            <b>Grace Time : {{ $grace_time }}</b>
                        </h3>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="table-responsive">
                                <table class="table table-bordered sf-table-list table-hover" id="LeaveApplicationRequestList">
                                    <thead>
                                    <th class="text-center">S No.</th>
                                    <th class="text-center">Late Arrival Date</th>
                                    <th class="text-center">Day</th>
                                    <th class="text-center">Clock In</th>
                                    <th class="text-center">Clock Out</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Action</th>
                                    <th class="text-center">Reason (If Neglected)</th>
                                    </thead>
                                    <tbody>
                                    <?php
                                    $counter = 1;

                                    $grace_time = strtotime($grace_time);
                                    ?>

                                    @foreach($attendance->get() as $value)
                                        <?php
                                        $min = 0;
                                        $hours = 0;
                                        $late = 0;
                                        $clock_in = strtotime($value->clock_in);

                                        if($value->clock_in != ''):
                                            if($clock_in > $grace_time):
                                                $late = (($clock_in - $grace_time) / 60);
                                                $min = $late;
                                                $hours = floor($min/60);
                                                $min = $min%60;
                                                CommonHelper::companyDatabaseConnection(Input::get('m'));
                                                $neglect_remarks = UserQuery::where([['emp_code','=',$value->emp_code],['query_date','=',$value->attendance_date],['status','=','1']])->value('remarks');
                                                CommonHelper::reconnectMasterDatabase();
                                                ?>
                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td class="text-center" id="getId_{{ $value->id }}">{{ HrHelper::date_format($value->attendance_date) }}</td>
                                                    <td class="text-center">{{ $value->day }}</td>
                                                    <td class="text-center">{{ $value->clock_in }}</td>
                                                    <td class="text-center">{{ $value->clock_out }}</td>
                                                    <td class="text-center">{{ $hours.' : '.$min }}</td>
                                                    <td class="text-center">
                                                        @if($value->neglect_attendance == 'yes')
                                                            <span style="color:green">Neglected </span>
                                                        @else
                                                            <button id="removeBtn<?=$value->id?>" class="btn btn-xs btn-danger" onclick="Neglect('{{ $value->id }}','{{ $value->attendance_date }}','{{ $value->emp_code }}')">Neglect Late</button>
                                                            <span style="color:green;" id="NeglectArea{{ $value->id }}"></span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $neglect_remarks }}</td>
                                                </tr>
                                                <?php
                                            endif;
                                        endif;
                                        ?>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function NeglectLateArrival(attendance_id,attendance_date,emp_code,reason)
    {
        var date2 = $("#getId_"+attendance_id).html();
        var m = '{{ Input::get('m') }}';

        if(confirm("Do you want to Neglect Late Arrival of "+date2+" ? "))
        {
            $('#NeglectArea'+attendance_id).html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

            $.ajax({
                url: '<?php echo url('/')?>/hedbac/NeglectEmployeeAttendance',
                type: "GET",
                data: {attendance_id:attendance_id,attendance_date:date2,emp_code:emp_code,reason:reason,m:m},
                success:function(data) {
                    viewAttendanceProgress();
                    $('#removeBtn'+attendance_id).remove();
                    $('#NeglectArea'+attendance_id).html('');
                    $('#NeglectArea'+attendance_id).html('Neglected');


                }
            });
        }
    }

    function Neglect(attendance_id,attendance_date,emp_code)
    {
        var reason;
        swal({
            title: "Reason",
            type: "input",
            showCancelButton: true,
            closeOnConfirm: false,
            inputPlaceholder: "Reason"
        }, function (value) {
            if (value === false) return false;
            if (value === "") {
                swal.showInputError("Reason is Required");
                return false
            }
            reason = value;
            swal.close();
            NeglectLateArrival(attendance_id, attendance_date, emp_code, reason);
        });
    }
</script>