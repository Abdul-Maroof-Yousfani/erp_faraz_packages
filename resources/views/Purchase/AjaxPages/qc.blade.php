
<?php
use App\Helpers\CommonHelper;
use App\Helpers\PurchaseHelper;
use App\Helpers\StoreHelper;
use App\Helpers\ReuseableCode;


$approve=ReuseableCode::check_rights(23);
$id = $_GET['id'];
$m = Session::get('m');
$currentDate = date('Y-m-d');
CommonHelper::companyDatabaseConnection($m);
$goodsReceiptNoteDetail = DB::table('goods_receipt_note')->where('grn_no','=',$id)->get();

        $AddionalExpense = DB::table('addional_expense')->where('voucher_no','=',$id);

CommonHelper::reconnectMasterDatabase();

foreach ($goodsReceiptNoteDetail as $row) {
$demandType = $row->demand_type;
$grn_status = $row->grn_status;
$grn_data =  DB::Connection('mysql2')->table('grn_data')->where('status',1)->where('master_id',$row->id)->get();

?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        @if ($approve==true)
                @if($grn_status==1)
                <button  onclick="approve_grn({{$row->id}}); btnDis()" id="BtnApproved"  type="button" class="btn btn-success btn-xs hide">Approve</button>
                @endif
        @endif
                    <?php CommonHelper::displayPrintButtonInView('printGoodsReceiptNoteVoucherDetail','LinkHide','1');?>
            </div>
    </div>
</div>
<div style="line-height:5px;">&nbsp;</div>
<form method="post" action="{{ url('/pdc/qc_submit') }}">
    <input type="hidden" name="grn_id" value="{{ $row->id }}"/>
<div class="row">
    <?php if($demandType == 2){?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        <?php echo PurchaseHelper::displayApproveDeleteRepostButtonGoodsReceiptNote($m,$row->grn_status,$row->status,$row->grn_no,'grn_no','grn_status','status','goods_receipt_note','grn_data','1');?>
    </div>
    <div style="line-height:5px;">&nbsp;</div>
    <?php }?>

    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
    <input type="hidden" name="grnNo" value="<?php echo $id; ?>">
    <input type="hidden" name="grnDate" value="<?php echo $row->grn_date; ?>">
    <input type="hidden" name="prNo" value="<?php echo $row->po_no; ?>">
    <input type="hidden" name="prDate" value="<?php echo $row->po_date; ?>">
    <input type="hidden" name="supplier_id" value="<?php echo $row->supplier_id; ?>">
    <input type="hidden" name="sub_deparment_id" value="<?php echo $row->sub_department_id; ?>">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" id="printGoodsReceiptNoteVoucherDetail">
        <div class="well">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <label style="border-bottom:2px solid #000 !important;">Printed On Date&nbsp;:&nbsp;</label><label style="border-bottom:2px solid #000 !important;"><?php echo CommonHelper::changeDateFormat(date('Y-m-d'));$x = date('Y-m-d');
                                echo ' '.'('.date('D', strtotime($x)).')';?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <?php echo CommonHelper::get_company_logo(Session::get('run_company'));?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <h3 style="text-align: center;">Goods Receipt Note</h3>
                        </div>
                    </div>
            </div>
            <div style="line-height:5px;">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div style="width:40%; float:left;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>
                            <tr>
                                <td style="width:40%;">GRN No.</td>
                                <td style="width:60%;"><?php echo strtoupper($row->grn_no);?></td>
                            </tr>
                            <tr>
                                <td>GRN Date</td>
                                <td><?php echo CommonHelper::changeDateFormat($row->grn_date);?></td>
                            </tr>
                            <?php if($row->type != 5):?>
                            <tr>
                                <td style="width:40%;">PO No.</td>
                                <td style="width:60%;"><?php echo strtoupper($row->po_no);?></td>
                                <?php   $po_type=CommonHelper::get_po_type_query($row->po_no); ?>
                            </tr>
                            <tr>
                                <td>PO Date</td>
                                <td><?php if ($row->type==0): echo CommonHelper::changeDateFormat($row->po_date);endif;?></td>
                            </tr>
                            <tr>
                                <td>Bill Date</td>
                                <td><?php  echo CommonHelper::changeDateFormat($row->bill_date);?></td>
                            </tr>
                            <?php endif;?>
                            <tr>
                                <td>Supplier Invoice No</td>
                                <td><?php  echo $row->supplier_invoice_no;?></td>
                            </tr>

                            <tr>
                                <td>Delivery Challan No </td>
                                <td><?php echo $row->delivery_challan_no;;?></td>
                            </tr>


                            </tbody>
                        </table>
                    </div>


                    <div style="width:40%; float:right;">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <tbody>


                            <tr>
                                <td style="width:60%;">Delivery Detail/ Vehicle #.</td>
                                <td style="width:40%;"><?php echo $row->delivery_detail;?></td>
                            </tr>

                            <tr>
                                <td>Department / Sub Department</td>
                                <td><?php echo CommonHelper::getMasterTableValueById($m,'sub_department','sub_department_name',$row->sub_department_id);?></td>
                            </tr>

                            <tr>
                                <td>Supplier Name</td>
                                <td><?php echo CommonHelper::getCompanyDatabaseTableValueById($m,'supplier','name',$row->supplier_id);?></td>
                            </tr>
                            <tr>
                                <td>Supplier Address</td>
                                <td width=""><?php echo CommonHelper::get_supplier_address($row->supplier_id);;?></td>
                            </tr>
                            </tbody>

                        </table>
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table  class="table table-bordered table-striped table-condensed tableMargin">
                            <thead>
                            <th class="text-center">S.No</th>
                            <th class="text-center">Item</th>
                            <th class="text-center">UOM</th>
                            <th class="text-center">Received QTY</th>
                            {{-- <th class="text-center">Reject QTY</th> --}}
                            <th class="text-center">Remarks</th>
                            {{-- <th class="text-center">Action</th> --}}

                            </thead>

                            <?php $count=1; ?>
                            <tbody>
                             @foreach ($grn_data as $key => $row)
                             <tr class="text-center">
                                 <td>{{ $count++ }}</td>
                                 <td>{{ CommonHelper::get_item_name($row->sub_item_id) }}</td>
                                 <td>{{ CommonHelper::get_uom($row->sub_item_id) }}</td>
                                 <td>{{ number_format($row->purchase_recived_qty,2) }}</td>
                                 {{-- <td><input onkeyup="check_qty('{{ $row->purchase_recived_qty }}','{{ $count }}')" onblur="{{ $row->purchase_recived_qty }}" class="form-control requiredField" readonly type="number" name="reject_qty[]" id="reject_qty{{ $count }}" step="0.001" value="{{ 0 }}"></td> --}}
                                 <td><textarea class="form-control" name="remarks[]" id="remarks{{ $count }}"></textarea></td>
                                 {{-- <td><input   onclick="reject_data('{{ $count }}')" class="" type="checkbox" id="checkd{{ $count }}" /> </td> --}}
                                 <input type="hidden" name="grn_data_id[]" value="{{ $row->id }}"/>
                             </tr>
                             @endforeach

                            </tbody>
                        </table>
                        <div class="text-right">
                            <input type="submit" value="Approve" class="btn btn-success btn-xs">
                        </div>



                    </div>
                </div>







            </div>

        </div>
    </div>
    <?php }

        ?>


    </div>
