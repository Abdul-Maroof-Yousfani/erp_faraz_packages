<?php
use App\Helpers\CommonHelper;

?>
@include('select2')

<form id="subm" action="{{route('prospectStore')}}" method="post">
    <div class="alert hide alert-success"></div>
    <div class="alert alert-danger hide  print-error-msg"><ul></ul></div>
        <div class="row">
            <div class="col-md-6">
                <div>
                    <label for="">Company Name</label>
                    <input type="text"  name="company_name" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <label for="">Contact</label>
                    <div class="pos-rel">
                        <select name="contact_id" id="contact_id" class="form-control prospectDD proppectsect"></select>
                        <a href="#" class="btn-add" onclick="createContact('contact/createContact','','Add Contact','')">+</a>
                    </div>
                </div>
            </div>
        </div> 

        <div class="row">
            
            <div class="col-md-6">
                <div>
                    <label for="">Company Address</label>
                    <input type="text" name="company_Address" class="form-control">
                </div>
            </div>
            <div class="col-md-6">
                <div>
                    <label for="">Company Location</label>
                    <select name="company_location" id="" class="form-control prospectDD proppectsect">
                        <option value="">Select Option</option>
                        @foreach(CommonHelper::get_comapny_location()  as $location)
                        <option value="{{$location->id}}">{{$location->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div>
                    <label for="">Company Group</label> 
                    <select name="company_group" id="" class="form-control prospectDD proppectsect">
                        <option value="">Select Option</option>
                        @foreach(CommonHelper::get_company_group()  as $group)
                        <option value="{{$group->id}}">{{$group->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
 

    <div class="modal-footer mt-4">
        <button type="submit" class="btn btn-primary mr-1">Add</button>
        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
    </div>
</form>

<script>
    $(document).ready(function(){
        viewRangeWiseDataFilter();
        });
        function viewRangeWiseDataFilter()
        {
            var my ='';
            $.ajax({
                url: '<?php echo url('/')?>/contact/getContact',
                type: 'Get',
                processData: false,  
                contentType: false,
                data: {my:my},
             success: function (data) {
                console.log(data);
                html = '';
                // Assuming data is an array of objects with 'id' and 'first_name' properties
                data.forEach(function (contact) {
                    html +=`'<option value="${contact.id}">${contact.first_name}</option>`;
                    
                });
                $('#contact_id').empty('');
                $('#contact_id').append(html);

                $('.prospectDD').each(function(){
                  $('.prospectDD').select2();
                });
            }
            });
            
          

        }

       
</script>