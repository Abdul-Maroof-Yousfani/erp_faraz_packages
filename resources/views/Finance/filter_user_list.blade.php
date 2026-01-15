<?php
use App\Helpers\CommonHelper;

$counter = 1;
foreach ($Users as $row1) {
?>
<tr>
    <td class="text-center"><?php echo $counter++;?></td>
    <td class="text-center">
        <div class="profile_list">
            <img class="" src="{{asset('assets/img/profile.png')}}">
        </div>
    </td>
    <td class="text-center"><?php echo strtoupper($row1->name);?></td>
    <td class="text-center"><?php echo $row1->email;?></td>

    <td class="text-center"><?php echo CommonHelper::getCompanyName($row1->company_id);?></td>
    <td class="text-center"><?php if($row1->status == 1){echo 'Active';} else{ echo 'Inactive';}?></td>
    <td class="text-center hidden-print">
    <div class="dropdown">
        <button class="drop-bt dropdown-toggle"type="button" data-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
        <ul class="dropdown-menu">
            <li>
            <?php if($row1->status == 1):?>
                <a target="new" href="{{ route('userEditForm',$row1->id)}}" class=""><i class="fa-solid fa-pencil"></i> Edit</a>
                <a type="button" class="" id="BtnInactive<?php echo $row1->id?>" onclick="ActiveInActiveUser('<?php echo $row1->id?>','2')"><i class="fa-regular fa-eye-slash"></i> Inactive</a>
                <?php else:?>
                <a type="button" class="" id="BtnActive<?php echo $row1->id?>" onclick="ActiveInActiveUser('<?php echo $row1->id?>','1')"><i class="fa-regular fa-eye"></i> Active</a>
                <?php endif;?>

            </li>
        </ul>
        </div>
    
        
    </td>
</tr>
<?php }?>