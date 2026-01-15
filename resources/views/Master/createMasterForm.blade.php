<?php
use App\Helpers\CommonHelper;
$m = Input::get('m');
?>
@extends('layouts.default')
@section('content')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">Create Master</h2>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Premise Details</h6>
                                    </div>
                                    {{ Form::open(array('url' => 'add-premises')) }}
                                    <div class="col-ld-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Premises Name</label>
                                                <input type="text" class="form-control" name="premises_name" id="premises_name" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Category</h6>
                                    </div>
                                    {{ Form::open(array('url' => 'add-category')) }}
                                    <div class="col-ld-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Category Name</label>
                                                <input type="text" class="form-control" name="category_name" id="category_name" required />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Category Abbreviation</label>
                                                <input type="text" class="form-control" name="category_abbreviation" id="category_abbreviation" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Sub Category</h6>
                                    </div>
                                    {{ Form::open(array('url' => 'add-sub-category')) }}
                                    <div class="col-ld-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Category Name</label>
                                                <select name="category_id" id="category_id" class="form-control" required>
                                                    <option value="">Select Option</option>
                                                    @foreach($categories as $value)
                                                        <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Sub Category Name</label>
                                                <input type="text" class="form-control" name="sub_category_name" id="sub_category_name" required />
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Sub Category Abbreviation</label>
                                                <input type="text" class="form-control" name="sub_category_abbreviation" id="sub_category_abbreviation" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Floors</h6>
                                    </div>
                                    {{ Form::open(array('url' => 'add-floor')) }}
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Floor</label>
                                                <input type="text" class="form-control" name="floor" id="floor" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Manufacturer</h6>
                                    </div>
                                    {{ Form::open(array('url' => 'add-manufacturer')) }}
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Manufacturer Name</label>
                                                <input type="text" class="form-control" name="manufacturer_name" id="manufacturer_name" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Useful Life</h6>
                                    </div>
                                    {{ Form::open(array('url' => 'add-useful-life')) }}
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Useful Life Name</label>
                                                <input type="text" class="form-control" name="useful_life_name" id="useful_life_name" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">PM Frequency</h6>
                                    </div>
                                    {{ Form::open(array('url' => 'add-frequency')) }}
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Frequency</label>
                                                <input type="text" class="form-control" name="frequency" id="frequency" required />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>
                            </div>
                        </div>
                        <hr style="border-color: #AEAEAE;">
                        {{  Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $('#category_id').select2();
    </script>
@endsection