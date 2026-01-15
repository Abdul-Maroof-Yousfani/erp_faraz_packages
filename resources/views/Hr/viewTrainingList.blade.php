<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
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
                            <span class="subHeadingLabelClass">View Trainings List</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            @if(in_array('print', $operation_rights))
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintTaxesList','','1');?>
                            @endif
                            @if(in_array('export', $operation_rights))
                                <?php echo CommonHelper::displayExportButton('TaxesList','','1')?>
                            @endif
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-body" id="PrintTaxesList">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered sf-table-list table-hover" id="TaxesList">
                                            <thead>
                                            <th class="text-center col-sm-1">S.No</th>
                                            <th class="text-center">Region</th>
                                            <th class="text-center">Category</th>
                                            <th class="text-center">Trainer Name</th>
                                            <th class="text-center">Certificate Number</th>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Topic</th>
                                            <th class="text-center">Training Date</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center col-sm-1 hidden-print">Action</th>
                                            </thead>
                                            <tbody>
                                            <?php $counter = 1;?>
                                            @foreach($trainingsData as $key => $value)
                                                <tr>
                                                    <td class="text-center">{{ $counter++ }}</td>
                                                    <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'regions','employee_region',$value->region_id) }}</td>
                                                    <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'employee_category','employee_category_name',$value->employee_category_id) }}</td>
                                                    <td class="text-center">{{$value->trainer_name}}</td>
                                                    <td class="text-center">{{$value->certificate_number}}</td>
                                                    <td>{{ HrHelper::getMasterTableValueById(Input::get('m'),'locations','employee_location',$value->location_id) }}</td>
                                                    <td>{{ $value->topic_name }}</td>
                                                    <td class="text-center">{{ HrHelper::date_format($value->training_date) }}</td>
                                                    <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>

                                                    <td class="text-center hidden-print">
                                                        <div class="dropdown">
                                                            <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions
                                                                <span class="caret"></span></button>
                                                            <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                @if(in_array('view', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showDetailModelTwoParamerter('hdc/viewTrainingDetail','<?php echo $value->id ?>','View Training Detail','<?php echo $m ?>')">
                                                                            View
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('edit', $operation_rights))
                                                                    <li role="presentation">
                                                                        <a  class="delete-modal btn" onclick="showMasterTableEditModel('hr/editTrainingDetailForm','<?php echo $value->id ?>','Training Edit Detail Form','<?php echo $m ?>')">
                                                                            Edit
                                                                        </a>
                                                                    </li>
                                                                @endif
                                                                @if(in_array('repost', $operation_rights))
                                                                    @if($value->status == 2)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="repostCompanyTableRecord('{{ $m }}','{{ $value->id }}','trainings')">
                                                                                Repost
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                @endif
                                                                @if(in_array('delete', $operation_rights))
                                                                    @if($value->status == 1)
                                                                        <li role="presentation">
                                                                            <a class="delete-modal btn" onclick="deleteRowCompanyHRRecords('{{ $m }}','{{ $value->id }}','trainings')">
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


@endsection