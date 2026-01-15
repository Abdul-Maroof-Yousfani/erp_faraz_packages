<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//    $m = $_GET['m'];
//}else{
//    $m = Auth::user()->company_id;
//}
$m = $_GET['m'];
?>
    <div class="well">
        <div class="row">
            <?php echo Form::open(array('url' => 'had/editTaxesDetail','id'=>'EOBIform'));?>
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="company_id" value="<?php echo Input::get('m')?>">
            <input type="hidden" name="recordId" value="<?php echo Input::get('id')?>">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="TaxesSection[]" class="form-control" id="sectionEOBI" value="1" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Tax Name:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="tax_name" id="tax_name" value="{{ $tax->tax_name }}" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Salary Range From:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="salary_range_from" id="salary_range_from" value="{{ $tax->salary_range_from }}" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                            <label>Salary Range To:</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="salary_range_to" id="salary_range_to" value="{{ $tax->salary_range_to }}" class="form-control requiredField" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Percentange % of Salary
                                <input @if($tax->tax_mode == 'Percentage') checked @endif type="radio" name="tax_mode" value="Percentage">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                Amount
                                <input @if($tax->tax_mode == 'Amount') checked @endif type="radio" name="tax_mode" value="Amount">
                            </label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="text" name="tax_percent" id="tax_percent" value="{{ $tax->tax_percent }}" class="form-control requiredField" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Tax Month & Year :</label>
                            <span class="rflabelsteric"><strong>*</strong></span>
                            <input type="month" name="tax_month_year" id="tax_month_year" value="{{ $tax->tax_month_year }}" class="form-control requiredField" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="lineHeight">&nbsp;</div>
            <div class="TaxesSection"></div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                    {{ Form::submit('Update', ['class' => 'btn btn-success']) }}
                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                </div>
            </div>
                <?php echo Form::close();?>
        </div>
    </div>



