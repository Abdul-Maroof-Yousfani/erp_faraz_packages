<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;
use App\Helpers\CommonHelper;
?>


<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('select2', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    <div class="well_N">
    <div class="dp_sdw">
        <div class="panel">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
                        <?php echo $__env->make('Purchase.'.$accType.'purchaseMenu', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="well">
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <span class="subHeadingLabelClass">Create Goods Receipt Note Form</span>
                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>
                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="panel">
                                        <div class="panel-body">
                                            <div class="row">
                                                <!--
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label class="sf-label">Purchae Request No & Date</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="purchase_request_no" id="purchase_request_no" onchange="loadGoodsReceiptNoteDetailByPRNo()">
                                                        <option value="">Select Purchae Request No & Date</option>
                                                        < ?php foreach($PurchaseRequestDatas as $row){?>
                                                            <option value="< ?php echo $row->purchase_request_no.'*'.$row->purchase_request_date?>">< ?php echo 'PO No =>&nbsp;&nbsp;&nbsp;'.strtoupper($row->purchase_request_no).'&nbsp;, PO Date =>&nbsp;&nbsp;&nbsp;'.CommonHelper::changeDateFormat($row->purchase_request_date)?></option>
                                                        < ?php }?>
                                                    </select>
                                                </div>
                                                <!-->
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label for="" style="border-radius: 7px;background-color: #000;padding: 10px 17px;color: #fff;box-shadow: 2px 2px 7px 0px #000;border: #0000;">Search By Supplier <input type="radio" value="1" class="form-control" id="One" name="GetVal" onchange="GetForm(1)"></label>
                                                    <label for="" style="border-radius: 7px;background-color: #000;padding: 10px 17px;color: #fff;box-shadow: 2px 2px 7px 0px #000;border: #0000;margin-left: 23px;">Search By Po No <input type="radio" value="2" class="form-control" id="Two" name="GetVal" onchange="GetForm(2)"></label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 SectionOne" style="display: none;">
                                                    <label class="sf-label">Supplier</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="supplier" id="supplier" onchange="get_po()">
                                                        <option value="0">Select Supplier</option>
                                                        <?php foreach(CommonHelper::get_all_supplier() as $row){?>
                                                        <option value="<?php echo e($row->id); ?>"> <?php echo e(ucwords($row->name)); ?> </option>
                                                        <?php }?>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 SectionOne" style="display: none">
                                                    <label class="sf-label">PO NO</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <select class="form-control requiredField" required name="po" id="po" onchange="loadGoodsReceiptNoteDetailByPRNo()">
                                                        <option>select</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 SectionTwo" style="display: none">
                                                    <label class="sf-label">PO NO Manual</label>
                                                    <span class="rflabelsteric"><strong>*</strong></span>
                                                    <input type="text" class="form-control" id="PoNoManual" name="PoNoManual" placeholder="Po No Manual">
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12 SectionTwo" style="display: none;">
                                                    <button type="button" class="btn btn-sm btn-primary" id="BtnGetData" onclick="GetManualPoByNo()" style="margin: 33px 0px 0px 0px;">Get Data</button>
                                                </div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <div class="loadGoodsReceiptNoteDetailSection"></div>
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

        function GetForm(Type)
        {
            if(Type == 1)
            {
                $('.SectionOne').css('display','block');
                $('.SectionTwo').css('display','none');
                $('.select2, .select2-container, .select2-container--default').css('width','100%');
            }
            else if(Type == 2)
            {
                $('.SectionOne').css('display','none');
                $('.SectionTwo').css('display','block');
            }
            else{

            }
        }

        function loadGoodsReceiptNoteDetailByPRNo(){


            var prNo = $('#po').val();
            var m = '<?php echo $_GET['m']?>';
            if(prNo == ''){
                alert('Please Select Purchase Request No');
                $('.loadGoodsReceiptNoteDetailSection').html('');
            }else{
                $('.loadGoodsReceiptNoteDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/makeFormGoodsReceiptNoteDetailByPRNo',
                    type: "GET",
                    data: { prNo:prNo,m:m},
                    success:function(data) {
                        $('.loadGoodsReceiptNoteDetailSection').html(data);
                    }
                });
            }
        }

        function GetManualPoByNo()
        {
            var prNo = $('#PoNoManual').val();
            var m = '<?php echo $_GET['m']?>';
            if(prNo == ''){
                alert('Please Enter Purchase Request No');
                $('.loadGoodsReceiptNoteDetailSection').html('');
            }else{
                $('.loadGoodsReceiptNoteDetailSection').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/pmfal/makeFormGoodsReceiptNoteDetailByPRNoManual',
                    type: "GET",
                    data: { prNo:prNo,m:m},
                    success:function(data) {
                        $('.loadGoodsReceiptNoteDetailSection').html(data);
                    }
                });
            }
        }
        $(document).ready(function() {
            $(".btn-success").click(function(e){

                var seletedPurchaseRequestRow = new Array();
                var val;
                $("form").each(function(){
                    seletedPurchaseRequestRow.push($(this).val());
                });
                var _token = $("input[name='_token']").val();
                for (val of seletedPurchaseRequestRow) {
                    jqueryValidationCustom();
                    if(validate == 0){
                        //alert(response);
                        vala = 0;
                        var flag = false;
                        $('.RecQty').each(function(){
                            vala = parseFloat($(this).val());
                            if(vala == 0)
                            {
                                alert('Please Enter Correct Recieved Qty....!');
                                $(this).css('border-color','red');
                                flag = true;
                                return false;
                            }
                            else{

                                $('#BtnSubmit').attr('readonly', true);
                                $(this).css('border-color','#ccc');
                            }
                        });
                        if(flag == true)
                        {return false;}
                        $('.BatchCode').each(function(){
                            if($(this).val() == "")
                            {
                                alert('Please Enter Batch Code....!');
                                $(this).css('border-color','red');
                                return false;
                            }

                        });


                    }else{

                        return false;
                    }
                }

            });
        });
        function get_po() {

            $('.loadGoodsReceiptNoteDetailSection').html('');
            var supplier_id=$('#supplier').val();
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/get_po',
                type: "GET",
                data: { supplier_id:supplier_id},
                success:function(data)
                {

                    $('#po').html(data);
                }
            });
        }

    </script>

    <script type="text/javascript">

        $('#supplier').select2();

        var CounterExpense = 1;
        function AddMoreExpense()
        {
            CounterExpense++;
            $('#AppendExpense').append("<tr id='RemoveExpenseRow"+CounterExpense+"'>" +
                    "<td>"+
                    "<select class='form-control requiredField select2' name='account_id[]' id='account_id"+CounterExpense+"'><option value=''>Select Account</option><?php foreach($accounts as $Fil){?><option value='<?php echo $Fil->id?>'><?php echo $Fil->code.'--'.$Fil->name;?></option><?php }?></select>"+
                    "</td>"+
                    "</td>" +
                    "<td>" +
                    "<input type='number' name='expense_amount[]' id='expense_amount"+CounterExpense+"' class='form-control requiredField'>" +
                    "</td>" +
                    "<td class='text-center'>" +
                    "<button type='button' id='BtnRemoveExpense"+CounterExpense+"' class='btn btn-sm btn-danger-2' onclick='RemoveExpense("+CounterExpense+")'><span class='glyphicon glyphicon-trash'></span> </button>" +
                    "</td>" +
                    "</tr>");
            $('#account_id'+CounterExpense).select2();
        }
        function RemoveExpense(Row)
        {
            $('#RemoveExpenseRow'+Row).remove();
        }


    </script>
    <script src="<?php echo e(URL::asset('assets/js/select2/js_tabindex.js')); ?>"></script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.default', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>