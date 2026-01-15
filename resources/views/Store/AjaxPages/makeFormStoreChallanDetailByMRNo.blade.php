<?php
	use App\Models\Account;
    use App\Helpers\CommonHelper;
    use App\Helpers\ReuseableCode;

	$m = Session::get('run_company');
	$makeGetValue = explode('<*>',$_GET['mrNo']);
	$mrNo = $makeGetValue[0];
	$mrDate = $makeGetValue[1];
?>
<script>
	function updateOverAllDebitAmount(){
		var sum = 0;
		$("input[class *= 'yesSubTotalAmount']").each(function(){
			sum += +$(this).val();
		});
		$('#pv_debit_amount').val(sum);
	}
	
	function calculateTotalTaxHeadAmount(){
		var sum = 0;
		$("input[class *= 'yesTaxHeadAmount']").each(function(){
			sum += +$(this).val();
		});
		$('#overAllTaxAmount').val(sum);
	}
	
	function optionEnableAndDisableTaxHeadSlap(paramOne){
		var taxHeadOption = $('#tax_head_option_'+paramOne+'').val();
		if(taxHeadOption == 1){
			$('#tax_head_amount_'+paramOne+'').addClass('yesTaxHeadAmount');
			$('#tax_head_amount_'+paramOne+'').prop("readonly", false);
		}else{
			$('#tax_head_amount_'+paramOne+'').removeClass('yesTaxHeadAmount');
			$('#tax_head_amount_'+paramOne+'').val('0');
			$('#tax_head_amount_'+paramOne+'').prop("readonly", true);
		}
		calculateTotalTaxHeadAmount();
	}
	
	function optionEnableAndDisablePurchaseOrderRequestRegionWise(paramOne,paramTwo,paramThree){
		var generatePurchaseOrderType = $('#generate_purchase_order_type_'+paramOne+'_'+paramThree+'').val();
		var countYesValue = $('#countYesValue_'+paramOne+'').val();
		if(generatePurchaseOrderType == 1){
			$('#countYesValue_'+paramOne+'').val(parseInt(countYesValue) + parseInt('1'));
			$('#generate_purchase_order_type_'+paramOne+'_'+paramThree+'').addClass('yesOption_'+paramOne+'');
			$('#purchase_order_qty_'+paramOne+'_'+paramThree+'').val('1');
			$('#unit_price_'+paramOne+'_'+paramThree+'').val('1');
			$('#sub_total_'+paramOne+'_'+paramThree+'').val('1');
			$('#sub_total_with_persent_'+paramOne+'_'+paramThree+'').val('0');
		}else{
			$('#countYesValue_'+paramOne+'').val(parseInt(countYesValue) - parseInt('1'));
			$('#generate_purchase_order_type_'+paramOne+'_'+paramThree+'').removeClass('yesOption_'+paramOne+'');
			$('#purchase_order_qty_'+paramOne+'_'+paramThree+'').val('0');
			$('#unit_price_'+paramOne+'_'+paramThree+'').val('0');
			$('#sub_total_'+paramOne+'_'+paramThree+'').val('0');
			$('#sub_total_with_persent_'+paramOne+'_'+paramThree+'').val('0');
		}
		var countYesValueTwo = $('#countYesValue_'+paramOne+'').val();
		if(countYesValueTwo == 1){
			$('.yesOption_'+paramOne+'').prop("disabled", true);
			updateOverAllDebitAmount();
		}else{
			$('.yesOption_'+paramOne+'').prop("disabled", false);
			updateOverAllDebitAmount();
		}
	}
	
	
	
	function calculateTaxHeadPercentageAndAmount(paramOne,paramTwo){
		var pvDebitAmount = $('#pv_debit_amount').val();
		if(pvDebitAmount == '0'){
			alert('Something Wrong!');
		}else if(pvDebitAmount == ''){
			alert('Something Wrong!');
		}else{
			var taxHeadPercentage = $('#tax_head_percentage_'+paramOne+'').val();
			var taxHeadAmount = $('#tax_head_amount_'+paramOne+'').val();
			if(paramTwo == 1){
				//Convert our percentage value into a decimal.
				var percentInDecimal = parseInt(taxHeadPercentage) / 100;
				//Get the result.
				var percentAmount = percentInDecimal * pvDebitAmount;
				//Print it out - Result is 232.
				$('#tax_head_amount_'+paramOne+'').val(percentAmount);
			}else if(paramTwo == 2){
				//Convert our percentage value into a decimal.
				var percentInDecimal = parseInt(taxHeadAmount) / pvDebitAmount;
				//Get the result.
				var percent = percentInDecimal * 100;
				//Print it out - Result is 232.
				$('#tax_head_percentage_'+paramOne+'').val(percent);
			}
		}
	}
