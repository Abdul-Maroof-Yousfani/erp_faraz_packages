<?php 
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}
$currentDate = date('Y-m-d');
?>
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
								<span class="subHeadingLabelClass">Create Loan Type Form</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<?php echo Form::open(array('url' => 'had/addLoanTypeDetail?m='.$m.'&&d=','id'=>'loanTypeForm'));?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
						<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="hidden" name="loanTypeSection[]" class="form-control" id="loanTypeSection" value="1" />
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label>Loan Type Name:</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<input type="text" name="loan_type_name_1" id="loan_type_name_1" value="" class="form-control requiredField" />
									</div>
								</div>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="loanTypeSection"></div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
								{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
								<button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
								<input type="button" class="btn btn-primary addMoreLoanTypeSection" value="Add More Loan Type Section" />
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

		// Wait for the DOM to be ready
		$(".btn-success").click(function(e){
			var loanType = new Array();
			var val;
			$("input[name='loanTypeSection[]']").each(function(){
				loanType.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of loanType) {

				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}

		});


		var loanType = 1;
		$('.addMoreLoanTypeSection').click(function (e){
			e.preventDefault();
        	loanType++;
			
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormLoanTypeDetail',
				type: "GET",
				data: { id:loanType},
				success:function(data) {
					$('.loanTypeSection').append('<div id="sectionLoanType_'+loanType+'"><a href="#" onclick="removeLoanTypeSection('+loanType+')" class="btn btn-sm btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});
	});
	
	function removeLoanTypeSection(id){
		var elem = document.getElementById('sectionLoanType_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection