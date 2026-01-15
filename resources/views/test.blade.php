<form class="form-horizontal" action="http://gudia.sprucenetwork.com/addcsvDetail" method="post" name="upload_excel" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
     <fieldset>

        <!-- Form Name -->
        <legend>Form Name</legend>

        <!-- File Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="filebutton">Select File</label>
            <div class="col-md-4">
                <input type="file" name="file" id="file" class="input-large">
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="singlebutton">Import data</label>
            <div class="col-md-4">
                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
            </div>
        </div>

    </fieldset>
</form>