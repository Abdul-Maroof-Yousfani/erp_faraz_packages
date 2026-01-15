@extends('layouts.default')
@section('content')

<div class="row">

    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="panel">
                    <div class="panel-body">
                        <div class="borderBtmMnd ">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">View Production List</h2>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <table class="userlittab table table-bordered sf-table-list">
                                        <thead>
                                            <tr>
                                                <th class="text-center">SR No.</th>
                                                <th class="text-center">PR NO </th>
                                                <th class="text-center">PP No</th>
                                                <th class="text-center">Product Name</th>
                                                <th class="text-center">Color</th>
                                                <th class="text-center">Qty</th>
                                                <th class="text-center">Start Date</th>
                                                <th class="text-center">End Date</th>
                                                <th class="text-center">Approval Status</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="data"> </tbody>
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
    $(document).ready(function () {
        viewRangeWiseDataFilter();
    });
    
    function viewRangeWiseDataFilter() {
        var Filter = $('#search').val();
        $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
        $.ajax({
            url: '<?php echo url('/')?>/selling/listProductionOrder',
            type: 'Get',
            data: { Filter: Filter },
            success: function (response) {
                $('#data').html(response);
            }
        });
    }
</script>


@endsection