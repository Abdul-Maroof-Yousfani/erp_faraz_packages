@extends('layouts.default')

@section('content')

<div class="row well_N align-items-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Zahabiya Chemicals INDUSTRIES PVT LTD</h1>
                </li>
            </ul>
        </div>
        
    </div>
    <div class="row well_N align-items-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h3>Raw Material Duty Forecsat Sheet</h3>
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
                    
                                    <div class="col-md-4 hide">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">From date</label>
                                            <div class="col-sm-8">
                                                <input name="rate_date" id="rate_date" value="" class="form-control" type="date">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 hide">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">To date</label>
                                            <div class="col-sm-8">
                                                <input name="to_date" id="to_date" value="" class="form-control" type="date">
                                            </div>
                                        </div>
                                    </div>
                                    {{-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Clearing Agent</label>
                                            <div class="col-sm-8">
                                                <select class="form-control" name="agent_id" id="agent_id">
                                                    <option value=""> All</option>
                                                    @foreach ($clearing_agent as $value)
                                                        <option value="{{$value->id}}">{{$value->agent_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">

                                        <button type="button" class="btn btn-sm btn-primary" style="margin: 5px 0px 0px px;" onclick="viewExchangeRate();">Submit</button>
                                    </div> --}}
                                </div>
                            </div>
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid">
                           <!-- <h2 class="subHeadingLabelClass">View Exchange Rate List </h2> -->
                        </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive" style="overflow: auto">
                                        <table class="userlittab table table-bordered sf-table-list m-font">
                                                <thead>
                                                <tr>
                                                    <th class="">S no</th>
                                                    <th class="max-ww1">Supplier</th>
                                                    <th class="max-ww1">PO #</th>
                                                    <th class="max-ww2">LOT #</th>
                                                    <th class="max-ww2">LC Detail</th>
                                                    <th class="max-ww2">Product Name</th>
                                                    <th class="">Product Text</th>
                                                    <th class="">ETA Karachi</th>
                                                    <th class="">No of Container</th>
                                                    <th class="">Currency</th>
                                                    <th class="">Invoice Value</th>
                                                    <th class="max-ww1">Total Duties (PKR)</th>
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

    <script>
    $(document).ready(function(){
        viewExchangeRate();
        });
         function viewExchangeRate()
        {

            let rate_date = $('#rate_date').val();
            let to_date = $('#to_date').val();
            let agent_id = $('#agent_id').val();
             $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/import/Report/rawMaterialDutySheet',
                type: 'Get',
                data: {
                        rate_date:rate_date,
                        to_date:to_date,
                        agent_id:agent_id,
                    },
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }

        
    </script>


@endsection