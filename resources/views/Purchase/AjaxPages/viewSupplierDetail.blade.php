<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
$m = $_GET['m'];
$status = $_GET['status'];
$id = $_GET['id'];
$name = $_GET['name'];
$accId = $_GET['accId'];
$tableName = $_GET['tableName'];
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$supplierDetail = App\Models\Supplier::where('id', '=', $id)
        ->first(['name', 'id', 'acc_id','email','country','province','city','resgister_income_tax','business_type','cnic','ntn','register_sales_tax',
                'strn','register_srb','srb','register_pra','pra','print_check_as','vendor_type','website','credit_limit',
                'acc_no','bank_name','branch_name','bank_address','swift_code']);
CommonHelper::reconnectMasterDatabase();
$monthYear = date('Y-m');
?>
<div class="well">
    <div class="row">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-4">
            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat($currentDate);?></label>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-5">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                     style="font-size: 30px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                    <?php echo CommonHelper::getCompanyName($m);?>
                </div>
                <br />
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"
                     style="font-size: 20px !important; font-style: inherit;
    								font-family: -webkit-body; font-weight: bold;">
                    <?php CommonHelper::checkMasterTableVoucherDetailStatus($status);?>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 text-right">
            <?php $nameOfDay = date('l', strtotime($currentDate)); ?>
            <label style="border-bottom:2px solid #000 !important;">Printed On Day&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo '&nbsp;'.$nameOfDay;?></label>

        </div>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div style="width:100%; float:left;">
                <table  class="table table-bordered table-striped table-condensed tableMargin">
                    <tbody>
                    <tr>
                        <td style="width:40%;">Vendor Name:</td>
                        <td style="width:60%;"><?php echo $name;?></td>

                    </tr>

                    <tr>
                        <td style="width:60%;">Country:</td>
                        <td style="width:40%;"><?php echo CommonHelper::getMasterTableValueByIdWithoutCompanyId('countries','name',$supplierDetail->country);?></td>
                    </tr>
                    <tr>
                        <td>Province:</td>
                        <td><?php echo CommonHelper::getMasterTableValueByIdWithoutCompanyId('states','name',$supplierDetail->province);?></td>
                    </tr>
                    <tr>
                        <td>City:</td>
                        <td><?php echo CommonHelper::getMasterTableValueByIdWithoutCompanyId('cities','name',$supplierDetail->city);?></td>
                    </tr>
                    <?php  $supplier_other_info = DB::connection('mysql2')->select('select contact_no,address from supplier_info where supp_id="'.$supplierDetail->id.'"');
                    $count=1;
                    ?>
                    @foreach($supplier_other_info as $row)

                        @if($row->contact_no!='')
                            <tr>

                                <td>Contact No{{$count}}</td>
                                <td><?php echo $row->contact_no;?></td>
                                <?php $count++; ?>
                            </tr>
                        @endif
                    @endforeach
                    <?php $count=1; ?>
                    <tr>
                        <td>Email:</td>
                        <td><?php echo $supplierDetail->email;?></td>
                    </tr>
                    @foreach($supplier_other_info as $row)

                        @if($row->address!='')
                            <tr>

                                <td>Address{{$count}}</td>
                                <td><?php echo $row->address;?></td>
                                <?php $count++; ?>
                            </tr>
                        @endif
                    @endforeach


                    <tr>
                        <td>Print Check As:</td>
                        <td>{{$supplierDetail->print_check_as}}</td>
                    </tr>
                    <tr>
                        <td>Vendor Type:</td>
                        <td>{{$supplierDetail->vendor_type}}</td>
                    </tr>

                    <tr>
                        <td>Website:</td>
                        <td>{{$supplierDetail->website}}</td>
                    </tr>

                    <tr>
                        <td>Credit Limit:</td>
                        <td>{{$supplierDetail->credit_limit}}</td>
                    </tr>


                    <tr>
                        <td>Bank Acc No:</td>
                        <td>{{$supplierDetail->acc_no}}</td>
                    </tr>


                    <tr>
                        <td>Bank Name:</td>
                        <td>{{$supplierDetail->bank_name}}</td>
                    </tr>


                    <tr>
                        <td>Branch Name:</td>
                        <td>{{$supplierDetail->branch_name}}</td>
                    </tr>

                    <tr>
                        <td>Bank Address:</td>
                        <td>{{$supplierDetail->bank_address}}</td>
                    </tr>

                    <tr>
                        <td>Swift Code:</td>
                        <td>{{$supplierDetail->swift_code}}</td>
                    </tr>
                    <tr>
                        <td>Register On Income Tax:</td>
                        <td><?php if($supplierDetail->resgister_income_tax==1): echo  'YES';else:echo 'NO';endif;;?></td>
                    </tr>
                    <tr>
                        <td>Register On Income Tax:</td>
                        <td><?php if($supplierDetail->business_type==1): echo  'Business Individual';endif;
                            if($supplierDetail->business_type==2): echo  'Company';endif;
                            if($supplierDetail->business_type==3): echo  'AOP';endif;

                            ?></td>
                    </tr>
                    <tr>
                        <td>NTN:</td>
                        <td><?php echo $supplierDetail->ntn;?></td>
                    </tr>

                    <tr>
                        <td>CNIC:</td>
                        <td><?php echo $supplierDetail->cnic;?></td>
                    </tr>

                    <tr>
                        <td>Register On Sales Tax:</td>
                        <td><?php  if($supplierDetail->register_sales_tax==1): echo  'YES';else:echo 'NO';endif;;?></td>
                    </tr>

                    <tr>
                        <td>STRN:</td>
                        <td><?php echo $supplierDetail->strn;?></td>
                    </tr>
                    <tr>
                        <td>Register On SRB:</td>
                        <td><?php  if($supplierDetail->register_srb==1): echo  'YES';else:echo 'NO';endif;;?></td>
                    </tr>

                    <tr>
                        <td>SRB:</td>
                        <td><?php echo $supplierDetail->srb;?></td>
                    </tr>

                    <tr>
                        <td>Register On PRA:</td>
                        <td><?php  if($supplierDetail->register_pra==1): echo  'YES';else:echo 'NO';endif;;?></td>
                    </tr>

                    <tr>
                        <td>PRA:</td>
                        <td><?php echo $supplierDetail->pra;?></td>
                    </tr>

                    </tbody>
                </table>
            </div>




        </div>
    </div>

    <!--
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <span class="subHeadingLabelClass">Current Month Purchase Activity</span>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <?php echo PurchaseHelper::monthWisePurchaseActivitySupplierWise($m,$id,$monthYear)?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <span class="subHeadingLabelClass">Current Month Payment Voucher Activity</span>
    </div>
    <div class="lineHeight">&nbsp;</div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="panel">
                <div class="panel-body">
                    <?php echo PurchaseHelper::monthWisePaymentVoucherActivitySupplierWise($m,$id,$monthYear,$accId)?>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
    </div>
</div>
</div>
