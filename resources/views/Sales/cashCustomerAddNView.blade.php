@extends('layouts.default')

@section('content')
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						@include('Sales.salesMenu')
					</div>
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
									<div class="row">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<span class="subHeadingLabelClass">Add Cash Customer</span>
										</div>
									</div>
									<div class="lineHeight">&nbsp;</div>
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<?php
													echo Form::open(array('url' => 'sad/addCashCustomerDetail','id'=>'addCashCustomerForm'));
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
														<label>Customer Name</label>
														<input type="text" name="customer_name" id="customer_name" value="" class="form-control" />
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Country</label>
														<select name="country" id="country" class="form-control">
															<option value="">Select Country</option>
															@foreach($countries as $key => $y)
                                    							<option value="{{ $y->id}}">{{ $y->nicename}}</option>
                                    						@endforeach
														</select>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>State</label>
														<select name="state" id="state" class="form-control">
														
														</select>
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>City</label>
														<select name="city" id="city" class="form-control">
														
														</select>
													</div>
													
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Contact No</label>
														<input type="text" name="contact_no" id="contact_no" value="" class="form-control" />
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<label>Email</label>
														<input type="text" name="email" id="email" value="" class="form-control" />
													</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<div class="row">
															<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
    	 														<label for="o_blnc" >Opening Balance </label>               
    															<input type="number" name="o_blnc" maxlength="15" min="0" id="o_blnc" placeholder="OPENING BALANCE" class="form-control sf-uc-first text-right" value="" autocomplete="off"/>
    														</div>
    														<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			  													<label for="o_blnc_trans">Transaction </label>
																<select name="o_blnc_trans" id="o_blnc_trans" class="form-control sf-uc-first text-left">
              														<option value="">select</option>
              														<option value="1"><strong>Debit</strong></option>
               														<option value="0"><strong>Credit</strong></option>
																</select>
    														</div>     
														</div>
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
											<span class="subHeadingLabelClass">View Cash Customer</span>
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
                                        						<th class="text-center">Customer Name</th>        
																<th class="text-center">Contact No</th>
																<th class="text-center">Email</th>
																<th class="text-center">Action</th>
															</thead>
															<tbody id="viewCashCustomerList">
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
		function viewCashCustomerList(){
			$('#viewCashCustomerList').html('<tr><td colspan="5"><div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center"><div class="loader"></div></div></div></div></td><tr>');
			$.ajax({
					url: '<?php echo url('/')?>/sdc/viewCashCustomerList',
					type: "GET",
					success:function(data) {
						setTimeout(function(){
							$('#viewCashCustomerList').html(data);
							$('#state').empty();
							$('#city').empty();
						},1000);
                    }
                });
		}
		viewCashCustomerList();
		
		
		$(function(){
			$('#addCashCustomerForm').on('submit',function(e){
    			$.ajaxSetup({
        			header:$('meta[name="_token"]').attr('content')
    			})
    			e.preventDefault(e);

        		$.ajax({
					type:"POST",
        			url:'<?php echo url('/')?>/sad/addCashCustomerDetail',
        			data:$(this).serialize(),
        			dataType: 'json',
        			success: function(data){
            			console.log(data);
					},
        			error: function(data){
					}
    			})
				$("#reset").click();
				viewCashCustomerList();
			});
		});
		
		
		$('select[name="country"]').on('change', function() {
			var countryID = $(this).val();
			if(countryID) {
				$.ajax({
					url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
					type: "GET",
					data: { id:countryID},
					success:function(data) {
						$('select[name="city"]').empty();
						$('select[name="state"]').empty();
						$('select[name="state"]').html(data);
                    }
                });
            }else{
                $('select[name="state"]').empty();
				$('select[name="city"]').empty();
            }
        });
		
		$('select[name="state"]').on('change', function() {
			var stateID = $(this).val();
			if(stateID) {
				$.ajax({
					url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
					type: "GET",
					data: { id:stateID},
					success:function(data) {
						$('select[name="city"]').empty();
						$('select[name="city"]').html(data);
                    }
                });
            }else{
                $('select[name="city"]').empty();
            }
        });
    });
</script>
@endsection