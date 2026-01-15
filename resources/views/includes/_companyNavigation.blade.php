<?php
        die;
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}
	$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
?>

          
                  
<header>
	<div class="header-top">
    	<div class="container">
        	<div class="row">
            	<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 pull-right">
                	<ul class="nav navbar-nav navbar-right">
            			<li class="dropdown user-name-drop">
              			<a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Auth::user()->name[0] }}</a>
                		<div class="account-information dropdown-menu">
                    		<div class="account-inner">
                        		<div class="title">
                            		<span>{{ Auth::user()->name[0] }}</span>
                            	</div>
                           	 	<div class="main-heading">
                            		<h5>{{ Auth::user()->name }}</h5>
                                	<p>Bridging the Future of Industry.</p>
                                    <ul class="list-unstyled" id="nav">
                                        <li><a href="#" rel="{{ url('assets/css/color-one.css') }}"><div class="color-one"></div></a></li>
                                        <li><a href="#" rel="{{ url('assets/css/color-two.css') }}"><div class="color-two"></div></a></li>
                                        <li><a href="#" rel="{{ url('assets/css/color-three.css') }}"><div class="color-three"></div></a></li>
                                        <li><a href="#" rel="{{ url('assets/css/color-four.css') }}"><div class="color-four"></div></a></li>
                                        <li><a href="#" rel="{{ url('assets/css/color-five.css') }}"><div class="color-five"></div></a></li>
                                        <li><a href="#" rel="{{ url('assets/css/color-six.css') }}"><div class="color-six"></div></a></li>
                                        <li><a href="#" rel="{{ url('assets/css/color-seven.css') }}"><div class="color-seven"></div></a></li>
                                    </ul>
                            	</div>
                       	 	</div>
                        	<div class="account-footer">
                            <a href="http://www.innovative-net.com/contact-us" target="_blank"  class="btn link-accounts contact_support">Contact Support</a>
                        	<a href="{{ url('/logout') }}" class="btn link-accounts sign_out">Sign out</a>
                         </div>
                    	</div>
                  </li>
                 	</ul> 	
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                	<a href="" class="btn btn-dashboard"><i class="glyphicon glyphicon-home" aria-hidden="true"></i> Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</header>


<div class="container-fluid">
  <nav class="navbar navbar-fixed-top erp-menus">
    <div class="navbar-header">
      <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".js-navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
    </div>
    <div class="collapse navbar-collapse js-navbar-collapse">
      <!--Hr Begin-->
      	<ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle {{ Request::is('hr/h','hr/*')? 'active-erp': '' }}" data-toggle="dropdown"><i class="glyphicon glyphicon-user" aria-hidden="true"></i> HR</a>
			<ul class="dropdown-menu mega-dropdown-menu row">
            	<li class="col-sm-2">
                    <ul class="list-unstyled">
                    	<li class="dropdown-header">Hiring Request</li>
                    	<li><a href=""><i class="glyphicon glyphicon-plus-sign"></i> Empaty</a></li>
                        <li><a href=""><i class="glyphicon glyphicon-plus-sign"></i> Empaty</a></li>
					</ul>
                    
                    <?php $HRAdvanceSalarys = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Advance Salary')->get(); ?>
                                @foreach($HRAdvanceSalarys as $HRAdvanceSalary)
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRAdvanceSalary->title }}</li>
                	 <?php
                                    $HRASalarys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAdvanceSalary->id)->get();
                                    foreach($HRASalarys as $HRASalary){
                                    $HRASalaryMakeUrl = url(''.$HRASalary->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRASalary->m_controller_name.'?pageType='.$HRASalary->m_type.'&&parentCode='.$HRASalary->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRASalary->name;?></a></li>

                        <?php } ?>
							@endforeach
                    </ul>
                     <?php
                                $HRLeavesPolicys = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Leaves Policy')->get();
                                ?>
                               @foreach($HRLeavesPolicys as $HRLeavesPolicy)
                             
                	<ul class="list-unstyled">
                    	<li class="dropdown-header">{{ $HRLeavesPolicy->title }}</li>
                    	<?php
                                    $HRLPolicys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRLeavesPolicy->id)->get();
                                    foreach($HRLPolicys as $HRLPolicy){
                                    $HRLPolicyMakeUrl = url(''.$HRLPolicy->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRLPolicy->m_controller_name.'?pageType='.$HRLPolicy->m_type.'&&parentCode='.$HRLPolicy->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRLPolicy->name;?></a></li>
                         <?php } ?>
                                @endforeach
					</ul>   
                      
                      
            	</li>
                <li class="col-sm-2">
                
                	<?php
                                $HREmployees = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Employee')->get();
                                ?>
                 @foreach($HREmployees as $HREmployee)
                 	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HREmployee->title }}</li>
                    <?php
                                    $HREmps = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HREmployee->id)->get();
                                    foreach($HREmps as $HREmp){
                                    $HREmpMakeUrl = url(''.$HREmp->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HREmp->m_controller_name.'?pageType='.$HREmp->m_type.'&&parentCode='.$HREmp->m_parent_code.'&&m='.$m.'#SFR')?>">
                        <i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HREmp->name;?></a></li>
                        
                        
                        <?php } ?>
                                @endforeach
                    </ul>
                    
                    
                    
                    <?php $HREmployees = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Exit Clearance')->get(); ?>
                     @foreach($HREmployees as $HREmployee)
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HREmployee->title }}</li>
                    <?php
                                    $HREmps = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HREmployee->id)->get();
                                    foreach($HREmps as $HREmp){
                                    $HREmpMakeUrl = url(''.$HREmp->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HREmp->m_controller_name.'?pageType='.$HREmp->m_type.'&&parentCode='.$HREmp->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HREmp->name;?></a></li>
                        
                        <?php } ?>
                                @endforeach
                    </ul>
                    <?php
                                $HREmployees = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Probationary Period')->get();
                                ?>
                                @foreach($HREmployees as $HREmployee)
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HREmployee->title }}</li>
                    <?php
                                    $HREmps = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HREmployee->id)->get();
                                    foreach($HREmps as $HREmp){
                                    $HREmpMakeUrl = url(''.$HREmp->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HREmp->m_controller_name.'?pageType='.$HREmp->m_type.'&&parentCode='.$HREmp->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HREmp->name;?></a></li>
                       
                        
                         <?php } ?>
	@endforeach
                    </ul>
                    <?php
                                $HREmployees = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'IQ Test')->get();
                                ?>
                                @foreach($HREmployees as $HREmployee)
                                
                                
                                
                   
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HREmployee->title }}</li>
                    <?php
                                    $HREmps = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HREmployee->id)->get();
                                    foreach($HREmps as $HREmp){
                                    $HREmpMakeUrl = url(''.$HREmp->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HREmp->m_controller_name.'?pageType='.$HREmp->m_type.'&&parentCode='.$HREmp->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HREmp->name;?></a></li>
                        
                        
                         <?php } ?>
@endforeach
                    </ul>
                
                
                
                 </li>
                <li class="col-sm-2">
                	<?php
                                $HRAttendances = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Attendance')->get();
                                ?>
								@foreach($HRAttendances as $HRAttendance)
                    
                    
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRAttendance->title }}</li>
                    <?php
                                    $HRAttens = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAttendance->id)->get();
                                    foreach($HRAttens as $HRAtten){
                                    $HRAttenMakeUrl = url(''.$HRAtten->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRAtten->m_controller_name.'?pageType='.$HRAtten->m_type.'&&parentCode='.$HRAtten->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRAtten->name;?></a></li>

                        <?php } ?>
								@endforeach
                    </ul>
                    
                   
                    
                    <?php
                                $HRCarPolicys = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Car Policy')->get();
                                ?>
								@foreach($HRCarPolicys as $HRCarPolicy)
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRCarPolicy->title }}</li>
                    <?php
                                    $HRCPolicys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRCarPolicy->id)->get();
                                    foreach($HRCPolicys as $HRCPolicy){
                                    $HRCPolicyMakeUrl = url(''.$HRCPolicy->m_controller_name.'');
                                    ?>
                    
                    	<li><a href="<?php echo url(''.$HRCPolicy->m_controller_name.'?pageType='.$HRCPolicy->m_type.'&&parentCode='.$HRCPolicy->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRCPolicy->name;?></a></li>
                        
                        
                        <?php } ?>
								@endforeach
                    </ul>
                    
                    
                    <?php
                                $HREmployees = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Exit Interview')->get();
                                ?>
                                @foreach($HREmployees as $HREmployee)
                    
                    
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HREmployee->title }}</li>
                     <?php
                                    $HREmps = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HREmployee->id)->get();
                                    foreach($HREmps as $HREmp){
                                    $HREmpMakeUrl = url(''.$HREmp->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HREmp->m_controller_name.'?pageType='.$HREmp->m_type.'&&parentCode='.$HREmp->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HREmp->name;?></a></li>
                         <?php } ?>
                                @endforeach
                    </ul>
                </li>
                <li class="col-sm-2">
                	 <?php
                                $HRPayrolls = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Payroll')->get();
                                ?>
								@foreach($HRPayrolls as $HRPayroll)
                    
                	<ul class="list-unstyled">
                    	<li class="dropdown-header">{{ $HRPayroll->title }}</li>
                    	<?php
                                    $HRPays = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRPayroll->id)->get();
                                    foreach($HRPays as $HRPay){
                                    $HRPayMakeUrl = url(''.$HRPay->m_controller_name.'');
                                    ?>
                        <li><a href="<?php echo url(''.$HRPay->m_controller_name.'?pageType='.$HRPay->m_type.'&&parentCode='.$HRPay->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> Create Payslip</a></li>
                       
                        
                        <?php } ?>
								@endforeach
                    </ul>
                    
                    
                    <?php
                                $HRLoanRequests = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Loan Request')->get();
                                ?>
								@foreach($HRLoanRequests as $HRLoanRequest)
                    
                    
                    
                    <ul class="list-unstyled">
                    	<li class="dropdown-header">{{ $HRLoanRequest->title }}</li>
                    	<?php
                                    $HRLRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRLoanRequest->id)->get();
                                    foreach($HRLRequests as $HRLRequest){
                                    $HRLRequestMakeUrl = url(''.$HRLRequest->m_controller_name.'');
                                    ?>
                        <li><a href="<?php echo url(''.$HRLRequest->m_controller_name.'?pageType='.$HRLRequest->m_type.'&&parentCode='.$HRLRequest->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRLRequest->name;?></a></li>
                        
                        
                        <?php } ?>
								@endforeach
                    </ul>
                </li>
                <li class="col-sm-2">
                	<?php
                                $HRAllowances = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Allowance')->get();
                                ?>
								@foreach($HRAllowances as $HRAllowance)
                    
                    
                	<ul class="list-unstyled">
                    	<li class="dropdown-header">{{ $HRAllowance->title }}</li>
                    <?php
                                    $HRAllos = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAllowance->id)->get();
                                    foreach($HRAllos as $HRAllo){
                                    $HRAlloMakeUrl = url(''.$HRAllo->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRAllo->m_controller_name.'?pageType='.$HRAllo->m_type.'&&parentCode='.$HRAllo->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRAllo->name;?></a></li>
                          <?php } ?>
								@endforeach
                    </ul>
                    
                     <?php
                                $HRBonuss = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Bonus')->get();
                                ?>
								@foreach($HRBonuss as $HRBonus)
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRBonus->title }}</li>
                    	<?php
                                    $HRBonus = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRBonus->id)->get();
                                    foreach($HRBonus as $HRBonu){
                                    $HRBonuMakeUrl = url(''.$HRBonu->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRBonu->m_controller_name.'?pageType='.$HRBonu->m_type.'&&parentCode='.$HRBonu->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRBonu->name;?></a></li>
                        <?php } ?>
								@endforeach
                    </ul>
                </li>
                <li class="col-sm-2">
                	<?php $HRDeductions = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Deduction')->get();
                                ?>
					@foreach($HRDeductions as $HRDeduction)
                    
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRDeduction->title }}</li>
                    				<?php
                                    $HRDeducs = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRDeduction->id)->get();
                                    foreach($HRDeducs as $HRDeduc){
                                    $HRDeducMakeUrl = url(''.$HRDeduc->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRDeduc->m_controller_name.'?pageType='.$HRDeduc->m_type.'&&parentCode='.$HRDeduc->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRDeduc->name;?></a></li>
                        			<?php } ?>
								@endforeach
                    </ul>
                    
<?php  $HRLeaveApplications = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Leave Application')->get();
                                ?>
								@foreach($HRLeaveApplications as $HRLeaveApplication)                    
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRLeaveApplication->title }}</li>
                    <?php
                                    $HRLApplications = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRLeaveApplication->id)->get();
                                    foreach($HRLApplications as $HRLApplication){
                                    $HRLApplicationMakeUrl = url(''.$HRLApplication->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRLApplication->m_controller_name.'?pageType='.$HRLApplication->m_type.'&&parentCode='.$HRLApplication->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRLApplication->name;?></a></li>
 <?php } ?>
								@endforeach
                    </ul>
                </li>
          </ul>
        </li>
      </ul>
      <!--Hr Begin-->
      
      <!--Hr Master table Begin-->
	  	<ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle {{ Request::is('hr/h','hr/*')? '': '' }}" data-toggle="dropdown"><i class="fa fa-table" aria-hidden="true"></i> HR Mstr Tb</a>
			<ul class="dropdown-menu mega-dropdown-menu row">
            	<li class="col-sm-2">
                	 <?php $hrMasterTbDepartment = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'Department')->get(); ?>
					@foreach($hrMasterTbDepartment as $HRHiringRequest)
					
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRHiringRequest->title }}</li>
                    <?php
                       $HRHRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRHiringRequest->id)->get();
                       foreach($HRHRequests as $HRHRequest){
                       $HRHRequestMakeUrl = url(''.$HRHRequest->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRHRequest->m_controller_name.'?pageType='.$HRHRequest->m_type.'&&parentCode='.$HRHRequest->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRHRequest->name;?></a></li>
                         <?php } ?>
								@endforeach
					</ul>
                   <?php
                                $HRMasterTbDesignation = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'Designation')->get();
                                ?>
								@foreach($HRMasterTbDesignation as $HRAdvanceSalary)
					
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRAdvanceSalary->title }}</li>
                    <?php
                                    $HRASalarys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAdvanceSalary->id)->get();
                                    foreach($HRASalarys as $HRASalary){
                                    $HRASalaryMakeUrl = url(''.$HRASalary->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRASalary->m_controller_name.'?pageType='.$HRASalary->m_type.'&&parentCode='.$HRASalary->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRASalary->name;?></a></li>
                        
                        <?php } ?>
								@endforeach
					</ul>
                </li>
                <li class="col-sm-2">
                	<?php
                                $hrMasterTbJobType = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'Job Type')->get();
                                ?>
								@foreach($hrMasterTbJobType as $HRHiringRequest)
                    
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRHiringRequest->title }}</li>
                    	<?php
                                    $HRHRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRHiringRequest->id)->get();
                                    foreach($HRHRequests as $HRHRequest){
                                    $HRHRequestMakeUrl = url(''.$HRHRequest->m_controller_name.'');
                                    ?>
                        <li><a href="<?php echo url(''.$HRHRequest->m_controller_name.'?pageType='.$HRHRequest->m_type.'&&parentCode='.$HRHRequest->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRHRequest->name;?></a></li>
                        <?php } ?>
								@endforeach
					</ul>
                    <?php
                                $HRMasterTbLeaveType = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'Leave Types')->get();
                                ?>
								@foreach($HRMasterTbLeaveType as $HRAdvanceSalary)
                    <ul class="list-unstyled">
                    	<li class="dropdown-header">{{ $HRAdvanceSalary->title }}</li>
                    	 <?php
                                    $HRASalarys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAdvanceSalary->id)->get();
                                    foreach($HRASalarys as $HRASalary){
                                    $HRASalaryMakeUrl = url(''.$HRASalary->m_controller_name.'');
                                    ?>
                        <li><a href="<?php echo url(''.$HRASalary->m_controller_name.'?pageType='.$HRASalary->m_type.'&&parentCode='.$HRASalary->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRASalary->name;?></a></li>
                        <?php } ?>
								@endforeach
					</ul>
                </li>
                <li class="col-sm-2">
                	<?php
                                $hrMasterTbMaritalStatus = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'Marital Status')->get();
                                ?>
								@foreach($hrMasterTbMaritalStatus as $HRHiringRequest)
                    
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRHiringRequest->title }}</li>
                     <?php
                                    $HRHRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRHiringRequest->id)->get();
                                    foreach($HRHRequests as $HRHRequest){
                                    $HRHRequestMakeUrl = url(''.$HRHRequest->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRHRequest->m_controller_name.'?pageType='.$HRHRequest->m_type.'&&parentCode='.$HRHRequest->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRHRequest->name;?></a></li>
                        
                        
                        <?php } ?>
								@endforeach
					</ul>
                    
              <?php $HRMasterTbEOBI = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'EOBI')->get();
                                ?>
								@foreach($HRMasterTbEOBI as $HRAdvanceSalary)
                    
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRAdvanceSalary->title }}</li>
                    <?php
                                    $HRASalarys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAdvanceSalary->id)->get();
                                    foreach($HRASalarys as $HRASalary){
                                    $HRASalaryMakeUrl = url(''.$HRASalary->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRASalary->m_controller_name.'?pageType='.$HRASalary->m_type.'&&parentCode='.$HRASalary->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRASalary->name;?></a></li>
                        
                        
                         <?php } ?>
								@endforeach
					</ul>
                </li>
                <li class="col-sm-2">
                	<?php
                                $hrMasterTbTaxes = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'Taxes')->get();
                                ?>
								@foreach($hrMasterTbTaxes as $HRHiringRequest)
                  
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRHiringRequest->title }}</li>
                    <?php
                                    $HRHRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRHiringRequest->id)->get();
                                    foreach($HRHRequests as $HRHRequest){
                                    $HRHRequestMakeUrl = url(''.$HRHRequest->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRHRequest->m_controller_name.'?pageType='.$HRHRequest->m_type.'&&parentCode='.$HRHRequest->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRHRequest->name;?></a></li>
                        <?php } ?>
								@endforeach
                    </ul>
                    
                    
                    
                    <?php
                                $HRMasterTbDegreeType = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','HR')->where('status','=','1')->where('menu_type','=','2')->Where('title', 'like', 'Degree Type')->get();
                                ?>
								@foreach($HRMasterTbDegreeType as $HRAdvanceSalary)
                    
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRAdvanceSalary->title }}</li>
                    <?php
                                    $HRASalarys = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$HRAdvanceSalary->id)->get();
                                    foreach($HRASalarys as $HRASalary){
                                    $HRASalaryMakeUrl = url(''.$HRASalary->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$HRASalary->m_controller_name.'?pageType='.$HRASalary->m_type.'&&parentCode='.$HRASalary->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $HRASalary->name;?></a></li>
                        
                        
                         <?php } ?>
								@endforeach
					</ul>
                </li>
                <li class="col-sm-2">
                </li>
                <li class="col-sm-2">
                </li>
          </ul>
        </li>
      </ul>
      <!--Hr Master table end-->
      
	   
       	<!--Finance Begin-->
      <ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle {{ Request::is('finance/f','finance/*')? 'active-erp': '' }}" data-toggle="dropdown"><i class="fa fa-usd" aria-hidden="true"></i> Finance</a>
			<ul class="dropdown-menu mega-dropdown-menu row">

                <li class="col-sm-2">
                    <?php
                    $FinancePaymentVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Finance')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '% Payment %')->get();?>
                    @foreach($FinancePaymentVouchers as $FinancePaymentVoucher)

                        <ul class="list-unstyled">
                            <li class="dropdown-header">{{ $FinancePaymentVoucher->title }}</li>
                            <?php
                            $PurchaseDemands = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$FinancePaymentVoucher->id)->get();
                            foreach($PurchaseDemands as $PurchaseDemand){
                            $PurchaseDemandMakeUrl = url(''.$PurchaseDemand->m_controller_name.'');
                            ?>
                            <li><a href="<?php echo url(''.$PurchaseDemand->m_controller_name.'?pageType='.$PurchaseDemand->m_type.'&&parentCode='.$PurchaseDemand->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $PurchaseDemand->name;?></a></li>

                            <?php } ?>

                        </ul>
                        @endforeach
                </li>
                  
                 
          </ul>
        </li>
      </ul>
      	<!--Finance end-->
      
      
      	
        <!--Purchase Begin-->
      	<ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle {{ Request::is('purchase/p','purchase/*')? 'active-erp': '' }}" data-toggle="dropdown"><i class="fa fa-shopping-cart" aria-hidden="true"></i> Purchase</a>
			<ul class="dropdown-menu mega-dropdown-menu row">
                    <li class="col-sm-2">
                    	 <?php
                                $PurchaseDemandVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Purchase')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Demand%')->get();
                                ?>
								@foreach($PurchaseDemandVouchers as $PurchaseDemandVoucher)
                	
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $PurchaseDemandVoucher->title }}</li>
                     <?php
                                    $PurchaseDemands = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$PurchaseDemandVoucher->id)->get();
                                    foreach($PurchaseDemands as $PurchaseDemand){
                                    $PurchaseDemandMakeUrl = url(''.$PurchaseDemand->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$PurchaseDemand->m_controller_name.'?pageType='.$PurchaseDemand->m_type.'&&parentCode='.$PurchaseDemand->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $PurchaseDemand->name;?></a></li>
                       
                         <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    
                    <li class="col-sm-2">
                    	<?php
                                $PurchaseGoodsReceiptNoteVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Purchase')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Goods Receipt Note%')->get();
                                ?>
								@foreach($PurchaseGoodsReceiptNoteVouchers as $PurchaseGoodsReceiptNoteVoucher)
                   
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $PurchaseGoodsReceiptNoteVoucher->title }}</li>
                     <?php
                                    $PurchaseGoodsReceiptNotes = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$PurchaseGoodsReceiptNoteVoucher->id)->get();
                                    foreach($PurchaseGoodsReceiptNotes as $PurchaseGoodsReceiptNote){
                                    $PurchaseGoodsReceiptNoteMakeUrl = url(''.$PurchaseGoodsReceiptNote->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$PurchaseGoodsReceiptNote->m_controller_name.'?pageType='.$PurchaseGoodsReceiptNote->m_type.'&&parentCode='.$PurchaseGoodsReceiptNote->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $PurchaseGoodsReceiptNote->name;?></a></li>
                        
                        
                          <?php } ?>
								@endforeach
                    </ul>
                    </li>
          	</ul>
        </li>
      </ul>
		<!--Finance end-->
        
        
        <!--Store Begin-->
       	<ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle {{ Request::is('store/st','store/*','stad/*')? 'active-erp': '' }}" data-toggle="dropdown"><i class="fa fa-building" aria-hidden="true"></i> Store </a>
			<ul class="dropdown-menu mega-dropdown-menu row">
                    <li class="col-sm-2">
                    	<?php $StoreDemandVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Demand%')->get();
                                ?>
								@foreach($StoreDemandVouchers as $StoreDemandVoucher)
								 
                    
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $StoreDemandVoucher->title }}</li>
                    <?php
                                    $StoreDemands = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreDemandVoucher->id)->get();
                                    foreach($StoreDemands as $StoreDemand){
                                    $StoreDemandMakeUrl = url(''.$StoreDemand->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$StoreDemand->m_controller_name.'?pageType='.$StoreDemand->m_type.'&&parentCode='.$StoreDemand->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $StoreDemand->name;?></a></li>
                        <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    <li class="col-sm-2">
                    	<?php
                                $StoreStoreChallanVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Store Challan')->get();
                                ?>
								@foreach($StoreStoreChallanVouchers as $StoreStoreChallanVoucher)
                	
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $StoreStoreChallanVoucher->title }}</li>
                     <?php
                                    $StoreStoreChallans = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreStoreChallanVoucher->id)->get();
                                    foreach($StoreStoreChallans as $StoreStoreChallan){
                                    $StoreStoreChallanMakeUrl = url(''.$StoreStoreChallan->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$StoreStoreChallan->m_controller_name.'?pageType='.$StoreStoreChallan->m_type.'&&parentCode='.$StoreStoreChallan->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $StoreStoreChallan->name;?></a></li>
                        
                        
                        <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    <li class="col-sm-2">
                    	<?php
                                $StorePurchaseRequestVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Purchase Request')->get();
                                ?>
								@foreach($StorePurchaseRequestVouchers as $StorePurchaseRequestVoucher)
                	
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $StorePurchaseRequestVoucher->title }}</li>
                    <?php
                                    $StorePurchaseRequests = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StorePurchaseRequestVoucher->id)->get();
                                    foreach($StorePurchaseRequests as $StorePurchaseRequest){
                                    $StorePurchaseRequestMakeUrl = url(''.$StorePurchaseRequest->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$StorePurchaseRequest->m_controller_name.'?pageType='.$StorePurchaseRequest->m_type.'&&parentCode='.$StorePurchaseRequest->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $StorePurchaseRequest->name;?> </a></li>
                       
                        
                         <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    <li class="col-sm-2">
                    	<?php
                                $StoreStoreChallanReturnVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', '%Store Challan Return%')->get();
                                ?>
								@foreach($StoreStoreChallanReturnVouchers as $StoreStoreChallanReturnVoucher)
                                
                	
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $StoreStoreChallanReturnVoucher->title }}</li>
                    	<?php
                                    $StoreStoreChallanReturns = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreStoreChallanReturnVoucher->id)->get();
                                    foreach($StoreStoreChallanReturns as $StoreStoreChallanReturn){
                                    $StoreStoreChallanReturnMakeUrl = url(''.$StoreStoreChallanReturn->m_controller_name.'');
                                    ?>
                        <li><a href="<?php echo url(''.$StoreStoreChallanReturn->m_controller_name.'?pageType='.$StoreStoreChallanReturn->m_type.'&&parentCode='.$StoreStoreChallanReturn->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $StoreStoreChallanReturn->name;?></a></li>
                        <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    <li class="col-sm-2">
                    	<?php
                                $StoreMasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Store')->where('status','=','1')->where('menu_type','=','3')->get();
                                ?>
								@foreach($StoreMasterMenuTitles as $StoreMasterMenuTitle)
                
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $HRAllowance->title }}</li>
                    <?php
                                    $StoresubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$StoreMasterMenuTitle->id)->get();
                                    foreach($StoresubMenu as $Storerow1){
                                    $StoremakeUrl = url(''.$Storerow1->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$Storerow1->m_controller_name.'?pageType='.$Storerow1->m_type.'&&parentCode='.$Storerow1->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $Storerow1->name;?></a></li>
                       
                        <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    <li class="col-sm-2">
                    </li>
          	</ul>
        </li>
      </ul>
      	<!--Store end-->
        
        
         <!--Sale Begin-->
       	<ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle {{ Request::is('sales/s','sales/*')? 'active-erp': '' }}" data-toggle="dropdown"><i class="fa fa-tags" aria-hidden="true"></i> Sale</a>
			<ul class="dropdown-menu mega-dropdown-menu row">
                    <li class="col-sm-2">
                    	<?php
                                $SaleCashVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Sales')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Cash Sale Vouchers')->get();
                                ?>
								@foreach($SaleCashVouchers as $SaleCashVoucher)
                
                	
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $SaleCashVoucher->title }}</li>
                    <?php
                                    $SaleCashs = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$SaleCashVoucher->id)->get();
                                    foreach($SaleCashs as $SaleCash){
                                    $SaleCashMakeUrl = url(''.$SaleCash->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$SaleCash->m_controller_name.'?pageType='.$SaleCash->m_type.'&&parentCode='.$SaleCash->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $SaleCash->name;?></a></li>
                        
                         <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    <li class="col-sm-2">
                    	<?php
                                $SaleCreditVouchers = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Sales')->where('status','=','1')->where('menu_type','=','1')->Where('title', 'like', 'Credit Sale Vouchers')->get();
                                ?>
								@foreach($SaleCreditVouchers as $SaleCreditVoucher)
                	
                	<ul class="list-unstyled">
                    <li class="dropdown-header">{{ $SaleCreditVoucher->title }}</li>
                     <?php
                                    $SaleCredits = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$SaleCreditVoucher->id)->get();
                                    foreach($SaleCredits as $SaleCredit){
                                    $SaleCreditMakeUrl = url(''.$SaleCredit->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$SaleCredit->m_controller_name.'?pageType='.$SaleCredit->m_type.'&&parentCode='.$SaleCredit->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $SaleCredit->name;?></a></li>
                       
                        
                        <?php } ?>
								@endforeach
                    </ul>
                    </li>
                    <li class="col-sm-2">
                    	 <h4>Sale Reports</h4>
                    </li>
                    <li class="col-sm-2">
                    	 <?php
                                $SaleMasterMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Sales')->where('status','=','1')->where('menu_type','=','2')->get();
                                ?>
								@foreach($SaleMasterMenuTitles as $SaleMasterMenuTitle)
                    
                    <ul class="list-unstyled">
                    <li class="dropdown-header">{{ $SaleMasterMenuTitle->title }}</li>
                    <?php
                                    $SalesubMenu = DB::table('menu')->select(['m_type','name','m_controller_name','m_main_title','id','m_parent_code'])->where('m_parent_code','=',$SaleMasterMenuTitle->id)->get();
                                    foreach($SalesubMenu as $Salerow1){
                                    $SalemakeUrl = url(''.$Salerow1->m_controller_name.'');
                                    ?>
                    	<li><a href="<?php echo url(''.$Salerow1->m_controller_name.'?pageType='.$Salerow1->m_type.'&&parentCode='.$Salerow1->m_parent_code.'&&m='.$m.'#SFR')?>"><i class="glyphicon glyphicon-plus-sign"></i> <?php echo $Salerow1->name;?></a></li>
                       
                        
                        <?php } ?>
								@endforeach
                    </ul>
                    </li>
          	</ul>
        </li>
      </ul>
      	<!--Sale end-->
      
      	
        <!--Users Begin-->
       	<ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-users" aria-hidden="true"></i> Users</a>
			<ul class="dropdown-menu mega-dropdown-menu row">
                    <li class="col-sm-2">
                    	 <h5>Hello Users</h5>
                    </li>
          	</ul>
        </li>
      </ul>
      	<!--Users end-->
        
        
        <!--Reports Begin-->
       	<ul class="nav navbar-nav">
        <li class="dropdown mega-dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-print" aria-hidden="true"></i> Reports</a>
			<ul class="dropdown-menu mega-dropdown-menu row">
                    <li class="col-sm-2">
                    	<h5>Hello Reports</h5>
                    </li>
          	</ul>
        </li>
      </ul>
      	<!--Reports end-->
        
      
      
    </div>
    <!-- /.nav-collapse -->
  </nav>
</div>













<br />
<br />

<!--For Demo Only (End Removable) -->
<input type="hidden" id="url" value="<?php echo url('/') ?>">


<!-- MENU SECTION END-->