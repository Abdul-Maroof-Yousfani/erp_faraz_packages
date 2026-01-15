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
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp;Edit Exchange Rate</h3>
                </li>
            </ul>
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
                                    <form action="{{ route('ExchangeRate.update', $exchangeRate->id) }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                                <div class="qout-h">
                                                    <div class="col-md-12 bor-bo">
                                                        <h1>Edit Exchange Rate</h1>
                                                    </div>
                                                    <div class="col-md-12 padt pos-r">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Currencies</label>
                                                                    <select name="currency" class="form-control"
                                                                        id="currency">
                                                                        <option value="">Select</option>
                                                                        @foreach($Currency as $value)
                                                                            <option value="{{ $value->id }}"
                                                                                @if($value->id == $exchangeRate->currency)
                                                                                selected @endif>
                                                                                {{ $value->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Date*</label>
                                                                    <input name="rate_date" id="rate_date"
                                                                        value="{{ $exchangeRate->rate_date }}"
                                                                        class="form-control" type="date">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label class="control-label">Rate</label>
                                                                    <input name="rate" id="rate" step="any"
                                                                        value="{{ $exchangeRate->rate }}"
                                                                        class="form-control" type="number" min="0">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12 padtb text-right">
                                                        <div class="col-md-9"></div>
                                                        <div class="col-md-3 my-lab">
                                                            <button type="submit" class="btn btn-primary mr-1"
                                                                data-dismiss="modal">Save</button>
                                                            <a href="{{ route('ExchangeRate.cancel') }}"
                                                                class="btnn btn-secondary">Cancel</a>
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


    <script>
        $('#currency').select2()
    </script>
@endsection