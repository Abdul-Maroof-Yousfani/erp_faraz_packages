@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="well_N">
			<div class="dp_sdw">	
				<div class="row">
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<span class="subHeadingLabelClass">Add Main Menu Title</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<?php
										echo Form::open(array('url' => 'uad/addMainMenuTitleDetail','id'=>'addMainMenuTitleForm'));
									?>
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>Main Navigation Name</label>
											<input type="text" name="main_menu_name" id="main_menu_name" value="" class="form-control" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>Sub Navigation Title Name</label>
											<input type="text" name="title_name" id="title_name" value="" class="form-control" />
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
											<label>Menu Type</label>
											<select type="text" name="menu_type" id="menu_type" class="form-control" />
												<option value="1">Company</option>
												<option value="2">Master</option>
											</select>
										</div>
										<div>&nbsp;</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
											<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>

											<?php
												//echo Form::submit('Click Me!');
											?>
										</div>
									<?php
										echo Form::close();
									?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<span class="subHeadingLabelClass">View Main Menu Title</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<div class="table-responsive">
											<table class="table table-bordered sf-table-list">
												<thead>
													<th class="text-center">S.No</th>
													<th class="text-center">Main Navigation</th>
													<th class="text-center">Sub Navigation Title</th>
													<th class="text-center">Action</th>
												</thead>
												<tbody id="viewMainMenuTitleList">
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
	</div>

	<script type="text/javascript">
		$(document).ready(function() {
			function viewMainMenuTitleList(){
				$('#viewMainMenuTitleList').html('<tr><td colspan="4"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
				$.ajax({
						url: '<?php echo url('/')?>/udc/viewMainMenuTitleList',
						type: "GET",
						success:function(data) {
							setTimeout(function(){
								$('#viewMainMenuTitleList').html(data);
							},1000);
						}
					});
			}
			viewMainMenuTitleList();


			$(function(){
				$('#addMainMenuTitleForm').on('submit',function(e){
					$.ajaxSetup({
						header:$('meta[name="_token"]').attr('content')
					})
					e.preventDefault(e);

					$.ajax({
						type:"POST",
						url:'<?php echo url('/')?>/uad/addMainMenuTitleDetail',
						data:$(this).serialize(),
						dataType: 'json',
						success: function(data){
							console.log(data);
						},
						error: function(data){
						}
					})
					$("#reset").click();
					viewMainMenuTitleList();
				});
			});
		});
	</script>
@endsection