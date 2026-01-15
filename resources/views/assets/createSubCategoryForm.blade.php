<div class="cus-padbody">
    {{ Form::open(array('url' => 'add-sub-category-on-assets', 'id' => 'subCategoryForm', 'method' => 'post')) }}
    <div class="row">
        <div class="col-md-6">
            <label for="">Category Name</label>
            <select name="category_id" id="category_id_1" class="form-control" required>
                <option value="">Select Option</option>
                @foreach($categories as $value)
                    <option value="{{ $value->id }}">{{ $value->category_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <label for="">Sub Category Name</label>
            <input type="text" name="sub_category_name" id="sub_category_name" class="form-control">
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 printListBtn text-center">
            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
            <button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
        </div>
    </div>
    {{ Form::close() }}
</div>

<script>
    $('#category_id_1').select2();

    $('#subCategoryForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting in the traditional way
        // Perform an AJAX request to submit the form data
        $.ajax({
            url: $('#subCategoryForm').attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var subCategoryDropdown = $('#sub_category_id');
                subCategoryDropdown.append('<option value="' + response.id + '" data-target="' + response.category_id + '">' + response.sub_category_name + '</option>');
                $('#showMasterEditModal').modal('hide');
            },
            error: function(xhr, status, error) {
                swalError();
            }
        });
    });
</script>