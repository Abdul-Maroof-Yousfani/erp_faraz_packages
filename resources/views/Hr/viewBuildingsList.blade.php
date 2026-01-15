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

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well">
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <span class="subHeadingLabelClass">View Buildings List</span>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                        {{ CommonHelper::displayPrintButtonInBlade('PrintAssetsList','','1') }}
                        {{ CommonHelper::displayExportButton('ExportAssetsList','','1') }}
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-body" id="PrintAssetsList">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list table-hover table-striped" id="ExportAssetsList">
                                        <thead>
                                        <th class="text-center col-sm-1">S.No</th>
                                        <th class="text-center">Region</th>
                                        <th class="text-center">Project</th>
                                        <th class="text-center">Building Name</th>
                                        <th class="text-center">Building Code</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center hidden-print">Action</th>
                                        </thead>
                                        <tbody>
                                        <?php $counter = 1;?>
                                        @if($buildings->count() > 0)
                                            @foreach($buildings->get() as $key => $y)
                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td class="text-center">{{ HrHelper::getMasterTableValueById($m, 'regions', 'employee_region', $y->region_id) }}</td>
                                                    <td class="text-center">{{ HrHelper::getMasterTableValueById($m, 'employee_projects', 'project_name', $y->project_id) }}</td>
                                                    <td>{{ $y->building_name }}</td>
                                                    <td class="text-center">{{ $y->building_code }}</td>
                                                    <td class="text-center">{{ HrHelper::getStatusLabel($y->status) }}</td>

                                                    <td class="text-center hidden-print">
                                                        <div class="dropdown">
                                                            <button class="btn btn-dashboard dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                @if(in_array('view', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewBuildingsDetail','{{ $y->id }}','View Premise Detail','{{ $m }}')">
                                                                            View
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editBuildingsForm','{{ $y->id }}','Buildings Edit Detail Form','{{ $m }}')">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($y->status == 1)
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="deleteRowMasterTable('{{ $y->id }}','buildings')">
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
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection