<?php
$accType = Auth::user()->acc_type;
$currentDate = date('Y-m-d');
if($accType == 'client'){
    $m = Session::get('run_company');
}else{
    $m = Auth::user()->company_id;
}
?>
@extends('layouts.default')

@section('content')
    @include('select2')
    <div class="well_N">
        <div class="dp_sdw">    
            <div class="panel">
                <div class="panel-body">
                    <div class="headquid">
						<h2 class="subHeadingLabelClass">Edit Bank Form</h2>
					</div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="row">
                        <?php echo Form::model($data, ['url' => ['finance/bank', $data->id], 'method' => 'PUT']); ?>
                            <input type="hidden" name="pageType" value="<?php echo $_GET['pageType'] ?? '' ?>">
                            <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode'] ?? '' ?>">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="meinti">
                                    <ul>
                                        <li>
                                            <div class="sellab">
                                                <label>Parent Account</label>
                                                <select name="account_id" id="account_id" required class="form-control select2 requiredField">
                                                    <option value="">Select Parent Account</option>
                                                    @foreach($accounts as $key => $row):
                                                        <option @if($row->id == $data->acc_id): selected @endif value="{{ $row->code }}">{{ $row->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sellab">
                                                <lable>Bank Name</lable>
                                                <input type="text" name="bank_name" required id="bank_name" class="form-control requiredField" value="{{ $data->bank_name }}"/>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sellab">
                                                <lable>Account Title</lable>
                                                <input type="text" name="account_title" required id="account_title" class="form-control requiredField"  value="{{ $data->account_title }}"/>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="sellab">
                                                <lable>Account No</lable>
                                                <input type="text" name="account_no" required id="account_no" class="form-control requiredField"  value="{{ $data->account_no }}"/>
                                            </div>
                                        </li>
                                    </ul>
                                    <div class="meinti2">
                                        <ul>
                                       
                           
                                            <li>
                                                <div class="sellab">
                                                    <lable>IBAN No</lable>
                                                    <input type="text" name="iban_no" required id="iban_no" class="form-control requiredField"  value="{{ $data->iban_no }}"/>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sellab">
                                                    <lable>Swift Code</lable>
                                                    <input type="text" name="swift_code" required id="swift_code" class="form-control requiredField"  value="{{ $data->swift_code }}"/>
                                                </div>
                                            </li>
                                   
                                            <li>
                                                <div class="sellab">
                                                    <lable>Bank Address</lable>
                                                    <input type="text" name="bank_address" id="bank_address" required class="form-control requiredField"  value="{{ $data->bank_address }}"/>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sellab">
                                                    <lable>Max Funded Facility</lable>
                                                    <input type="text" name="max_funded_facility" id="max_funded_facility" required class="form-control requiredField"  value="{{ $data->max_funded_facility }}"/>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="sellab">
                                                    <lable>Max Non-Funded Facility</lable>
                                                    <input type="text" name="max_non_funded_facility" id="max_non_funded_facility" required class="form-control requiredField"  value="{{ $data->max_non_funded_facility }}"/>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                                <div class="btus addblatebut">
                                    {{ Form::submit('Update', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) }}
                                    <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                                </div>
                            </div>
                        <?php echo Form::close();?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#account_id').select2();
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection