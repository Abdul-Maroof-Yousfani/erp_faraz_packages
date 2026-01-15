<form id="subm" action="{{route('JobtitleStore')}}" method="post">
    <div class="alert hide alert-success">

    </div>
    
    <div class="alert alert-danger hide  print-error-msg">
        <ul></ul>
    </div>
<div class="row">
    <div class="col-md-12">
        <div>
            <label for="">Title </label>
            <input type="text" name="name" class="form-control">
        </div>
    </div>
   
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary mr-1">Submit</button>
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
</div>
</form>
