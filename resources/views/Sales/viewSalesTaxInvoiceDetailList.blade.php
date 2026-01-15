<?php
use App\Helpers\CommonHelper;
use App\Helpers\ReuseableCode;
$export=ReuseableCode::check_rights(232);

$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$current_date = date('Y-m-d');
$currentMonthStartDate = date('Y-m-01');
$currentMonthEndDate   = date('Y-m-t');


?>

@extends('layouts.default')

@section('content')

    <div class="row well_N align-items-center">
        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
            <ul class="cus-ul">
                <li>
                    <h1>Sales</h1>
                </li>
                <li>
                    <h3><span class="glyphicon glyphicon-chevron-right"></span> &nbsp; Sales Invoices Details</h3>
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
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right"  style="margin-top:10px">
                                    
                                        <?php if($export == true):?>
                                    <?php echo CommonHelper::displayExportButton('csvData','','1')?>
                                    <?php endif;?>
                                </div>
                                <div class="lineHeight">&nbsp;</div>
                            <div class="row" style="margin-top:10px">
                    
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class=" control-label">Customer</label>
                                            
                                                <select class="form-control" name="customer_id" id="customer_id">
                                                    <option value="">Select Customer</option>
                                                    @foreach($Customer as $key => $value)
                                                        <option value="{{$value->id}}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                               
                                        </div>
                                    </div> 
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class=" control-label">From date</label>
                                            
                                                <input name="fromDate" id="fromDate" value="" class="form-control" type="date">
                                           
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label class=" control-label">To date</label>
                                            
                                                <input name="toDate" id="toDate" value="" class="form-control" type="date">
                                            
                                        </div>
                                    </div>
                                    <div class="col-md-3" style="height: 86px;
                                                                display: flex;
                                                                flex-direction: row;
                                                                flex-wrap: wrap;
                                                                align-content: center;">

                                        <button type="button" class="btn btn-sm btn-primary" style="margin: 5px 0px 0px px;" onclick="viewSalesTaxInvoiceDetailListAjax();">Submit</button>
                                    </div>
                                </div>
                            </div>
                        <div class="panel">
                            <div class="panel-body">
                            <div class="headquid">
                           <h2 class="subHeadingLabelClass">View Sales Invoices Details List </h2>
                        </div>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="">
                                        <table class="userlittab table table-bordered sf-table-list" id="csvData">
                                                <thead>
                                                <tr>
                                                    <th class="text-center">S. No.</th>
                                                    <th class="text-center">NTN/CNIC</th>
                                                    <th class="text-center">Party Name</th>
                                                    <th class="text-center">Destination of Supply</th>
                                                    <th class="text-center">Inv #</th>
                                                    <th class="text-center">Inv Date</th>
                                                    <th class="text-center">Quantity</th>
                                                    <th class="text-center">UoM</th>
                                                    <th class="text-center">Value of Sales Excluding Sales Tax</th>
                                                    <th class="text-center">Sales Tax</th>
                                                    <th class="text-center">Further Tax</th>
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
        viewSalesTaxInvoiceDetailListAjax();
        });
         function viewSalesTaxInvoiceDetailListAjax()
        {

            let fromDate = $('#fromDate').val();
            let toDate = $('#toDate').val();
            let customer_id = $('#customer_id').val();

             $('#data').html('<tr><td colspan="12"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div></td><tr>');

            $.ajax({
                url: '<?php echo url('/')?>/sales/viewSalesTaxInvoiceDetailListAjax',
                type: 'Get',
                data: {
                        fromDate,
                        toDate,
                        customer_id
                    },
                success: function (response) {

                    $('#data').html(response);


                }
            });


        }

        
    </script>


@endsection