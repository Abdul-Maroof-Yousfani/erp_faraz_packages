<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
    <style>
        hr{border-top: 1px solid cadetblue}

        td{ padding: 2px !important;}
        th{ padding: 2px !important;}
    </style>
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Leave Application List</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeaveApplicationList','','1');?>
                            <?php echo CommonHelper::displayExportButton('LeaveApplicationList','','1')?>
                        </div>
                    </div>
                    <?php $leave_type = [4 => 'Maternity Leaves',1 => 'Annual/Earned Leave',2 => 'Sick Leave',3 => 'Casual'];?>
                    <?php $leave_day_type = [1 => 'full Day Leave',2 => 'Half Day Leave',3 => 'Short Leave'];?>
                    <div class="panel">
                        <div class="panel-body" id="PrintLeaveApplicationList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover table-striped" id="LeaveApplicationList">
                                            <thead>
                                            <tr>
                                                <th class="text-center">S No.</th>
                                                <th class="text-center">Leave Type</th>
                                                <th class="text-center">Day Type</th>
                                                <th class="text-center">Approval Status</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Created on</th>
                                                <th class="text-center hidden-print">Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php $counter = 1;?>
                                            @foreach($leave_application_list as $value)
                                                <tr>
                                                    <td class="text-center">
                                                        <span class="badge badge-pill badge-secondary">{{ $counter++ }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span>{{ $leave_type[$value->leave_type] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span>{{ $leave_day_type[$value->leave_day_type] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        {{ HrHelper::getApprovalStatusLabel($value->approval_status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ HrHelper::getStatusLabel($value->status) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ HrHelper::date_format($value->date) }}
                                                    </td>

                                                    <td class="text-center hidden-print">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                @if(in_array('view', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a class="delete-modal btn" onclick="getLeavesData('<?=$value->id?>','<?=$value->leave_day_type?>','<?=$value->leave_type?>')" data-toggle="collapse" data-target="#collapseExample<?=$value->id?>" aria-expanded="false" aria-controls="collapseExample">
                                                                            View
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editLeaveApplicationDetailForm','<?php echo $value->id."|".$value->emr_no;?>','Edit Leave Application Detail','<?php echo $m; ?>')">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($value->status == 1)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="deleteLeaveApplicationData('<?= $m ?>','<?=$value->id?>')">
                                                                                Delete
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="7">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 collapse" id="collapseExample<?=$value->id?>">
                                                            <div class="card card-body" id="leave_area<?=$value->id?>"></div>
                                                        </div>
                                                    </td>
                                                </tr>

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
    </div>

    <script>

        function getLeavesData(id,leave_day_type,leave_type)
        {
            $('#leave_area'+id).append('<div class="row">&nbsp;</div><div class="loader"></div>');
            var m = '<?= Input::get('m'); ?>';
            var url= '<?php echo url('/')?>/hdc/viewLeaveApplicationDetail';
            var data = {m:m,id:id,leave_day_type:leave_day_type,leave_type:leave_type};
            $.getJSON(url, data ,function(result){
                $.each(result, function(i, field){
                    $('#leave_area'+id).html('<hr>' +
                        '<div class="row text-center" style="background-color: gainsboro">' +
                        '<h4><b>Leave Application Details</b></h4>' +
                        '</div>' +
                        '<div class="row">&nbsp;</div>'+field);

                });
            })

        }


    </script>
    <script type="text/javascript" src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>
@endsection