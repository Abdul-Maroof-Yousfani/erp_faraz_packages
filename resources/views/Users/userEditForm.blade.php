@extends('layouts.default')

@section('content')

	<div class="row">
		<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			<div class="well_N">
			<div class="dp_sdw">	
				<div class="row">
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<span class="subHeadingLabelClass">Update User</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									
									<?php
										echo Form::open(array('url' => 'users/editUser','id'=>'addMainMenuTitleForm'));
									?>
										<input type="hidden" name="_token" value="{{ csrf_token() }}">
										<input type="hidden" name="id" value="{{$Users->id}}">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>Name</label>
											<input type="text" name="name" id="name" value="{{$Users->name}}" class="form-control" />
										</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>Email</label>
											<input type="text" name="email" id="email" value="{{$Users->email}}" class="form-control" />
										</div>
										
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<label>Acount Type</label>
											<select onchange="checkUserForCategory(this.value)" type="text" name="acc_type" id="acc_type" class="form-control" />
												<option @if($Users->acc_type == 'client') selected @endif value="client">Client</option>
												<option @if($Users->acc_type == 'user') selected @endif value="user">User</option>
											</select>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 category hide">
											<label>Categories</label>
												<br>
										@php
											$userCategory = explode(',',$Users->categories_id)
										@endphp
										 @foreach($category as $key => $value)
										 <label for="checkbox{{$value->id}}">{{$value->main_ic}}</label>
										 <input 
										 	@foreach($userCategory as $userCategorykey => $userCategoryvalue)
										 
										 	@if($value->id == $userCategoryvalue ) checked @endif  
										 
										 		@endforeach 
										 	id="checkbox{{$value->id}}" type="checkbox" name="category[]" value="{{$value->id}}">
										 <br>
										@endforeach 
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 dashboard hide">
											<label>dashboard Access</label>
												<br>
										@php
											$dashboard_access = explode(',',$Users->dashboard_access);

										@endphp
										 
										 <label for="checkboxDash1">DashBoard</label>
										 <input 
										 	
										 	id="checkboxDash1" @if(in_array("dashboard", $dashboard_access)) checked @endif type="checkbox" name="dashboard_access[]" value="dashboard">
										 <br>
										 
										 <label for="checkboxDash2">Production Dashboard </label>
										 <input 
										 	
										 	id="checkboxDash2" @if(in_array("dashboard_production", $dashboard_access)) checked @endif  type="checkbox" name="dashboard_access[]" value="dashboard_production">
										 <br>
										 
										 <label for="checkboxDash3">Management Dashboard</label>
										 <input 
										 	
										 	id="checkboxDash3" @if(in_array("dashboard_management", $dashboard_access)) checked @endif  type="checkbox" name="dashboard_access[]" value="dashboard_management">
										 <br>
										</div>
										<div>&nbsp;</div>
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
											<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>											
										</div>
									<?php
										echo Form::close();
									?>
								</div>
							</div>
						</div>
					</div>					
				</div>
			</div>
			</div>
		</div>
	</div>	
@endsection

<script>
	function checkUserForCategory(value) {

		let checkboxes = document.querySelectorAll('input[type="checkbox"]');
		if(value == 'client')
		{
			
			checkboxes.forEach(function (checkbox) {
					checkbox.checked = true;
			});
		}
		else
		{
			checkboxes.forEach(function (checkbox) {
					checkbox.checked = false;
			});
		}
		
	}

</script>