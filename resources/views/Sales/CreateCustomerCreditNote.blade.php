


<?php
$accType = Auth::user()->acc_type;
if($accType == 'client')
{
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
use App\Helpers\SalesHelper;
use App\Helpers\CommonHelper;


?>
@extends('layouts.default')
@section('content')
    @include('select2');

    <style>
        #myInput {

            background-position: 10px 10px;
            background-repeat: no-repeat;
            width: 100%;
            font-size: 16px;
            padding: 12px 20px 12px 40px;
            border: 1px solid #ddd;
            margin-bottom: 12px;
        }
    </style>






    <div class="panel-body">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="well_N">
                <div class="dp_sdw">    
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <span class="subHeadingLabelClass">Sales Return </span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                            <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                        </div>
                    </div>
                    <div class="row">&nbsp;</div>
                    <div class="tab-content">
                        <div id="home" class="tab-pane in active">
                            <div class="panel">
                                <div class="panel-body" id="PrintEmpExitInterviewList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <?php echo Form::open(array('url' => 'sales/addCustomerCredit_no?m='.$m.'','id'=>'cashPaymentVoucherForm'));?>
                                    <input type="hidden" name="type" value="1"/>
                                    <div class="row">

                                        <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                            <label>SO No.</label>
                                            <input type="text" name="so_no" id="so_no" max="<?php ?>" value="" class="form-control" />

                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                            <label>DN
                                            <input checked type="radio" name="type" id="dn"  value="1" class=""  /></label>

                                            <label>SI
                                                <input type="radio" name="type" id="si"  value="2" class="" /></label>
                                            <button type="button" class="btn btn-sm btn-primary" style="margin-top: 30px;" onclick="ShowData();" tabindex="2">Search</button>

                                        </div>



                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list" id="myTable">
                                                    <thead>
                                                    <th class="text-center col-sm-1"></th>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center col-sm-1">Item</th>
                                          
                                                    <th class="text-center col-sm-1">UOM</th>
                                                    <th class="text-center col-sm-1">QTY.</th>
                                                    <th class="text-center col-sm-1">Rate</th>
                                                    <th class="text-center col-sm-1">Discount</th>
                                                    <th class="text-center col-sm-1">Amount</th>

                                                    {{--<th class="text-center">Edit</th>--}}
                                                    {{--<th class="text-center">Delete</th>--}}
                                                    </thead>
                                                    <tbody id="ShowOn">
                                                    <?php $counter = 1;?>
                                                    </tbody>
                                                </table>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ShowBtn">
                                                    <input type="submit" value="Create Customer Credit Note" class="btn btn-xs btn-success pull-left" id="add" disabled="">
                                                </div>

                                                <div id="data1"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php Form::close(); ?>
                                </div>
                            </div>
                        </div>



                    </div>


                    <div class="lineHeight">&nbsp;</div>

                </div>
                </div>
            </div>
        </div>
    </div>

    <script !src="">
        $(document).ready(function () {
            $('#SupplierId').select2();
            var action = $('form').attr('action');
            $('form').attr('action',action);
        });

        function ShowData()
        {
            var so = $('#so_no').val();
            var type = $('input[name="type"]:checked').val();

            if(so == "")

            {

               alert('Required So');
                return false;

            }

                $('#SupplierError').html('');
                $('#ShowOn').html('<tr><td colspan="10" class="loader"></td></tr>');

                $.ajax({
                    url: '<?php echo url('/')?>/sdc/getSalesTaxInvoice',
                    type: "GET",
                    data: {so:so,type:type},
                    success:function(data) {
                        $('#data1').html('');
                        $('#ShowOn').html(data);


                    }
                });
        }


        
    </script>
@endsection
