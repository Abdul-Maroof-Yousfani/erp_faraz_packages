<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>

@extends('layouts.default')

@section('content')

    <style>
        td{ padding: 0px !important;}
        th{ padding: 0px !important;}
    </style>

    <div class="row">&nbsp;</div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">View Leave Policy List</span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                        <?php echo CommonHelper::displayPrintButtonInBlade('PrintLeavesPolicyList','','1');?>
                        <?php echo CommonHelper::displayExportButton('LeavesPolicyList','','1')?>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body" id="PrintLeavesPolicyList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list table-hover" id="LeavesPolicyList">
                                        <thead>
                                        <th class="text-center col-sm-1">S.No</th>
                                        <th class="text-center">Leave Policy Name</th>
                                        <th class="text-center">Policy Month/Year From</th>
                                        <th class="text-center">Policy Month/Year Till</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @foreach($leavesPolicy as $key => $value)
                                            <tr>
                                                <td class="text-center"><?php echo $counter++;?></td>
                                                <td class="text-center"><?php echo $value->leaves_policy_name;?></td>
                                                <td class="text-center"><?php echo HrHelper::date_format($value->policy_date_from);?></td>
                                                <td class="text-center"><?php echo HrHelper::date_format($value->policy_date_till);?></td>
                                                <td class="text-center"><?php echo HrHelper::getStatusLabel($value->status); ?></td>

                                                <td class="text-center hidden-print">
                                                    <div class="dropdown">
                                                        <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                            <span class="caret"></span></button>
                                                        <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                            <li role="presentation">
                                                                <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewLeavePolicyDetail','<?php echo $value->id; ?>','View Leaves Policy Detail ','<?php echo $m;  ?>')">
                                                                    View
                                                                </a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editLeavesPolicyDetailForm','<?php echo $value->id ?>','Leaves Policy Edit Detail Form','<?php echo $m?>')">
                                                                    Edit
                                                                </a>
                                                            </li>
                                                            <li role="presentation">
                                                                <a  class="delete-modal btn" onclick="deleteLeavesDataPolicyRows('/cdOne/deleteLeavesDataPolicyRows','<?php echo $m; ?>','<?php echo $value->id ?>')">
                                                                    Edit
                                                                </a>
                                                            </li>
                                                        </ul>
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
        function deleteLeavesDataPolicyRows(functionName,companyId,recordId)
        {   var baseUrl = $('#url').val();
            var main_url = '<?php echo url('/') ?>'+functionName;

            $.ajax({
                url: main_url,
                type: "GET",
                data: {companyId:companyId,recordId:recordId},
                success:function(data) {
                    location.reload();
                }
            });
        }
    </script>
    <script src="{{ URL::asset('assets/custom/js/customHrFunction.js') }}"></script>

@endsection