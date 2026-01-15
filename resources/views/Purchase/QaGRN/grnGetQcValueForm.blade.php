<div class="col-md-12 padt">
    <div class="col-md-12">
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Actual Qty</label>
                    <div class="col-sm-8">
                        <input type="number" name="actual_qty" id="actual_qty" class="form-control" 
                            value="{{ $actual_qty }}" step="0.01" readonly>
                        <input type="hidden" name="grn_data_id" value="{{ $grn_data_id }}">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label class="col-sm-4 control-label">Accepted Qty <span style="color: red;">*</span></label>
                    <div class="col-sm-8">
                        <input type="number" name="tested_qty" id="tested_qty" class="form-control" 
                            value="{{ $tested_qty }}" step="0.01" min="0" max="{{ $actual_qty }}" required 
                            onchange="calculateReturnQty()">
                        <small class="text-muted">Difference: <span id="return_qty_display">0</span></small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table">
            <tr>
                <th class="text-center"><input type="checkbox" onclick="checkedCheckBox(this)" id="check_all"></th>
                <th class="text-center">S.No</th>
                <th class="text-center">Test Name</th>
                <th class="text-center">ZCI Standard Value</th>
                <th class="text-center">Test Value</th>
                <th class="text-center">Test Status</th>
                <th class="text-center">Test Type</th>
                <th class="text-center">Remarks</th>
            </tr>
            <tbody id="more_details">
                @foreach($qc_values as $key => $test)
                    <tr>
                        <td class="text-center">
                            <input type="hidden" name="qa_test_id[]" id="" value="{{$test->qa_test_id}}">
                            @php
                                $isChecked = isset($existing_qc_data[$test->qa_test_id]) ? 1 : 0;
                                $existingData = isset($existing_qc_data[$test->qa_test_id]) ? $existing_qc_data[$test->qa_test_id] : [];
                                $existingTestValue = isset($existingData['test_value']) ? $existingData['test_value'] : '';
                                $existingTestStatus = isset($existingData['test_status']) ? $existingData['test_status'] : '';
                                $existingTestType = isset($existingData['test_type']) ? $existingData['test_type'] : '';
                                $existingRemarks = isset($existingData['remarks']) ? $existingData['remarks'] : '';
                            @endphp
                            <input type="checkbox" class="checkbox" onclick="setValueOnCheckBox(this,{{$test->qa_test_id}})"
                                name="checkBox{{$test->id}}" value="{{$test->id}}" data-test-id="{{$test->qa_test_id}}" {{ $isChecked ? 'checked' : '' }}>
                            <input type="hidden" class="checkbox" id="checkBox{{$test->qa_test_id}}" name="checkBox{{$test->qa_test_id}}"
                                value="{{ $isChecked }}">
                        </td>
                        <td class="text-center">{{$key+1}}</td>
                        <td>{{$test->name}}</td>
                        <td class="text-center">
                            <input type="text" name="standard_value{{$test->qa_test_id}}" id="standard_value{{$test->qa_test_id}}"
                                class="form-control" value="{{ $test->standard_value }}" readonly />
                        </td>
                        <td class="text-center">
                            <input type="text" name="test_value{{$test->qa_test_id}}" id="test_value{{$test->qa_test_id}}"
                                class="form-control test-value-field" value="{{ $existingTestValue }}" {{ $isChecked ? 'required' : '' }} />
                        </td>
                        <td class="text-center">
                            <select name="test_status{{$test->qa_test_id}}" id="test_status{{$test->qa_test_id}}" class="form-control test-status-field" {{ $isChecked ? 'required' : '' }}>
                                <option value="">Select Status</option>
                                <option value="ok" {{ $existingTestStatus == 'ok' ? 'selected' : '' }}>OK</option>
                                <option value="not ok" {{ $existingTestStatus == 'not ok' ? 'selected' : '' }}>Not OK</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <select name="test_type{{$test->qa_test_id}}" id="test_type{{$test->qa_test_id}}" class="form-control test-type-field" {{ $isChecked ? 'required' : '' }}>
                                <option value="">Select Type</option>
                                <option value="Physical" {{ $existingTestType == 'Physical' ? 'selected' : '' }}>Physical</option>
                                <option value="Mechanical" {{ $existingTestType == 'Mechanical' ? 'selected' : '' }}>Mechanical</option>
                                <option value="Chemical" {{ $existingTestType == 'Chemical' ? 'selected' : '' }}>Chemical</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <textarea name="remarks{{$test->qa_test_id}}" id="remarks{{$test->qa_test_id}}"
                                class="form-control remarks-field" rows="2" {{ $isChecked ? 'required' : '' }}>{{ $existingRemarks }}</textarea>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function calculateReturnQty() {
        var actual_qty = parseFloat($('#actual_qty').val()) || 0;
        var tested_qty = parseFloat($('#tested_qty').val()) || 0;
        var return_qty = actual_qty - tested_qty;
        
        if (return_qty < 0) {
            return_qty = 0;
            $('#tested_qty').val(actual_qty);
        }
        
        $('#return_qty_display').text(return_qty.toFixed(2));
        
        // Update hidden field if exists
        if ($('#return_qty').length) {
            $('#return_qty').val(return_qty);
        }
    }
    
    // Calculate on page load
    $(document).ready(function() {
        calculateReturnQty();
    });
</script>