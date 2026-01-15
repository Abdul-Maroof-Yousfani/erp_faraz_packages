<?php 
	url('/');
	url()->current();
?>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<span class="subHeadingLabelClass">Add Companies</span>
			</div>
		</div>
		<div class="lineHeight">&nbsp;</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="panel">
					<div class="panel-body">
						<?php echo Form::open(array('url' => 'companies/addCompanyDetail','id'=>'companyForm'));?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="companiesSection[]" class="form-control" id="companiesSection" value="1" />
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label class="sf-label">Company Name</label>
								<span class="rflabelsteric"><strong>*</strong></span>
								<input type="text" class="form-control requiredField" placeholder="Company Name" name="company_name_1" id="company_name_1" value="" />
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label class="sf-label">Company Address</label>
								<span class="rflabelsteric"><strong>*</strong></span>
								<input type="text" class="form-control requiredField" placeholder="Company Address" name="company_address_1" id="company_address_1" value="" />
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
								<label class="sf-label">Contact No</label>
								<span class="rflabelsteric"><strong>*</strong></span>
								<input type="text" class="form-control requiredField" placeholder="Contact No" name="company_contact_no_1" id="company_contact_no_1" value="" />
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
								{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
								<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
							</div>
						</div>
						<?php echo Form::close();?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<span class="subHeadingLabelClass">View Companies</span>
			</div>
		</div>
		<div class="lineHeight">&nbsp;</div>
		<div class="row">
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
			</div>
			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
				<div class="panel">
					<div class="panel-body">
						<?php 
							$companiesList = DB::table('company')->select(['name','id','dbName'])->where('status','=','1')->get();
						?>
						@foreach($companiesList as $cRow1)
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 text-center">
								<div class="well">
									<a href="{{ url('/ccd/'.$cRow1->dbName.'?m='.$cRow1->id.'') }}" class="{{ Request::is('ccd/'.$cRow1->dbName.'')? 'triangle-isosceles': '' }} list-group-item list-group-item-collaps">{{ $cRow1->name }}</a>
								</div>	
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $(document).ready(function() {
		// Wait for the DOM to be ready
		$(".btn-success").click(function(e){
			var companies = new Array();
			var val;
			$("input[name='companiesSection[]']").each(function(){
    			companies.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of companies) {
    			jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
			
		});
		
	});
</script>