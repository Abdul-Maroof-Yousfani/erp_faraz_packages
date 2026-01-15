@extends('layouts.default')

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid">
                                        <h2 class="subHeadingLabelClass">View Sale Order List </h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="">
                                                <table class="userlittab table table-bordered sf-table-list">
                                                    <thead>
                                                        <tr>
                                                            <th class="text-center">So No.</th>
                                                            <th class="text-center">Customer Name</th>
                                                            <th class="text-center">Order Date</th>
                                                            <th class="text-center">Amount</th>
                                                            <th class="text-center">Status</th>
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
                url: '<?php echo url('/')?>/selling/listSaleOrder',
                type: 'Get',
                data: { Filter: Filter },
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>


@endsection