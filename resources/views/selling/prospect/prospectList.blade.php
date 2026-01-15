@extends('layouts.default')
@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Prospects</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <ul class="cus-ul2">
                <li>
                    <a href="#" class="btn btn-primary" onclick="createprospects('prospect/createProspect','','Create Prospect','')">Create Prospect</a>
                </li>
                <li>
                    <input type="text" class="fomn1" id="search" placeholder="Search Anything" >
                </li>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid">
                                        <h2 class="subHeadingLabelClass">View Prospects</h2>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <table class="userlittab table table-bordered sf-table-list table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">Contact Person Name</th>
                                                    <th class="text-center">Company Name</th>
                                                    {{-- <th>Company Address</th> --}}
                                                    <th class="text-center">Company Location</th>
                                                    {{-- <th>Customer</th> --}}
                                                    <th class="text-center">Contact</th>
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
        $(document).ready(function(){
            viewRangeWiseDataFilter();
        });

        function viewRangeWiseDataFilter()
        {
            var Filter=$('#search').val();
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');
            $.ajax({
                url: '<?php echo url('/')?>/prospect/prospectList',
                type: 'Get',
                data: {Filter:Filter},
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }


        

    </script>
@endsection