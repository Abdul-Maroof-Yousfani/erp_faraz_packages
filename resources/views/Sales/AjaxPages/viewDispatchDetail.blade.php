<?php
use App\Helpers\CommonHelper;
use App\Helpers\StoreHelper;
use App\Helpers\FinanceHelper;

$customer = CommonHelper::byers_name($dispatch->customer_id);

$sale_order = db::connection('mysql2')
    ->table('sales_order')
    ->where('id', $dispatch->so_id)
    ->first();
$packing = db::connection('mysql2')
    ->table('packings')
    ->where('id', $dispatch->packing_list_id)
    ->first();

    $delivery_challan = db::connection('mysql2')
    ->table('delivery_note')
    ->where('id', $dispatch->dc_id)
    ->first();
    $production_plan = db::connection('mysql2')
    ->table('production_plane')
    ->where('id', $dispatch->production_plan_id)
    ->first();

$customerName = $customer ? $customer->name : '';
$customerAddress = $customer ? $customer->address : '';
//$m = $_GET['m'];
$currentDate = date('Y-m-d');

?>


<style>
    textarea {
        border-style: none;
        border-color: Transparent;

    }

    @media print {
        .printHide {
            display: none !important;
        }

        .fa {
            font-size: small;
            !important;
        }

        .table-bordered {
            border: 1px solid black;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid black !important;
        }
    }

    table {
        border: solid 1px black;
    }

    tr {
        border: solid 1px black;
    }

    td {
        border: solid 1px black;
    }

    th {
        border: solid 1px black;
    }
