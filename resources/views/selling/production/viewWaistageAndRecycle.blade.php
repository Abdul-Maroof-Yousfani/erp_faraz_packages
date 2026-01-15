<?php
use App\Helpers\CommonHelper;
// $ppc = CommonHelper::generateUniquePosNo('production_plane', 'order_no', 'PPC');
?>
<style>
    tbody.disabled {
        opacity: 0.5;
        /* You can adjust the styling for disabled rows */
        /* Add any other styles as needed */
    }

    input[type="checkbox"] {
        width: 30px;
        height: 30px;
    }

    input[type="number"]::-webkit-inner-spin-button,
    input[type="number"]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>
<div class="row well_N align-items-center">

    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

    </div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel-body">
                    <div class="headquid bor-bo">
                        <h2 class="subHeadingLabelClass">view Recycle Wastage</h2>
                    </div>
                    @if ($recycle_wastage->type == 1)
                        
                    <div class="headquid ">
                        <h2 class="subHeadingLabelClass">Wastage Detail  ,  Date : {{ $recycle_wastage->date }}</h2>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row qout-h"> 
                        <div class="col-md-12 ">

                            <div class="headquid ">
                                <h2 class="subHeadingLabelClass">Input Materials</h2>
                            </div>
                            <div class="col-md-12" id="AppnedHtml">

                                <table class="userlittab table table-bordered sf-table-list" id="more_details">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th> 
                                            <th>QTY</th>
                                            <th>PPC</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recycle_wastage_data as $row)
                                            <tr>
                                                <td>{{ CommonHelper::get_item_name($row->item_id) }}</td> 
                                                <td>{{ $row->qty }}</td>
                                                <td>{{ $row->ppc }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>


                            <div class="headquid ">
                                <h2 class="subHeadingLabelClass">OutPut Material</h2>
                            </div>

                            <div class="col-md-12" id="AppnedHtml">

                                <table class="userlittab table table-bordered sf-table-list" id="more_details">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Ware House</th>
                                            <th>Batch code</th>
                                            <th>QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ CommonHelper::get_item_name($recycle_wastage->item_id) }}</td>
                                            <td>{{ CommonHelper::get_location($recycle_wastage->warehouse_id) }}</td>
                                            <td>{{ $recycle_wastage->batch_code }}</td>
                                            <td>{{ $recycle_wastage->qty }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            

                        </div>




                    </div>
                    @else
                    <div class="headquid ">
                        <h2 class="subHeadingLabelClass">Recycle Detail ,  Date : {{ $recycle_wastage->date }}</h2>
                    </div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row qout-h"> 
                        <div class="col-md-12 ">

                            <div class="headquid ">
                                <h2 class="subHeadingLabelClass">Input Materials</h2>
                            </div>
                            <div class="col-md-12" id="AppnedHtml">

                                <table class="userlittab table table-bordered sf-table-list" id="more_details">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Ware House</th>
                                            <th>Batch code</th>
                                            <th>QTY</th>
                                            <th>Item Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recycle_wastage_data as $row)
                                            <tr>
                                                <td>{{ CommonHelper::get_item_name($row->item_id) }}</td>
                                                <td>{{ CommonHelper::get_location($row->warehouse_id) }}</td>
                                                <td>{{ $row->batch_code }}</td>
                                                <td>{{ $row->qty }}</td>
                                                <td>{{ $row->item_type == 1 ? "Recycle Material" : 'Raw Material'}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
 


                            <div class="headquid ">
                                <h2 class="subHeadingLabelClass">OutPut Material</h2>
                            </div>

                            <div class="col-md-12" id="AppnedHtml">

                                <table class="userlittab table table-bordered sf-table-list" id="more_details">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Ware House</th>
                                            <th>Batch code</th>
                                            <th>QTY</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ CommonHelper::get_item_name($recycle_wastage->item_id) }}</td>
                                            <td>{{ CommonHelper::get_location($recycle_wastage->warehouse_id) }}</td>
                                            <td>{{ $recycle_wastage->batch_code }}</td>
                                            <td>{{ $recycle_wastage->qty }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            

                        </div>




                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
