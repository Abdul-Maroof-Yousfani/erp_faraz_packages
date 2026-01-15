<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
?>
<style>
    td{ padding: 2px !important;}
    th{ padding: 2px !important;}
</style>
@extends('layouts.default')
@section('content')
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well_N">
                    <div class="dp_sdw">    
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Employee Medical List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <a style="float: right" onclick="showDetailModelTwoParamerter('hr/createEmployeeMedicalForm','','Create Employee Medical Form','<?php echo $m?>')" class="btn btn-primary">Add Employee Medical</a>
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                <input type="text" id="emp_code" name="emp_code" class="form-control" placeholder="Search By Emp Code" >
                            </div>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <span id="employee-list">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list table-hover table-striped" id="MedicalList">
                                                    <thead>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center">Emp Code</th>
                                                    <th class="text-center">Employee Name</th>
                                                    <th class="text-center">Disease</th>
                                                    <th class="text-center">Amount</th>
                                                    <th class="text-center">Cheque Number</th>
                                                    <th class="text-center">Date</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Documents</th>
                                                    <th class="text-center">Action</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>
                                                    @foreach($employeeMedical->get() as $key => $y)
                                                        <tr>
                                                            <td class="text-center">{{ $counter++ }}</td>
                                                            <td class="text-center">{{ $y->emr_no }}</td>
                                                            <td class="text-center">{{ HrHelper::getCompanyTableValueByIdAndColumn($m, 'employee', 'emp_name', $y->emp_code, 'emp_code') }}</td>
                                                            <td class="text-center">{{ HrHelper::getMasterTableValueById($m, 'diseases', 'disease_type', $y->disease_type_id) }}</td>
                                                            <td class="text-right">{{ number_format($y->amount,0) }}</td>
                                                            <td class="text-center">{{ $y->cheque_number }}</td>
                                                            <td class="text-center">{{ HrHelper::date_format($y->disease_date) }}</td>
                                                            <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>

                                                            <?php $documentsCheck =  HrHelper::getCompanyTableValueByIdAndColumn(Input::get('m'),'employee_medical_documents','medical_file_path',$y->emp_code, 'emp_code'); ?>
                                                            @if($documentsCheck != null)
                                                                <td class="text-center">
                                                                    <a onclick="showDetailModelTwoParamerter('hdc/viewEmployeeMedicalDocuments','<?php echo $y->id;?>','View Employee Medical Documents','<?php echo $m; ?>')" class=" btn btn-info btn-xs">View</a>
                                                                </td>
                                                            @else
                                                                <td class="text-center"> -- </td>
                                                            @endif

                                                            <td class="text-center hidden-print">
                                                                <div class="dropdown">
                                                                    <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                        <span class="caret"></span></button>
                                                                    <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                        @if(in_array('edit', $operation_rights))
                                                                            <li role="presentation">
                                                                                <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hr/editEmployeeMedicalDetailForm','<?php echo $y->id ?>','Employee Medical Edit Detail Form','<?php echo $m?>')">
                                                                                    Edit
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                        @if(in_array('repost', $operation_rights))
                                                                            @if($y->status == 2)
                                                                                <li role="presentation">
                                                                                    <a class="delete-modal btn" onclick="repostCompanyTableRecord('<?php echo $m ?>','<?php echo $y->id ?>','employee_medical')">
                                                                                        Repost
                                                                                    </a>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                        @if(in_array('delete', $operation_rights))
                                                                            @if($y->status == 1)
                                                                                <li role="presentation">
                                                                                    <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('<?php echo $m ?>','<?php echo $y->id ?>','employee_medical')">
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
                        </span>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>

        $(document).ready(function() {
            var table = $('#MedicalList').DataTable({
                "dom": "t",
                "bPaginate" : false,
                "bLengthChange" : true,
                "bSort" : false,
                "bInfo" : false,
                "bAutoWidth" : false

            });

            $('#emr_no').keyup( function() {
                table.columns(1).search(this.value).draw();
            });

        });



    </script>
@endsection