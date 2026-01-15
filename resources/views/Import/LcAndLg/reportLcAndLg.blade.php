@extends('layouts.default')

@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Import</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; LC And LG Report List</h3>
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

                                    <div class="row" style="margin-top:10px ;display:none">

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">From date</label>
                                                <div class="col-sm-8">
                                                    <input name="rate_date" id="rate_date" value="" class="form-control"
                                                        type="date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="col-sm-4 control-label">To date</label>
                                                <div class="col-sm-8">
                                                    <input name="to_date" id="to_date" value="" class="form-control"
                                                        type="date">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">

                                            <button type="button" class="btn btn-sm btn-primary"
                                                style="margin: 5px 0px 0px px;" onclick="reportLcLg();">Submit</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="panel">
                                    <div class="panel-body">
                                        <div class="headquid">
                                            <h2 class="subHeadingLabelClass">View LC And LG Report List </h2>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="">
                                                    <table class="userlittab table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">Type</th>
                                                                <th class="text-center">Account</th>
                                                                <th class="text-center">Sanctioned Limit</th>
                                                                <th class="text-center">Limit Utilized</th>
                                                                <th class="text-center">Un Utilized</th>
                                                                <th class="text-center">Remaining %</th>
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
            reportLcLg();
        });
        function reportLcLg() {

            let rate_date = $('#rate_date').val();
            let to_date = $('#to_date').val();
            $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '{{ url('/import/LcAndLg/reportLcLg') }}',
                type: 'Get',
                data: {
                    rate_date: rate_date,
                    to_date: to_date
                },
                success: function (response) {
                    $('#data').html(response);
                }
            });
        }
    </script>


@endsection