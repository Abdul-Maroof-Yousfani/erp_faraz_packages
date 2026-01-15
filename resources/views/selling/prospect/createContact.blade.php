<form id="subm" action="{{route('contactStore')}}" method="post">
    <div class="alert hide alert-success">

    </div>
    
    <div class="alert alert-danger hide  print-error-msg">
        <ul></ul>
    </div>
<div class="row">
    <div class="col-md-6">
        <div>
            <label for="">First Name</label>
            <input type="text" name="first_name" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Last Name</label>
            <input type="text" name="last_name" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Gender</label>
            <select name="gender" id="" class="form-control">
                <option value="1">Male</option>
                <option value="2">Female</option>
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Personal Title</label>
            <input type="text" name="title" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Cell</label>
            <input type="text" name="cell" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Phone</label>
            <input type="text" name="phone" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Email</label>
            <input type="text" name="email" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Website</label>
            <input type="text" name="website" class="form-control">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Job Title</label>
            <div class="pos-rel">
            <select name="job_title" id="job_title" class="form-control">
                <option value="1">Admin</option>
                <option value="2">Accountant</option>
                <option value="3">IT</option>
               
            </select>
            <a href="#" class="btn-add" onclick="createJobtitle('createJobtitle','','Add Job Title','')">+</a>
        </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary mr-1">Submit</button>
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
</div>
</form>
<script>
    $(document).ready(function(){
        getJobtitle();
        });
        function getJobtitle()
        {
var my ='';
            $.ajax({
                url: '<?php echo url('/')?>/getJobtitle',
                type: 'Get',
                processData: false,  
                contentType: false,
                data: {my:my},
             success: function (data) {
                html = '';
                // Assuming data is an array of objects with 'id' and 'first_name' properties
                data.forEach(function (contact) {
                    html +=`'<option value="${contact.id}">${contact.name}</option>`;
                    
                });
                $('#job_title').empty('');
                $('#job_title').append(html);

               
            }
            });


        }
</script>