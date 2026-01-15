<?php
use App\Helpers\CommonHelper;
// echo "<pre>";
// print_r($ProspectData);
// exit();
?>

<?php echo Form::open(array('url' => 'prospect/editProspectDetail/'.$ProspectData->id.'','id'=>'editProspectDetail'));?>
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
    <div class="alert hide alert-success">

    </div>
    
    <div class="alert alert-danger hide  print-error-msg">
        <ul></ul>
    </div>
<div class="row">
    <div class="col-md-12">
        <div>
            <label for="">Company Name</label>
            <input type="text"  name="company_name" class="form-control" value="{{ $ProspectData->company_name }}">
        </div>
    </div>
    <div class="col-md-12">
        <div>
            <label for="">Contact</label>
            <div class="pos-rel">
                <select name="contact_id" id="contact_id" class="form-control">
                   
                </select>
                <a href="#" class="btn-add" onclick="createContact('contact/createContact','','Add Contact','')">+</a>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div>
            <label for="">Company Address</label>
            <input type="text" name="company_Address" class="form-control" value="{{ $ProspectData->company_address }}">
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Company Location</label>
            <select name="company_location" id="" class="form-control">
                <option value="">Select Option</option>
                @foreach(CommonHelper::get_comapny_location()  as $location)
                <option @if($location->id ==  $ProspectData->company_location_id ) selected @endif value="{{$location->id}}">{{$location->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div>
            <label for="">Company Group</label> 
            <select name="company_group" id="" class="form-control">
                <option value="">Select Option</option>
                @foreach(CommonHelper::get_company_group()  as $group)
                <option  @if($group->id ==  $ProspectData->customer_group_id ) selected @endif value="{{$group->id}}">{{$group->name}}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-primary mr-1">Add</button>
    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
</div>
<?php
                                            echo Form::close();
                                            ?>

<script>
    $(document).ready(function(){
        viewRangeWiseDataFilter();

        setTimeout(() => {
           $('#contact_id').val('{{$ProspectData->contact_id}}');
        }, 2000);
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
                    html +=`<option value="${contact.id}">${contact.first_name}</option>`;
                    
                });
                $('#contact_id').empty('');
                $('#contact_id').append(html);
            }
            });


        }
</script>