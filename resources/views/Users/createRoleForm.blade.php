
@extends('layouts.default')
@section('content')
	<style>
		.privilegesList {
			list-style-type: none;
			margin: 0;
			padding: 0;
			overflow: hidden;

		}

		.pagesList {
			float: left;
		}

		input[type="radio"], input[type="checkbox"]{ width:15px;
			height:15px;
		}
	</style>
	<link rel="stylesheet" href="{{ URL::asset('assets/css/select2.min.css') }}">
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="well_N">
				<div class="dp_sdw">	
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<span class="subHeadingLabelClass">Add Role and Permission Detail</span>
						</div>
					</div>
					<div class="lineHeight">&nbsp;</div>
                    <?php echo Form::open(array('url' => 'uad/addRoleDetail','id'=>'addRoleDetail'));?>
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<input type="hidden" name="company_id" value="{{ Input::get('m') }}">
					<div class="panel">
						<div class="panel-body">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label class="sf-label">Regions:</label>
									<span class="rflabelsteric"><strong>*</strong></span>
									<select class="form-control requiredField" name="region_id" id="region_id">
										<option value="">Select Region</option>
										@foreach($employee_regions as $key2 => $y2)
											<option value="{{ $y2->id}}">{{ $y2->employee_region}}</option>
										@endforeach
									</select>
								</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<label class="sf-label">Employee:</label>
									<span class="rflabelsteric"><strong>*</strong></span>
									<select class="form-control emp_code requiredField" name="emp_code" id="emp_code" required></select>
									<span id="emp_loader"></span>
								</div>

								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 approval_area">
									<label>Approval Code &nbsp;&nbsp;&nbsp;
										<input type="checkbox" value="1" id="approval_code_check" name="approval_code_check">
									</label>
									<input class="form-control" disabled="disabled" id="sampleField">
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<h3 class="panel-title"> Regions Access</h3>
										</div>
									</div>
									<p><b>User can view Employees of Region : </b></p>
									@foreach($employee_regions as $regionValue)
										<ul>
											<li class="pagesList">
												<input onclick="checkDepartmentAndRegions()" class="regions" name="employee_regions[]" checked="" type="checkbox" value="{{$regionValue->id}}">
												<strong> {{$regionValue->employee_region}}</strong>&nbsp;&nbsp;
											</li>
											<br>
										</ul>
									@endforeach
									<br>
									<span style="font-size:18px;" class="regionError"></span>
								</div>

								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<h3 class="panel-title"> Department Access</h3>
										</div>
									</div>
									<p><b>User can view Employees of Department : </b></p>
									@foreach($departments as $department)
										<ul>
											<li class="pagesList">
												<input onclick="checkDepartmentAndRegions()" class="department" name="department_permission[]" checked="" type="checkbox" value="{{$department->id}}">
												<strong> {{$department->department_name}}</strong>&nbsp;&nbsp;
											</li>
											<br>
										</ul>
									@endforeach
									<br>
									<span style="font-size:18px;" class="departmentError"></span>
								</div>

                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                    <?php
                                    $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['title_id', '=', 'dashboard'],['status', '=', 1]])->groupBy('main_menu_id')->get();

                                    $counter = 1;
                                    foreach($MainMenuTitles as $row){ ?>

                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title"> Dashboard
                                                <input style="float: right" checked type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" id="<?php echo $row->main_menu_id;?>" name="main_modules[]" value="<?php echo $row->id;?>">
                                            </h3>
                                            <span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
                                        </div>
                                        <div class="panel-body" id="Prilviges_<?php echo $row->main_menu_id;?>">
                                            <?php
                                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',1],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                            foreach($MainMenuTitlesSub as $row1){ ?>

                                            <div class="row">
                                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                    <label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
                                                    <input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                                                    <p><b>Pages / Screens :</b></p>
                                                    <?php
                                                    $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                    foreach($data as $dataValue){
                                                        $data = explode(' ',$dataValue->name);
                                                        ?>
                                                    <ul>
                                                        <li class="pagesList">
                                                            <input name="sub_menu_<?php echo $row1->title_id; ?>[]" checked type="checkbox" value="<?=$dataValue->id?>">
                                                            <strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
                                                        </li>
														<br>
                                                    </ul>
                                                <?php } ?>
                                                </div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<p><b>Default Dashboard</b></p>
													<?php
													$data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
													foreach($data as $dataValue){
													$data = explode(' ',$dataValue->name);
													?>
													<input type="radio" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="<?=$data[0]?>" /> <strong><?php echo $dataValue->name;?></strong>
													<br>
												<?php } ?>
												</div>
                                            </div>
                                            <div class="lineHeight">&nbsp;</div>
                                            <?php }?>
                                        </div>
                                    </div>
                                </div>
                                <?php }?>
                            </div>

                            <div class="row">
								<?php
                                $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id'])->where([['menu_type','=',1],['status', '=', 1],['title_id', '!=', 'dashboard']])->groupBy('main_menu_id')->get();

                                $counter = 1;
                                foreach($MainMenuTitles as $row){ ?>

								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<h3 class="panel-title"><?php echo $row->main_menu_id;?>
												<input style="float: right" checked type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>')" id="<?php echo $row->main_menu_id;?>" name="main_modules[]" value="<?php echo $row->id;?>">
											</h3>
											<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
										</div>
										<div class="panel-body" id="Prilviges_<?php echo $row->main_menu_id;?>">
                                            <?php
                                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',1],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                            foreach($MainMenuTitlesSub as $row1){ ?>

											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
													<input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<p><b>Pages / Screens :</b></p>
                                                    <?php
                                                    $data = DB::table('menu')->select(['name','id'])->where('m_parent_code','=',$row1->id)->get();
                                                    foreach($data as $dataValue){?>
													<li class="pagesList">
														<input name="sub_menu_<?php echo $row1->title_id; ?>[]" checked type="checkbox" value="<?=$dataValue->id?>">
														<strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
													</li>
                                                    <?php } ?>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<p><b>Actions :</b></p>
													<ul class="privilegesList">
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="view" /> <strong>View</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> <strong>Edit</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="approve" /> <strong>Approve</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="reject" /> <strong>Reject</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="repost" /> <strong>Repost</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> <strong>Delete</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> <strong>Print</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> <strong>Export</strong>&nbsp;&nbsp;</li>
													</ul>
												</div>
											</div>
											<div class="lineHeight">&nbsp;</div>
                                            <?php }?>
										</div>
									</div>
								</div>
                                <?php }?>
							</div>
							<div class="row">
                                <?php
                                $MainMenuTitles = DB::table('main_menu_title')->select(['id','main_menu_id', 'menu_type'])->where([['menu_type','=',2],['status', '=', 1]])->groupBy('main_menu_id')->get();

                                $counter = 1;
                                foreach($MainMenuTitles as $row){ ?>
								<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
									<div class="panel panel-primary">
										<div class="panel-heading">
											<h3 class="panel-title"><?php echo $row->main_menu_id;?>
												<input style="float: right" checked type="checkbox" onclick="showPrilvigesMenu('<?php echo $row->main_menu_id;?>','master')" id="<?php echo $row->main_menu_id;?>_master" name="main_modules[]" value="<?php echo $row->id;?>">
											</h3>
											<span class="pull-right clickable"><i class="glyphicon glyphicon-chevron-up"></i></span>
										</div>
										<div class="panel-body" id="Prilviges_master_<?php echo $row->main_menu_id;?>">
                                            <?php
                                            $MainMenuTitlesSub = DB::table('main_menu_title')->select(['main_menu_id','title','title_id','id'])->where([['main_menu_id','=',$row->main_menu_id],['menu_type','=',2],['status', '=', 1]])->orderBy('id', 'desc')->get();
                                            foreach($MainMenuTitlesSub as $row1){ ?>

											<div class="row">
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													<label style="text-decoration: underline;color:royalblue;"><?php echo $row1->title; ?> :</label>
													<input type="hidden" name="menu_title_<?php echo $row->id;?>[]" value="<?php echo $row1->title_id; ?>" />
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<p><b>Pages / Screens :</b></p>
                                                    <?php
                                                    $data = DB::table('menu')->select(['name','id'])->where([['m_parent_code','=',$row1->id]])->get();

                                                    foreach($data as $dataValue){?>
													<li>
														<input name="sub_menu_<?php echo $row1->title_id; ?>[]" checked type="checkbox" value="<?=$dataValue->id?>">
														<strong> <?php echo $dataValue->name;?></strong>&nbsp;&nbsp;
													</li>
                                                    <?php } ?>
												</div>
												<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
													<p><b>Actions :</b></p>
													<ul class="privilegesList">
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="edit" /> <strong>Edit</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="delete" /> <strong>Delete</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="print" /> <strong>Print</strong>&nbsp;&nbsp;</li>
														<li class="pagesList"><input type="checkbox" checked="checked" name="crud_rights_<?php echo $row1->title_id; ?>[]" value="export" /> <strong>Export</strong>&nbsp;&nbsp;</li>
													</ul>
												</div>
											</div>
											<div class="lineHeight">&nbsp;</div>
                                            <?php }?>
										</div>
									</div>
								</div>
                                <?php }?>
							</div>
							<div class="row">&nbsp;</div>
							<div class="row">&nbsp;</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
								<button type="submit" class="btn btn-success">Submit</button>
							</div>
						</div>
					</div>

                    <?php echo Form::close();?>
					<div class="text-right"><span class="regionError" style="font-size:18px;"></span></div>
					<div class="text-right"><span class="departmentError" style="font-size:18px;"></span></div>
				</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		$(function(){
			$('select[name="region_id"]').on('change', function() {
				var region_id = $(this).val();

				if(region_id == ''){alert('Please Select Region !');return false;}
				var m = '<?= Input::get('m'); ?>';
				if(region_id) {
					$('#emp_loader').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

					$.ajax({
						url: '<?php echo url('/')?>/slal/getEmployeeRegionList',
						type: "GET",
						data: {region_id:region_id,m:m},
						success:function(data) {
							$('#emp_loader').html('');
							$('select[name="emp_code"]').empty();
							$('select[name="emp_code"]').html(data);
							$("#emp_code option[value='All']").remove();
						}
					});
				}else{
					$('select[name="emp_code"]').empty();
				}
			});

			$('#approval_code_check').click(function(){
				if($(this).is(":checked") == true)
				{
					$("#sampleField").remove();
					$(".approval_area").append('<input class="form-control requiredField" required id="approval_code" name="approval_code" type="text" >');
				}
				else
				{
					$("#approval_code").remove();
					$(".approval_area").append('<input class="form-control" disabled="disabled" id="sampleField">');
				}
			});

			$('#region_id').select2();
			$('.emp_code').select2();

		});

        <?php /*?>$(document).on('click', '.panel-heading span.clickable', function(e){
            var $this = $(this);
            if(!$this.hasClass('panel-collapsed')) {
                $this.parents('.panel').find('.panel-body').slideUp();
                $this.addClass('panel-collapsed');
                $this.find('i').removeClass('glyphicon-chevron-up').addClass('glyphicon-chevron-down');
            } else {
                $this.parents('.panel').find('.panel-body').slideDown();
                $this.removeClass('panel-collapsed');
                $this.find('i').removeClass('glyphicon-chevron-down').addClass('glyphicon-chevron-up');
            }
        })<?php */?>
