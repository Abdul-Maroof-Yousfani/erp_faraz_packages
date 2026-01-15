<?php
    use App\Helpers\CommonHelper;
    use App\Helpers\ImportHelper;

    // echo "<pre>";
    // print_r(ImportHelper::get_hs_code_data(1));
    // exit();
$hs_code_data = [];
?>                                      <div class="row">       
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label class="control-label">Po </label>
                                                    
                                                    <input value="{{$LcAndLgAgainstPo->purchase_request_no}}" disabled="true" name="buyer_name" id="buyer_name" class="form-control" type="text">
                                                
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Applicant Name </label>
                                                        
                                                        <input value="{{$LcAndLgAgainstPo->buyer_name}}" disabled="true" name="buyer_name" id="buyer_name" class="form-control" type="text">
                                                        <input value="{{$LcAndLgAgainstPo->buyer_id}}" disabled="true" name="buyer_id" id="buyer_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Applicant Full Address </label>
                                                            <textarea value="{{$LcAndLgAgainstPo->applicant_full_address}}" disabled="true" name="applicant_full_address" cols="30" >{{$LcAndLgAgainstPo->applicant_full_address}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Beneficiary Name  </label>
                                                        
                                                        <input value="{{$LcAndLgAgainstPo->beneficiary_name}}" disabled="true" name="beneficiary_name" id="beneficiary_name" class="form-control" type="text">
                                                        <input value="{{$LcAndLgAgainstPo->beneficiary_id}}" disabled="true" name="beneficiary_id" id="beneficiary_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Beneficiary Full address </label>
                                                            <textarea value="" disabled="true" name="beneficiary_full_address" cols="30" >{{$LcAndLgAgainstPo->beneficiary_full_address}}</textarea>
                                                    </div>
                                                </div>
                                        </div>
                                        
                                        <div class="row">
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Advising Bank </label>
                                                        
                                                        <input value="{{$LcAndLgAgainstPo->advising_bank}}" disabled="true" name="advising_bank" id="advising_bank" class="form-control" type="text">
                                                        <input value="{{$LcAndLgAgainstPo->advising_bank_id}}" disabled="true" name="advising_bank_id" id="advising_bank_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Account No </label>
                                                        <input value="{{$LcAndLgAgainstPo->advising_bank_account_no}}" disabled="true" name="advising_bank_account_no" id="advising_bank_account_no" class="form-control" type="text">

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Swift code </label>
                                                        <input value="{{$LcAndLgAgainstPo->advising_bank_swift_code}}" disabled="true" name="advising_bank_swift_code" id="advising_bank_swift_code" class="form-control" type="text">

                                                    </div>
                                                </div>
            
                                        </div>
                                        
                                        <div class="row">
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Inter mediary Bank</label>
                                                        
                                                        <input value="{{$LcAndLgAgainstPo->inter_mediary_bank}}" disabled="true" name="inter_mediary_bank" id="inter_mediary_bank" class="form-control" type="text">
                                                        <input value="{{$LcAndLgAgainstPo->inter_mediary_bank_id}}" disabled="true" name="inter_mediary_bank_id" id="inter_mediary_bank_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Account No </label>
                                                        <input value="{{$LcAndLgAgainstPo->inter_mediary_bank_account_no}}" disabled="true" name="inter_mediary_bank_account_no" id="inter_mediary_bank_account_no" class="form-control" type="text">

                                                    </div>
                                                </div>

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Swift code </label>
                                                        <input value="{{$LcAndLgAgainstPo->inter_mediary_bank_swift_code}}" disabled="true" name="inter_mediary_bank_swift_code" id="inter_mediary_bank_swift_code" class="form-control" type="text">

                                                    </div>
                                                </div>
            
                                        </div>
                                        
                                        <div class="row">
                                            
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Currency</label>
                                                        
                                                        <input value="{{$LcAndLgAgainstPo->Currency}}" disabled="true" name="Currency" id="Currency" class="form-control" type="text">
                                                        <input value="{{$LcAndLgAgainstPo->Currency_id}}" disabled="true" name="Currency_id" id="Currency_id" class="form-control" type="hidden">
                                                       
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Amount</label>
                                                        <input value="{{$LcAndLgAgainstPo->amount}}" disabled="true" name="amount" id="d_t_amount_1" class="form-control" type="text">

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
                                                        <input value="{{$LcAndLgAgainstPo->fob}}" disabled="true" name="fob" id="fob" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">CFR</label>
                                                        <input value="{{$LcAndLgAgainstPo->cfr}}" disabled="true" name="cfr" id="cfr" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">CPT</label>
                                                        <input value="{{$LcAndLgAgainstPo->cpt}}" disabled="true" name="cpt" id="cpt" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Sight</label>
                                                        <input value="{{$LcAndLgAgainstPo->sight}}" disabled="true" name="sight" id="sight" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                        </div>

                                        <div class="row">
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Shipment From</label>
                                                        <input value="{{$LcAndLgAgainstPo->shipment_from}}" disabled="true" name="shipment_from" id="shipment_from" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Shipment To</label>
                                                        <input value="{{$LcAndLgAgainstPo->shipment_to}}" disabled="true" name="shipment_to" id="shipment_to" class="form-control" type="text">

                                                    </div>
                                                </div>
                                        
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Latest Shipment date</label>
                                                        <input value="{{$LcAndLgAgainstPo->latest_shipment_date}}" disabled="true" name="latest_shipment_date" id="latest_shipment_date" class="form-control" type="date">

                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label class="control-label">Expirty Date</label>
                                                        <input value="{{$LcAndLgAgainstPo->expirty_date}}" disabled="true" name="expirty_date" id="expirty_date" class="form-control" type="date">

                                                    </div>
                                                </div>
                                        
                                        </div>

                                        <div class="row">
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Days from</label>
                                                        <input value="{{$LcAndLgAgainstPo->days_from}}" disabled="true" name="days_from" id="days_from" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">BL DATE</label>
                                                        <input value="{{$LcAndLgAgainstPo->bl_date}}" disabled="true" name="bl_date" id="bl_date" class="form-control" type="date">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Delivery Type</label>
                                                        <br>
                                                        <label for="">Partail shipment </label>
                                                        <input value="{{$LcAndLgAgainstPo->delivery_type}}" disabled="true" name="delivery_type"  id="deleivery_type" class="" type="radio" value="partail_shipment" >
                                                        <label for="">Transhipment </label>
                                                        <input value="{{$LcAndLgAgainstPo->delivery_type}}" disabled="true" name="delivery_type"  id="deleivery_type" class="" type="radio" value="transhipment" >

                                                    </div>
                                                </div>
                                        </div>        

                                        <div class="row">
                                                
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Origin</label>
                                                        <input value="{{$LcAndLgAgainstPo->origin}}" disabled="true" name="origin" id="origin" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Hs Code</label>
                                                        <input value="{{$LcAndLgAgainstPo->hs_code}}" disabled="true" name="hs_code"  step="any"  id="hs_code" class="form-control" type="text">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label class="control-label">Insurance</label>
                                                        <input value="{{$LcAndLgAgainstPo->insurance}}" disabled="true" name="insurance" id="insurance" class="form-control" type="text">
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
                                                        @foreach($LcAndLgAgainstPoData as $key => $value)    
                                                            <tr>
                                                                <td>{{ $counter1 }}</td>
                                                                <td>
                                                                    {{ CommonHelper::getCompanyDatabaseTableValueById(Session::get('run_company'),'subitem','sub_ic',$value->item_id) }}
                                                                </td>
                                                                <td>
                                                                    {{ $value->qty }}
                                                                </td>
                                                                <td>
                                                                    {{  ImportHelper::get_uom_name($value->item_id) }}
                                                                </td>
                                                                <td>
                                                                    {{ $value->rate }}
                                                                </td>
                                                                <td>
                                                                    {{ $value->total_amount }}
                                                                </td>
                                                                <td> 
                                                                    {{ $value->hs_code_amount }}
                                                                </td>

                                                            </tr>
                                                            @php
                                                                $counter1 ++;
                                                            @endphp
                                                        @endforeach    
                                                        </tbody>
                                                    </table>    

                                                </div>
                                            </div>
                                        </div>
                                  
<script>
        toWords(1);

</script>