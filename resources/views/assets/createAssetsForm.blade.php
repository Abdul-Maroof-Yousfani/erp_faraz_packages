@extends('layouts.default')
@section('content')
    @include('select2')
    <div class="well_N">
        <div class="dp_sdw">
            <div class="panel">
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="headquid">
                                <h2 class="subHeadingLabelClass">Create Asset</h2>
                            </div>
                        </div>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    {{ Form::open(array('url' => 'add-assets')) }}
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Premise Details</h6>
                                    </div>
                                    <div class="col-ld-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Premise Name</label>
                                                <select name="premise_id" id="premise_id" class="form-control">
                                                    <option value="">Select Option</option>
                                                    @foreach($premises as $value)
                                                        <option value="{{ $value->id }}">{{ $value->premises_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Floor</label>
                                                <select name="floor_id" id="floor_id" class="form-control" required>
                                                    <option value="">Select Option</option>
                                                    @foreach($floors as $value)
                                                        <option value="{{ $value->id }}">{{ $value->floor }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Department</label>
                                                <select name="department_id" id="department_id" class="form-control" required>
                                                    <option value="">Select Option</option>
                                                    @foreach($departments as $value)
                                                        <option value="{{ $value->id }}">{{ $value->department_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Asset Details</h6>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Asset Code</label>
                                                <input type="text" class="form-control" name="asset_code" id="asset_code" required />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Asset Name</label>
                                                <input type="text" class="form-control requiredField" name="asset_name" id="asset_name" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Status</label>
                                                <select name="asset_status" id="asset_status" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="In Use">In Use</option>
                                                    <option value="Not In Use">Not In Use</option>
                                                    <option value="Removed">Removed</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="set-j">Category
                                                    <span class="add-btt">
                                                        <a onclick="showMasterEditModal('create-category','','Create Category','')">+</a>
                                                    </span>
                                                </label>
                                                <select name="category_id" id="category_id" class="form-control" onchange="getSubCategoryData(this.value)" required>
                                                    <option value="">Select Option</option>
                                                    @foreach($categories as $value)
                                                        <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label class="set-j">Sub Category
                                                    <span class="add-btt">
                                                        <a onclick="showMasterEditModal('create-sub-category','','Create Sub Category','')">+</a>
                                                    </span>
                                                </label>
                                                <select name="sub_category_id" id="sub_category_id" class="form-control">
                                                    <option value="">Select Option</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Manufacturer</label>
                                                <select name="manufacturer_id" id="manufacturer_id" class="form-control">
                                                    <option value="">Select Option</option>
                                                    @foreach($manufacturers as $value)
                                                        <option value="{{ $value->id }}">{{ $value->manufacturer_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Installed Date</label>
                                                <input type="date" name="installed_date" id="installed_date" class="form-control" />
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                <label for="">Details</label>
                                                <textarea name="asset_detail_description" id="asset_detail_description" class="form-control" ></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Depreciation</h6>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Depreciation Method</label>
                                                <select name="depreciation_method" id="depreciation_method" class="form-control">
                                                    <option value="">Select Option</option>
                                                    <option value="1">Straight Line</option>
                                                    <option value="2">Reducing Balance Method</option>
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Purchased Date</label>
                                                <input type="date" name="purchased_date" id="purchased_date" class="form-control" />
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Purchase Price</label>
                                                <input type="number" step="any" name="purchase_price" id="purchase_price" class="form-control">
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Useful Life</label>
                                                <select name="useful_life_id" id="useful_life_id" class="form-control">
                                                    <option value="">Select Option</option>
                                                    @foreach($life as $value)
                                                        <option value="{{ $value->id }}">{{ $value->useful_life_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                                <label for="">Depreciation Per Yer</label>
                                                <input type="text" name="depreciation" id="depreciation" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <hr style="border-color: #AEAEAE;">
                                <div class="row">
                                    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
                                        <h6 class="fieldsTopHeading">Preventive Maintenance</h6>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
                                        <div class="row">
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Start / Last PM Date</label>
                                                <input type="date" name="last_pm_date[]" id="last_pm_date_1" class="form-control" onchange="generateNextPpmDate('1')">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">PM Frequency</label>
                                                <select name="pm_frequency_id[]" id="pm_frequency_id_1" class="form-control" onchange="generateNextPpmDate('1')">
                                                    <option value="">Select Option</option>
                                                    @foreach($frequencies as $value)
                                                        <option value="{{ $value->id }}">{{ $value->frequency }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <label for="">Next PM Date</label>
                                                <input type="date" name="next_pm_date[]" id="next_pm_date_1" class="form-control">
                                            </div>
                                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                                <button type="button" class="btn btn-primary" onclick="addMoreRow()" style="margin-top: 40px;">Add More</button>
                                            </div>
                                        </div>
                                        <div id="addMoreSection"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="loader"></div>
                        <div class="row">&nbsp;</div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-right">
                                {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
                                <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#premise_id').select2();
        $('#floor_id').select2();
        $('#sub_category_id').select2();
        $('#category_id').select2();
        $('#manufacturer_id').select2();
        $('#useful_life_id').select2();
        $('#pm_frequency_id_1').select2();
        $('#asset_status').select2();
        $('#department_id').select2();
        $('#useful_life_id').select2();
        $('#depreciation_method').select2();
        
        function getSubCategoryData(category_id) {
            if(category_id) {
                $('#loader').html('<div class="loading"></div>')
                $.ajax({
                    type: "get",
                    url: '{{ url('/') }}/getSubCategoryData',
                    data: { category_id: category_id}, // serializes the form's elements.
                    success: function(data) {
                        $('#loader').html('')
                        $('#sub_category_id').html(data);
                    }
                });
            }
        }

        function appendLeadingZeroes(n){
            if(n <= 9){
                return "0" + n;
            }
            return n
        }

        function generateNextPpmDate(key)
        {
            var last_pm_date = $('#last_pm_date_'+key).val();
            var pm_frequency_id = $("#pm_frequency_id_"+key+" option:selected").html();
            pm_frequency_id = parseInt(pm_frequency_id.split(/[a-zA-Z]/));
            var CurrentDate = new Date(last_pm_date);
            CurrentDate.setMonth((CurrentDate.getMonth()) + pm_frequency_id);
            var next_pm_date = CurrentDate.getFullYear() +'-'+ appendLeadingZeroes(CurrentDate.getMonth() + 1) +'-'+ appendLeadingZeroes(CurrentDate.getDate());

            var today = new Date();
            var now = today.getFullYear()+'-'+appendLeadingZeroes(today.getMonth()+1)+'-'+appendLeadingZeroes(today.getDate());

            if(next_pm_date < now) {
                while(next_pm_date < now) {
                    var last_pm_date2 = new Date(next_pm_date);
                    last_pm_date2.setMonth((last_pp_date2.getMonth()) + pm_frequency_id);
                    next_pm_date = last_pm_date2.getFullYear() +'-'+ appendLeadingZeroes(last_pm_date2.getMonth() + 1) +'-'+ appendLeadingZeroes(last_pm_date2.getDate());
                }
            }

            $('#next_pm_date_'+key).val(next_pm_date);
        }

        var counter = 1;
        function addMoreRow() {
            counter++;
            $('#addMoreSection').append('<div class="row" id="sectionAddMore_'+counter+'"><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
            '<label for="">Start / Last PM Date</label><input type="date" name="last_pm_date[]" id="last_pm_date_'+counter+'" class="form-control" onchange="generateNextPpmDate('+counter+')">' +
            '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label for="">PM Frequency</label>' +
            '<select name="pm_frequency_id[]" id="pm_frequency_id_'+counter+'" class="form-control" onchange="generateNextPpmDate('+counter+')">' +
            '<option value="">Select Option</option>@foreach($frequencies as $value)<option value="{{ $value->id }}">{{ $value->frequency }}</option>' +
            '@endforeach</select></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label for="">Next PM Date</label>' +
            '<input type="date" name="next_pm_date[]" id="next_pm_date_'+counter+'" class="form-control"></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">' +
            '<button href="#" onclick="removeAddMoreSection('+counter+')" class="btn-modal" style="margin-top: 40px;">X</button></div></div>');

            $('#pm_frequency_id_'+counter+'').select2();
        }

        function removeAddMoreSection(id) {
            var elem = document.getElementById('sectionAddMore_'+id+'');
            elem.parentNode.removeChild(elem);
        }
    </script>
@endsection