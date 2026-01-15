@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Inventory Master</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Sale Tax Group</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <ul class="cus-ul2">
                <li>
                    {{-- <a href="#" class="btn-a" onclick="createprospects('prospect/createProspect','','Create Prospect','')">Create Prospect</a> --}}
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
            </ul>
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
                                                    <th>S.No</th>
                                                    <th>Sale Tax Group</th>
                                                    <th>Sale Tax Rate</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                                @php
                                                   $i =1; 
                                                @endphp
                                                @foreach($responses as $response)
                                                <tr>
                                                    <td>{{$i}}</td>
                                                    <td>{{CommonHelper::get_account_name_by_id($response->acc_id)}}</td>
                                                    <td>{{$response->rate}}</td>
                                                    <td>
                                                        <div class="dropdown">
                                                            <button class="drop-bt dropdown-toggle"
                                                                type="button" data-toggle="dropdown"
                                                                aria-expanded="false">
                                                                ...
                                                            </button>
                                                            <ul class="dropdown-menu">
                                                            <li>
                                                                <a  class="btn btn-sm btn-success" >
                                                                    <i class="fa fa-eye" aria-hidden="true"></i>  View</a>
                                                
                                                                <a href="#"  class="btn btn-sm btn-warning "><i class="fa fa-pencil" aria-hidden="true"></i>  Edit</a>
                                                                <a href="#" class="btn btn-sm btn-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Delete</a>
                                                               
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                                @php
                                                $i ++;
                                             @endphp
                                                @endforeach
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