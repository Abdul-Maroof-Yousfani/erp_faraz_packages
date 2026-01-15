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

    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Bonus List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                @if(in_array('print', $operation_rights))
                                    <?php echo CommonHelper::displayPrintButtonInBlade('PrintBonusList','','1');?>
                                @endif
                                @if(in_array('export', $operation_rights))
                                    <?php echo CommonHelper::displayExportButton('BonusList','','1')?>
                                @endif
                            </div>

                        </div>
                        <div class="panel">
                            <div class="panel-body" id="PrintBonusList">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover" id="BonusList">
                                                <thead>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">Bonus Name</th>
                                                <th class="text-center">% Of Salary</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center col-sm-1 hidden-print">Action</th>

                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @foreach($bonus as $key => $value)
                                                    <tr>
                                                        <td class="text-center">{{ $counter++ }}</td>
                                                        <td class="text-center">{{ $value->bonus_name }}</td>
                                                        <td class="text-center">{{ $value->percent_of_salary }}</td>
                                                        <td class="text-center">{{ HrHelper::getStatusLabel($value->status) }}</td>
                                                        <td class="text-center hidden-print">
                                                            <div class="dropdown">
                                                                <button class="btn btn-primary dropdown-toggle btn-xs" type="button" id="menu1" data-toggle="dropdown">Actions <span class="caret"></span></button>
                                                                <ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
                                                                    @if(in_array('edit', $operation_rights))
                                                                        <li role="presentation">
                                                                            <a class="edit-modal btn" onclick="showDetailModelTwoParamerter('hr/editBonusDetailForm','<?php echo $value->id;?>','Edit Bonus Detail','<?php echo $m; ?>')">
                                                                                Edit
                                                                            </a>
                                                                        </li>
                                                                    @endif
                                                                    @if(in_array('delete', $operation_rights))
                                                                        @if($value->status == 1)
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn" onclick="deleteRowCompanyRecords('<?php echo $m ?>','<?php echo $value->id ?>','bonus')">
                                                                                    Delete
                                                                                </a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                    @if(in_array('repost', $operation_rights))
                                                                        @if($value->status == 2)
                                                                            <li role="presentation">
                                                                                <a class="delete-modal btn" onclick="repostOneTableRecords('<?php echo $m ?>','<?php echo $value->id ?>','bonus')">
                                                                                    Repost
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
    </div>


@endsection