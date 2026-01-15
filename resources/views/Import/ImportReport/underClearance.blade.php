
@extends('layouts.default')

@section('content')
    <style>
        th{
            text-align: center !important;
            /* min-width: fit-content; */
            min-width: 150px;
            max-width: 350px;
            width: auto;
        }
        td{
            text-align: center !important;
            /* min-width: fit-content;
            width: auto; */
        }
        th , td {
            text-transform: uppercase;
        }
    </style>

    <div class="row well_N align-items-center">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Zahabiya Chemicals INDUSTRIES (PVT) LIMITED</h1>
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
                                <div class="panel">
                                    <div class="panel-body">
                             
                                        <div class="row" style="margin-top:20px;">
                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">From date</label>
                                                        <div class="col-sm-8">
                                                            <input name="from_date" id="from_date" value="" class="form-control" type="date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">To date</label>
                                                        <div class="col-sm-8">
                                                            <input name="to_date" id="to_date" value="" class="form-control" type="date">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Status</label>
                                                        <div class="col-sm-8">
                                                            <select name="shipment_status"  class="form-control"  id="shipment_status">
                                                                <option value="Pending">Pending</option>
                                                                <option value="Recieved">Recieved</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
            
                                                    <button type="button" class="btn btn-sm btn-primary" style="margin: 5px 0px 0px px;" onclick="viewExchangeRate();">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    <div class="panel-body">
                                    <div class="headquid">
                                        <h2 class="subHeadingLabelClass">Import Under Clearance and In Transit Consignment Report </h2>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="data">
                                            {{-- <div class="table-responsive" style="overflow: auto;">
                                            <table class="userlittab table table-bordered sf-table-list tab-cen">
                                                <thead>
                                                    <tr>
                                                        <th class="text-center">Supplier</th>
                                                        <th class="text-center" style="width: 300px !important;">File # - PO # Lc Number  - PI Number - Date</th>
                                                        <th class="text-center">Description Lot #</th>
                                                        <th class="text-center">Origin</th>
                                                        <th class="text-center" style="width: 200px !important;">BL number / AWB Number - Line - Forwarder - Clearing Agent</th>
                                                        <th class="text-center" style="width: 200px !important;">As per Lc</th>
                                                        <th class="text-center">BL / AWB ETD  Date</th>
                                                        <th class="text-center">ETA Karachi</th>
                                                        <th class="text-center">No Of Container</th>
                                                        <th class="text-center">Packages</th>
                                                        <th class="text-center">Gross and Net Weight</th>
                                                        <th class="text-center">Shipment status / Remarks</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="data"> --}}
                                                    
                                                    


                                                {{-- </tbody>
                                                    
                                                </table>
                                            </div> --}}
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

            let from_date = $('#from_date').val();
            let to_date = $('#to_date').val();
            let shipment_status = $('#shipment_status').val();
            
             $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/import/Report/underClearance',
                type: 'Get',
                data: {
                    from_date:from_date,
                        to_date:to_date,
                        shipment_status:shipment_status,
                    },
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }

        
    </script>
    

@endsection