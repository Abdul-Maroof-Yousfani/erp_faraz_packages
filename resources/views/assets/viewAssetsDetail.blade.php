<?php
use App\Helpers\CommonHelper;

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php CommonHelper::displayPrintButtonInView('printDemandVoucherVoucherDetail','','1');?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printDemandVoucherVoucherDetail">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well">
            <div style="line-height:5px;">&nbsp;</div>
            <h3 style="text-align: center">Asset Detail</h3>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div>
                        <table class="table table-bordered tableMargin">
                     
                            <tr>
                                <td>Asset Code</td>
                                <td>{{ $assets->asset_code }}</td>
                                <td>Asset Name</td>
                                <td>{{ $assets->asset_name }}</td>
                                <td>Status</td>
                                <td>{{ $assets->asset_status }}</td>
                            </tr>
                            <tr>
                                <td>Premise</td>
                                <td>@if(array_key_exists($assets->premise_id, $premises_array)) {{ $premises_array[$assets->premise_id]->premises_name }} @endif</td>

                                <td>Floor</td>
                                <td>@if(array_key_exists($assets->floor_id, $floors_array)) {{ $floors_array[$assets->floor_id]->floor }} @endif</td>

                                <td>Department</td>
                                <td>@if(array_key_exists($assets->department_id, $departments_array)) {{ $departments_array[$assets->department_id]->department_name }} @endif</td>
                            </tr>
                            <tr>
                                <td>Category</td>
                                <td>@if(array_key_exists($assets->category_id, $category_array)) {{ $category_array[$assets->category_id]->category_name }} @endif</td>

                                <td>Sub Category</td>
                                <td>@if(array_key_exists($assets->sub_category_id, $sub_category_array)) {{ $sub_category_array[$assets->sub_category_id]->sub_category_name }} @endif</td>

                                <td>Manufacturer</td>
                                <td>@if(array_key_exists($assets->manufacturer_id, $manufacturer_array)) {{ $manufacturer_array[$assets->manufacturer_id]->manufacturer_name }} @endif</td>
                            </tr>
                            <tr>
                                <td>Installed Date</td>
                                <td>{{ CommonHelper::changeDateFormat($assets->installed_date) }}</td>

                                <td>Details</td>
                                <td colspan="3">{{ $assets->asset_detail_description }}</td>
                            </tr>
                            <tr>
                                <td>Purchased Date</td>
                                <td>{{ CommonHelper::changeDateFormat($assets->purchase_date) }}</td>
                                <td>Purchase Price</td>
                                <td>{{ $assets->purchase_price }}</td>
                                <td>Useful Life</td>
                                <td>@if(array_key_exists($assets->useful_life_id, $life_array)) {{ $life_array[$assets->useful_life_id]->useful_life_name }} @endif</td>
                            </tr>
                            <tr>
                                <td>Depreciation Method</td>
                                <td>@if(array_key_exists($assets->depreciation_method, $depreciation_method)) {{ $depreciation_method[$assets->depreciation_method] }} @endif</td>

                                <td>Depreciation Per Year</td>
                                <td>{{ $assets->depreciation }}</td>

                                <td>Net Book Value</td>
                                <td>100%</td>
                            </tr>
                        </table>
                    </div>
                    <div>
                        <h3 style="text-align: center">Asset PM Detail</h3>
                            <table class="userlittab table table-bordered table-hover tableFixHead" id="exportList">
                                <thead>
                                <th>S.No</th>
                                <th>PM Date</th>
                                <th>Remarks</th>
                                <th id="hide-table-column" class="hidden-print">Action</th>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>2</td>
                                    <td>{{ CommonHelper::changeDateFormat('2024-02-01') }}</td>
                                    <td>--</td>
                                    <td id="hide-table-column" class="hidden-print">
                                        <div class="dropdown">
                                            <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                ...
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li onclick="showModal('viewAssetsDetail','','View Assets Detail','')">
                                                    <a class="edit-modal">View Findings</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>{{ CommonHelper::changeDateFormat('2023-12-01') }}</td>
                                    <td>--</td>
                                    <td id="hide-table-column" class="hidden-print">
                                        <div class="dropdown">
                                            <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                                ...
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li onclick="showModal('viewAssetsDetail','','View Assets Detail','')">
                                                    <a class="edit-modal">View Findings</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>