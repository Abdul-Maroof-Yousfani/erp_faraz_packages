@extends('layouts.default')

@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Raw Material</h3>
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

        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
            <div class="dp_sdw2">    
                <div class="row">

                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="row">
                                            <table class="table cus-tab">
                                                <thead>
                                                <tr>
                                                    <th>SR No.</th>
                                                    <th>MR No.</th>
                                                    <th>MR Date</th>
                                                    <th>Work Order No.</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>#123</td>
                                                    <td>MR-0001</td>
                                                    <td>11-13-2023</td>
                                                    <td>$3302</td>
                                                    <td>Issued</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="drop-bt dropdown-toggle"
                                                                type="button" data-toggle="dropdown"
                                                                aria-expanded="false">
                                                                ...
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="#"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                                                    <a href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                                                    <a href="#"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>#123</td>
                                                    <td>MR-0001</td>
                                                    <td>11-13-2023</td>
                                                    <td>$3302</td>
                                                    <td>Issued</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="drop-bt dropdown-toggle"
                                                                type="button" data-toggle="dropdown"
                                                                aria-expanded="false">
                                                                ...
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="#"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                                                    <a href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                                                    <a href="#"><span class="glyphicon glyphicon-eye-open"></span> View</a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>#123</td>
                                                    <td>MR-0001</td>
                                                    <td>11-13-2023</td>
                                                    <td>$3302</td>
                                                    <td>Issued</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="drop-bt dropdown-toggle"
                                                                type="button" data-toggle="dropdown"
                                                                aria-expanded="false">
                                                                ...
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <a href="#"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                                                    <a href="#"><span class="glyphicon glyphicon-trash"></span> Delete</a>
                                                                    <a href="#"><span class="glyphicon glyphicon-eye-open"></span> View</a>
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
                </div>
            </div>
            </div>
        </div>
    </div>

    
@endsection