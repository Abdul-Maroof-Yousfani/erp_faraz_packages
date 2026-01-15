


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



                    <ul class="nav nav-tabs">
                        <li class="active"><a onclick="change(1)" data-toggle="tab" href="#home">Direct Purchase</a></li>
                        <li><a onclick="change(2)" data-toggle="tab" href="#menu1">Purchase With GRN</a></li>

                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <div class="panel">
                                <div class="panel-body" id="PrintEmpExitInterviewList">
                                    <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                    <?php echo Form::open(array('url' => 'finance/createPaymentForOutstanding/','id'=>'cashPaymentVoucherForm'));?>
                                        <input type="hidden" name="type" value="1"/>
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered sf-table-list " id="EmpExitInterviewList">
                                                    <thead>
                                                    <th class="text-center"></th>
                                                    <th class="text-center col-sm-1">S.No</th>
                                                    <th class="text-center">Pv No</th>
                                                    <th class="text-center">Pv Date</th>
                                                    <th class="text-center">GRN NO</th>
                                                    <th class="text-center">Due Date</th>
                                                    <th class="text-center">Supplier</th>


                                                    <th class="text-center">Total Qty</th>
                                                    <th class="text-center">Total Rate</th>
                                                    <th class="text-center">Total Sales %</th>
                                                    <th class="text-center">Total Sales Amount</th>
                                                    <th class="text-center">Total Amount</th>
                                                    <th class="text-center">View</th>
                                                    </thead>
                                                    <tbody>
                                                    <?php $counter = 1;?>

                                                    @foreach($purchase_voucher as $row)
                                                        <tr title="{{$row->id}}">
                                                            <td class="text-center"><input name="checkbox[]" onclick="check(),supplier_check('{{$row->supplier}}',this.id)" class="checkbox1" id="1chk{{$counter}}" type="checkbox" value="{{$row->id}}"></td>
                                                            <td class="text-center">{{$counter++}}</td>
                                                            <td class="text-center">{{strtoupper($row->pv_no)}}</td>
                                                            <td class="text-center">{{ CommonHelper::changeDateFormat($row->purchase_date)}}</td>
                                                            <td class="text-center">{{CommonHelper::changeDateFormat($row->due_date)}}</td>
                                                            <td class="text-center">{{$row->grn_no}}</td>
                                                            <td class="text-center">{{CommonHelper::get_supplier_name($row->supplier)}}</td>
                                                            <td class="text-right">{{$row['total_qty']}}</td>
                                                            <td class="text-right">{{$row['total_rate']}}</td>
                                                            <td class="text-right">{{$row['total_salesTax']}}</td>
                                                            <td class="text-right">{{number_format($row['total_salesTax_amount'],2)}}</td>
                                                            <td class="text-right">{{number_format($row['total_net_amount'],2)}}</td>
                                                            <td class="text-center"><button
                                                                        onclick="showDetailModelOneParamerter('pdc/viewPurchaseVoucherDetail/<?php echo $row->id ?>','','View Purchase Voucher')"
                                                                        type="button" class="btn btn-success btn-xs">View</button></td>

                                                        </tr>
                                                        <?php ?>
                                                    @endforeach
                                                    </tbody>
                                                </table>

                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <input type="submit" value="CREATE Payment" class="btn btn-xs btn-success pull-left" id="add" disabled="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php Form::close(); ?>
                                </div>
                            </div>
                        </div>
                        <div  id="menu1" class="tab-pane fade">
                            <div class="panel-body" id="PrintEmpExitInterviewList">
                                <?php echo CommonHelper::headerPrintSectionInPrintView($m);?>
                                <?php echo Form::open(array('url' => 'finance/createPaymentForOutstanding/','id'=>'cashPaymentVoucherForm'));?>

                                <input type="hidden" name="type" value="2"/>
                                <div class="row">
                                    <div class="col-lg-12 col-md-12 col-sm-12col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                                <thead>
                                                <th class="text-center"></th>
                                                <th class="text-center col-sm-1">S.No</th>
                                                <th class="text-center">PV no</th>
                                                <th class="text-center">PV Date</th>
                                                <th class="text-center">GRN No</th>
                                                <th class="text-center">GRN Date</th>
                                                <th class="text-center">Due date</th>
                                                <th class="text-center">Supplier</th>

    