<?php
use App\Helpers\CommonHelper;
use App\Helpers\HrHelper;
use App\Models\MenuPrivileges;
use App\Models\Menu;
$dashboard_access = explode(',',Auth::user()->dashboard_access);
$icons = array(
    'Finance'=>'fa fa-usd',
    'Purchase'=>'fa fa-money-bill',
    'Inventory'=>'fa fa-list',
    'Store'=>'fa fa-shopping-cart',
    'Sales'=>'fa fa-money',
    'Reports'=>'fa fa-print',
    'Users'=>'glyphicon glyphicon-user',
    'Dashboard' => 'glyphicon glyphicon-home',
    'HR' => 'glyphicon glyphicon-heart',
    'Productions' => 'glyphicon glyphicon-cog',
    'Production' => 'glyphicon glyphicon-cog',
    'Import' => 'glyphicon glyphicon-import',
    'Inventory Reports' => 'fa fa-print',
    'HR Master' => 'glyphicon glyphicon-wrench',
    'Inventory Master' => 'glyphicon glyphicon-wrench',
    'Production Master' => 'glyphicon glyphicon-wrench',
    "Assets" => "glyphicon glyphicon-list",
    "Machine Production" => "glyphicon glyphicon-cog",
);
$accType = Auth::user()->acc_type;
if($accType == 'client'){$m = $_GET['m'];}else{$m = Auth::user()->company_id;}
$company_id=  Session::get('run_company');
$user_rights = MenuPrivileges::where([['emp_code','=',Auth::user()->emp_code],['compnay_id','=',$company_id]]);
$parent_code = [];
$crud_permission='';
if($user_rights->count() > 0):
    $main_modules = explode(",",$user_rights->value('main_modules'));
    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));
    $crud_rights  = explode(",",$user_rights->value('crud_rights'));
    $companyList= $user_rights->value('company_list');

    foreach($submenu_ids as $val):
        $parent_code[] = Menu::where([['id', '=', $val],['status','=', 1]])->value('m_parent_code');
    endforeach;
else:
    echo "Account Type:".$accType;
    echo 'Insufficient Menu Privileges'."<br>";
    echo "<a href='".url('/logout')."'>Logout</a>";
    die;
endif;
?>
<style>
 img.logo_m{width:216px;}
