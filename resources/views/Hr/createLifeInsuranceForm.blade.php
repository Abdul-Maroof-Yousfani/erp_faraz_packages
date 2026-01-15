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
									<span class="subHeadingLabelClass">Create Life Insurance Form</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<?php echo Form::open(array('url' => 'had/addLifeInsuranceDetail?m='.$m.'&&d='.$d.'','id'=>'lifeInsuranceForm'));?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
								<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<input type="hidden" name="lifeInsuranceSection[]" class="form-control" id="lifeInsuranceSection" value="1" />
											</div>		
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Life Insurance Name:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="lifeInsurance_name_1" id="lifeInsurance_name_1" value="" class="form-control requiredField" />
											</div>
										</div>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="lifeInsuranceSection"></div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
										{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
										<input type="button" class="btn btn-sm btn-primary addMoreLifeInsuranceSection" value="Add More Life Insurance Section" />
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
		var lifeInsurance = 1;
		$('.addMoreLifeInsuranceSection').click(function (e){
			e.preventDefault();
        	lifeInsurance++;
			
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormLifeInsuranceDetail',
				type: "GET",
				data: { id:lifeInsurance},
				success:function(data) {
					$('.lifeInsuranceSection').append('<div id="sectionLifeInsurance_'+lifeInsurance+'"><a href="#" onclick="removeLifeInsuranceSection('+lifeInsurance+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});
		
		// Wait for the DOM to be ready
		$(".btn-success").click(function(e){
			var lifeInsurance = new Array();
			var val;
			$("input[name='lifeInsuranceSection[]']").each(function(){
    			lifeInsurance.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of lifeInsurance) {
				
				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
			
		});
		
	});
	
	function removeLifeInsuranceSection(id){
		var elem = document.getElementById('sectionLifeInsurance_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection