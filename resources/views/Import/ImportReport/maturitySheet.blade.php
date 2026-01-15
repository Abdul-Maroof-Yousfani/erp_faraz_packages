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
                    <h3>Maturity Sheet</h3>
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
                                        <div class="table-responsive" style="overflow: auto">
                                        <table class="userlittab table table-bordered sf-table-list m-font">
                                                <thead>
                                                <tr>
                                                    <th class="">S.No</th>
                                                    <th class="max-ww1">PO#</th>
                                                    <th class="">LC#</th>
                                                    <th class="">LC Date</th>
                                                    <th class="">Lot#</th>
                                                    <th class="max-ww">Supplier</th>
                                                    <th class="max-ww3">Description of Goods</th>
                                                    <th class=" max-ww1">Qty (N.KG)</th>
                                                    <th class="">Cur.</th>
                                                    <th class="max-ww2">Amount In FC</th>
                                                    <th class="max-ww1">Exc Rate</th>
                                                    <th class="max-ww2">Amount In Pkr</th>
                                                    <th class="max-ww2">Bank</th>
                                                    <th class="max-ww2">BL Date</th>
                                                    <th class="max-ww2">Payment Terms</th>
                                                    <th class="max-ww">Actually Maturity Date</th>
                                                    <th class="max-ww">Raw Material Reached At Factory</th>
                                                    <th class="max-ww2">Paid / Unpaid</th>
                                                    <th class="max-ww2">Pool</th>
                                                    <th class="max-ww">Shipment Status Name</th>
                                                </tr>
                                            </thead>
                                            <tbody id="data">
                                                
                                                {{-- <tr>
                                                    <td>1</td>
                                                    <td>PO-003294</td>
                                                    <td>LCU/99/064/9391</td>
                                                    <td>11/4/2022</td>
                                                    <td>1</td>
                                                    <td>Bluestar Chengdu New Material CO.,Ltd</td>
                                                    <td>PARA-ARAMID FILAMENT S481-1500 (1670dtex/1500D)</td>
                                                    <td>2160</td>
                                                    <td>USD</td>
                                                    <td>48600</td>
                                                    <td>268.00</td>
                                                    <td>13,024,800.00</td>
                                                    <td>HMBL-53046</td>
                                                    <td>2 Dec 2022	</td>
                                                    <td>60</td>
                                                    <td>1 Feb 2023</td>
                                                    <td>29 Dec 2022</td>
                                                    <td>Paid</td>
                                                    <td>IMPORT</td>
                                                    <td>Received at Factory</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>PO-003294</td>
                                                    <td>LCU/99/064/9391</td>
                                                    <td>11/4/2022</td>
                                                    <td>1</td>
                                                    <td>Bluestar Chengdu New Material CO.,Ltd</td>
                                                    <td>PARA-ARAMID FILAMENT S481-1500 (1670dtex/1500D)</td>
                                                    <td>2160</td>
                                                    <td>USD</td>
                                                    <td>48600</td>
                                                    <td>268.00</td>
                                                    <td>13,024,800.00</td>
                                                    <td>HMBL-53046</td>
                                                    <td>2 Dec 2022	</td>
                                                    <td>60</td>
                                                    <td>1 Feb 2023</td>
                                                    <td>29 Dec 2022</td>
                                                    <td>Paid</td>
                                                    <td>IMPORT</td>
                                                    <td>Received at Factory</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>PO-003294</td>
                                                    <td>LCU/99/064/9391</td>
                                                    <td>11/4/2022</td>
                                                    <td>1</td>
                                                    <td>Bluestar Chengdu New Material CO.,Ltd</td>
                                                    <td>PARA-ARAMID FILAMENT S481-1500 (1670dtex/1500D)</td>
                                                    <td>2160</td>
                                                    <td>USD</td>
                                                    <td>48600</td>
                                                    <td>268.00</td>
                                                    <td>13,024,800.00</td>
                                                    <td>HMBL-53046</td>
                                                    <td>2 Dec 2022	</td>
                                                    <td>60</td>
                                                    <td>1 Feb 2023</td>
                                                    <td>29 Dec 2022</td>
                                                    <td>Paid</td>
                                                    <td>IMPORT</td>
                                                    <td>Received at Factory</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>PO-003294</td>
                                                    <td>LCU/99/064/9391</td>
                                                    <td>11/4/2022</td>
                                                    <td>1</td>
                                                    <td>Bluestar Chengdu New Material CO.,Ltd</td>
                                                    <td>PARA-ARAMID FILAMENT S481-1500 (1670dtex/1500D)</td>
                                                    <td>2160</td>
                                                    <td>USD</td>
                                                    <td>48600</td>
                                                    <td>268.00</td>
                                                    <td>13,024,800.00</td>
                                                    <td>HMBL-53046</td>
                                                    <td>2 Dec 2022	</td>
                                                    <td>60</td>
                                                    <td>1 Feb 2023</td>
                                                    <td>29 Dec 2022</td>
                                                    <td>Paid</td>
                                                    <td>IMPORT</td>
                                                    <td>Received at Factory</td>
                                                </tr>
                                                <tr>
                                                    <td>1</td>
                                                    <td>PO-003294</td>
                                                    <td>LCU/99/064/9391</td>
                                                    <td>11/4/2022</td>
                                                    <td>1</td>
                                                    <td>Bluestar Chengdu New Material CO.,Ltd</td>
                                                    <td>PARA-ARAMID FILAMENT S481-1500 (1670dtex/1500D)</td>
                                                    <td>2160</td>
                                                    <td>USD</td>
                                                    <td>48600</td>
                                                    <td>268.00</td>
                                                    <td>13,024,800.00</td>
                                                    <td>HMBL-53046</td>
                                                    <td>2 Dec 2022	</td>
                                                    <td>60</td>
                                                    <td>1 Feb 2023</td>
                                                    <td>29 Dec 2022</td>
                                                    <td>Paid</td>
                                                    <td>IMPORT</td>
                                                    <td>Received at Factory</td>
                                                </tr> --}}
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
                url: '<?php echo url('/')?>/import/Report/maturitySheet',
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