</style>

   <div id="mySidenav" class="sidenavnr">
        <div class="logo_flex">
            <div class="logo_wrp">
                <a href="{{route('dClient')}}">
                <img class="logo_m hide" src="{{ asset('logoo.png') }}">
                <img class="logo_m" src="{{ url('public/logoo.png') }}" onerror="this.onerror=null;this.src='{{ asset('logoo.png') }}'" />
            
                </a>
            </div>
            
            <div class="o_f">
                <a href="#" class="closebtn theme-f-clr Navclose" ><i class="fa fa-list-ul" aria-hidden="true"></i></a>
            </div>
        </div>

            @if(Session::get('run_company') != null)
         <?php
         $MainMenuTitles = DB::table('main_menu_title')->select(['main_menu_id','id'])->
         where([['status','=',1]])->whereIn('id',$main_modules)
         ->groupBy('main_menu_id')->orderBy('menu_type')->orderBy('id')->get();
            $counter = 1;
            $count = 1;
         ?>
         @foreach($main_modules as $row)
         @if(in_array($row,$main_modules))
            <?php
            $main_menu_id = DB::table('main_menu_title')->select('main_menu_id')->where([['id','=',$row]])->value('main_menu_id');
            ?>
         <ul  class="m_list " id="myGroup">
         <li>
               <div class="sm-bx">
                  <button class="btn settingListSb theme-bg" data-toggle="collapse" data-target="#masterSetting<?=$counter?>" >
                     <span><i class="<?=$icons[$main_menu_id]?>" aria-hidden="true"></i></span>
                     <p><?php echo $main_menu_id;?></p>
                  </button>
                  <div id="masterSetting<?=$counter?>" class="collapse pmastermnu">
                     <ul class="list-unstyled">
                           <?php



                           $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->
                           where([['main_menu_id','=',$main_menu_id],['status','=',1]])->whereIn('id', $parent_code)->orderBy('orderby','ASC')->get();


                           foreach($MainMenuTitlesSub as $row1){
                           ?>
                           <li class="dd">
                              <ul class="list-unstyled">
                                 <a href="#" class="settingListSb-subItem" data-toggle="collapsee" data-target="#masterSetting<?=$counter?>-<?= $count ?>"><?php echo $row1->title; ?></a>
                                 <div id="masterSetting<?= $counter ?>-<?= $count ?>" class="collapsee smastermnu">
                                       <ul class="list-unstyled">
                                          <?php
                                          $InCompany = Session::get('run_company');
                                          //if($InCompany != 1):
                                          $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->orderBy('order_by', 'ASC')->get();

                                          //else:
                                          //  $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->whereNotIn('id', [309,310,311])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->orderBy('order_by', 'ASC')->get();
                                          //endif;
                                          foreach($data as $dataValue){
                                          if(in_array($dataValue->id,$submenu_ids)):
                                          $MakeUrl = url(''.$dataValue->m_controller_name.'');?>
                                          <li>
                                             <span><i class="fal fa-circle-notch"></i></span>
                                             <a href="<?php echo url(''.$dataValue->m_controller_name.'?pageType='.$dataValue->m_type.'&&parentCode='.$dataValue->m_parent_code.'&&m='.Session::get('run_company').'#signsnow')?>"> <?php echo $dataValue->name;?>
                                             </a>
                                          </li>
                                          <?php endif; } ?>
                                       </ul>
                                 </div>
                              </ul>
                           </li>
                           <?php $count++; ?>
                           <?php } ?>
                     </ul>
                  </div>
               </div>
         </li>
         </ul>
      <?php $counter++; ?>
         @endif @endforeach
         @endif
   </div>

   <div class="container-fluid head-sh">
      <div class="headerwrap">
         <div class="row align-items-center">

            <div class="col-md-4 col-lg-4">
               <div class="searchBox">
                  <div class="serch_input">
                     <input class="searchInput"type="text" name="" placeholder="Search">
                     <div class="but_search">
                           <button class="searchButton" href="#">
                              <i class="fa fa-search"></i>
                           </button>
                     </div>
                  </div>
               </div>
         </div>  
      
         <div class="col-md-4 col-lg-4">
               <div class="tim d">
                  <h3>11:20<span>AM</span></h3>
                  <p class="date">January 1, 2024</p>
               </div>
         </div>

         <div class="col-md-4 col-lg-4">   
               <div class="meinbox">
                  <div class="profie">
                     <ul class="profile-admin d-flex">
                           <li>
                              <div class="calender">
                                 <a href="#">
                                       <i class="fa-regular fa-calendar-days"></i>
                                 </a>
                              </div>
                           </li>
                           <li class="nav-item dropdown dropdown-notification me-25">
                              <div class="notifction_text">
                                 <a class="nav-link bella" href="{{route('alert')}}" data-bs-toggle="dropdown">
                                       <div class="not1">
                                          <i class="fa-regular fa-bell"></i>
                                          <span class="badge rounded-pill bg-danger badge-up">5</span>
                                       </div>
                                 </a>
                              </div>
                           </li>
                           <li>
                              <div class="pro-user d-flex">
                                 <span class="avatar">
                                       <img class="round" src="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/avatars/1.png" alt="avatar" height="40" width="40">
                                 </span>
                                 <div class="user-nav d-sm-flex d-none"><span class="user-name fw-bolder">{{ Auth::user()->name }}</span></div>
                              </div>
                           </li>
                           <li class="dropdown user-name-drop">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa-solid fa-angle-down"></i></a>
                              <div class="account-information dropdown-menu">
                                 <div class="account-inner">
                                       <div class="davtar">
                                          <span class="avatar"> <img class="round" src="https://demos.pixinvent.com/vuexy-html-admin-template/assets/img/avatars/1.png" alt="avatar" > </span>
                                          <div class="content_profile">
                                             <h5>{{ Auth::user()->name }}</h5>
                                             <!-- <p>Bridging the Future of Industry.</p> -->
                                             <p>amir@innovative-net.com</p>
                                          </div>
                                       </div>
                                       <div class="main-heading">
                                          <ul class="list-unstyled" id="nav">
                                             <li>
                                             <a href="#" rel="{{ url('assets/css/color-one.css') }}">
                                                   <div class="color-one"></div>
                                             </a>
                                             </li>
                                             <li>
                                             <a href="#" rel="{{ url('assets/css/color-two.css') }}">
                                                   <div class="color-two"></div>
                                             </a>
                                             </li>
                                             <li>
                                             <a href="#" rel="{{ url('assets/css/color-three.css') }}">
                                                   <div class="color-three"></div>
                                             </a>
                                             </li>
                                             <li>
                                             <a href="#" rel="{{ url('assets/css/color-four.css') }}">
                                                   <div class="color-four"></div>
                                             </a>
                                             </li>
                                             <li>
                                             <a href="#" rel="{{ url('assets/css/color-five.css') }}">
                                                   <div class="color-five"></div>
                                             </a>
                                             </li>
                                             <li>
                                             <a href="#" rel="{{ url('assets/css/color-six.css') }}">
                                                   <div class="color-six"></div>
                                             </a>
                                             </li>
                                             <li>
                                             <a href="#" rel="{{ url('assets/css/color-seven.css') }}">
                                                   <div class="color-seven"></div>
                                             </a>
                                             </li>
                                          </ul>
                                       </div>
                                 </div>
                                 <div class="account-footer">
                                       <div class="butts">
                                          <ul>
                                             <li>
                                                   <a href="{{ url('/users/editUserProfile') }}" class=""><i class="fa-solid fa-pencil"></i> Edit</a>
                                             </li>
                                             <li>
                                                   <a href="{{ url('/logout') }}" class=""><i class="fa-solid fa-right-to-bracket"></i> Sign out</a>
                                             </li>
                                          </ul>
                                       </div>
                                 </div>
                              </div>
                           </li>
                     </ul>
                  </div>
               </div>
         </div>
      </div>
   </div>
