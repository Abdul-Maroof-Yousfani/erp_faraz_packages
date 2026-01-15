<form id="submit2" action="{{ route('edit-category') }}" method="post">
    <div class="mt">
        <input type="hidden" name="ajaxLoadUrl" id="ajaxLoadUrl" value="{{ route('master-items-list') }}" />
        <input type="hidden" name="modalId" id="modalId" value="showMasterEditModal" />
        <input type="hidden" name="flag" id="flag" value="2" />
        <input type="hidden" name="id" id="id" value="{{ $data->id }}" />
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="">Category Name</label>
                <input type="text" class="form-control" name="category_name" id="category_name" value="{{ $data->category_name }}" required />
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                <label for="">Category Abbreviation</label>
                <input type="text" class="form-control" name="category_abbreviation" id="category_abbreviation" value="{{ $data->category_abbreviation }}" />
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