</script>
<div>
    <input type="hidden" name="mrNo" id="mrNo" value="<?php echo $mrNo ?>" readonly/>
    <input type="hidden" name="mrDate" id="mrDate" value="<?php echo $mrDate ?>" readonly/>
    {{-- <input type="hidden" name="subDepartmentId" id="subDepartmentId" value="< ?php echo $getMaterialRequestDetail->sub_department_id ?>" readonly/> --}}
    {{-- <input type="hidden" name="locationId" id="locationId" value="< ?php echo $getMaterialRequestDetail->location_id ?>" readonly/> --}}
    {{-- <input type="hidden" name="projectId" id="projectId" value="< ?php echo $getMaterialRequestDetail->project_id ?>" readonly/> --}}
    {{-- <input type="hidden" name="departmentId" id="departmentId" value="< ?php echo $getMaterialRequestDetail->department_id?>" readonly /> --}}
    {{-- <input type="hidden" name="initialEmailAddress" id="initialEmailAddress" value="< ?php echo CommonFacades::voucherInitialEmailAddress($getMaterialRequestDetail->user_id)?>" /> --}}
</div>
<div class="row">
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label class="sf-label">Material Request No</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="text" class="form-control" readonly name="material_request_no" id="material_request_no" value="<?php echo $mrNo ?>" />
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label class="sf-label">Warehouse From</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <select onchange="warehouseFromChange(this.value)" name="warehouse_from_id" id="warehouse_from_id" class="form-control requiredField">
            {{-- <option value="">Select Warehouse</option> --}}
            @foreach(CommonHelper::get_all_warehouse() as $row)
                <option value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <?php $warehouseToId=0; ?>
        <label class="sf-label">Warehouse to</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <select name="warehouse_to_id" id="warehouse_to_id" class="form-control requiredField">
            {{-- <option value="">Select Warehouse</option> --}}
            @foreach(CommonHelper::get_all_warehouse() as $row)
                <option value="{{ $row->id }}">{{ ucwords($row->name) }}</option>
            @endforeach
        </select>
        <!-- <input type="text" name="warehouse_to_id" id="warehouse_to_id" class="form-control" readonly value="{{ $getMaterialRequestDetail->warehouse_id }}" > -->
        <input type="hidden" name="location_name" id="location_name" class="form-control" readonly value="<?php //echo $getMaterialRequestDetail->location_name;?>" >
    </div>
    
    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
        <label class="sf-label">Issuance Date.</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <input type="date" class="form-control requiredField" name="store_challan_date" id="store_challan_date" value="{{ date('Y-m-d') }}" />
    </div>
    
</div>
<div class="row">    
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <label class="sf-label">Remarks</label>
        <span class="rflabelsteric"><strong>*</strong></span>
        <textarea name="main_description" id="main_description" rows="2" cols="50" style="resize:none;" class="form-control">-</textarea>
    </div>
