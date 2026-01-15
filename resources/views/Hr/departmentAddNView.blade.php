@extends('layouts.default')

@section('content')
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						@include('Hr.hrMenu')
					</div>
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<span class="subHeadingLabelClass">Add Department</span>
										</div>
									</div>
									<div class="lineHeight">&nbsp;</div>
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<?php
													echo Form::open(array('url' => 'had/addDepartmentDetail','id'=>'addDepartmentForm'));
												?>
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Department Name</label>
														<input type="text" name="department_name" id="department_name" value="" class="form-control" />
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
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<span class="subHeadingLabelClass">View Department</span>
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
                                        						<th class="text-center">Department Name</th> 
																<th class="text-center">Action</th>
															</thead>
															<tbody id="viewDepartmentList">
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
		function viewDepartmentList(){
			$('#viewDepartmentList').html('<tr><td colspan="3"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
			$.ajax({
					url: '<?php echo url('/')?>/hdc/viewDepartmentList',
					type: "GET",
					success:function(data) {
						setTimeout(function(){
							$('#viewDepartmentList').html(data);
						},1000);
                    }
                });
		}
		viewDepartmentList();
		
		
		$(function(){
			$('#addDepartmentForm').on('submit',function(e){
    			$.ajaxSetup({
        			header:$('meta[name="_token"]').attr('content')
    			})
    			e.preventDefault(e);

        		$.ajax({
					type:"POST",
        			url:'<?php echo url('/')?>/had/addDepartmentDetail',
        			data:$(this).serialize(),
        			dataType: 'json',
        			success: function(data){
            			console.log(data);
					},
        			error: function(data){
					}
    			})
				$("#reset").click();
				viewDepartmentList();
			});
		});
    });
</script>
@endsection