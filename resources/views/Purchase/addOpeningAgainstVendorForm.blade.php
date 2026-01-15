@extends('layouts.default')
@section('content')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                @if(count($errors) > 0)
                    <div class="alert alert-danger">
                        
                        <ul>
                            <li>Upload Validation Error</li>
                            @foreach($errors as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(!empty(Session::get('submit_errors')))
                    <div class="alert alert-danger">
                        <ul>
                            @foreach(Session::get('submit_errors') as $errorTwo)
                                <li>Vendor Name => {{ $errorTwo['vendor_name'] }}, Opening Amount => {{ $errorTwo['amount'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if($message = Session::get('success'))
                    <div class="alert alert-success alert-block">
                        <button type="button" class="close" data-dismiss="alert">×</button>
                        <strong>{{ $message }}</strong>
                    </div>
                @endif
                <form method="post" enctype="multipart/form-data" action="{{ url('/pad/import_vendor_opening') }}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <table class="table">
                            <tr>
                                <td width="40%" align="right"><label>Select File for Upload</label></td>
                                <td width="30">
                                    <input type="file" name="select_file" class="form-control" />
                                </td>
                                <td width="30%" align="left">
                                    <input type="submit" name="upload" class="btn btn-primary" value="Upload">
                                </td>
                            </tr>
                            <tr>
                                <td width="40%" align="right"></td>
                                <td width="30"><span class="text-muted">.xls, .xslx</span></td>
                                <td width="30%" align="left"></td>
                            </tr>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection