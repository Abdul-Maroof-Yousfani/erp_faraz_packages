@extends('layouts.default')
@section('content')

<div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="row  align-items-center">
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <ul class="cus-ul">
                            <li class="first">
                                <h1>Dashboard</h1>
                            </li>
                            <li class="last">
                                <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; My Desk</h3>
                            </li>
                        </ul>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
                        <div class="desk_buts">
                            <a href="#" class="btn pendings_approvalss">Pending Approvals</a>
                            <a href="#" class="btn approveds">Approved</a>
                            <a href="#" class="btn rejecteds">Rejected Approvals</a>
                        </div>
                    </div>
                </div>
                <div class="dp_sdw">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="headquid">
                                        <div class="desk_head">
                                            <h2 class="subHeadingLabelClass">My Desk</h2>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="userlittab table table-bordered sf-table-list">
                                                    <thead>
                                                    <tr>
                                                        <th class="text-center">Form Details</th>
                                                        <th class="text-center">Id</th>
                                                        <th class="text-center">Description</th>
                                                        <th class="text-center">Department</th>
                                                        <th class="text-center">Date</th>
                                                        <th class="text-center">Pending Days</th>
                                                        <th class="text-center">Qty</th>
                                                        <th class="text-center">Amount</th>
                                                        <th class="text-center">Status</th>
                                                        <th class="text-center">Action</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Purchase Requisition</td>
                                                            <td>#45</td>
                                                            <td>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia delectus, officiis accusantium autem fuga veritatis nostrum </td>
                                                            <td>Marketing</td>
                                                            <td>16-05-2024</td>
                                                            <td>10</td>
                                                            <td>05</td>
                                                            <td>30,000 PKR</td>
                                                            <td style="width: 198px;">
                                                                <div class="pending">
                                                                    Pending For Approve
                                                                </div>
                                                                <div class="approved">
                                                                    Approved
                                                                </div>
                                                                <div class="rejected">
                                                                    Rejected
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a class="" onclick=""><i class="fa-regular fa-eye"></i> View</a>
                                                                            <a class="" onclick=""><i class="fa-solid fa-print"></i> Print</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-pencil"></i> Edit</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-trash"></i> Delete</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-check"></i> Approve</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-xmark"></i> Reject </a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        <br>
                                                        <tr>
                                                            <td>Purchase Requisition</td>
                                                            <td>#45</td>
                                                            <td>Lorem ipsum dolor sit amet consectetur, adipisicing elit. Mollitia delectus, officiis accusantium autem fuga veritatis nostrum </td>
                                                            <td>Marketing</td>
                                                            <td>16-05-2024</td>
                                                            <td>10</td>
                                                            <td>05</td>
                                                            <td>30,000 PKR</td>
                                                            <td style="width: 198px;">
                                                                <div class="pending">
                                                                    Pending For Approve
                                                                </div>
                                                                <div class="approved">
                                                                    Approved
                                                                </div>
                                                                <div class="rejected">
                                                                    Rejected
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <div class="dropdown">
                                                                    <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                    <ul class="dropdown-menu">
                                                                        <li>
                                                                            <a class="" onclick=""><i class="fa-regular fa-eye"></i> View</a>
                                                                            <a class="" onclick=""><i class="fa-solid fa-print"></i> Print</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-pencil"></i> Edit</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-trash"></i> Delete</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-check"></i> Approve</a>
                                                                            <a href="#" class=""><i class="fa-solid fa-xmark"></i> Reject </a>
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