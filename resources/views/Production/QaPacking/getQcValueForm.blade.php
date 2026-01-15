<div class="col-md-12 padt">
    <div class="col-md-12">
        <table class="table">
            <tr>
                <th class="text-center"><input type="checkbox" onclick="checkedCheckBox(this)" id="check_all"></th>
                <th class="text-center">S.No</th>
                <th class="text-center">Test Name</th>
                <th class="text-center">ZCI Standard Value</th>
                <th class="text-center">Value</th>
                <th class="text-center">Status</th>
            </tr>
            <tbody id="more_details">
                @foreach($qc_values as $key => $test)
                    <tr>
                        <td class="text-center">
                            <input type="hidden" name="qc_test_id[]" id="" value="{{$test->qa_test_id}}">
                            @php
                                $isChecked = isset($existing_qc_data[$test->qa_test_id]) ? 1 : 0;
                                $existingValue = isset($existing_qc_data[$test->qa_test_id]) ? $existing_qc_data[$test->qa_test_id] : '';
                                $existingStatus = isset($existing_qc_status[$test->qa_test_id]) ? $existing_qc_status[$test->qa_test_id] : '';
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
                                class="form-control test-value-field" value="{{ $existingValue }}" {{ $isChecked ? 'required' : '' }} />
                        </td>
                        <td>
                            <select name="test_status{{$test->qa_test_id}}" id="test_status{{$test->qa_test_id}}" class="form-control test-status-field" {{ $isChecked ? 'required' : '' }}>
                                <option value="ok" {{ empty($existingStatus) || $existingStatus == 'ok' ? 'selected' : '' }}>OK </option>
                                <option value="not ok" {{ $existingStatus == 'not ok' ? 'selected' : '' }}>Not OK </option>
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>