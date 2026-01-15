@include('select2')
<form id="submit" action="" method="post">
    <div class="mt">
        <input type="hidden" name="ajaxLoadUrl" id="ajaxLoadUrl" value="{{ route('assets-list') }}">
        <input type="hidden" name="modalId" id="modalId" value="showModal">
        <input type="hidden" name="flag" id="flag" value="1">
        <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label for="">Premise Name</label>
                <select name="premise_id_search" id="premise_id_search" class="form-control">
                    <option value="">Select Option</option>
                    @foreach($premises as $value)
                        <option value="{{ $value->id }}" data-target="{{ $value->project_id }}">{{ $value->premises_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="set-j">Category </label>
                <select name="category_id_search" id="category_id_search" class="form-control" onchange="getSubCategoryData(this.value)">
                    <option value="">Select Option</option>
                    @foreach($categories as $value)
                        <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                <label class="set-j">Sub Category</label>
                <select name="sub_category_id_search" id="sub_category_id_search" class="form-control">
                    <option value="">Select Option</option>
                    @foreach($sub_categories as $value)
                        <option value="{{ $value->id }}" data-target="{{ $value->category_id }}">{{ $value->sub_category_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="row">&nbsp;</div>
    <div class="mt text-center">
        <button type="submit" class="btn btn-primary mr-1">Search</button>
    </div>
</form>
<script src="{{ URL::asset('assets/custom/js/customFunctions.js') }}"></script>
<script>
        $('#premise_id_search').select2();
        $('#sub_category_id_search').select2();
        $('#category_id_search').select2();

        function getSubCategoryData(category_id) {
            if(category_id) {
                $('#loader').html('<div class="loading"></div>')
                $.ajax({
                    type: "get",
                    url: '{{ url('/') }}/getSubCategoryData',
                    data: { category_id: category_id}, // serializes the form's elements.
                    success: function(data) {
                        $('#loader').html('')
                        $('#sub_category_id_search').html(data);
                    }
                });
            }
        }
</script>