</div>
<div class="lineHeight">&nbsp;</div>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-bordered sf-table-list">
                <thead>
                    <tr>
                        <th class="text-center">Option</th>
                        <th class="text-center">Item Code / Item Name</th>
                        <th class="text-center">Batch</th>
                        <th class="text-center">Current Balance</th>
                        <th class="text-center">Material Request Qty.</th>
                        <th class="text-center">Privious Issueance Qty.</th>
                        <th class="text-center">Issuance Qty.</th>
                        <th class="text-center">Sub Description</th>
                    </tr>
                </thead>
                <?php
                    $counter = 1;
                    foreach($getMaterialRequestDataDetail as $key => $gmrddRow){
                        $remainingStoreChallanQty = $gmrddRow->qty - $gmrddRow->totalIssueQty;
                        // $currentBalance = CommonFacades::checkItemWiseCurrentBalanceQtyNew($m,$gmrddRow->category_id,$gmrddRow->sub_item_id,'',date('Y-m-d'),$getMaterialRequestDetail->location_id);
                        $currentBalance = 0
                        
                ?>
                        <input type="hidden" name="storeChallanData[]" id="storeChallanData" value="<?php echo $gmrddRow->id?>" />
                        
                        <input type="hidden" class="sub_item_id" name="sub_item_id_<?php echo $gmrddRow->id?>" id="sub_item_id_<?php echo $gmrddRow->id?>" value="<?php echo $gmrddRow->sub_item_id?>" />
                        <tr id="storeChallanDetailRow_<?php echo $gmrddRow->id?>">
                            <td>
                                <select name="issue_status_<?php echo $gmrddRow->id?>" id="issue_status_<?php echo $gmrddRow->id?>" class="issueStatus form-control" onchange="storeChallanOptionDisableAndEnable('<?php echo $gmrddRow->id?>')">
                                    <option value="1">Yes</option>
                                    <option value="2">No</option>
                                </select>
                            </td>
                            <td><?php echo $gmrddRow->item_code;?> / <?php echo $gmrddRow->sub_ic;?></td>
                            @php
                                $batch_codes = ReuseableCode::get_bacth_code($getMaterialRequestDetail->warehouse_id,$gmrddRow->sub_item_id)
                            @endphp
                            <td class="text-center">
                                <select onchange="getStockBatchWise({{$gmrddRow->id}}, {{$gmrddRow->sub_item_id}}, this.value)" class="form-control" name="batch_code_{{$gmrddRow->id}}" id="batch_code_{{$gmrddRow->id}}">
                                    <option value="">Select Batch</option>
                                    @foreach ($batch_codes as $batch_code)
                                        <option value="{{ $batch_code->batch_code }}">{{ $batch_code->batch_code }}</option>                                        
                                    @endforeach
                                </select>
                            </td>
                            <td class="text-center current-balances stock_detail_{{$gmrddRow->id}}">0</td>
                            <td class="text-center"><?php echo $gmrddRow->qty?></td>
                            <td class="text-center">
                                <?php if(empty($gmrddRow->totalIssueQty)){echo 0;}else{echo $gmrddRow->totalIssueQty;}?>
                                <input type="hidden" name="remaining_store_challan_qty_<?php echo $gmrddRow->id?>" id="remaining_store_challan_qty_<?php echo $gmrddRow->id?>" value="<?php echo $remainingStoreChallanQty?>" />
                            </td>
                            <td>
                                <input type="text" name="store_challan_qty_<?php echo $gmrddRow->id?>" id="store_challan_qty_<?php echo $gmrddRow->id?>" class="form-control requiredField" value="" onkeyup="checkAvailableBalance(this.id,this.value,<?php echo $key?>);checkqty(this.id,this.value, {{ $gmrddRow->qty }},<?php echo $key?>)" />
                            </td>
                            <td>
                                <input type="text" name="sub_description_<?php echo $gmrddRow->id?>" id="sub_description_<?php echo $gmrddRow->id?>" class="form-control" value="-" />
                            </td>
                        </tr>     
                <?php
                    }
                ?>
            </table>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
        {{ Form::button('Submit', ['class' => 'btn btn-success btn-add-success btnSubmit','id' => 'submit-btn-abc']) }}
        <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $("select").select2();
    });
    var totalRows = '<?php echo count($getMaterialRequestDataDetail)?>';
    function storeChallanOptionDisableAndEnable(id){
        var issueStatusValue = $('#issue_status_'+id+'').val();
        if(issueStatusValue == 1){
            totalRows += 1;
            $("#issue_status_"+id+"").addClass("issueStatus");
            $("#store_challan_qty_"+id).addClass('requiredField');
            $("#storeChallanDetailRow_"+id+" input").prop('disabled', false);
        }else{
            totalRows -= 1;
            $("#storeChallanDetailRow_"+id+" input").prop('disabled', true);
            $("#store_challan_qty_"+id).removeClass('requiredField');
            $("#issue_status_"+id+"").removeClass("issueStatus");

        }
        if(totalRows == 1){
            $('.issueStatus').prop('disabled', true);
        }else{
            $('.issueStatus').prop('disabled', false);
        }

    }
	if(totalRows == 1){
        $('.issueStatus').prop('disabled', true);
    }
    $(".btn-add-success").click(function(e){
        var storeChallanData = new Array();
		var val;
		$("input[name='storeChallanData[]']").each(function(){
			storeChallanData.push($(this).val());
		});
		var _token = $("input[name='_token']").val();
		for (val of storeChallanData) {
            jqueryValidationCustom();
			if(validate == 0){
                $('.issueStatus').prop('disabled', false);
				$(".btnSubmit").val('Sending, please wait...');
				$('.btnSubmit').prop("disabled", true);
				setTimeout(function(){
					$(".btnSubmit").prop("type", "button");
				},50);
			}else{
				return false;
			}
		}
        formSubmitOne();

	});

    function formSubmitOne(){

        var postData = $('#addStoreChallanDetail').serializeArray();
        var formURL = $('#addStoreChallanDetail').attr("action");
        $.ajax({
            url : formURL,
            type: "POST",
            data : postData,
            success:function(data){
                window.location.href = "<?php echo url('/')?>/store/viewStoreChallanList?pageType=viewlist&&parentCode=158&&m=<?php echo $m;?>#SFR";
            }
        });
        }
	$(document).ready(function() {
        var startAccountYear = $("#startAccountYearDMYFormat").val();
        var endAccountYear = $("#endAccountYearDMYFormat").val();
        // $(".fromDateDatePicker").datepicker({
        //     showAnim: "slideDown",
        //     dateFormat: "dd-mm-yy",
        //     maxDate: endAccountYear,
        //     minDate: startAccountYear
        // });

        $('.sub_item_id').each(function(){
            let id=$(this).attr('id');
            let sub_item_id=$(this).val();
            let parts = id.split("sub_item_id_");
            let numericValue = parseInt(parts[1]);
            let ware_house_id=$('#warehouse_from_id').val();
            let uri = "{{ url('/') }}"+"/store/getBatchCodes"
            if(ware_house_id != '' && sub_item_id != ''){
                $.ajax({
                    url : uri,
                    type: "GET",
                    data :  {sub_item_id:sub_item_id,ware_house_id:ware_house_id},
                    success:function(data){
                        let selectElement = $('#batch_code_' + numericValue);
               
                        selectElement.empty();
                    
                        selectElement.append($('<option>', {
                            value: '',
                            text: 'Select Batch'
                        }));
                    
                        $.each(data, function(index, batch) {
                            selectElement.append($('<option>', {
                                value: batch.batch_code,
                                text: batch.batch_code
                            }));
                        });
                    }
                });
            }else{
                alert('Select warehouse and subitem first!');
            }
           
        })

    });


    function warehouseFromChange(){
        
        $('.sub_item_id').each(function(){
            let id=$(this).attr('id');
            let sub_item_id=$(this).val();
            let parts = id.split("sub_item_id_");
            let numericValue = parseInt(parts[1]);
            let ware_house_id=$('#warehouse_from_id').val();
            let uri = "{{ url('/') }}"+"/store/getBatchCodes"
            if(ware_house_id !== '' && sub_item_id !== ''){
                $.ajax({
                    url : uri,
                    type: "GET",
                    data : {sub_item_id:sub_item_id,ware_house_id:ware_house_id},
                    success:function(data){
                        let selectElement = $('#batch_code_' + numericValue);
               
                        selectElement.empty();
                    
                        selectElement.append($('<option>', {
                            value: '',
                            text: 'Select Batch'
                        }));
                    
                        $.each(data, function(index, batch) {
                            selectElement.append($('<option>', {
                                value: batch.batch_code,
                                text: batch.batch_code
                            }));
                        });
                    }
                    });
            }else{
                alert('Select warehouse and subitem first!');
            }
           
        })
    }

    function checkqty(id,value, mrQty,index){
        console.log(value, mrQty);
        // availableBalance = itemsBalanceLocationWise[index][current_location]
        if(value > mrQty){
            $('#'+id+'').val('0');
            alert('Something went wrong! Your are issuing more qty....');
        }
    }

    

    function getStockBatchWise(id, item, value) 
    {   
        let count = id;
        let warehouse_id=$('#warehouse_from_id').val();
        let batch_code = value;
        let item_id = item;
        let uri = "{{ url('/') }}"+"/store/getStockBatchWise"
        $.ajax({
            type: "get",
            url: uri,
            data: { batch_code:batch_code, item_id:item_id, warehouse_id:warehouse_id }, //warehouse_id=5
            dataType: "json",
            success: function (response) {
                html = `<span>${response.current_stock} ${response.batch_detail.subitem_uom}</span><br>
                    <span>${response.batch_detail.packing_qty} ${response.batch_detail.subitem_uom} /${response.batch_detail.uom_name}</span> 
                    <input type="hidden" name="packing_qty_${count}" value="${response.batch_detail.packing_qty}">
                    <input type="hidden" name="packing_uom_${count}" value="${response.batch_detail.packing_uom}">
                    <input type="hidden" name="supplier_id_${count}" value="${response.batch_detail.supplier_id}">`;
                $('.stock_detail_'+count).html(html);
            }
        });
    }
</script>

