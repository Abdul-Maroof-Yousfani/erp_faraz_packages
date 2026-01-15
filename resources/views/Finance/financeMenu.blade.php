<?php 
	url('/');
	url()->current();
?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="bhoechie-tab-container">
			<div class="bhoechie-tab-menu">
				<div class="list-group">
					<?php 
						Config::set('database.default', 'mysql');
						DB::reconnect('mysql');
						$MainMenuTitles = DB::table('main_menu_title')->select(['title','id','title_id'])->where('main_menu_id','=','Finance')->get();
						$roleNo = Auth::user()->role_no;
					?>
					@foreach($MainMenuTitles as $MainMenuTitle)
						<div data-toggle="collapse" data-target="#{{ $MainMenuTitle->title_id }}" class="collapsed">
							<a href="#" class="list-group-item list-group-item-collaps">{{ $MainMenuTitle->title }}</a>
						</div>
						<div class="sub-menu collapse" id="{{ $MainMenuTitle->title_id }}">
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
									<?php 
										$columnName = 'right_'.$row1->m_type;
										$roleDetail = DB::selectOne('select '.$columnName.' as columnName from `role_detail` where `menu_id` = '.$row1->m_parent_code.' and `role_no` = "'.$roleNo.'"')->columnName;
										if($roleDetail == 1){	
									?>
									<a href="<?php echo url(''.$row1->m_controller_name.'?pageType='.$row1->m_type.'&&parentCode='.$row1->m_parent_code.'')?>" class="list-group-item <?php if(url()->current() == $makeUrl){echo 'triangle-isosceles right';}?>">&nbsp;<?php echo $row1->name;?></a>
							<?php 
										}
								}
							?>
                  		</div>
						<div class="lineHeight">&nbsp;</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
</div>