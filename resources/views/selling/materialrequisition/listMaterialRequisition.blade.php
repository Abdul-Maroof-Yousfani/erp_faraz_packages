@extends('layouts.default')
@section('content')

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="headquid">
                                    <h2 class="subHeadingLabelClass">Material Requisition List</h2>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <table class="userlittab table table-bordered sf-table-list">
                                            <thead>
                                                <tr>
                                                    <!-- <th class="text-center"></th> -->
                                                    <th class="text-center">S.no</th>
                                                    <th class="text-center">PR No</th>
                                                    <th class="text-center">PP No</th>
                                                    <th class="text-center">MR No</th>
                                                    <th class="text-center">Item</th>
                                                    <th class="text-center">MR Date</th>
                                                    <th class="text-center">Status</th>
                                                    <th class="text-center">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data"></tbody>
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

<script>
    $(document).ready(function () {
        viewRangeWiseDataFilter();
    });

    function viewRangeWiseDataFilter() {
        var Filter = $('#search').val();
        $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
        $.ajax({
            url: '<?php echo url('/')?>/selling/listMaterialRequisition',
            type: 'Get',
            data: { Filter: Filter },
            success: function (response) {
                $('#data').html(response);
            }
        });
    }
</script>
@endsection