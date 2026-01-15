@extends('layouts.default')

@section('content')

	<div class="panel">
		<div class="panel-body">
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
					<div class="well_N">
					<div class="dp_sdw">	
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<span class="subHeadingLabelClass">Add Sub Menu</span>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											{{ Form::open(array('url' => 'uad/addSubMenuDetail','id'=>'addSubMenuForm')) }}

												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
													<label>Main Navigation Name</label>
													<select class="form-control" name="main_navigation_name" id="main_navigation_name">
														<option value="">Select Main Navigation</option>
														@foreach($MainMenuTitles as $key => $y)
															<option value="<?php echo $y->id.'_'.$y->title_id?>">{{ $y->title}}</option>
														@endforeach

													</select>
												</div>
												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
													<label>Sub Navigation Title Name</label>
													<input type="text" name="sub_navigation_title_name" id="sub_navigation_title_name" value="" class="form-control" />
												</div>
												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
													<label>Sub Navigation Url</label>
													<input type="text" name="sub_navigation_url" id="sub_navigation_url" value="" class="form-control" />
												</div>
												<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">>
													<label>Page Type</label>
													<select class="form-control" name="page_type" id="page_type">
														<option value="1">Outer Page</option>
														<option value="2">Inner Page</option>
													</select>
												</div>
												<div>&nbsp;</div>
												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
													{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
													<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
												</div>
											{{ Form::close() }}
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<span class="subHeadingLabelClass">View Sub Menu</span>
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
															<th class="text-center">Sub Navigation</th>
															<th class="text-center">Action</th>
														</thead>
														<tbody id="viewSubMenuList">
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
		</div>
	</div>

<script type="text/javascript">
	$(document).ready(function() {
		function viewSubMenuList(){
			$('#viewSubMenuList').html('<tr><td colspan="4"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
			$.ajax({
					url: '<?php echo url('/')?>/udc/viewSubMenuList',
					type: "GET",
					success:function(data) {
						setTimeout(function(){
							$('#viewSubMenuList').html(data);
						},1000);
                    }
                });
		}
		viewSubMenuList();
		
		
		$(function(){
			$('#addSubMenuForm').on('submit',function(e){
    			$.ajaxSetup({
        			header:$('meta[name="_token"]').attr('content')
    			})
    			e.preventDefault(e);

        		$.ajax({
					type:"POST",
        			url:'<?php echo url('/')?>/uad/addSubMenuDetail',
        			data:$(this).serialize(),
        			dataType: 'json',
        			success: function(data){
						alert(data);
            			console.log(data);
					},
        			error: function(data){
					}
    			})
				$("#reset").click();
				viewSubMenuList();
			});
		});
    });
</script>
@endsection