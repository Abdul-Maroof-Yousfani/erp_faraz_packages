<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = $_GET['m'];
}else{
    $m = Auth::user()->company_id;
}
use App\Helpers\PurchaseHelper;

use App\Helpers\FinanceHelper;
use App\Helpers\CommonHelper;

$str = DB::connection('mysql2')->selectOne("select max(convert(substr(`grn_no`,4,length(substr(`grn_no`,4))-4),signed integer)) reg from `goods_receipt_note` where substr(`grn_no`,-4,2) = " . date('m') . " and substr(`grn_no`,-2,2) = " . date('y') . "")->reg;
$grn_no = 'grn' . ($str + 1) . date('my');
?>


@extends('layouts.default')
@section('content')
    @include('number_formate')
    @include('select2')

    <div class="well">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <span class="subHeadingLabelClass">Direct GRN Form</span>
            </div>
        </div>
        <div class="lineHeight">&nbsp;</div>
        <div class="row">
            <?php echo Form::open(array('url' => 'pad/addDirectGrnForm?m='.$m.'','id'=>'cashPaymentVoucherForm'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
            <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <input type="hidden" name="demandsSection[]" class="form-control requiredField" id="demandsSection" value="1" />
                            </div>
                        </div>


                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">GRN NO.</label>
                                        <input readonly  type="text" class="form-control requiredField" placeholder=""  name="grn_no" id="grn_no" value="{{strtoupper($grn_no)}}" />
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">GRN Date.</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="grn_date" id="grn_date" value="<?php echo date('Y-m-d') ?>" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">BIll Date.</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="date" class="form-control requiredField" max="<?php echo date('Y-m-d') ?>" name="bill_date" id="bill_date" value="<?php echo date('Y-m-d') ?>" />
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Supplier Invoice No</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input autofocus type="text" class="form-control requiredField" placeholder="Supplier Invoice No" name="invoice_no" id="invoice_no" value="" />
                                    </div>


                                    <div class="lineHeight">&nbsp;</div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Delivery Challan No</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="Delivery Challan No" name="del_chal_no" id="del_chal_no" value="" />
                                    </div>


                                </div>
                                <div class="row">






                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label">Delivery Detail/ Vehicle # </label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <input type="text" class="form-control requiredField" placeholder="Delivery Detail/ Vehicle #" name="del_detail" id="del_detail" value="" />
                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createWarehouseFormAjax')" class="">Warehouse</a> </label>
                                        <select class="form-control requiredField select2" name="warehouse_id" id="warehouse_id">
                                            <option value="">Select</option>

                                                   @foreach(CommonHelper::get_all_warehouse() as $row)
                                                        <option value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
                                                    @endforeach

                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label"> Sub Department</label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select select2 class="form-control requiredField select2" name="subDepartmentId" id="subDepartmentId">
                                            <option value="">Select Department</option>
                                            @foreach($departments as $key => $y)
                                                <optgroup label="{{ $y->department_name}}" value="{{ $y->id}}">
                                                    <?php
                                                    $subdepartments = DB::select('select `id`,`sub_department_name` from `sub_department` where `company_id` = '.$m.' and `department_id` ='.$y->id.'');
                                                    ?>
                                                    @foreach($subdepartments as $key2 => $y2)
                                                        <option value="{{ $y2->id}}">{{ $y2->sub_department_name}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>

                                    </div>

                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label class="sf-label"> <a href="#" onclick="showDetailModelOneParamerter('pdc/createSupplierFormAjax');" class="">Vendor</a></label>
                                        <span class="rflabelsteric"><strong>*</strong></span>
                                        <select select2 onchange="get_address()" name="supplier_id" id="supplier_id" class="form-control requiredField select2">
                                            <option value="">Select Vendor</option>
                                            <?php
                                            foreach ($supplier as $row1){

                                            $address= CommonHelper::get_supplier_address($row1->id);
                                            ?>
                                            <option value="<?php echo $row1->id.'+'.$address.'+'.$row1->ntn?>"><?php echo ucwords($row1->name)?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>



                                </div>
                            </div>
                            <div class="lineHeight">&nbsp;</div>

                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <label class="sf-label">Supplier's Address</label>
                                        <input style="text-transform: capitalize;" readonly type="text" class="form-control requiredField" placeholder="" name="address" id="addresss" value="" />
                                    </div>

                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <label class="sf-label">Supplier's NTN</label>
                                        <input readonly type="text" class="form-control" placeholder="Ntn" name="ntn" id="ntn_id" value="" />
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <label class="sf-label">Description</label>
                                    <span class="rflabelsteric"><strong>*</strong></span>
                                    <textarea name="main_description" id="main_description" rows="4" cols="50" style="resize:none;" class="form-control requiredField"></textarea>
                                </div>
                            </div>


                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <div class="well">
                            <input type="hidden" name="goodsReceiptNoteDataSection_1[]" class="form-control requiredField" id="goodsReceiptNoteDataSection_1" value="1" />
                            <div class="panel">
                                <div class="panel-body">

                                    <div class="row addMoreDemandsDetailRows_1" id="addMoreDemandsDetailRows_1">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">

                                                <table id="buildyourform" class="table table-bordered">
                                                    <thead>
                                                    <tr>
                                                        <th colspan="" class="text-center">Region <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th style="width: 250px;" colspan="" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createCategoryFormAjax')" class="">Category</a></th>
                                                        <th style="width: 250px;" colspan="" class="text-center hidden-print"><a tabindex="-1"  href="#" onclick="showDetailModelOneParamerter('pdc/createSubItemFormAjax/0')" class="">Sub Item</a></th>
                                                        <th colspan="" class="text-center" >Uom <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="" class="text-center" >Pack Size <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="" class="text-center">Qty in Unit <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="" class="text-center">Rate <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="" class="text-center">Amount <span class="rflabelsteric"><strong>*</strong></span></th>
                                                    </tr>
                                                    </thead>
                                                    <tbody class="" id="">
                                                    <input type="hidden" name="demandDataSection_1[]" class="form-control requiredField" id="demandDataSection_1" value="1" />
                                                    <tr>
                                                        <td>
                                                            <select class="form-control requiredField select2" name="region_1_1" id="region_1_1">
                                                                <option value="">Select</option>
                                                                @foreach(CommonHelper::get_all_regions() as $row)
                                                                    <option value="{{ $row->id }}"> {{ ucwords($row->region_name) }} </option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                        <td colspan="">
                                                            <select name="category_id_1_1" id="category_id_1_1" onchange="subItemListLoadDepandentCategoryId(this.id,this.value)" class="form-control requiredField select2">
                                                                <?php echo PurchaseHelper::categoryList($_GET['m'],'0');?>
                                                            </select>
                                                        </td>
                                                        <td colspan="">
                                                            <select onchange="get_detail(this.id)"  name="sub_item_id_1_1" id="sub_item_id_1_1" class="form-control requiredField select2">
                                                                <option>Select Sub Item</option>
                                                            </select>
                                                        </td>
                                                        <td colspan="">
                                                            <input class="form-control" readonly type="text" id="uom_1_1" name="uom_1_1"/>
                                                            <input type="hidden" id="uom_id_1_1" name="uom_id_1_1"/>
                                                        </td>
                                                        <td colspan="">
                                                            <input class="form-control" readonly type="text" id="pack_size_1_1" name="pack_size_1_1"/>
                                                        </td>

                                                        <input class="form-control" readonly type="hidden" id="demand_type_id_1_1" name="demand_type_id_1_1"/>

                                                        <td colspan="">
                                                            <input type="text" name="qty_1_1" id="qty_1_1" onkeyup="CalculationQtyRate('1')" class="form-control requiredField" />
                                                        </td>
                                                        <td colspan="">
                                                            <input type="text" name="rate_1_1" id="rate_1_1" onkeyup="CalculationQtyRate('1')" class="form-control requiredField" />
                                                        </td>
                                                        <td colspan="">
                                                            <input type="text" name="amount_1_1" id="amount_1_1" class="form-control requiredField" readonly/>
                                                        </td>
                                                    </tr>


                                                    <tr>
                                                        <th colspan="1" class="text-center hidden-print">Remarks</th>
                                                        <th colspan="1" class="text-center hidden-print">Item Manufacturing Date</th>
                                                        <th colspan="1" class="text-center" >Item Expiry Date <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="1" class="text-center" >Item Batch no. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="1" class="text-center" >NO Of PKG Per Item. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="1" class="text-center"> Gross Weight Per Item. <span class="rflabelsteric"><strong>*</strong></span></th>
                                                        <th colspan="1" class="text-center"> Net Weight Per Item. <span class="rflabelsteric"><strong>*</strong></span></th>

                                                    </tr>

                                                    <tr>
                                                        <td colspan="1"><textarea  style="resize: none" class="requiredField form-control" name="description_1_1" id="description_1_1"></textarea> </td>
                                                        <td><input name="maufac_date_1_1" id="maufac_date_1_1"  class="form-control requiredField" type="date" value=""/> </td>

                                                        <!--Item Expiry Date-->
                                                        <td><input name="expiry_date_1_1" id="expiry_date_1_1"  class="form-control requiredField" type="date" value=""/> </td>





                                                        <!--Item Batch no.-->
                                                        <td><input name="batch_no_1_1" id="batch_no_1_1"  class="form-control requiredField" type="text" value=""/> </td>


                                                        <!--Number Of Packages Per Item..-->
                                                        <td><input name="no_pack_per_item_1_1" id="no_pack_per_item_1_1"  class="form-control requiredField" type="text" value=""/> </td>

                                                        <!--Net & Gross Weight Per Item-->
                                                        <td><input  name="gross_1_1" id="gross_1_1" class="form-control requiredField" type="text" value=""/> </td>

                                                        <td><input  name="net_1_1" id="net_1_1" class="form-control requiredField" type="text" value=""/> </td>

                                                    </tr>


                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script>
                            function pick_amount(id,send)
                            {


                                var current_amount=$('#'+id).val();
                                $('#'+send).val(current_amount);
                            }
                        </script>
                        <!-- for  dept allocation><!-->



                        <!-- for  dept allocation End><!-->



                        <table class="table table-bordered">
                            <tr>

                                <td class="col-sm-2" class="text-center" colspan="3">TOTAL&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                                <td  class="col-sm-2"  colspan="1"><!--<input type="number" maxlength="15" class="form-control text-right" name="total_qty" value="" id="total_qty" readonly="readonly">!--></td>
                                <td  class="col-sm-2"  colspan="1"><!--<<input  type="number" maxlength="15" class="form-control text-right" name="total_rate" value="" id="total_rate" readonly=""><!--></td>
                                <td  class="col-sm-2"  colspan="1"><!--<input type="number" maxlength="15" class="form-control text-right" name="total_amount" value="" id="total_amount" readonly="">!--></td>
                                <td  class="col-sm-2"  class="col-sm-6" colspan="1"><!--<input type="number" maxlength="15" class="form-control text-right" name="total_salesTax_amount" value="" id="total_sales_tax_amount" readonly="">!--> </td>
                                <td  class="col-sm-2"   colspan="1"><input tabindex="-1" type="text" maxlength="15" class="form-control text-right" name="total_net_amounttd" value="" id="net_amounttd" readonly=""></td>
                                <input type="hidden" name="total_net_amount" id="net_amount" value=""/>
                                <input type="hidden" name="d_t_amount_1" id="d_t_amount_1" value=""/>
                                <td class="text-center" colspan="1"></td>
                            </tr>
                        </table>

                        <table>
                            <tr>

                                <td id="rupees"></td>
                                <input type="hidden" name="rupeess" id="rupeess"/>
                            </tr>
                        </table>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <input type="button" class="btn btn-sm btn-primary" onclick="addMoreDemandsDetailRows('1')" value="Add More Demand's Rows" />
                            </div>
                        </div>


                        <!--department-->


                    </div>

                    <!--start-->



                    <!-->



                </div>
                <div class="demandsSection"></div>
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
                        {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <!--
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                <input type="button" class="btn btn-sm btn-primary addMoreDemands" value="Add More Demand's Section" />
                                <!-->
                    </div>
                </div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>
    </div>


    <script>

        $(document).ready(function() {

            $('#qty_1_1').number(true,2);
        });

        function CalculationQtyRate(id)
        {
            var qty  =$('#qty_1_'+id).val();
            var rate =$('#rate_1_'+id).val();
            var amount = (qty * rate);
            $('#amount_1_'+id).val(amount);
        }

        function get_address()
        {
            var supplier= $('#supplier_id').val();

            supplier=  supplier.split('+');
            $('#addresss').val(supplier[1]);

            $('#ntn_id').val(supplier[2]);
        }
        function subItemListLoadDepandentCategoryId(id,value) {
            //alert(id+' --- '+value);
            var arr = id.split('_');
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/subItemListLoadDepandentCategoryId',
                type: "GET",
                data: { id:id,m:m,value:value},
                success:function(data) {
                    $('#sub_item_id_'+arr[2]+'_'+arr[3]+'').html(data);
                }
            });
        }


        function get_detail(id) {



            var number=id.replace("sub_item_id_", "");
            number=number.split('_');
            number=number[1];


            id=$('#'+id).val();
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/get_detail_purchase_voucher',
                type: "GET",
                data: { id:id},
                success:function(data) {

                    data=data.split(',');

                    $('#uom_1_'+number).val(data[0]);
                    //   $('#rate_1_'+number).val(data[1]);
                    $('#uom_id_1_'+number).val(data[2]);
                    $('#pack_size_1_'+number).val(data[3]);
                    $('#item_description_1_'+number).val(data[4]);
                    $('#demand_type_1').val(data[5]);
                    $('#remaining_qty_1_'+number).val(data[6]);
                    $('#demand_type_1_'+number).val(data[5]);
                    $('#demand_type_id_1_'+number).val(data[7]);
                }
            });
        }

        var x=1;
        function addMoreDemandsDetailRows(id){
            x++;
            //alert(id+' ---- '+x);
            var m = '<?php echo $_GET['m'];?>';
            $.ajax({
                url: '<?php echo url('/')?>/pmfal/addDirectgrn',
                type: "GET",
                data: { counter:x,id:id,m:m},
                success:function(data) {


                    $('.addMoreDemandsDetailRows_'+id+'').append(data);
                    $('#category_id_1_' + x + '').select2();
                    $('#sub_item_id_1_' + x + '').select2();
                    $('#category_id_1_' + x + '').focus();
                    //$('#demandDataSection_1').val(x);
                    $('#qty_1_'+x).number(true,2);

                }
            });
        }

        function removeDemandsRows(x)
        {
            $('#tr'+x).remove();
        }

        $(".btn-success").click(function(e){
            var demands = new Array();
            var val;
            $("input[name='demandsSection[]']").each(function(){
                demands.push($(this).val());
            });
            var _token = $("input[name='_token']").val();
            for (val of demands) {
                jqueryValidationCustom();
                if(validate == 0){
                    //alert(response);
                }else{
                    return false;
                }
            }

        });

    </script>

    <script type="text/javascript">

        $('.select2').select2();
    </script>

    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>

@endsection