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
                    <h1>Import</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Edit LC And LG</h3>
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
                                    <form action="{{ route('LcAndLg.update', $LcAndLg->id) }}" method="post">
                                         <input type="hidden" name="_token" value="{{ csrf_token() }}">

                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class=" qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>Edit LC And LG</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                    
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Type</label>
                                                            
                                                                <select name="type" class="form-control" id="type">
                                                                    <option value=""> Select type</option>
                                                                    <option  @if($LcAndLg->type == "lc") selected @endif value="lc">LC</option>
                                                                    <option  @if($LcAndLg->type == "bank_gurantee") selected @endif value="bank_gurantee">Bank Gurantee</option>
                                                                </select>                                                            
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label">Bank Accounts*</label>
                                                            
                                                                <select name="acc_id" class="form-control" id="acc_id">
                                                                    <option value=""> Select Account</option>
                                                                    @foreach($account as $key => $value)
                                                                    
                                                                    <option @if($value->id == $LcAndLg->acc_id) selected @endif value="{{ $value->id }}" > {{ $value->name }} </option>
                                                                    
                                                                    @endforeach
                                                                    
                                                                </select>                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group">
                                                            <label class="control-label">limit</label>
                                                            <input name="limit" id="limit" class="form-control" type="number" min="0" value="{{$LcAndLg->limit}}">
                                                        </div>
                                                    </div>
                                                   
                                                    <div class="col-md-3" style="margin-top: 30px">
                                                        <div class="col-md-12 padtb text-right"> 
                                                            <div class="col-md-12 my-lab">
                                                                <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                                <a href="{{ route('LcAndLg.cancel') }}" class="btnn btn-secondary">Cancel</a>
                                                            </div>    
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
