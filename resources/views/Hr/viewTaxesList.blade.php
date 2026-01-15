<?php
$accType = Auth::user()->acc_type;
$m = Input::get('m');
use App\Helpers\HrHelper;
use App\Helpers\CommonHelper;
?>

@extends('layouts.default')
@section('content')
    <style>
        td{ padding: 2px !important;}
        th{ padding: 2px !important;}
    </style>
    <div class="panel">
        <div class="panel-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="well_N">
                    <div class="dp_sdw">    
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Tax List</span>
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
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list table-hover table-striped" id="TaxesList">
                                                <thead>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center">Tax Name</th>
                                                    <th class="text-center">Salary Range</th>
                                                    <th class="text-center">Tax Mode</th>
                                                    <th class="text-center">% / Amount</th>
                                                    <th class="text-center">Tax Year</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center col-sm-2 hidden-print">Action</th>
                                                </thead>
                                                <tbody>
                                                <?php $counter = 1;?>
                                                @foreach($tax as $key => $value)
                                                    <tr>
                                                        <td class="text-center"><?php echo $counter++;?></td>
                                                        <td class="text-center"><?php echo $value->tax_name;?></td>
                                                        <td class="text-center"><?php echo $value->salary_range_from."--".$value->salary_range_to;?></td>
                                                        <td class="text-center"><?php echo $value->tax_mode;?></td>
                                                        <td class="text-center"><?php echo $value->tax_percent;?></td>
                                                        <td class="text-center"><?php echo $value->tax_month_year;?></td>
                                                        <td class="text-center"><?php echo HrHelper::getStatusLabel($value->status);?></td>
                                                        <td class="text-center hidden-print">
                                                            @if(in_array('edit', $operation_rights))
                                                                 <button class="edit-modal btn btn-info btn-xs" onclick="showMasterTableEditModel('hr/editTaxesDetailForm','<?php echo $value->id ?>','Taxes Edit Detail Form','<?php echo $m?>')">
                                                                    <span class="glyphicon glyphicon-edit"></span>
                                                                 </button>
                                                            @endif
                                                            @if(in_array('repost', $operation_rights))
                                                                @if($value->status == 2)
                                                                    <button class="delete-modal btn btn-primary btn-xs" onclick="repostMasterTableRecords('<?php echo $value->id ?>','tax')">
                                                                        <span class="glyphicon glyphicon-refresh"></span>
                                                                    </button>
                                                                @endif
                                                            @endif
                                                            @if(in_array('delete', $operation_rights))
                                                                @if($value->status == 1)
                                                                    <button class="delete-modal btn btn-danger btn-xs" onclick="deleteRowMasterTable('','<?php echo $value->id ?>','tax')">
                                                                        <span class="glyphicon glyphicon-trash"></span>
                                                                    </button>
                                                                @endif
                                                            @endif
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
    </div>

@endsection