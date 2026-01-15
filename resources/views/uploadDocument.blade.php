<?php

$m = Session::get('run_company');
use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
        <div class="dp_sdw">

            <div class="well">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <span class="subHeadingLabelClass">Document Upload </span>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <form method="post" action="{{route('uploadCustomer')}}" enctype='multipart/form-data' >
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="">Select Excel File</label>
                                <input type="file" class="form-control" name="file">
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                <label for="">&nbsp;</label>
                              <button type="submit" class="btn btn-primary"> Submit</button>
                            </div>
                        </form>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
