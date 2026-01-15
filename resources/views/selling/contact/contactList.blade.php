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
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Contact</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <ul class="cus-ul2">
                <li>
                    <a href="#" class="btn btn-primary" onclick="createContact('contact/createContact','','Add Contact','')">Create Contact</a>
                </li>
                <li>
                    <input type="text" class="fomn1" id="search" placeholder="Search Anything" >
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
                                                    <th>Name</th>
                                                    <th>Title</th>
                                                    <th>Contact No</th>
                                                    <th>Email</th>
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
        </div>
    </div>

    
    <script>
        $(document).ready(function(){
            get_contact_all();
        });
        function get_contact_all()
    {
         
        $('#data').empty();
        var search  = '';
         $.ajax({
                    url: "{{route('contactList')}}",
                    type: 'Get',
                    data: {search:search},
                 success: function (responsedata) {
                        $('#data').append(responsedata);
                    
                 }
                });
    
    }
    </script>

@endsection
