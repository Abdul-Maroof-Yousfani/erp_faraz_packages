<div class="cus-padbody">
    {{ Form::open(array('url' => 'add-category-on-assets', 'id' => 'categoryForm', 'method' => 'post')) }}
    <div class="row">
        <div class="col-md-12">
            <label for="">Category Name</label>
            <input type="text" name="category_name" id="category_name" class="form-control">
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
    $('#categoryForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting in the traditional way
        // Perform an AJAX request to submit the form data
        $.ajax({
            url: $('#categoryForm').attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                var categoryDropdown = $('#category_id');
                categoryDropdown.append('<option value="' + response.id + '">' + response.category_name + '</option>');
                $('#showMasterEditModal').modal('hide');
            },
            error: function(xhr, status, error) {
                swalError();
            }
        });
    });
</script>