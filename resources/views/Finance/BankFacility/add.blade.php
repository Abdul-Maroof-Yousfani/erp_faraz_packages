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
            <div class="headquid">
                <h2 class="subHeadingLabelClass">Create Bank Facility Form</h2>
            </div>
            <div class="row">
                <?php echo Form::open(array('url' => 'finance/bankFacility?m='.$m.''));?>
                    <input type="hidden" name="pageType" value="<?php echo $_GET['pageType'] ?? '' ?>">
                    <input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode'] ?? '' ?>">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="meinti">
                            <ul>
                                <li>
                                    <div class="sellab">
                                        <lable>Bank Name</lable>
                                        <select class="form-control requiredField" required name="bank_id" id="bank_id" onchange="loadBankFacililtyDetailForm()">
                                            <option value="">Select Bank Name</option>
                                            @foreach ($bankList as $blRow)
                                            <option value="{{ $blRow->id }}">{{ $blRow->bank_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div class="sellab">
                                        <lable>Facility Name</lable>
                                        <input type="text" name="facility_name" id="facility_name" required class="form-control requiredField" />
                                    </div>
                                </li>
                                <li class="hidden">
                                    <div class="sellab">
                                        <lable>From Days</lable>
                                        <input type="number" name="from_days" id="from_days" step="any" required class="form-control requiredField" value="0" />
                                    </div>
                                </li>
                                <li>
                                    <div class="sellab">
                                        <lable>No Of Days</lable>
                                        <input type="number" name="to_days" id="to_days" step="any" required class="form-control requiredField" />
                                    </div>
                                </li>
                            </ul>
                            <ul>
                                <li>
                                    <div class="sellab">
                                        <lable>Interest Expense</lable>
                                        <select name="calculate_days_type" id="calculate_days_type" class="form-control" required>
                                            <option value="1">No of Days</option>
                                            <option value="2">Maturity Days</option>
                                        </select>
                                    </div>
                                </li>
                                <li>
                                    <div class="sellab">
                                        <lable>Bank Limit</lable>
                                        <input type="number" name="loan_amount" id="loan_amount" required step="any" class="form-control requiredField" />
                                    </div>
                                </li>
                                <li class="hidden">
                                    <div class="sellab">
                                        <lable>Interest Rate</lable>
                                        <input type="number" name="interest_rate" id="interest_rate" value="0" required step="any" class="form-control requiredField" />
                                    </div>
                                </li>
                                <li>
                                    <div class="sellab">
                                        <lable>Funded / Non-Funded</lable>
                                        <select class="form-control requiredField" required name="bank_type_id" id="bank_type_id">
                                            <option value="1">Funded</option>
                                            <option value="2">Non-Funded</option>
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
                            <div class="btus addblatebut">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success','id'=> 'BtnSubmit']) }}
                                <button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="loadBankFacililtyDetailForm"></div>
                <?php echo Form::close();?>
            </div>
        </div>
    </div>
    <script>        
        function loadBankFacililtyDetailForm(){


            var bank_id = $('#bank_id').val();
            if(bank_id == ''){
                alert('Please Select Bank Name');
                $('.loadBankFacililtyDetailForm').html('');
            }else{
                $('.loadBankFacililtyDetailForm').html('<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div>');
                $.ajax({
                    url: '<?php echo url('/')?>/finance/loadBankFacililtyDetailForm',
                    type: "GET",
                    data: { bank_id:bank_id},
                    success:function(data) {
                        $('.loadBankFacililtyDetailForm').html(data);
                    }
                });
            }
        }
    </script>
    <script src="{{ URL::asset('assets/js/select2/js_tabindex.js') }}"></script>


@endsection