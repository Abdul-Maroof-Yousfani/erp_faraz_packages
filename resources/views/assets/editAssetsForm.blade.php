<?php 
$counter = 0;
?>
@include('select2')
<form id="submit" action="{{ route('edit-assets') }}" method="post">
    <div class="mt">
        <input type="hidden" name="ajaxLoadUrl" id="ajaxLoadUrl" value="{{ route('assets-list') }}" />
        <input type="hidden" name="modalId" id="modalId" value="showModal" />
        <input type="hidden" name="flag" id="flag" value="1" />
        <input type="hidden" name="id" id="id" value="{{ $assets->id }}" />
        <div class="row">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <h6 class="fieldsTopHeading">Premise Details</h6>
                        </div>
                        <div class="col-ld-9 col-md-9 col-sm-9 col-xs-12">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Premise Name</label>
                                    <select name="premise_id" id="premise_id" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($premises as $value)
                                            <option @if($assets->premise_id == $value->id) selected @endif value="{{ $value->id }}" data-target="{{ $value->project_id }}">{{ $value->premises_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Floor</label>
                                    <select name="floor_id" id="floor_id" class="form-control" required>
                                        <option value="">Select Option</option>
                                        @foreach($floors as $value)
                                            <option @if($assets->floor_id == $value->id) selected @endif value="{{ $value->id }}">{{ $value->floor }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Department</label>
                                    <select name="department_id" id="department_id" class="form-control" required>
                                        <option value="">Select Option</option>
                                        @foreach($departments as $value)
                                            <option @if($assets->department_id == $value->id) selected @endif value="{{ $value->id }}">{{ $value->department_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="border-color: #AEAEAE;">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <h6 class="fieldsTopHeading">Asset Details</h6>
                        </div>
                        <div class="col-ld-9 col-md-9 col-sm-9 col-xs-12">
                            <div class="row">
                            <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label for="">Asset Code</label>
                                    <input type="text" class="form-control" name="asset_code" id="asset_code" required value="{{ $assets->asset_code }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label for="">Asset Name</label>
                                    <input type="text" class="form-control requiredField" name="asset_name" id="asset_name" value="{{ $assets->asset_name }}" />
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label class="set-j">Category
                                        <span class="add-btt">
                                        <a onclick="showMasterEditModal('create-category','','Create Category','')">+</a>
                                    </span>
                                    </label>
                                    <select name="category_id" id="category_id" class="form-control" onchange="getSubCategoryData(this.value)" required>
                                        <option value="">Select Option</option>
                                        @foreach($categories as $value)
                                            <option @if($assets->category_id == $value->id) selected @endif value="{{ $value->id }}">{{ $value->category_name }}</option>
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
                                        @foreach($sub_categories as $value)
                                            <option @if($assets->sub_category_id == $value->id) selected @endif value="{{ $value->id }}" data-target="{{ $value->category_id }}">{{ $value->sub_category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label for="">Manufacturer</label>
                                    <select name="manufacturer_id" id="manufacturer_id" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($manufacturers as $value)
                                            <option @if($assets->manufacturer_id == $value->id) selected @endif value="{{ $value->id }}">{{ $value->manufacturer_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                    <label for="">Installed Date</label>
                                    <input type="date" name="installed_date" id="installed_date" class="form-control" value="{{ $assets->installed_date }}" />
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <label for="">Details</label>
                                    <textarea name="asset_detail_description" id="asset_detail_description" class="form-control" >{{ $assets->asset_detail_description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr style="border-color: #AEAEAE;">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <h6 class="fieldsTopHeading">Depreciation</h6>
                        </div>
                        <div class="col-ld-9 col-md-9 col-sm-9 col-xs-12">
                            <div class="row">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Depreciation Method</label>
                                    <select name="depreciation_method" id="depreciation_method" class="form-control">
                                        <option value="">Select Option</option>
                                        <option @if($assets->depreciation_method == 1) selected @endif value="1">Straight Line</option>
                                        <option @if($assets->depreciation_method == 2) selected @endif value="2">Reducing Balance Method</option>
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Purchased Date</label>
                                    <input type="date" name="purchased_date" id="purchased_date" class="form-control" value="{{ $assets->purchased_date }}" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Purchase Price</label>
                                    <input type="number" step="any" name="purchase_price" id="purchase_price" class="form-control" value="{{ $assets->purchase_price }}" />
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Useful Life</label>
                                    <select name="useful_life_id" id="useful_life_id" class="form-control">
                                        <option value="">Select Option</option>
                                        @foreach($life as $value)
                                            <option @if($assets->useful_life_id == $value->id) selected @endif value="{{ $value->id }}">{{ $value->useful_life_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <label for="">Depreciation Per Yer</label>
                                    <input type="text" name="depreciation" id="depreciation" class="form-control" value="{{ $assets->depreciation }}" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr style="border-color: #AEAEAE;">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                            <h6 class="fieldsTopHeading">Preventive Maintenance</h6>
                        </div>
                        <div class="col-ld-9 col-md-9 col-sm-9 col-xs-12">
                            @foreach($assets->assetPreventive as $key => $value)
                                <div class="row" id="sectionAddMore_{{ $key }}">
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label for="">Start / Last PM Date</label>
                                        <input type="date" name="last_pm_date[]" id="last_pm_date_{{ $key }}" class="form-control" onchange="generateNextPpmDate('{{ $key}}')" value="{{ $value->last_pm_date }}" />
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                        <label for="">PM Frequency</label>
                                        <select name="pm_frequency_id[]" id="pm_frequency_id_{{ $key }}" class="form-control" onchange="generateNextPpmDate('{{ $key}}')">
                                            <option value="">Select Option</option>
                                            @foreach($frequencies as $value2)
                                                <option @if($value->pm_frequency_id == $value2->id) selected @endif value="{{ $value2->id }}">{{ $value2->frequency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
                                        <label for="">Next PM Date</label>
                                        <input type="date" name="next_pm_date[]" id="next_pm_date_{{ $key }}" class="form-control" value="{{ $value->next_pm_date }}" />
                                    </div>
                                    <div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">
                                        <button href="#" onclick="removeAddMoreSection('{{ $key }}')" class="btn-modal" style="margin-top: 40px;">X</button>
                                    </div>
                                </div>
                                <?php $counter = $key; ?>
                                <script> $('#pm_frequency_id_{{ $key }}').select2(); </script>
                            @endforeach
                            
                            <div id="addMoreSection"></div>
                            <div class="row">
                                <div class="col-ld-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="button" class="btn btn-primary" onclick="addMoreRow()" style="float: right">Add More</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="loader"></div>
            <div class="row">&nbsp;</div>
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-center">
                    <button type="submit" class="btn btn-primary ">Submit</button>
                    <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
                </div>
            </div>
        </div>
    </div>
</form>
<script src="{{ URL::asset('assets/custom/js/customFunctions.js') }}"></script>
<script>
    $('#premise_id').select2();
    $('#project_id').select2();
    $('#floor_id').select2();
    $('#asset_status').select2();
    $('#sub_category_id').select2();
    $('#category_id').select2();
    $('#manufacturer_id').select2();
    $('#condition_id').select2();
    $('#risk_likelihood_id').select2();
    $('#useful_life_id').select2();
    $('#department_id').select2();
 

    function getPremiseData(project_id) {
        if(project_id) {
            $('#loader').html('<div class="loading"></div>')
            $.ajax({
                type: "get",
                url: '{{ url('/') }}/getPremiseData',
                data: { project_id: project_id}, // serializes the form's elements.
                success: function(data) {
                    $('#loader').html('')
                    $('#premise_id').html(data);
                }
            });
        }
    }

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
        if(n <= 9) {
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

    var counter = '{{ $counter }}';
    function addMoreRow() {
        counter++;
        $('#addMoreSection').append('<div class="row" id="sectionAddMore_'+counter+'"><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">' +
        '<label for="">Start / Last PM Date</label><input type="date" name="last_pm_date[]" id="last_pm_date_'+counter+'" class="form-control" onchange="generateNextPpmDate('+counter+')">' +
        '</div><div class="col-lg-4 col-md-4 col-sm-4 col-xs-12"><label for="">PM Frequency</label>' +
        '<select name="pm_frequency_id[]" id="pm_frequency_id_'+counter+'" class="form-control" onchange="generateNextPpmDate('+counter+')">' +
        '<option value="">Select Option</option>@foreach($frequencies as $value)<option value="{{ $value->id }}">{{ $value->frequency }}</option>' +
        '@endforeach</select></div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><label for="">Next PM Date</label>' +
        '<input type="date" name="next_pm_date[]" id="next_pm_date_'+counter+'" class="form-control"></div><div class="col-lg-1 col-md-1 col-sm-1 col-xs-12">' +
        '<button href="#" onclick="removeAddMoreSection('+counter+')" class="btn-modal" style="margin-top: 40px;">X</button></div></div>');

        $('#pm_frequency_id_'+counter+'').select2();
    }

    function removeAddMoreSection(id) {
        var elem = document.getElementById('sectionAddMore_'+id+'');
        elem.parentNode.removeChild(elem);
    }
</script>