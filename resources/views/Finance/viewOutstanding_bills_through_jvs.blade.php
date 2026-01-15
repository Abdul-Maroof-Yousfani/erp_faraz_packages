


<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
$parentCode = $_GET['parentCode'];
use App\Helpers\HrHelper;
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
                <div class="well">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                <span class="subHeadingLabelClass">View Purchase List</span>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                                <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                                <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                            </div>
                        </div>
                    </div>





                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <div class="panel">
                                <div class="panel-body" id="PrintEmpExitInterviewList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <?php echo Form::open(array('url' => 'finance/CreatePayment_through_jvs/','id'=>'cashPaymentVoucherForm'));?>
                                    <input type="hidden" name="type" value="1"/>
                                    <div class="row">

                                        <div class="col-sm-6">
                                            <label>Supplier</label>
                                            <select name="SupplierId" id="SupplierId" class="form-control" tabindex="1">
                                                <option value="">Select Supplier</option>
                                                <?php foreach($supplier as $SuppFil):?>
                                                <option value="<?php echo $SuppFil->id;?>"><?php echo $SuppFil->name?></option>
                                                <?php endforeach;?>
                                            </select>
                                            <span id="SupplierError"></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <button type="button" class="btn btn-sm btn-primary" style="margin-top: 30px;" onclick="ShowData();" tabindex="2">Show</button>
                                        </div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">&nbsp;</div>

                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list" id="myTable">
                                                    <thead>
                                                    <th class="text-center"></th>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center">Refrence</th>
                                                    <th class="text-center">Jv No</th>
                                                    <th class="text-center">Jv Date</th>
                                                    <th class="text-center">Ref / Bill No</th>
                                                    <th class="text-center">Bill Date</th>
                                                    <th class="text-center">Due Date</th>
                                                    <th class="text-center">Purchased Amount</th>
                                                    <th class="text-center">Paid Amount</th>
                                                    <th class="text-center">Remaining Amount</th>

                                                    </thead>
                                                    <tbody id="ShowOn">
                                                    <?php $counter = 1;?>
                                                    </tbody>
                                                </table>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="ShowBtn">
                                                    <input type="submit" value="CREATE Payment" class="btn btn-xs btn-success pull-left" id="add" disabled="">
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

    <script !src="">
        $(document).ready(function () {
            $('#SupplierId').select2();
            var action = $('form').attr('action');
            $('form').attr('action',action+'/1');
        });

        function ShowData()
        {
            var SupplierId = $('#SupplierId').val();

            if(SupplierId != "")
            {
                $('#SupplierError').html('');
                $('#ShowOn').html('<tr><td colspan="10" class="loader"></td></tr>');

                $.ajax({
                    url: '<?php echo url('/')?>/fdc/getDataSupplierWise',
                    type: "GET",
                    data: {SupplierId:SupplierId},
                    success:function(data) {
                        $('#data1').html('');
                        $('#ShowOn').html(data);

                    }
                });

            }
            else
            {
                $('#SupplierError').html('<p class="text-danger">Select Supplier</p>');

            }



        }
    </script>
@endsection
