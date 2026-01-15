@extends('layouts.default')

@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Wastage and recycle List</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <!-- <ul class="cus-ul2">
                <li>
                    <a href="#" class="btn-a" onclick="createprospects('prospect/createProspect','','Create Prospect','')">Create Prospect</a>
                </li>
                <li>
                    <input type="text" class="fomn1" id="search" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li>
            </ul> -->
        </div>
    </div>
    <div class="row">
      
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw">    
            <div class="panel">
                            <div class="panel-body">
                                <div class="borderBtmMnd ">
                                <div class="headquid">
                           <h2 class="subHeadingLabelClass">View Wastage and recycle List</h2>
                        </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <table class="userlittab table table-bordered sf-table-list">
                                                <thead>
                                                <tr>
                                                    <th>SR No.</th>
                                                 
                                                    <th>Item Name</th>
                                                    <th>Ware House</th>
                                                    <th>Batch code</th>
                                                    <th>QTY</th>
                                                    <th>Date</th> 
                                                    <th>Material </th> 
                                                    <th>Status</th> 
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                                
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

    <script>
    $(document).ready(function(){
        viewRangeWiseDataFilter();
        });
         function viewRangeWiseDataFilter()
        {

            var Filter=$('#search').val();

           
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/selling/listWaistageAndRecycle',
                type: 'Get',
                data: {Filter:Filter},
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }
    </script>


@endsection