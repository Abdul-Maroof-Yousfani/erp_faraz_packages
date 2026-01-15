@extends('layouts.default')

@section('content')

<div class="row well_N align-items-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>LC - LG Bank Limit</h1>
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
                    
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">From date</label>
                                            <div class="col-sm-8">
                                                <input name="rate_date" id="rate_date" value="" class="form-control" type="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">To date</label>
                                            <div class="col-sm-8">
                                                <input name="to_date" id="to_date" value="" class="form-control" type="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <button type="button" class="btn btn-sm btn-primary" style="margin: 5px 0px 0px px;" onclick="viewExchangeRate();">Submit</button>
                                    </div>
                                </div>
                            </div>
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid">
                           <!-- <h2 class="subHeadingLabelClass">View Exchange Rate List </h2> -->
                        </div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                        <table class="userlittab table table-bordered sf-table-list m-font mm-tabb">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" rowspan="2">Zahabiya Chemicals</th>
                                                    <th class="text-center max-ww1">Sanctioned Limit</th>
                                                    <th class="text-center">Limit Utilized</th>
                                                    <th class="text-center">Un Utilized</th>
                                                    <th class="text-center">Remaining % age</th>
                                                </tr>
                                                <tr>
                                                    <th class="text-centers" colspan="4">Rs in Millions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="background-color: #e5e5e5;">LC</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                @php
                                                    $lc_data = DB::connection('mysql2')->table('lc_and_lg as l')
                                                    ->join('accounts as a', 'a.id', '=', 'l.acc_id')
                                                    ->where('a.status', 1)
                                                    ->where('l.status', 1)
                                                    ->where('l.type', 'lc')
                                                    ->select(
                                                        'l.id',
                                                        'l.type',
                                                        'a.name',
                                                        'l.limit',
                                                        DB::raw('l.limit_utilize as limit_utilized'),
                                                        DB::raw('l.limit - l.limit_utilize as un_utilized'),
                                                        DB::raw('ROUND(((l.limit - l.limit_utilize) / l.limit ) * 100, 2) as remaining_percentage')
                                                        // DB::raw('CONCAT(FORMAT(((l.limit - l.limit_utilize) / l.limit ) * 100, 2), " %") as remaining_percentage')
                                                    )->orderBy('l.id', 'desc')->get();
                                                @endphp
                                                @foreach ($lc_data as $row)
                                                    <tr>
                                                        <td>{{$row->name}}</td>
                                                        <td>{{number_format($row->limit , 2)}}</td>
                                                        <td>{{number_format($row->limit_utilized , 2)}}</td>
                                                        <td>{{number_format($row->un_utilized , 2)}}</td>
                                                        <td style="background-color: #e5e5e5;">{{$row->remaining_percentage}} %</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <table class="userlittab table table-bordered sf-table-list m-font mm-tabb">
                                            <tbody>
                                                <tr>
                                                    <td style="background-color: #e5e5e5;">Bank Gurantee</td>
                                                    <td></td>
                                                    <td class="max-ww1"></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                @php
                                                    $bg_data = DB::connection('mysql2')->table('lc_and_lg as l')
                                                    ->join('accounts as a', 'a.id', '=', 'l.acc_id')
                                                    ->where('a.status', 1)
                                                    ->where('l.status', 1)
                                                    ->where('l.type', 'bank_gurantee')
                                                    ->select(
                                                        'l.id',
                                                        'l.type',
                                                        'a.name',
                                                        'l.limit',
                                                        DB::raw('l.limit_utilize as limit_utilized'),
                                                        DB::raw('l.limit - l.limit_utilize as un_utilized'),
                                                        DB::raw('ROUND(((l.limit - l.limit_utilize) / l.limit ) * 100, 2) as remaining_percentage')
                                                        // DB::raw('CONCAT(ROUND((l.limit / (l.limit - 0)) * 100, 2), " %") as remaining_percentage')
                                                    )->orderBy('l.id', 'desc')->get();
                                                @endphp
                                                @foreach ($bg_data as $row)
                                                    <tr>
                                                        <td >{{$row->name}}</td>
                                                        <td>{{$row->limit}}</td>
                                                        <td>{{$row->limit_utilized}}</td>
                                                        <td>{{$row->un_utilized}}</td>
                                                        <td style="background-color: #e5e5e5;">{{$row->remaining_percentage}} %</td>
                                                    </tr>
                                                @endforeach
                                                
                                            </tbody>
                                        </table>
                                        {{-- <table class="userlittab table table-bordered sf-table-list m-font mm-tabb">
                                            <thead>
                                                <tr>
                                                    <th class="text-center" rowspan="2">Zahabiya Chemicals</th>
                                                    <th class="text-center max-ww1">Bank Al Habib</th>
                                                    <th class="text-center">Habib Metro</th>
                                                    <th class="text-center"></th>
                                                </tr>
                                                <tr>
                                                    <th class="text-centers" colspan="3">Rs in Millions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td style="background-color: #e5e5e5;">LC</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Sanctioned Limit</td>
                                                    <td>200</td>
                                                    <td>250</td>
                                                    <td>250</td>
                                                </tr>
                                                <tr>
                                                    <td>Limit Utilized</td>
                                                    <td>50.00</td>
                                                    <td>100.00</td>
                                                    <td>100.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Un Utilized</td>
                                                    <td>150.00</td>
                                                    <td>150.00</td>
                                                    <td>150.00</td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #e5e5e5;">Remaining % age</td>
                                                    <td style="background-color: #e5e5e5;">75.00%</td>
                                                    <td style="background-color: #e5e5e5;">60.00%</td>
                                                    <td style="background-color: #e5e5e5;">60.00%</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <table class="userlittab table table-bordered sf-table-list m-font mm-tabb">
                                            <tbody>
                                                <tr>
                                                    <td style="background-color: #e5e5e5;">Bank Gurantee</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td>Sanctioned Limit</td>
                                                    <td>150</td>
                                                    <td>50</td>
                                                    <td>50</td>
                                                </tr>
                                                <tr>
                                                    <td>Limit Utilized</td>
                                                    <td>25.00</td>
                                                    <td>15.00</td>
                                                    <td>15.00</td>
                                                </tr>
                                                <tr>
                                                    <td>Un Utilized</td>
                                                    <td>125.00</td>
                                                    <td>35.00</td>
                                                    <td>35.00</td>
                                                </tr>
                                                <tr>
                                                    <td style="background-color: #e5e5e5;">Remaining % age</td>
                                                    <td style="background-color: #e5e5e5;">83.33%</td>
                                                    <td style="background-color: #e5e5e5;">70.00%</td>
                                                    <td style="background-color: #e5e5e5;">70.00%</td>
                                                </tr>
                                            </tbody>
                                        </table> --}}
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
    $(document).ready(function(){
        viewExchangeRate();
        });
         function viewExchangeRate()
        {

            let rate_date = $('#rate_date').val();
            let to_date = $('#to_date').val();
             $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/import/Report/lcandLgBankLimit',
                type: 'Get',
                data: {
                        rate_date:rate_date,
                        to_date:to_date
                    },
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }

        
    </script>


@endsection