<?php
use App\Helpers\HrHelper;
use App\Models\MenuPrivileges;
use App\Models\Menu;

$accType = Auth::user()->acc_type;
if($accType == 'client'){$m = $_GET['m'];}else{$m = Auth::user()->company_id;}


$user_rights = MenuPrivileges::where([['user_id','=',Auth::user()->emp_id]]);

$crud_permission='';
if($user_rights->count() > 0):
    $main_modules = explode(",",$user_rights->value('main_modules'));
    $submenu_ids  = explode(",",$user_rights->value('submenu_id'));

else:

    echo 'Insufficient Menu Privileges'."<br>";
    echo "<a href='".url('/logout')."'>Logout</a>";
    die;
endif;

?>

<style>
    .title{font-weight:600;}
    .megamenu{border:1px solid black}

</style>
<div class="wsmenucontainer clearfix">
    <div class="overlapblackbg"></div>
    <div class="wsmobileheader clearfix">
        <a id="wsnavtoggle" class="animated-arrow"><span></span></a>
    </div>


    <div class="header">
        <div class="wrapper clearfix bigmegamenu">
            <!--Main Menu HTML Code-->
            <nav class="wsmenu clearfix">
                <ul class="mobile-sub wsmenu-list">
                    <li aria-haspopup="true"><a href="#" class="{{ Request::is('dCompany')? 'active': '' }}"> Dashboard2</a></li>
                    <?php
                    $MainMenuTitles = DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',1],['status','=',1]])->groupBy('main_menu_id')->get();
                    $counter = 1;
                    foreach($MainMenuTitles as $row){
                        if(in_array($row->id,$main_modules)): ?>


                    <li aria-haspopup="true"><a href="#">&nbsp;&nbsp;
                       <?php echo $row->main_menu_id;?><span class="arrow"></span></a>
                        <div class="megamenu clearfix">

                            <?php
                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['status','=',1],['menu_type','=',1]])->get();
                            foreach($MainMenuTitlesSub as $row1){
                            ?>
                            <ul class="col-lg-2 col-md-2 col-xs-12 link-list">
                                <li class="title"><?php echo $row1->title; ?></li>

                                <?php
                                $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$row1->id)->get();
                                foreach($data as $dataValue){
                                $MakeUrl = url(''.$dataValue->m_controller_name.'');
                                if(in_array($dataValue->id,$submenu_ids)): ?>

                                <li><a href="<?php echo url(''.$dataValue->m_controller_name.'?pageType='.$dataValue->m_type.'&&parentCode='.$dataValue->m_parent_code.'&&m='.$m.'#SFR')?>">
                                        <i class="fa fa-arrow-circle-right"></i><?php echo $dataValue->name;?>
                                    </a>
                                </li>
                                <?php

                                endif;
                                } ?>
                            </ul>
                            
                            <?php } ?>
                         </div>
                    </li>
                    <?php
                    endif;
                    }
                    ?>

                    <?php
                    $MainMenuTitles = DB::table('main_menu_title')->select(['main_menu_id','id'])->where([['menu_type','=',2],['status','=',1]])->groupBy('main_menu_id')->get();
                    $counter = 1;
                    foreach($MainMenuTitles as $row){
                    if(in_array($row->id,$main_modules)): ?>


                    <li aria-haspopup="true"><a href="#">&nbsp;&nbsp;
                            <?php echo $row->main_menu_id." Master";?><span class="arrow"></span></a>
                        <div class="megamenu clearfix">

                            <?php
                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',2],['status','=',1]])->get();
                            foreach($MainMenuTitlesSub as $row1){
                            ?>
                            <ul class="col-lg-2 col-md-2 col-xs-12 link-list">
                                <li class="title"><?php echo $row1->title; ?></li>
                                <?php
                                $data = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$row1->id)->get();
                                foreach($data as $dataValue){
                                $MakeUrl = url(''.$dataValue->m_controller_name.'');
                                if(in_array($dataValue->id,$submenu_ids)):
                                ?>
                                <li><a href="<?php echo url(''.$dataValue->m_controller_name.'?pageType='.$dataValue->m_type.'&&parentCode='.$dataValue->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="fa fa-arrow-circle-right"></i><?php echo $dataValue->name;?></a></li>
                                <?php endif;} ?>
                            </ul>
                            
                            <?php } ?>
                        </div>
                    </li>
                    <?php
                    endif;
                    }
                    ?>

                    <li aria-haspopup="true"><a href="{{ url('/logout') }}"> {{ Auth::user()->name }}  =>  Logout <i class="fa fa-btn fa-sign-out"></i></a>
                    </li>
                </ul>
            </nav>
            <!--Menu HTML Code-->
    </div>
</div>



</div>
<br />
<br />

<!--For Demo Only (End Removable) -->
<input type="hidden" id="url" value="<?php echo url('/') ?>">


<!-- MENU SECTION END-->