@extends('layouts.default')
@section('content')

<?php
use App\Helpers\CommonHelper;
$total_amount = 0;
?>

<div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Sale Module</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
            <ul class="cus-ul2">
                <li>
                    <a href="#" class="btn btn-primary"  >Create New Sale Module</a>
                </li>
                <li>
                    <input type="text" class="fomn1" onkeypress="viewRangeWiseDataFilter()" id="search" placeholder="Search Anything" >
                </li>
                {{-- <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
            </ul>
        </div>
    </div>
<div class="row">
<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"></div>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="well_N">
            <div class="dp_sdw2">
                <div class="row" id="printReport">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="panel">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right hidden-print">
                                        <h1><?php CommonHelper::displayPrintButtonInView('printReport','','1');?></h1>
                                    </div>
                                    <div class="modu">
                                        <div class="row">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="tasetb">
                                                    <table class="delivery_sale_modul table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr>
                                                                <th colspan="6" class="dc_sm">DELIVERY CHALLAN</th>
                                                            </tr>
                                                            <tr>
                                                                <th colspan="">COMPANY NAME: </th>
                                                                <th colspan="4"><div class="premier_head"> <h2>Zahabiya Chemicals INDUSTRIES (PRIVATE) LIMITED</h2></div></th>
                                                                <th rowspan="5"> <div class="div_img"><?php echo CommonHelper::get_company_logo(Session::get('run_company'));?> </div> </th>
                                                            </tr>
                                                            <tr>
                                                                <th>Address: </th>
                                                                <td colspan="6"><div class="add">43-E, Block-6, P.E.C.H.S., Behind FedEx, off. Razi Road, Shahrah-e-Faisal, Karachi, Pakistan</div></td>

                                                            </tr>
                                                            <tr>
                                                                <th>Phone No.:</th>
                                                                <td colspan="6">+92 21 34397771 ~ 75</td>
                                                            </tr>
                                                            <tr>
                                                                <th>Email:</th>
                                                                <td colspan="6">sales@premierpipeindustries.com / info@premierpipeindustries.com</td>

                                                            </tr>

                                                            <tr>
                                                                <th>NTN No.:</th>
                                                                <td colspan="6">9291035</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="dc_bor" colspan="6"></td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th  colspan="3" class="dc_bor">Delivery Challan For:</th>
                                                                <th  colspan="6" class="dc_bor ">Shipping To:</th>
                                                            </tr>
                                                            <tr>
                                                                <th class="dc_bor">Party Name & Address:<br><br><br><br><br></th>
                                                                <td colspan="2"  class="dc_bor">«Party_Name» «Party_Address»<br><br><br><br><br></td>
                                                                <th   class="dc_bor ">Address:<br><br><br><br><br></th>
                                                                <td colspan="3"class="dc_bor">«Shipping_Address»<br><br><br><br><br></td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="dc_bor" colspan="6"></td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th class="dc_bor ">Contact Person:</th>
                                                                <td   class="dc_bor">«Contact_Person»</td>
                                                                <th colspan="2" class="dc_bor ">Phone No.:</th>
                                                                <td  colspan=""  class="dc_bor">«Phone_No»</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="dc_bor" colspan="6"></td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th class="dc_bor ">Challan No.:</th>
                                                                <td  class="dc_bor centertd ">«Challan_No»</td>
                                                                <th class="dc_bor wthdat ">Date:</th>
                                                                <td   class=" centertd dc_bor ">«DC_Date»</td>
                                                                <th  colspan="1" class="dc_bor ">Lot No.</th>
                                                                <td colspan=""  class="dc_bor">«Lot_No»</td>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="dc_bor" colspan="6"></td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th class="dc_bor">Purchase Order No.:</th>
                                                                <td class="dc_bor centertd  ">«PO_No»</td>
                                                                <th   class="dc_bor ">Date:</th>
                                                                <td  class=" centertd  dc_bor">«DC_Date»</td>
                                                                <th colspan="3"  class="dc_bor "></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="dc_bor" colspan="6"></td>
                                                            </tr>
                                                        </tbody>
                                                        <thead>
                                                            <tr>
                                                                <th colspan="6" class="dc_sm"></th>
                                                            </tr>
                                                        </thead>
                                                        <thead>
                                                            <tr>
                                                                <th class="dc_th_blu">Category</th>
                                                                <th class="dc_th_blu">Description of Goods </th>
                                                                <th class="dc_th_blu">Quantity</th>
                                                                <th colspan="2" class="dc_th_blu">UoM</th>
                                                                <th class="dc_th_blu">Packaging</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td class="dc_bor2" colspan="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_th_blu2">«Category_1»</td>
                                                                <td class="dc_th_blu2">«Item_Desc_1»</td>
                                                                <td class="dc_th_blu2">«Qty_1»</td>
                                                                <td colspan="2" class="dc_th_blu2">«UoM_1»</td>
                                                                <td class="dc_th_blu2">«Pack_1» «Packaging_1»</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_bor2" colspan="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_th_blu2">«Category_2»</td>
                                                                <td class="dc_th_blu2">«Item_Desc_2»</td>
                                                                <td class="dc_th_blu2">«Qty_2»</td>
                                                                <td colspan="2" class="dc_th_blu2">«UoM_2»</td>
                                                                <td class="dc_th_blu2">«Pack_1» «Packaging_2»</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_bor2" colspan="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_th_blu2">«Category_3»</td>
                                                                <td class="dc_th_blu2">«Item_Desc_3»</td>
                                                                <td class="dc_th_blu2">«Qty_3»</td>
                                                                <td colspan="2" class="dc_th_blu2">«UoM_3»</td>
                                                                <td class="dc_th_blu2">«Pack_1» «Packaging_3»</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_bor2" colspan="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_th_blu2">«Category_4»</td>
                                                                <td class="dc_th_blu2">«Item_Desc_4»</td>
                                                                <td class="dc_th_blu2">«Qty_4»</td>
                                                                <td colspan="2" class="dc_th_blu2">«UoM_4»</td>
                                                                <td class="dc_th_blu2">«Pack_1» «Packaging_4»</td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_bor2" colspan="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_bor2" colspan="6">
                                                                    <div class="cont_warranty">
                                                                        <h2>Warranty:</h2>
                                                                        <p>We hereby certify that the goods supplied confirm to Specs No «Specs» laid down in the contract and that the goods in question have been tested and checked by us prior to
                                                                            packing and dispatch. In the event of the goods having been found to have manufacturing defects, or not conforming to the specification/particulars, with a period of twelve
                                                                            months from the date of delivery, we will be held responsible for replacement at our expense and cost of the unacceptable goods with the acceptable goods provided these are
                                                                            correctly handled, installed and used under the conditions for which these are designed.</p> 
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_bor" colspan="6"></td>
                                                            </tr>
                                                            <tr>
                                                                <td class="dc_bor" colspan="4">
                                                                    <div class="recived">
                                                                        <h2>Received By</h2>
                                                                        <p>Name:</p>
                                                                        <p>Date:</p>
                                                                        <p>Signature & Stamp:</p>
                                                                    </div>
                                                                </td>
                                                                <td class="dc_bor" colspan="0"></td>
                                                                <td class="dc_bor" colspan="5">
                                                                    <div class="recived2">
                                                                        <h2>For, Zahabiya Chemicals Industries (Private) Limited</h2>
                                                                        <p>Authorized Signature</p>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                        <!-- <div class="col-md-6 ">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                                              
                                                </div>
                                            </div>
                                        </div> -->


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
