@extends('layouts.default')

@section('content')
    @include('select2')
    <style>
        .my-lab label {
            padding-top: 0px;
        }
    </style>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Create Qa Test</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">

        </div>
    </div>
    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw2">
                    <div class="row">

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <form action="{{route('QaTest.store')}}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>Add Qa Test</h1>
                                                    </div>

                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="row">

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Name</label>
                                                                    <input name="name" id="name" class="form-control"
                                                                        type="text">
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">QA Type</label>
                                                                    <select name="qc_type" id="qc_type"
                                                                        class="form-control">
                                                                        <option value="">Select QA Type</option>
                                                                        <option value="1">Packaging</option>
                                                                        <option value="2">GRN</option>
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4 text-right" style="margin-top: 25px;">
                                                                <button type="submit"
                                                                    class="btn btn-primary mr-1">Save</button>
                                                                <a href="{{ route('QaTest.cancel') }}"
                                                                    class="btn btn-secondary">Cancel</a>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection