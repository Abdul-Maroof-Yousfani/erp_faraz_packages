@extends('layouts.default')
@section('content')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">Upload Asset</h2>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    {{ Form::open(array('url' => 'upload-assets-file', 'enctype' => 'multipart/form-data')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div id="importProducts___BV_modal_body_" class="modal-body">
                            <div class="row">
                                <div class="col-lg-4 col-sm-4 col-md-4 col-xs-12">
                                    <fieldset class="form-group" id="__BVID__194">
                                        <div>WWWW
                                            <input type="file" name='file' class="form-control" label="Choose File" required>
                                            <div id="File-feedback" class="d-block invalid-feedback">File must be in
                                                csv format</div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <!-- <div class="row">
                                <div class="col-sm-6 col-md-6"><button type="submit"
                                                                    class="btn btn-primary btn-sm btn-block">Submit</button></div>
                                <div class="col-sm-6 col-md-6"><button onclick="download_csv_file()" target="_self"
                                                                    class="btn btn-info btn-sm btn-block">Download example</button></div>
                            </div> -->
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                            <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@endsection