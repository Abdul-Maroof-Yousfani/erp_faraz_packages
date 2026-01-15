@extends('layouts.default')

@section('content')
<?php
use App\Helpers\CommonHelper;
$so_no =CommonHelper::generateUniquePosNo('sales_order','so_no','SO');
?>
    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Selling</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Raw Material</h3>
                </li>
            </ul>
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-right">
          <ul class="cus-ul2">
                <li>
                    <a href="{{ url()->previous() }}" class="btn-a">Back</a>
                </li>
                {{-- <li>
                    <input type="text" class="fomn1" placeholder="Search Anything" >
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-edit"></span> Edit Columns</a>
                </li>
                <li>
                    <a href="#" class="cus-a"><span class="glyphicon glyphicon-filter"></span> Filter</a>
                </li> --}}
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
                                <form action="{{route('salesorder.store')}}" method="post">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cus-tab">
                                        <div class="row qout-h">
                                            <div class="col-md-12 bor-bo">
                                                <h1>Create Purchase Request</h1>
                                            </div>
                                            
                                            <div class="col-md-12 padt pos-r">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>Purchase Request No.</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <label>1024574</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="col-sm-4 control-label">Voucher Date</label>
                                                        <div class="col-sm-8">
                                                            <input name="" class="form-control" type="date">
                                                        </div>
                                                    </div>
                                                    <div class="form-group">
                                                        <div class="col-md-4">
                                                            <label>MR No.</label>
                                                        </div>
                                                        <div class="col-sm-8">
                                                            <label>MR - 1024574</label><br>
                                                            <label>MR - 1024574</label><br>
                                                            <label>MR - 1024574</label><br>
                                                            <label>MR - 1024574</label><br>
                                                            <label>MR - 1024574</label>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                                <div class="col-md-2"></div>
                                                <div class="col-md-4">
                                                    
                                                </div>
                                            </div>


                                            <div class="col-md-12 padt">
                                                <div class="col-md-12 padt">
                                                    <div class="col-md-12">
                                                        <table class="table">
                                                            <tr>
                                                                <th>S.No</th>
                                                                <th>Product Name</th>
                                                                <th>Total Qty Required</th>
                                                                <th>Total Qty Available</th>
                                                                <th>Remaining Qty</th>
                                                            </tr>
                                                            <tbody>
                                                                <tr>
                                                                    <td>001</td>
                                                                    <td>
                                                                        <input type="text" value="C/C - M/B PIGMENT-WHITE (CLARIANT)" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>002</td>
                                                                    <td>
                                                                        <input type="text" value="C/C - M/B PIGMENT-WHITE (CLARIANT)" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>003</td>
                                                                    <td>
                                                                        <input type="text" value="C/C - M/B PIGMENT-WHITE (CLARIANT)" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control">
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td>004</td>
                                                                    <td>
                                                                        <input type="text" value="C/C - M/B PIGMENT-WHITE (CLARIANT)" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control" readonly>
                                                                    </td>
                                                                    <td>
                                                                        <input type="text" value="" class="form-control">
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 padtb text-right">
                                                <div class="col-md-9"></div>    
                                                <div class="col-md-3 my-lab">
                                                    <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
                                                </div>    
                                            </div>
                                        </div>        
                                    </div>
                                </div>
                            </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>


@endsection