// $('li :checkbox').on('click', function () {
//             var $chk = $(this),
//                 $li = $chk.closest('li'),
//                 $ul, $parent;
//             if ($li.has('ul')) {
//                 $li.find(':checkbox').not(this).prop('checked', this.checked)
//             }do{
//                 $ul = $li.parent();
//                 $parent = $ul.siblings(':checkbox');
//                 if ($chk.is(':checked')) {
//                     $parent.prop('checked', true)
//                 } else {
//                     $parent.prop('checked', false)
//                 }
//                 $chk = $parent;
//                 $li = $chk.closest('li');
//             } while ($ul.is(':not(.someclass)'));
//         });


        function showPrilvigesMenu(name,type)
        {
            if(type == 'master')
			{
                if($('#'+name+'_master').is(':checked'))
                {
                    $('#Prilviges_master_'+name).css("display","block");
                }
                else
                {
                    $('#Prilviges_master_'+name).css("display","none");
                }
			}

            if($('#'+name).is(':checked'))
            {
                $('#Prilviges_'+name).css("display","block");
            }
            else
            {
                $('#Prilviges_'+name).css("display","none");
            }
        }

		function checkDepartmentAndRegions()
		{
			var region = document.querySelectorAll('input.regions:checked');
			var department = document.querySelectorAll('input.department:checked');

			if (region.length === 0 || department.length === 0) {
				if(region.length === 0) {
					$('.regionError').html('<span class="label label-danger">Please Select at least one Region.</span>');
					$('.btn').attr('disabled','disabled');
				}
				if(department.length === 0) {
					$('.departmentError').html('<span class="label label-danger">Please Select at least one Department.</span>');
					$('.btn').attr('disabled','disabled');
				}

			}
			else {
				$('.regionError').html('');
				$('.departmentError').html('');
                $('.btn').removeAttr('disabled')
			}
		}

	</script>
	<script src="{{ URL::asset('assets/js/select2.min.js') }}"></script>

@endsection