</div>
</div>
</form>


<script type="text/javascript">


   function  reject_data(id)
   {


       if ($('#checkd'+id).prop('checked') == true)
       {

                // readonly
           $('#reject_qty'+id).attr('readonly', false);
           $('#remarks'+id).attr('readonly', false);


                    // add class
           $('#reject_qty'+id).addClass("zerovalidate");
           $('#remarks'+id).addClass("zerovalidate");
           $('#remarks'+id).addClass("requiredField");

       }

       else
       {
           $('#reject_qty'+id).attr('readonly', true);
           $('#remarks'+id).attr('readonly', true);


           $('#reject_qty'+id).removeClass("zerovalidate");
           $('#remarks'+id).removeClass("zerovalidate");
           $('#remarks'+id).removeClass("requiredField");
       }
   }

   $("form").submit(function(e) {
  
       var validate=form_validate();
    

     //  if (validate==false)
     //  {
     //      console.log(validate);
     //      e.preventDefault();
     //      return false;
     //  }
     //  if (validate==1) {


           $('form').submit();

      // }
   });

function check_qty(qty,count)
{
    var received_qty = parseFloat(qty);
 
    var reject_qty =parseFloat($('#reject_qty'+count).val());
   
    if (reject_qty > received_qty)
    {
        $('#reject_qty'+count).val(0);
    }


}
</script>
