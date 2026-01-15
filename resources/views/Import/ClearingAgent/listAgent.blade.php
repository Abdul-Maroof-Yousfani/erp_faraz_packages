@extends('layouts.default')

@section('content')
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Import</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Clearing Agent List</h3>
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

                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="headquid">
                                            <h2 class="subHeadingLabelClass">View Clearing Agent List </h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="">
                                                    <table class="userlittab table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Clearing Agent</th>
                                                                {{-- <th class="text-center">Rate</th>
                                                    <th class="text-center">Date</th> --}}
                                                                <!-- <th class="text-center">To Date</th> -->
                                                                <th class="text-center">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data">
                                                            @php
                                                                $count = 1;
                                                            @endphp

                                                            @foreach ($claering_agent as $claering_agent)
                                                                <tr id="tr">
                                                                    <td>{{ $claering_agent->agent_name }}</td>
                                                                    <td class="text-center">
                                                                        <div class="dropdown">
                                                                            <button class="drop-bt dropdown-toggle"
                                                                                type="button" data-toggle="dropdown"
                                                                                aria-expanded="false">
                                                                                ...
                                                                            </button>
                                                                            <ul class="dropdown-menu">
                                                                                <li>

                                                                                    <a href="{{ route('Agent.edit', $claering_agent->id) }}"
                                                                                        class="btn btn-sm btn-warning "
                                                                                        target="_blank"><i
                                                                                            class="fa fa-pencil"
                                                                                            aria-hidden="true"></i> Edit</a>
                                                                                    {{-- <a onclick="delete_row('#tr{{ $count }}' , {{ $currency->id }})"
                                                                                        href="#"
                                                                                        class="btn btn-sm btn-danger"><i
                                                                                            class="fa fa-trash-o"
                                                                                            aria-hidden="true"></i>
                                                                                        Delete</a> --}}
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </td>

                                                                </tr>
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
