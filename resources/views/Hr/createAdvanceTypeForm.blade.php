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
									<span class="subHeadingLabelClass">Create Advance Type Form</span>
								</div>
							</div>
							<div class="lineHeight">&nbsp;</div>
							<?php echo Form::open(array('url' => 'had/addAdvanceTypeDetail?m='.$m.'&&d='.$d.'','id'=>'advanceTypeForm'));?>
								<input type="hidden" name="_token" value="{{ csrf_token() }}">
								<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
								<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
								<div class="panel">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<input type="hidden" name="advanceTypeSection[]" class="form-control" id="advanceTypeSection" value="1" />
											</div>		
										</div>
										<div class="row">
											<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
												<label>Advance Type Name:</label>
												<span class="rflabelsteric"><strong>*</strong></span>
												<input type="text" name="advance_type_name_1" id="advance_type_name_1" value="" class="form-control requiredField" />
											</div>
										</div>
									</div>
								</div>
								<div class="lineHeight">&nbsp;</div>
								<div class="advanceTypeSection"></div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
										{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
										<button type="reset" id="reset" class="btn btn-primary">Clear Form</button>
										<input type="button" class="btn btn-sm btn-primary addMoreAdvanceTypeSection" value="Add More Advance Type Section" />
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
		var advanceType = 1;
		$('.addMoreAdvanceTypeSection').click(function (e){
			e.preventDefault();
        	advanceType++;
			
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormAdvanceTypeDetail',
				type: "GET",
				data: { id:advanceType},
				success:function(data) {
					$('.advanceTypeSection').append('<div id="sectionAdvanceType_'+advanceType+'"><a href="#" onclick="removeAdvanceTypeSection('+advanceType+')" class="btn btn-xs btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});
		
		// Wait for the DOM to be ready
		$(".btn-success").click(function(e){
			var advanceType = new Array();
			var val;
			$("input[name='advanceTypeSection[]']").each(function(){
    			advanceType.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of advanceType) {
				
				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
			
		});
		
	});
	
	function removeAdvanceTypeSection(id){
		var elem = document.getElementById('sectionAdvanceType_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection