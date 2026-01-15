<?php
if(!empty($_GET['m'])){
    $_GET['m'];
}
?>
<div class="navbar-inverse set-radius-zero" >
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
    </div>
</div>

<!-- LOGO HEADER END-->
<!-- Services Section -->
<section class="menu-section">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-md-12">
                <div class="navbar-collapse collapse ">
                    <ul id="menu-top" class="nav navbar-nav" style="margin-bottom: -8px;">
                        <a href="{{ url('/dClient') }}" style="float: left; font-size: 25px;
                        padding: 15px; color: #9170E4; margin-right:10px;" class="triangle-obtuse top">Logo Area</a>&nbsp;&nbsp;
                        <li><a href="{{ url('/dClient') }}" class="{{ Request::path() == 'dClient' ? 'triangle-isosceles' : '' }}">Dashboard</a></li>
                        <li><a href="{{ url('companies/c') }}" class="{{ Request::is('companies/c','companies/*')? 'triangle-isosceles': '' }}">Companies</a></li>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">Request Hiring <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li><a href="{{ url('RequestHiring/viewHiringRequestList') }}">View List</a></li>
                            </ul>
                        </li>
                        <?php if(!empty($_GET['m'])){?>
                        <?php $selectedCompany = DB::selectOne('select `name` from `company` where `id` = '.$_GET['m'].'')->name?>
                        <li><a href="" class="{{ Request::is('ccd/*','users/*','hr/*')? 'triangle-isosceles': '' }}"><?php echo $selectedCompany;?></a></li>
                        <?php }?>
                        <li>
                            <a href="#" class="dropdown-toggle" id="ddlmenuItem" data-toggle="dropdown">{{ Auth::user()->name }} <i class="fa fa-angle-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="ddlmenuItem">
                                <li><a href="{{ url('/logout') }}"><i class="fa fa-btn fa-sign-out"></i>Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>
<?php
if(!empty($_GET['m'])){
?>
<br />
<div class="container-fluid" style="margin-bottom: -10px;">
    <div class="well" style="margin: 0px; padding: 3px;">
        <div class="navbar-collapse collapse ">
            <ul id="menu-top" class="nav navbar-nav" style="margin-bottom: -8px;">
                <li><a href="{{ url('users/u?m='.$_GET['m'].'') }}" class="{{ Request::is('users/u','users/*')? 'triangle-isosceles': '' }}">Users</a></li>
                <li><a href="{{ url('hr/h?m='.$_GET['m'].'') }}" class="{{ Request::is('hr/h','hr/*')? 'triangle-isosceles': '' }}">HR</a></li>
                <li><a href="{{ url('finance/f?m='.$_GET['m'].'') }}" class="{{ Request::is('finance/f','finance/*')? 'triangle-isosceles': '' }}">Finance</a></li>
                <li><a href="{{ url('purchase/p?m='.$_GET['m'].'') }}" class="{{ Request::is('purchase/p','purchase/*')? 'triangle-isosceles': '' }}">Purchase</a></li>
                <li><a href="{{ url('store/st?m='.$_GET['m'].'') }}" class="{{ Request::is('store/st','store/*','stad/*')? 'triangle-isosceles': '' }}">Store</a></li>
                <li><a href="{{ url('sales/s?m='.$_GET['m'].'') }}" class="{{ Request::is('sales/s','sales/*')? 'triangle-isosceles': '' }}">Sales</a></li>
            </ul>
        </div>
    </div>
</div>
<?php
}
?>
<input type="hidden" id="url" value="<?php echo url('/') ?>">
<!-- MENU SECTION END-->