</style>
<?php

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
      @if ($dispatch->dispatch_status == 1)
         <button class="btn btn-primary prinn"  onclick="approve_dispatch('{{$dispatch->id}}')" style="">
               <span class="glyphicon glyphicon-print"></span> Approve
         </button>
      @endif
        <?php CommonHelper::displayPrintButtonInView('printPurchaseRequestVoucherDetail', '', '1'); ?>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<div class="row" id="printPurchaseRequestVoucherDetail">
    <div class="">

        <div style="line-height:5px;">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <section class="mainBanner" style="background-image:url(assets/images/banner/bg1.png); ">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive ">
                                <table class="table table-bordered">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th colspan="6" class="text-center">Dispatch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3">COMPANY NAME: Zahabiya Chemicals INDUSTRIES (PRIVATE) LIMITED
                                            </th>
                                            <td rowspan="5" colspan="6">
                                                <img class="hide" src='url("public/premior_new_logo.png")'>
                                                <img src="{{ url('public/premior_new_logo.png') }}"
                                                    onerror="this.onerror=null;this.src='{{ asset('premior_new_logo.png') }}'" />

                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Address:</th>
                                            <td colspan="2">
                                                <div class="addr">
                                                    <p>43-E, Block-6, P.E.C.H.S., Behind FedEx, off. Razi
                                                        Road,Shahrah-e-Faisal, Karachi, Pakistan.</p>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Phone No.: </th>
                                            <td colspan="2">+92 21 34397771 ~ 75</td>
                                        </tr>
                                        <tr>
                                            <th>Email:</th>
                                            <td colspan="2">
                                                sales@premierpipeindustries.com
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>NTN No.:</th>
                                            <td colspan="2">9291035</td>
                                        </tr>
                                        <tr>
                                          <td colspan="6"></td>
                                      </tr>
                                        <tr>
                                          <th>So No:</th>
                                          <td>{{ $sale_order->so_no }}</td>
                                          <th>Dilvery Challan NO: </th>
                                          <td colspan="6">{{ $delivery_challan->dc_no }}</td>
                                       </tr>
                                       <tr>
                                          <th>Production Plan:</th>
                                          <td>{{ $production_plan->order_no }}</td>
                                       </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                        </tr>
                                        <tr>
                                            <th>Party Name & Address:</th>
                                            <td>{{ $customerName }} <br>{{ $customerAddress }}</td>
                                            <th>Address: </th>
                                            <td colspan="6">{{ $dispatch->dispatch_location }}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                        </tr>
                                        <tr>
                                            <th>Transporter Name:</th>
                                            <td>{{ $dispatch->transporter_name }}</td>
                                            <th>Phone No.: </th>
                                            <td colspan="6">{{ $delivery_note->phone_no??''}}</td>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                        </tr>
                                        <tr>
                                            <th>Vehicle Type.:</th>
                                            <td>{{ $dispatch->vehicle_type }}</td>
                                            <th>Date: </th>
                                            <td>
                                                @php
                                                    echo CommonHelper::new_date_formate($dispatch->dispatch_date);
                                                @endphp
                                            </td>
                                            <th>Vehicle No. </th>
                                            <td>{{ $dispatch->vehicle_no }}</td>
                                        </tr>
                                        
                                        {{-- <tr>
                              <th>Purchase Order No.:</th>
                              <td>{{$sale_order->purchase_order_no}}</td>
                              <th>Date: </th>
                              <td>
                                 @php 
                                    echo  CommonHelper::new_date_formate($sale_order->purchase_order_date);
                                 @endphp   
                              </td>
                              <td colspan="6"></td>
                           </tr> --}}

                                        <tr>
                                            <th class="text-center" style="background:#c5d9f0;">Category</th>
                                            <th class="text-center" style="background:#c5d9f0;">Description of Goods
                                            </th>
                                            <th class="text-center" style="background:#c5d9f0;">Item Name
                                            <th class="text-center" style="background:#c5d9f0;">UoM</th>
                                            <th class="text-center" style="background:#c5d9f0;">Quantity</th>
                                            <th colspan="6" class="text-center" style="background:#c5d9f0;">Packaging
                                            </th>
                                        </tr>
                                        <tr>
                                            <td colspan="6"></td>
                                        </tr>
                                        <?php
                                        $count = 1;
                                        $total_before_tax = 0;
                                        $total_tax = 0;
                                        $total_after_tax = 0;
                                        $bundle_length = CommonHelper::bundle_length_by_packing_id($dispatch->packing_id);
                                        ?>
                                        {{-- @foreach ($delivery_note_data as $row)
                                            @php
                                               $sub_category = CommonHelper::get_sub_category_by_item_id($row->item_id);
                                               $sub_category_name = $sub_category ? $sub_category->sub_category_name : '';
                                               
                                               $sale_order_data = db::connection('mysql2')
                                                   ->table('sales_order_data')
                                                   ->where('id', $row->so_data_id)
                                                   ->where('item_id', $row->item_id)
                                                   ->first();
                                            @endphp
                                            
                                            
                                            <tr>
                                                <td class="text-center" style="background:#c5d9f0;">
                                                    {{ $sub_category_name }}</td>
                                                <td class="text-center" style="background:#c5d9f0;">
                                                    {{ $sale_order_data->item_description ?? '' }}</td>
                                                <td class="text-center hide" style="background:#c5d9f0;">
                                                    {{ CommonHelper::get_item_name($row->item_id) }}</td>
                                                <td class="text-center" style="background:#c5d9f0;">
                                                    {{ CommonHelper::get_uom($row->item_id) }}</td>
                                                <td class="text-center" style="background:#c5d9f0;">{{ $row->qty }}
                                                </td>
                                                <td class="text-center" colspan="6" style="background:#c5d9f0;">
                                                    PKG-{{ number_format($row->qty, 0) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                            </tr>
                                        @endforeach --}}

                                        @foreach ($dispatch_data as $row)
                                            <?php
                                          //   dump($row , $dispatch);
                                            $sub_category = CommonHelper::get_sub_category_by_item_id($row->item_id);
                                            $sub_category_name = $sub_category ? $sub_category->sub_category_name : '';
                                            
                                            $sale_order_data = db::connection('mysql2')
                                                ->table('sales_order_data')
                                                ->where('master_id', $dispatch->id)
                                                ->where('item_id', $row->item_id)
                                                ->first();
                                            
                                            ?>
                                            <tr>
                                                <td class="text-center" style="background:#c5d9f0;">
                                                    {{ $sub_category_name }}</td>
                                                <td class="text-center" style="background:#c5d9f0;">
                                                    {{ $sale_order_data->item_description ?? '' }}</td>
                                                <td class="text-center" style="background:#c5d9f0;">
                                                    {{ CommonHelper::get_item_name($row->item_id) }}</td>
                                                <td class="text-center" style="background:#c5d9f0;">
                                                    {{ CommonHelper::get_uom($row->item_id) }}</td>
                                                <td class="text-center" style="background:#c5d9f0;">{{ $row->qty }}
                                                </td>
                                                <td class="text-center" colspan="6" style="background:#c5d9f0;">
                                                    Bundle-{{ number_format($bundle_length, 0) }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                            </tr>
                                        @endforeach


                                       
                                        <tr>
                                            <td colspan="6"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                
                                                <div class="recsivesname">
                                                    <p>Name: {{$dispatch->username}}</p>
                                                </div>
                                                <div class="recsivesname">
                                                    <p>Date: {{$dispatch->date}}</p>
                                                </div>
                                            </td>
                                            <td></td>
                                            {{-- <td colspan="4">
                                                <div class="stamp">
                                                    <ul>
                                                        <li>
                                                            <div class="prenme">
                                                                <p> For, Zahabiya Chemicals Industries<br> (Private) Limited
                                                                </p>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="autor">
                                                                <p>Authorized Signature</p>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td> --}}
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>
</div>

<script>
    function change()

    {


        if (!$('.showw').is(':visible')) {
            $(".showw").css("display", "block");

        } else {
            $(".showw").css("display", "none");

        }

    }

    function show_hide() {
        if ($('#formats').is(":checked")) {
            $("#actual").css("display", "none");
            $("#printable").css("display", "block");
        } else {
            $("#actual").css("display", "block");
            $("#printable").css("display", "none");
        }
    }

    function approve_dispatch(id)
    {

        $.ajax({
            url: '{{url('/sdc/approve_dispatch')}}',
            type: "GET",
            data: { id:id},
            success:function(data)
            {
                alert('Approved');
                $("#showDetailModelOneParamerter").modal("hide");
                $('.hide'+data).css('display','none');
            }
        });
    }

    function show_hide2() {
        if ($('#formats2').is(":checked")) {
            $(".ShowHideHtml").fadeOut("slow");
            $(".bundleHide").fadeOut("slow");

            //                $("#printable").css("display", "block");
        } else {
            $(".ShowHideHtml").fadeIn("slow");
            $(".bundleHide").fadeIn("slow");

            //                $("#printable").css("display", "none");
        }
    }


    function remove_bundle(id) {
        //Q$('#'+id).css('display','none');
    }

    function diss(id) {
        $('#' + id).remove();
    }

    function checkk() {

        if ($("#check").is(":checked")) {


            $('.tra').css('display', 'block');
        } else {
            $('.tra').css('display', 'none');
        }
    }
</script>
