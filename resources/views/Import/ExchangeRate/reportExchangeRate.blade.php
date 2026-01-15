@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Import</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Exchange Rate List</h3>
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

                                    <div class="row" style="margin-top:10px">

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">Currencies</label>

                                                <select class="form-control" id="currency">
                                                    <option value="">Select</option>
                                                    @foreach($Currency as $value)
                                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">From date</label>

                                                <input name="rate_date" id="rate_date" value="" class="form-control"
                                                    type="date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="control-label">To date</label>

                                                <input name="to_date" id="to_date" value="" class="form-control"
                                                    type="date">
                                            </div>
                                        </div>
                                        <div class="col-md-3">

                                            <button type="button" class="btn btn-sm btn-primary"
                                                style="margin-top: 30px;"
                                                onclick="viewExchangeRate();">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="headquid">
                                            <h2 class="subHeadingLabelClass">View Exchange Rate List </h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="">
                                                    <table class="userlittab table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Currency</th>
                                                                <th class="text-center">Rate</th>
                                                                <th class="text-center">Date</th>
                                                                <!-- <th class="text-center">To Date</th> -->
                                                                {{-- <th class="text-center">To Date</th> --}}
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
    </div>

    <script>
        $(document).ready(function () {
            $('#currency').select2();
            viewExchangeRate();

        });
        function viewExchangeRate() {

            let rate_date = $('#rate_date').val();
            let to_date = $('#to_date').val();
            let currency = $('#currency').val();
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/import/ExchangeRate/report',
                type: 'Get',
                data: {
                    rate_date: rate_date,
                    to_date: to_date,
                    currency: currency
                },
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }


    </script>


@endsection