</div>
   <div class="container-fluid">
      <div class="headerwrap">
         <nav class="navbar  erp-menus">
            <div class="navbar-header">
               <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               </button>
            </div>
            <div class="collapse navbar-collapse js-navbar-collapse">
               <!--Company List Begin-->
               <!--Company List End-->
               @if(Session::get('run_company') != null)
               <?php
               $Clause = "";
               if (Session::get("run_company") == 3) {
                  $Clause = ",['id','!=',174]";
               } else {
                  $Clause = "";
               }

               if (Auth::user()->id == 1040) {
                  $MainMenuTitles = DB::table("main_menu_title")
                     ->select(["main_menu_id", "id"])
                     ->where([
                           ["status", "=", 1],
                           ["main_menu_id", "!=", "HR"],
                           ["main_menu_id", "!=", "HR Master"],
                           ["main_menu_id", "!=", "Users"],
                     ])
                     ->groupBy("main_menu_id")
                     ->orderBy("menu_type")
                     ->orderBy("id")
                     ->get();
               } else {
                  $MainMenuTitles = DB::table("main_menu_title")
                     ->select(["main_menu_id", "id"])
                     ->where([["status", "=", 1]])
                     ->groupBy("main_menu_id")
                     ->orderBy("menu_type")
                     ->orderBy("id")
                     ->get();
               }

               $counter = 1;

               foreach ($MainMenuTitles as $row) { ?>
               <ul class="nav navbar-nav">
                  <li class="dropdown mega-dropdown">
                     <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="<?= $icons[
                        $row->main_menu_id
                     ] ?>" aria-hidden="true"></i> <?php echo $row->main_menu_id; ?></a>
                     <ul class="dropdown-menu mega-dropdown-menu row">
                        <?php
                        $m = 1;

                        if (Session::get("run_company") != 2) {
                           $MainMenuTitlesSub = DB::table("main_menu_title")
                              ->select([
                                    "main_menu_id",
                                    "title",
                                    "title_id",
                                    "id",
                              ])
                              ->where([
                                    ["main_menu_id", "=", $row->main_menu_id],
                                    ["status", "=", 1],
                                    ["id", "!=", 174],
                              ])
                              ->orderBy("id", "ASC")
                              ->get();
                        } else {
                           $MainMenuTitlesSub = DB::table("main_menu_title")
                              ->select([
                                    "main_menu_id",
                                    "title",
                                    "title_id",
                                    "id",
                              ])
                              ->where([
                                    ["main_menu_id", "=", $row->main_menu_id],
                                    ["status", "=", 1],
                              ])
                              ->orderBy("id", "ASC")
                              ->get();
                        }

                        foreach ($MainMenuTitlesSub as $row1) { ?>
                        <li class="col-sm-2">
                           <ul>
                              <li class="dropdown-header"><?php echo $row1->title; ?> </li>
                              <?php
                              $InCompany = Session::get("run_company");
                              //if($InCompany != 1):
                              $data = DB::table("menu")
                                 ->select([
                                    "m_type",
                                    "name",
                                    "m_controller_name",
                                    "m_main_title",
                                    "id",
                                    "m_parent_code",
                                 ])
                                 ->where([
                                    ["m_parent_code", "=", $row1->id],
                                    ["page_type", "=", 1],
                                    ["status", "=", 1],
                                 ])
                                 ->orderBy("id", "ASC")
                                 ->get();
                              //else:
                              //  $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->whereNotIn('id', [309,310,311])->where([['m_parent_code','=',$row1->id],['page_type', '=', 1],['status', '=', 1]])->orderBy('order_by', 'ASC')->get();
                              //endif;
                              foreach ($data as $dataValue) {
                                 $MakeUrl = url(
                                    "" . $dataValue->m_controller_name . ""
                                 ); ?>
                              <li><a href="<?php echo url(
                                 "" .
                                    $dataValue->m_controller_name .
                                    "?pageType=" .
                                    $dataValue->m_type .
                                    "&&parentCode=" .
                                    $dataValue->m_parent_code .
                                    "&&m=" .
                                    Session::get("run_company") .
                                    "#signsnow"
                              ); ?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $dataValue->name; ?></a></li>
                              <?php
                              }
                              ?>
                           </ul>
                        </li>
                        <?php }
                        ?>
                     </ul>
                  </li>
               </ul>
               <?php }
               ?>
               @endif
            </div>
         </nav>
      </div>
   </div>

   <div class="container-fluid head-sh">
      <div class="col-md-12 col-lg-12">
         <div class="nav_home">
               <div class="nav navbar-nav">
                  <ul class="tmenu-list d">
                     @if(in_array("dashboard", $dashboard_access)) 
                     <li class="active">
                           <a class="btn btn-primary primary_nav " href="{{route('dClient')}}">
                           Dashboard
                     </a>
                     </li>
                     @endif
                     @if(in_array("dashboard_production", $dashboard_access)) 

                     <li>
                           <a class="btn btn-primary primary_nav" href="{{route('production_dashboard')}}">
                           Production Dashboard
                           </a>
                     </li>
                     @endif
                     @if(in_array("dashboard_management", $dashboard_access)) 

                     <li>
                           <a class="btn btn-primary primary_nav" href="{{route('fClient')}}">
                           Management Dashboard
                           </a>
                     </li>
                     @endif
                     @if(in_array("dashboard_management", $dashboard_access)) 
                     <li>
                           <a class="btn btn-primary primary_nav" href="{{route('mydesk')}}">
                           My Desk
                           </a>
                     </li>
                     @endif
                     @if(in_array("dashboard_management", $dashboard_access)) 
                     <li>
                           <a class="btn btn-primary primary_nav" href="{{route('alert')}}">
                           Alert
                           </a>
                     </li>
                     @endif
                  </ul>
               </div>
         </div>
      </div>
   </div>
<br />
<!--For Demo Only (End Removable) -->
<input type="hidden" id="baseUrl" value="<?php echo url('/') ?>">
<input type="hidden" id="emp_code" value="<?php echo Auth::user()->emp_code ?>">
<!-- MENU SECTION END-->
