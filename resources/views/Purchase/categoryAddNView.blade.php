@extends('layouts.default')

@section('content')
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
						@include('Purchase.purchaseMenu')
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<span class="subHeadingLabelClass">Add Category</span>
										</div>
									</div>
									<div class="lineHeight">&nbsp;</div>
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<?php
													echo Form::open(array('url' => 'pad/addCategoryDetail','id'=>'addCategoryForm'));
												?>
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Account Head</label>
														<select name="account_head" id="account_head" class="form-control">
															<option value="">Select Account</option>
															@foreach($accounts as $key => $y)
                                    							<option value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                    						@endforeach
														</select>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Category Name</label>
														<input type="text" name="category_name" id="category_name" value="" class="form-control" />
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
											<span class="subHeadingLabelClass">View Category</span>
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
                                        						<th class="text-center">Category Name</th> 
																<th class="text-center">Action</th>
															</thead>
															<tbody id="viewCategoryList">
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
		function viewCategoryList(){
			$('#viewCategoryList').html('<tr><td colspan="3"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
			$.ajax({
					url: '<?php echo url('/')?>/pdc/viewCategoryList',
					type: "GET",
					success:function(data) {
						setTimeout(function(){
							$('#viewCategoryList').html(data);
						},1000);
                    }
                });
		}
		viewCategoryList();
		
		
		$(function(){
			$('#addCategoryForm').on('submit',function(e){
    			$.ajaxSetup({
        			header:$('meta[name="_token"]').attr('content')
    			})
    			e.preventDefault(e);

        		$.ajax({
					type:"POST",
        			url:'<?php echo url('/')?>/pad/addCategoryDetail',
        			data:$(this).serialize(),
        			dataType: 'json',
        			success: function(data){
            			console.log(data);
					},
        			error: function(data){
					}
    			})
				$("#reset").click();
				viewCategoryList();
			});
		});
    });
</script>
@endsection