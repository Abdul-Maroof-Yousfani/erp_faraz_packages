<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
$m = Input::get('m');
use App\Helpers\CommonHelper;
use App\Helpers\AssetsHelper;

?>
<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">

<style>
    input[type="radio"], input[type="checkbox"]{ width:30px;
        height:20px;
    }

    td{ padding: 2px !important;}
    th{
        padding: 2px !important;
        background-color: #e0dbdb82 !important;
    }

    table { font-size: 14px; }

</style>

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div class="row">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="m" value="{{ $m }}">
                <input type="hidden" name="assetsSection[]" class="form-control" id="assetsSection" value="1" />
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="panel">
                        <div class="panel-body" id="PrintAssetsDetail">
                            <table style="table-layout: fixed;" class="table table-bordered sf-table-list">
                                <thead>
                                <tr>
                                    <th class="text-center project">Project Name / Code </th>
                                    <td class="text-center">{{ $assets_project->value('project_name') }}</td>
                                    <th class="text-center employee_region">Region</th>
                                    <td class="text-center">{{ $regions->value('employee_region') }}</td>
                                </tr>
                                </thead>

                                <thead>
                                <tr>
                                    <th class="text-center building">Premise Code</th>
                                    <td class="text-center">{{ $buildings->value('building_code') }}</td>
                                    <th class="text-center building">Premise Name</th>
                                    <td class="text-center">{{ $buildings->value('building_name') }}</td>
                                </tr>
                                </thead>

                                <thead>
                                <tr>

                                </tr>
                                </thead>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
