<?php 
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}
	$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
?>
@extends('layouts.default')

@section('content')
	<?php 
		$currentDate = date('Y-m-d');
	?>
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12">
						@include('Hr.'.$accType.'hrMenu')
					</div>
					<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
						<div class="well">
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<span class="subHeadingLabelClass">Create Health Insurance Form</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<?php echo Form::open(array('url' => 'had/addHealthInsuranceDetail?m='.$m.'&&d='.$d.'','id'=>'healthInsuranceForm'));?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
								<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<input type="hidden" name="healthInsuranceSection[]" class="form-control" id="healthInsuranceSection" value="1" />
											</div>		
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Health Insurance Name:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="healthInsurance_name_1" id="healthInsurance_name_1" value="" class="form-control requiredField" />
											</div>
										</div>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="healthInsuranceSection"></div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
										{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
										<input type="button" class="btn btn-sm btn-primary addMoreHealthInsuranceSection" value="Add More Health Insurance Section" />
									</div>
								</div>
							<?php echo Form::close();?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script>
    $(document).ready(function() {
		var healthInsurance = 1;
		$('.addMoreHealthInsuranceSection').click(function (e){
			e.preventDefault();
        	healthInsurance++;
			
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormHealthInsuranceDetail',
				type: "GET",
				data: { id:healthInsurance},
				success:function(data) {
					$('.healthInsuranceSection').append('<div id="sectionHealthInsurance_'+healthInsurance+'"><a href="#" onclick="removeHealthInsuranceSection('+healthInsurance+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});
		
		// Wait for the DOM to be ready
		$(".btn-success").click(function(e){
			var healthInsurance = new Array();
			var val;
			$("input[name='healthInsuranceSection[]']").each(function(){
    			healthInsurance.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of healthInsurance) {
				
				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
			
		});
		
	});
	
	function removeHealthInsuranceSection(id){
		var elem = document.getElementById('sectionHealthInsurance_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection