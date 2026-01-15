<?php
use App\Helpers\CommonHelper;
$counter = 1;
?>

@extends('layouts.default')
@section('content')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row align-items-center head-bott">
                        <div class="col-md-4 head-h2">
                            <h2 class="subHeadingLabelClass">View Assets Reports</h2>
                        </div>
                        <div class="col-md-8 text-right">
                            {{ CommonHelper::displayPrintButtonInBlade('printList','','1') }}&nbsp;&nbsp;
                            {{ CommonHelper::displayExportButton('EmpExitInterviewList','','1') }}
                        </div>
                    </div>
                    <div id="printList">
                        <div class="panel">
                            <div>
                                <div class="table-responsive wrapper">
                                    <h5 style="text-align: center" id="h3"></h5>
                                    <table class="userlittab table table-bordered table-hover tableFixHead" id="exportList">
                                        <thead>
                                        <th>S.No</th>
                                        <th>Asset Code</th>
                                        <th>Asset Nmae</th>
                                        <th>Installed Date</th>
                                        <th>Premise</th>
                                        <th>Purchase Price</th>
                                        <th>Useful Life</th>
                                        <th>Depreciation Method</th>
                                        <th>Depreciation Per Year</th>
                                        <th>Net Book Value</th>
                                        </thead>
                                        <tbody>
                                        @if(!empty($assets))
                                            @foreach ($assets as $key => $value)
                                                <?php
                                                $book_value = 0;
                                                $useful_life = 5;
                                                if(array_key_exists($value->useful_life_id, $life_array)) {
                                                    $useful_life = $life_array[$value->useful_life_id]->useful_life_name;
                                                }
                                                if($value->depreciation_method == 1) {


                                                    // Assuming $purchase_price is the price of the asset and $purchase_date is the purchase date
                                                     $purchase_price = $value->purchase_price; // in Rs
                                                     $purchased_date = strtotime($value->purchased_date);
                                                     $current_date = time(); // Current date
                                 
                                                     // Calculate the elapsed time since the purchase date (in years)
                                                     $elapsed_time = ($current_date - $purchased_date) / (365 * 24 * 60 * 60);
                                 
                                                     // Assuming depreciation rate is 10% per year
                                                     $depreciation_rate = $value->depreciation / 100;
                                 
                                                     // Calculate accumulated depreciation
                                                     $accumulated_depreciation = $purchase_price * $depreciation_rate * $elapsed_time;
                                 
                                                     // Calculate net book value
                                                     $net_book_value = $purchase_price - $accumulated_depreciation;
                                 
                                                     // Output the net book value
                                                     $book_value = round($net_book_value);
                                                 }
                                                ?>
                                                <tr>
                                                    <td>{{ $counter++ }}</td>
                                                    <td>{{ $value->asset_code }}</td>
                                                    <td>{{ $value->asset_name }}</td>
                                                    <td>{{ CommonHelper::changeDateFormat($value->installed_date) }}</td>
                                                    <td>@if(array_key_exists($value->premise_id, $premises_array)) {{ $premises_array[$value->premise_id]->premises_name }} @endif</td>
                                                    <td>{{ $value->purchase_price }}</td>
                                                    <td>{{ $useful_life }}</td>
                                                    <td>@if(array_key_exists($value->depreciation_method, $depreciation_method)) {{ $depreciation_method[$value->depreciation_method] }} @endif</td>
                                                    <td>{{ $value->depreciation }}</td>
                                                    <td>{{ $book_value }}</td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr><td colspan="8" style="text-align:center;color: red">No record found !</td></tr>
                                        @endif
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

@endsection
