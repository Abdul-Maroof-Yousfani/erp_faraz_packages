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
                            <span class="subHeadingLabelClass">Create And View Paid To</span>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 text-right">
                            <?php echo CommonHelper::displayPrintButtonInBlade('PrintEmpExitInterviewList','','1');?>
                            <?php echo CommonHelper::displayExportButton('EmpExitInterviewList','','1')?>
                        </div>
                    </div>
                </div>

                <div class="lineHeight">&nbsp;</div>
                <div class="panel">
                    <div class="panel-body" id="PrintEmpExitInterviewList">
                        <?php //echo CommonHelper::headerPrintSectionInPrintView($m);?>


                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 well">
                                <?php echo Form::open(array('url' => 'fad/addPaidTo?m='.$m.'','id'=>'chartofaccountForm'));?>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <input type="text" name="PaidToName" id="PaidToName" class="form-control requiredField" placeholder="Paid To Name" required>
                                    <input type="hidden" name="m" id="m" value="<?php echo $m;?>">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <select name="Type" id="Type" class="form-control" required>
                                        <option value="">Select Type</option>
                                        <option value="1">Employee</option>
                                        <option value="2">Customer</option>
                                        <option value="3">Supplier</option>
                                        <option value="4">Buyer</option>
                                        <option value="5">Owner</option>
                                        <option value="6">Other</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <input type="text" name="MobilNo" id="MobilNo" class="form-control" placeholder="Mobile No">
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                </div>
                                <?php echo Form::close();?>
                                </div>
                            </div>

                            <div class="lineHeight">&nbsp;</div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered sf-table-list" id="EmpExitInterviewList">
                                        <thead>
                                        <th class="text-center">S.No</th>
                                        <th class="text-center">Paid To</th>
                                        <th class="text-center">Type</th>
                                        <th class="text-center">Mobile No</th>
                                        </thead>
                                        <tbody id="data">
                                        <?php $counter = 1;$total=0;
                                        $paramOne = "sdc/editTypeList?m=".$m;
                                        foreach($PaidTo as $Fil):?>
                                        <tr>
                                            <td class="text-center"><?php echo $counter++;?></td>
                                            <td class="text-center"><?php echo  $Fil->name ?></td>
                                            <td class="text-center">
                                                <?php if($Fil->type == 1):?>
                                                    Employee
                                                <?php elseif($Fil->type == 2):?>
                                                    Customer
                                                <?php elseif($Fil->type == 3):?>
                                                    Supplier
                                                <?php elseif($Fil->type == 4):?>
                                                    Buyer
                                                <?php elseif($Fil->type == 5):?>
                                                Owner
                                                <?php elseif($Fil->type == 6):?>
                                                Other
                                                <?php else:?>
                                                <?php endif;?>

                                            </td>
                                            <td class="text-center"><?php echo $Fil->mobil_no?></td>
                                        </tr>
                                        <?php endforeach;?>
                                        </tbody>
                                    </table>



                           <?php    $data= DB::Connection('mysql2')->select('select a.gi_no,a.description,a.gi_date,c.voucher_no,

                                                sum(c.amount)as amount
                                                from sales_tax_invoice as a
                                                inner join
                                                sales_tax_invoice_data as b
                                                on
                                                a.id=b.master_id
                                                inner join
                                                stock c
                                                on
                                                c.so_data_id=b.so_data_id
                                                where a.so_type=0
                                                and a.status=1
                                                group by c.voucher_no

                                                ');




                                    foreach($data as $row):


                                    $data1 =array
                                    (
                                    'acc_id'=>768,
                                    'v_date'=>$row->gi_date,
                                    'voucher_no'=>$row->gi_no,
                                    'voucher_type'=>8,
                                    'acc_code'=>'1-2-1-2',
                                    'particulars'=>$row->description,
                                    'opening_bal'=>0,
                                    'debit_credit'=>1,
                                    'amount'=>$row->amount,
                                    'username'=>'Amir1993',
                                    'status'=>1,
                                    'date'=>date('Y-m-d'),
                                    );
                                //   DB::Connection('mysql2')->table('transactions')->insert($data1);

                                    $data =array
                                    (
                                    'acc_id'=>97,
                                    'v_date'=>$row->gi_date,
                                    'voucher_no'=>$row->gi_no,
                                    'voucher_type'=>8,
                                    'acc_code'=>'1-2-1-1',
                                    'particulars'=>$row->description,
                                    'opening_bal'=>0,
                                    'debit_credit'=>0,
                                    'amount'=>$row->amount,
                                    'username'=>'Amir1993',
                                    'status'=>1,
                                    'date'=>date('Y-m-d'),
                                    );
                               //     DB::Connection('mysql2')->table('transactions')->insert($data);

                                     endforeach


                                    ?>
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
    });
//    $(".btn-success").click(function(e){
//        jqueryValidationCustom();
//        if(validate == 0){
//            //$('#BtnSave').css('display','none');
//            //return false;
//        }else{
//            return false;
//        }
//    });

</script>

@endsection