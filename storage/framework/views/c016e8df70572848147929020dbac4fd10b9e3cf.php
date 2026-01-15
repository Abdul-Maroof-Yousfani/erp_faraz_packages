<?php
url('/');
url()->current();
Config::set('database.default', 'mysql');
DB::reconnect('mysql');
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="bhoechie-tab-container">
            <div class="bhoechie-tab-menu">
                <div class="list-group">
                    <div data-toggle="collapse" data-target="#MasterMenu" class="collapsed">
                        <a href="#" class="list-group-item list-group-item-collaps">Master Menu</a>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <div class="sub-menu collapse" id="MasterMenu">
                        <?php
                        $MasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Purchase')->where('status','=','1')->where('menu_type','=','2')->get();
                        ?>
                        <?php $__currentLoopData = $MasterMenuTitles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $MasterMenuTitle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div data-toggle="collapse" data-target="#<?php echo e($MasterMenuTitle->title_id); ?>" class="collapsed" style="margin-left: 10px;">
                            <a href="#" class="list-group-item list-group-item-collaps"><?php echo e($MasterMenuTitle->title); ?></a>
                        </div>
                        <div class="sub-menu collapse" id="<?php echo e($MasterMenuTitle->title_id); ?>"  style="margin-left: 10px;">
                            <?php
                            $subMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$MasterMenuTitle->id)->get();
                            foreach($subMenu as $row1){
                                $makeUrl = url(''.$row1->m_controller_name.'');
                            if(url()->current() == $makeUrl){
                                ?>
                                <script>$('#<?php echo $row1->m_main_title?>').addClass("in");</script>
                                <script>$('#MasterMenu').addClass("in");</script>
                            <?php
                            }
                            ?>
                                <a href="<?php echo url(''.$row1->m_controller_name.'?pageType='.$row1->m_type.'&&parentCode='.$row1->m_parent_code.'&&m='.$_GET['m'].'#SFR')?>" class="list-group-item <?php if(url()->current() == $makeUrl){echo 'triangle-isosceles right';}?>">&nbsp;<?php echo $row1->name;?></a>
                                <?php

                            }
                            ?>
                        </div>
                        <div class="lineHeight">&nbsp;</div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php
                    $MainMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Purchase')->where('status','=','1')->where('menu_type','=','1')->get();
                    ?>
                    <?php $__currentLoopData = $MainMenuTitles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $MainMenuTitle): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div data-toggle="collapse" data-target="#<?php echo e($MainMenuTitle->title_id); ?>" class="collapsed">
                        <a href="#" class="list-group-item list-group-item-collaps"><?php echo e($MainMenuTitle->title); ?></a>
                    </div>
                    <div class="sub-menu collapse" id="<?php echo e($MainMenuTitle->title_id); ?>">
                        <?php
                        $subMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$MainMenuTitle->id)->get();
                        foreach($subMenu as $row1){
                            $makeUrl = url(''.$row1->m_controller_name.'');
                        if(url()->current() == $makeUrl){
                            ?>
                            <script>$('#<?php echo $row1->m_main_title?>').addClass("in");</script>
                        <?php
                        }
                        ?>
                            <a href="<?php echo url(''.$row1->m_controller_name.'?pageType='.$row1->m_type.'&&parentCode='.$row1->m_parent_code.'&&m='.$_GET['m'].'#SFR')?>" class="list-group-item <?php if(url()->current() == $makeUrl){echo 'triangle-isosceles right';}?>">&nbsp;<?php echo $row1->name;?></a>
                            <?php

                        }
                        ?>
                    </div>
                    <div class="lineHeight">&nbsp;</div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</div>