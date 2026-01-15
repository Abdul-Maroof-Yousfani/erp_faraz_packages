<form id="submit2" action="{{ route('edit-sub-category') }}" method="post">
    <div class="mt">
        <input type="hidden" name="ajaxLoadUrl" id="ajaxLoadUrl" value="{{ route('master-items-list') }}" />
        <input type="hidden" name="modalId" id="modalId" value="showMasterEditModal" />
        <input type="hidden" name="flag" id="flag" value="2" />
        <input type="hidden" name="id" id="id" value="{{ $data->id }}" />
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label for="">Category Name</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">Select Option</option>
                    @foreach($categories as $value)
                        <option @if($data->category_id == $value->id) selected @endif  value="{{ $value->id }}">{{ $value->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label for="">Sub Category Name</label>
                <input type="text" class="form-control" name="sub_category_name" id="sub_category_name" value="{{ $data->sub_category_name }}" required />
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label for="">Sub Category Abbreviation</label>
                <input type="text" class="form-control" name="sub_category_abbreviation" id="sub_category_abbreviation" value="{{ $data->sub_category_abbreviation }}" required />
            </div>
        </div>
    </div>
    <div class="row">&nbsp;</div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-center">
            <button type="submit" class="btn btn-primary ">Submit</button>
            <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
        </div>
    </div>
</form>
<script src="{{ URL::asset('assets/custom/js/customFunctions.js') }}"></script>
<script>
    $('#category_id').select2();
</script>