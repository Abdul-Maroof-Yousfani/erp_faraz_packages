@extends('layouts.default')
@section('content')
    <?php
    use App\Helpers\CommonHelper;
    use App\Helpers\SalesHelper;
    $count = 1;
    $total_qty = 0;
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
    <div class="row">
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
                                            <h1><?php CommonHelper::displayPrintButtonInView('printReport', '', '1');?></h1>
                                        </div>
                                    </div>

                                    <div class="row" style="align-items: center;">
    
                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
                                            <div class="premier_igm2">
                                                <?php echo CommonHelper::get_company_logo(Session::get('run_company')); ?>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 text-center">
                                            <h3 class="subHeadingLabelClass">VIEW PACKING LIST</h3>
                                        </div>

                                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>

                                    </div>                             
                                    <div class="row">
                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-6">
                                            <!-- <div class="premier_igmcol">
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
                                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-5">
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
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                                        <div class="premier_igmcol">
                                            <div class="row">
                                               
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 ">
                                                    <ul class="m-li">
                                                        <li class="firs">
                                                            <h2 class="adas">Category</h2>
                                                        </li>
                                                        <li class="las">
                                                            <p>Finish Good</p>
                                                        </li>
                                                    </ul>
                                                    <ul class="m-li">
                                                        <li class="firs">
                                                            <h2 class="adas">Product</h2>
                                                        </li>
                                                        <li class="las">
                                                            <p>{{ ($packing->item_code ?? '') . ' -- ' . ($packing->sub_ic ?? '') . ($packing->color ? ' (' . $packing->color . ')' : '') }}
                                                            </p>
                                                        </li>
                                                    </ul>
                                                    <ul class="m-li">
                                                        <li class="firs">
                                                            <h2 class="adas">Details:</h2>
                                                        </li>
                                                        <li class="las">
                                                            <p>{{ $packing->description ?? '' }}</p>
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
                                                    <li class="firs">
                                                        <h2 class="adas">Packing List No.</h2>
                                                    </li>
                                                    <li class="las">
                                                        <p>{{ $packing->packing_list_no }}</p>
                                                    </li>
                                                </ul>
                                                <!-- <ul class="m-li">
                                                    <li class="firs">
                                                        <h2 class="adas">Customer Name:</h2>
                                                    </li>
                                                    <li class="las">
                                                        <p>{{ $packing->customer_name }}</p>
                                                    </li>
                                                </ul>
                                                <ul class="m-li">
                                                    <li class="firs">
                                                        <h2 class="adas">Delivery To:</h2>
                                                    </li>
                                                    <li class="las">
                                                        <p>{{ $packing->deliver_to }}</p>
                                                    </li>
                                                </ul> -->
                                                <ul class="m-li hide">
                                                    <li class="firs">
                                                        <h2 class="adas">Printing Date:</h2>
                                                    </li>
                                                    <li class="las">
                                                        <p>{{ $packing->packing_date }}</p>
                                                    </li>
                                                </ul>
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
                                                                <th class="text-center" style="width:6%">S.No.</th>
                                                                <th class="text-center" style="width:24%">Primary Packaging
                                                                </th>
                                                                <th class="text-center" style="width:14%">Number of
                                                                    Pails/Drums</th>
                                                                <th class="text-center" style="width:14%">Quantity</th>
                                                                <th class="text-center" style="width:24%">Secondary
                                                                    Packaging</th>
                                                                <th class="text-center" style="width:10%">Carton Qty</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="data">
                                                            @foreach($packing_data as $key => $value)
                                                                @php
                                                                    $primary = CommonHelper::get_item_by_id($value->primary_packing_item_id);
                                                                    $secondary = $value->secondary_packing_item_id ? CommonHelper::get_item_by_id($value->secondary_packing_item_id) : null;
                                                                    $total_qty += $value->qty;
                                                                @endphp
                                                                <tr>
                                                                    <td class="text-center">{{ $count++ }}</td>
                                                                    <td class="text-center">{{ $primary->item_code ?? '' }}
                                                                        {{ isset($primary->sub_ic) ? ' -- ' . $primary->sub_ic : '' }}
                                                                    </td>
                                                                    <td class="text-center">{{ $value->number_of_pails ?? 0 }}
                                                                    </td>
                                                                    <td class="text-center">{{ $value->qty }}</td>
                                                                    <td class="text-center">
                                                                        @if($secondary)
                                                                            {{ $secondary->item_code ?? '' }}
                                                                            {{ isset($secondary->sub_ic) ? ' -- ' . $secondary->sub_ic : '' }}
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-center">{{ $value->carton_count ?? 0 }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center" colspan="3">TOTAL</th>
                                                                <th class="text-center">{{ $total_qty }}</th>
                                                                <th class="text-center" colspan="2"></th>
                                                            </tr>
                                                        </thead>
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
@endsection