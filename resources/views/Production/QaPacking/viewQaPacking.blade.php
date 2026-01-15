@extends('layouts.default')
@section('content')
    <?php
    use App\Helpers\CommonHelper;
    use App\Helpers\SalesHelper;
    $count = 1;
    $total_lenght = 0;
    ?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Production</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; View Packing</h3>
                </li>
            </ul>
        </div>
    </div>
    <div class="row" >
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="well_N">
                <div class="dp_sdw" id="printReport">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 "></div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-right hidden-print">
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-6 ">
                                            <div class="premier_igm2">
                                                <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?> 
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                            <div class="premier_igmcol">
                                                 <div class="row">
                                                    <ul class="m-li">
                                                        <li class="firs"><h2 class="adas">Address:</h2></li>
                                                        <li class="las"><p>43-E, Block-6, P.E.C.H.S Behind FedEx, Off.
                                                            Razi Road, Sharah-e-Faisal
                                                            Karachi, 75400
                                                            PAK(Pakistan)</p>
                                                        </li>
                                                    </ul>
                                                    <ul class="m-li">
                                                        <li class="firs"><h2>Contact #:</h2></li>
                                                        <li class="las"><p>+92-21-34397771-75</p></li>
                                                    </ul>
                                                    <ul class="m-li">
                                                        <li class="firs"><h2>Fax:</h2></li>
                                                        <li class="las"><p>+92-21-34397779</p></li>
                                                    </ul>
                                                    <ul class="m-li">
                                                        <li class="firs"><h2>Email:</h2></li>
                                                        <li class="las"><p>sales@premiercables.net</p></li>
                                                    </ul>
                                                    <ul class="m-li">
                                                        <li class="firs"><h2>Website:</h2></li>
                                                        <li class="las"><p>www.premiercables.net</p></li>
                                                    </ul>
                                                    <!-- <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5">
                                                        <div class="hgeaads">
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-7">
                                                        <div class="ptaad">
                                                        </div>
                                                    </div> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="slist_te">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="headquid4">
                                                    <h2 class="subHeadingLabelClass"><u>PACKING LIST</u></h2>
                                                    <br>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>

                                    <div class="slist_te">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                                <div class="premier_igmcol">
                                                    <div class="row">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                            <h3>Product Details</h3>    
                                                        </div>   
                                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 "> 
                                                            <ul class="m-li">
                                                                <li class="firs"><h2 class="adas">Category</h2></li>
                                                                <li class="las"><p>{{ $packing->sub_category_name }}</p>
                                                                </li>
                                                            </ul>
                                                            <ul class="m-li">
                                                                <li class="firs"><h2 class="adas">Product</h2></li>
                                                                <li class="las"><p>{{ $packing->sub_ic }}</p>
                                                                </li>
                                                            </ul>
                                                            <ul class="m-li">
                                                                <li class="firs"><h2 class="adas">Details:</h2></li>
                                                                <li class="las"><p>{{ $packing->description }}</p>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">

                                                <div class="premier_igmcol">
                                                    <div class="row">
                                                        <ul class="m-li">
                                                            <li class="firs"><h2 class="adas">SO No.</h2></li>
                                                            <li class="las"><p>{{ $packing->so_no }}</p>
                                                            </li>
                                                        </ul>
                                                        <ul class="m-li">
                                                            <li class="firs"><h2 class="adas">PO No.</h2></li>
                                                            <li class="las"><p>{{ $packing->purchase_order_no }}</p>
                                                            </li>
                                                        </ul>
                                                        <ul class="m-li">
                                                            <li class="firs"><h2 class="adas">Packing List No.</h2></li>
                                                            <li class="las"><p>{{ $packing->packing_list_no }}</p>
                                                            </li>
                                                        </ul>
                                                        <ul class="m-li">
                                                            <li class="firs"><h2 class="adas">Customer Name:</h2></li>
                                                            <li class="las"><p>{{ $packing->customer_name }}</p>
                                                            </li>
                                                        </ul>
                                                        <ul class="m-li">
                                                            <li class="firs"><h2 class="adas">Delivery To:</h2></li>
                                                            <li class="las"><p>{{ $packing->deliver_to }}</p>
                                                            </li>
                                                        </ul>
                                                        <ul class="m-li hide">
                                                            <li class="firs"><h2 class="adas">Printing Date:</h2></li>
                                                            <li class="las"><p>{{ $packing->packing_date }}</p>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <table class="userlittab2 table table-bordered sf-table-list2">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">S.No. </th>
                                                    <th class="text-center">Bundle No.</th>
                                                    <th class="text-center">Length</th>
                                                </tr>
                                                </thead>
                                                <tbody id="data">
                                                  @foreach($packing_data as $key => $value)  
                                                    <tr>
                                                        <td class="text-center">{{ $count++ }}</td>
                                                        <td class="text-center">{{ $value->bundle_no }}</td>
                                                        <td class="text-center">{{ $value->qty }}</td>

                                                        <?php $total_lenght += $value->qty?>
                                                    </tr>
                                                  @endforeach  
                                                </tbody>
                                                <thead>
                                                <tr>
                                                    <th class="text-center" colspan="2">TOTAL</th>
                                                    <th class="text-center">{{ $total_lenght }} Mtrs</th>
                                                </tr>
                                                </thead>
                                            </table>
                                            <div class="tabhead">
                                                <h2><span>Total Length In Words:</span> *** Five hundred meters</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprks tecs">
                                    <h2 class="subHeadingLabelClass">TERMS & CONDITIONS</h2>
                                </div>
                                <div class="headquid_apr2">
                                    <h2 class="subHeadingLabelClass">1.	Payment: Within Thirty (30) days from the date of delivery.</h2>
                                    <h2 class="subHeadingLabelClass">2.	DELIVERY DESTINATIONS: Hyderabad to Sukkur.</h2>
                                    <h2 class="subHeadingLabelClass">3.	DELIVERY LENGTH: 100 Meters.</h2>
                                    <h2 class="subHeadingLabelClass">4.	O CLOSURE TIMELINE: The Quantities mutually agreed to close the said project by or before 02 months. Prolonged remaining quantities may be subject to revision of price and delivery terms.</h2>
                                    <h2 class="subHeadingLabelClass">5.	Delivery/Unloading: Delivery at desired location will be manufacturer’s responsibility. However, unloading will be of customer’s responsibility, customer have to make arrangements at their own.</h2>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <!-- <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprks">
                                    <h2 class="subHeadingLabelClass">Kindly issue PI so that LC to be established.</h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="slist_te">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="headquid_aprk3">
                                    <h2 class="subHeadingLabelClass">Best Regards,</h2>
                                    <h2 class="subHeadingLabelClass">for Zahabiya Chemicalss (PVT) Limited</h2>
                                    <div class="akk">
                                        <h2 class="subHeadingLabelClass">FUZAIL AHMED</h2>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </div>
@endsection