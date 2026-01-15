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
                    <h3>Exchange Gain or Loss Report</h3>
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
                                                    <input name="rate_date" id="rate_date" value=""
                                                        class="form-control" type="date">
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
                                                style="margin: 5px 0px 0px px;"
                                                onclick="viewExchangeRate();">Submit</button>
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
                                                <div class="table-responsive" style="overflow: auto;">
                                                    <table class="userlittab table table-bordered sf-table-list m-font">
                                                        <thead>
                                                            
                                                            <tr>
                                                                <th class="">S.No</th>
                                                                <th class="exc-maxw">SUPPLIER NAME</th>
                                                                <th class="exc-maxw">Description of Goods</th>
                                                                <th class="exc-maxw">SUB Description</th>
                                                                <th class="exc-maxw2">LC Number</th>
                                                                <th class="exc-maxw2">LC Date</th>
                                                                <th class="exc-maxw2">Currency</th>
                                                                <th class="exc-maxw">Foreign Currency (as per CI)</th>
                                                                <th class="exc-maxw">Foreign Currency (as per GD)</th>
                                                                <th class="exc-maxw2">BL/AWB DATE</th>
                                                                <th class="exc-maxw2">BL - NBP Rate</th>
                                                                <th class="exc-maxw2">Maturity Rate</th>
                                                                <th class="exc-maxw2">Maturity date</th>
                                                                <th class="exc-maxw3">Amount AS per BL - NBP Rate</th>
                                                                <th class="exc-maxw3">Amount As per Matrurity Rate</th>
                                                                <th class="exc-maxw3">Gain / Loss Amount</th>
                                                                <th class="exc-maxw3">Gain / Loss Remarks</th>
                                                                <th class="exc-maxw2">GD No</th>
                                                                <th class="exc-maxw2">GD Date</th>
                                                                <th class="exc-maxw">GD EXCHANGE RATE</th>
                                                                <th class="exc-maxw3">Total Net Weight Kgs</th>
                                                                <th class="exc-maxw2">IOCO Qty Kgs</th>
                                                                <th class="exc-maxw2">DTRE QTY Kgs</th>
                                                                <th class="exc-maxw2">Assessed Value</th>
                                                                <th class="exc-maxw2">CD %</th>
                                                                <th class="exc-maxw2">CD Amount</th>
                                                                <th class="exc-maxw2">RD %</th>
                                                                <th class="exc-maxw2">RD Amount</th>
                                                                <th class="exc-maxw">Total of CD + Assessed Value</th>
                                                                <th class="exc-maxw2">ST%</th>
                                                                <th class="exc-maxw2">ST Amount</th>
                                                                <th class="exc-maxw2">ACD %</th>
                                                                <th class="exc-maxw2">ACD Amountt</th>
                                                                <th class="exc-maxw2">IT% </th>
                                                                <th class="exc-maxw2">IT Amount </th>
                                                                <th class="exc-maxw2">FED% </th>
                                                                <th class="exc-maxw2">FED Amount </th>
                                                                <th class="exc-maxw2">AST% </th>
                                                                <th class="exc-maxw2">AST Amount </th>
                                                                <th class="exc-maxw2">CD % IN BOND </th>
                                                                <th class="exc-maxw2">CD Amount </th>
                                                                <th class="exc-maxw2">ETO </th>
                                                                <th class="exc-maxw2">DO + CSC Charges </th>
                                                                <th class="exc-maxw2">LOLO Charges </th>
                                                                <th class="exc-maxw3">Yard / Port Charges </th>
                                                                <th class="exc-maxw3">Aviation / Wharfage Chargest </th>
                                                                <th class="exc-maxw2">Clearing Agent Bill </th>
                                                                <th class="exc-maxw2">Stamping charges </th>
                                                                <th class="exc-maxw2">Total </th>
                                                                <th class="exc-maxw2">Expense % </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data">
                                                            
                                                            {{-- <tr>
                                                                <td>1</td>
                                                                <td>JIANGSU NANFENG-CHINA</td>
                                                                <td>OPTICAL FIBER G.652D (COLORED)</td>
                                                                <td>SINGLE/MULTI MODE OFC G652D (COLORD)</td>
                                                                <td>LCU/99/064/10363</td>
                                                                <td>19/Jun/23</td>
                                                                <td>US$.</td>
                                                                <td>62,818.56</td>
                                                                <td>62,818.50</td>
                                                                <td>28/Jun/23</td>
                                                                <td>286.40 </td>
                                                                <td>303.50</td>
                                                                <td>29/Aug/23</td>
                                                                <td>17,991,235.58</td>
                                                                <td>19,065,432.96</td>
                                                                <td>(1,074,197.38)</td>
                                                                <td>LOSS</td>
                                                                <td>KPAF-HC-1111</td>
                                                                <td>7/7/2023</td>
                                                                <td>-</td>
                                                                <td>1,059.00</td>
                                                                <td>1,059.00</td>
                                                                <td>-</td>
                                                                <td>17,641,571</td>
                                                                <td>0%</td>
                                                                <td>0</td>
                                                                <td>0%</td>
                                                                <td>0</td>
                                                                <td>17,641,571</td>
                                                                <td>18%</td>
                                                                <td>3,175,483</td>
                                                                <td>0%</td>
                                                                <td>0</td>
                                                                <td>5.5%</td>
                                                                <td>1,144,938</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>
                                                                <td>-</td>

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
            $(document).ready(function() {
                viewExchangeRate();
            });

            function viewExchangeRate() {

                let rate_date = $('#rate_date').val();
                let to_date = $('#to_date').val();
                $('#data').html(
                    '<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>'
                    );

                $.ajax({
                    url: '<?php echo url('/'); ?>/import/Report/exchangeAndGainLossReport',
                    type: 'Get',
                    data: {
                        rate_date: rate_date,
                        to_date: to_date
                    },
                    success: function(response) {

                        $('#data').html(response);


                    }
                });


            }
        </script>
    @endsection
