<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Auth;
use DB;
use Config;
use App\Models\MainMenuTitle;
use App\Models\Menu;
class UsersDataCallController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
   	public function viewMainMenuTitleList(){
		$MainMenuTitles = new MainMenuTitle;
		$MainMenuTitles = $MainMenuTitles::where('status', '=', '1')->get();
		$counter = 1;
		foreach($MainMenuTitles as $row){
	?>
		<tr>
			<td class="text-center"><?php echo $counter++;?></td>
			<td><?php echo $row['main_menu_id'];?></td>
			<td><?php echo $row['title'];?></td>
			<td><a href="<?php echo  route('menu.menu_delete',$row->id) ?>" type="button" class="btn btn-primary btn-xs">Delete</a></td>
		</tr>
	<?php
		}
	}

	public function viewSubMenuList(){
		$Menus = new Menu;
		$Menus = $Menus::where('status', '=', '1')->get();
		$counter = 1;
		foreach($Menus as $row){
	?>
		<tr>
			<td class="text-center"><?php echo $counter++;?></td>
			<td><?php echo $row['m_parent_code'];?></td>
			<td><?php echo $row['name'];?></td>
			<td><a href="<?php echo  route('menu.submenu_delete',$row->id) ?>" type="button" class="btn btn-primary btn-xs">Delete</a></td>
		</tr>
	<?php
		}
	}
}
