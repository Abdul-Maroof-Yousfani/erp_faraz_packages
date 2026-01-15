<?php
use App\Helpers\CommonHelper;
use Illuminate\Support\Facades\DB;
$i=1;
?>
@foreach($recycle_wastages as $recycle_wastage)
<?php

// $customer = CommonHelper::byers_name($production->customer);

// $customer = (!empty($customer)) ? $customer->name : '' ;

?>
<tr id="{{$i}}">
    <td>{{$i}}</td>
    <td>{{$recycle_wastage->sub_ic}}</td>
    <td>{{$recycle_wastage->name}}</td>
    <td>{{$recycle_wastage->batch_code}}</td> 
    <td>{{ $recycle_wastage->qty}}</td>
    <td>{{ $recycle_wastage->recycle_wastage_date}}</td> 
    <td>{{ $recycle_wastage->type == 1 ? 'Wastage' : 'Recycle' }}</td> 
    <td>{{ $recycle_wastage->approval_status != 2 ? 'Pending' : 'Approve'}}</td> 
  
    <td>
        <div class="dropdown">
        <button class="drop-bt dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
            <ul class="dropdown-menu">
                <li>

                    <a  class="" onclick="showDetailModelTwoParamerter('selling/viewRecycleWastage/','<?php echo $recycle_wastage->id?>','view Recycle Wastage','')">
                          View
                    </a>   
                    
                    @if($recycle_wastage->approval_status != 2 )
                        <a href="editWaistageAndRecycle?id=<?php echo $recycle_wastage->id?>" class=" ">
                            Edit
                        </a>    
                    
                        <a class="" onclick="deleteRecycleWastage({{$recycle_wastage->id}},{{$i}},'deleteRecycleWastage')" >
                            Delete
                        </a>
                
                    <a  class="" onclick="approveRecycleWastage( {{$recycle_wastage->id}}  )">Approved </a>
                    
                    @endif
                  
                    
                </li>
            </ul>
        </div>
    </td>
</tr>
@php
$i++;
@endphp
@endforeach


<script>
   function approveRecycleWastage(id)
    {
        $.ajax({
                url: '/selling/approveRecycleWastage',
                type: 'Get',
                data: {
                    id:id,
                    },
                success: function (response) {
                    if(response == 1)
                    {
                        window.location.reload();

                    }
                    else{
                        alert(response.msg);
                    }
                }   
            });
    }

    function deleteRecycleWastage(id,count,link) {
        console.log('/selling/'+link)
        $.ajax({
                url: '/selling/'+link,
                type: 'Get',
                data:   {
                        id:id,
                        },
                success: function (response) {
                    
                    if(response == 1)
                    {
                        $('#'+count).remove();
                    }
                    else
                    {
                        alert('record not found')
                    }

                }   
            });
    }
</script>