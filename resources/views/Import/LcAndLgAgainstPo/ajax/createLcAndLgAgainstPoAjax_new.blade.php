<?php
    use App\Helpers\CommonHelper;
    use App\Helpers\ImportHelper;

    // echo "<pre>";
    // print_r(ImportHelper::get_hs_code_data(1));
    // exit();
$hs_code_data = [];
?>                                            
                                        <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Applicant Name </label>
                                                        
                                                        <input name="buyer_name" id="buyer_name" class="form-control" type="text">
                                                        <input name="buyer_id" id="buyer_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Applicant Full Address </label>
                                                            <textarea name="applicant_full_address" cols="30" ></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Beneficiary Name  </label>
                                                        
                                                        <input name="beneficiary_name" id="beneficiary_name" class="form-control" type="text">
                                                        <input name="beneficiary_id" id="beneficiary_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Beneficiary Full address </label>
                                                            <textarea name="beneficiary_full_address" cols="30" ></textarea>
                                                    </div>
                                                </div>
                                        </div>
                                        
                                        <div class="row">
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Advising Bank </label>
                                                        
                                                        <input name="advising_bank" id="advising_bank" class="form-control" type="text">
                                                        <input name="advising_bank_id" id="advising_bank_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Account No </label>
                                                        <input name="advising_bank_account_no" id="advising_bank_account_no" class="form-control" type="text">

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Swift code </label>
                                                        <input name="advising_bank_swift_code" id="advising_bank_swift_code" class="form-control" type="text">

                                                    </div>
                                                </div>
            
                                        </div>
                                        
                                        <div class="row">
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Inter mediary Bank</label>
                                                        
                                                        <input name="inter_mediary_bank" id="inter_mediary_bank" class="form-control" type="text">
                                                        <input name="inter_mediary_bank_id" id="inter_mediary_bank_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Account No </label>
                                                        <input name="inter_mediary_bank_account_no" id="inter_mediary_bank_account_no" class="form-control" type="text">

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Swift code </label>
                                                        <input name="inter_mediary_bank_swift_code" id="inter_mediary_bank_swift_code" class="form-control" type="text">

                                                    </div>
                                                </div>
            
                                        </div>
                                        
                                        <div class="row">
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Currency</label>
                                                        
                                                        <input name="Currency" id="Currency" class="form-control" type="text">
                                                        <input name="Currency_id" id="Currency_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Amount</label>
                                                        <input name="amount" id="d_t_amount_1" class="form-control" type="text">

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Amount in words</label>
                                                            <textarea id="rupees" cols="40" ></textarea>
                                                    </div>
                                                </div>
            
                                        </div>

                                        <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">FOB</label>
                                                        <input name="fob" id="fob" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">CFR</label>
                                                        <input name="cfr" id="cfr" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">CPT</label>
                                                        <input name="cpt" id="cpt" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Sight</label>
                                                        <input name="sight" id="sight" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                        </div>

                                        <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Shipment From</label>
                                                        <input name="shipment_from" id="shipment_from" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Shipment To</label>
                                                        <input name="shipment_to" id="shipment_to" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Latest Shipment date</label>
                                                        <input name="latest_shipment_date" id="latest_shipment_date" class="form-control" type="date">

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Expirty Date</label>
                                                        <input name="expirty_date" id="expirty_date" class="form-control" type="date">

                                                    </div>
                                                </div>
                                        
                                        </div>

                                        <div class="row">
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Days from</label>
                                                        <input name="days_from" id="days_from" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">BL DATE</label>
                                                        <input name="bl_date" id="bl_date" class="form-control" type="date">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Delivery Type</label>
                                                        <br>
                                                        <label for="">Partail shipment </label>
                                                        <input name="delivery_type"  id="deleivery_type" class="" type="radio" value="partail_shipment" >
                                                        <label for="">Transhipment </label>
                                                        <input name="delivery_type"  id="deleivery_type" class="" type="radio" value="transhipment" >

                                                    </div>
                                                </div>
                                        </div>        

                                        <div class="row">
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Origin</label>
                                                        <input name="origin" id="origin" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Hs Code</label>
                                                        <input name="hs_code"  step="any"  id="hs_code" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Insurance</label>
                                                        <input name="insurance" id="insurance" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                
                                        </div>
                                        

                                        <div class="row">
                                            <h5>Description of Goods</h5>
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <?php
                                                    $counter1 = 1;
                                                    $all_total=0;
                                                    ?>

                                                    <table class="table table-bordered sf-table-list">
                                                        <thead>
                                                            <tr>

                                                                <th class="text-center">Sr No</th>
                                                                <th class="text-center" colspan="1">Item Name</th>
                                                                <th class="text-center" >Quantity</th>
                                                                <th class="text-center" >Unit</th>
                                                                <th class="text-center" >Rate</th>
                                                                <th class="text-center" >Total Value</th>
                                                                <th class="text-center" >Hs Code</th>
                                                            </tr>

                                                        </thead>
                                                        <tbody>
                                                        @foreach($purchase_request_data as $key => $value)    
                                                            <tr>
                                                                <td>{{ $counter1 }}</td>
                                                                <td>
                                                                    {{ CommonHelper::getCompanyDatabaseTableValueById(Session::get('run_company'),'subitem','sub_ic',$value->sub_item_id) }}
                                                                    <input type="hidden" name="item_id[]" id="item_id{{$counter1}}" value="{{ $value->sub_item_id }}">
                                                                </td>
                                                                <td>
                                                                    <input type="number" step="any" class="qty" onkeyup="calculation_po({{$counter1}})"  min="0" name="qty[]" id="qty{{$counter1}}" value="{{ $value->purchase_request_qty }}">
                                                                </td>
                                                                <td>{{  ImportHelper::get_uom_name($value->sub_item_id) }}</td>
                                                                <td>
                                                                    <input type="number" step="any" onkeyup="calculation_po({{$counter1}})"  name="rate[]" id="rate{{$counter1}}" value="{{ $value->rate }}">
                                                                </td>
                                                                <td>
                                                                    <input type="number"  step="any" class="total_amount"  name="total_amount[]" id="total_amount{{$counter1}}" value="{{ $value->amount }}">
                                                                </td>
                                                                <td> 
                                                                    <input type="number" class="hs_code_amount" name="hs_code_amount[]"  step="any"  id="hs_code_amount{{$counter1}}" value="0">
                                                                </td>

                                                            </tr>
                                                            @php
                                                                $hs_code_data[] = ImportHelper::get_hs_code_data($value->sub_item_id);
                                                                $counter1 ++;
                                                            @endphp
                                                        @endforeach    
                                                        </tbody>
                                                    </table>    

                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                                
                                            <div class="col-md-3">
                                                <div class="col-md-12 padtb text-right"> 
                                                    <div class="col-md-12 my-lab">
                                                        <button type="submit" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                                                        <a href="{{ route('LcAndLgAgainstPo.cancel') }}" class="btnn btn-secondary">Cancel</a>
                                                    </div>    
                                                </div>
                                            </div>

                                        </div>


<script>                                        
      var hsCodeData = @json($hs_code_data);

    

</script>                                        