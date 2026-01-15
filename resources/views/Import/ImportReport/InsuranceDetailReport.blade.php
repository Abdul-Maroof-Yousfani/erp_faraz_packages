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
                    <h3>Insuarace Detail</h3>
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
                                        <table class="userlittab table table-bordered sf-table-list m-font">
                                                <thead>
                                                <tr>
                                                    <th class="">S.No</th>
                                                    <th class="max-ww1">PO#</th>
                                                    <th class="max-ww2">Company</th>
                                                    <th class="max-ww2">Letter of Credit</th>
                                                    <th class="max-ww2">Reference No</th>
                                                    <th class="">Lot</th>
                                                    <th class="max-ww1">Covernote No</th>
                                                    <th class=" max-ww1">Covernote Date</th>
                                                    <th class="">Covernote Tolerance</th>
                                                    <th class="max-ww1">Policy No</th>
                                                    <th class="max-ww1">Policy Date</th>
                                                    <th class="max-ww1">Policy Amount</th>
                                                    <th class="max-ww2">Remarks</th>
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
             $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/import/Report/InsuranceDetail',
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