<?php

$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;

?>

<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>


@extends('layouts.default')
@section('content')
    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">View Employee Equipments List</span>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body" id="PrintEmpExitCleareanceList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover" id="EmployIdCardRequestList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center">EMR No.</th>
                                            <th class="text-center">Employee Name</th>
                                            <th class="text-center">Department</th>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Action</th>

                                            </thead>
                                            <tbody>
                                            <?php $counter = 1;?>
                                            @foreach($employeeEquipment as  $row)
                                                <?php $sub_department_id = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee','emp_sub_department_id', $row->emr_no, 'emr_no');
                                                    $location_id = HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee','branch_id', $row->emr_no, 'emr_no') ?>
                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td class="text-center">{{ $row->emr_no }}</td>
                                                    <td>{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee','emp_name', $row->emr_no, 'emr_no')  }}</td>
                                                    <td>{{ HrHelper::getMasterTableValueById($m, 'sub_department','sub_department_name', $sub_department_id ) }}</td>
                                                    <td>{{ HrHelper::getMasterTableValueById($m, 'locations','employee_location', $location_id ) }}</td>
                                                    <td class="text-center">{{ HrHelper::getStatusLabel($row->status) }}</td>
                                                    <td class="text-center hidden-print">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">

                                                                @if(in_array('view', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewEmployeeEquipmentsDetail','{{ $row->id }}','View Employee Equipments Detail','{{ $m }}')">
                                                                            View
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeeEquipmentsDetailForm','{{ $row->id }}','Edit Employee Equipments Detail','{{ $m }}')">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('repost', $operation_rights))
                                                                    @if($row->status == 2)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="repostCompanyTableRecord('{{ $m }}','{{ $row->id }}','employee_equipments')">
                                                                                Repost
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($row->status == 1)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="deleteEmployeeEquipments('{{ $m }}','{{ $row->id. '|' .$row->emr_no  }}')">
                                                                                Delete
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
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
        function deleteEmployeeEquipments(companyId,recordId){
            var companyId;
            var recordId

            if(confirm("Do you want to delete this record ?") == true){
                $.ajax({
                    url: '<?php echo url('/')?>/cdOne/deleteEmployeeEquipments',
                    type: "GET",
                    data: {companyId:companyId,recordId:recordId},
                    success:function(data) {
                        location.reload();
                    }
                });
            }
            else{
                return false;
            }

        }

    </script>

@endsection