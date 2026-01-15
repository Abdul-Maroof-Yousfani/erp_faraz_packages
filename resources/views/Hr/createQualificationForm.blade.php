<?php
$accType = Auth::user()->acc_type;
//if($accType == 'client'){
//	$m = $_GET['m'];
//}else{
//	$m = Auth::user()->company_id;
//}
//$d = DB::selectOne('select `dbName` from `company` where `id` = '.$m.'')->dbName
$m = $_GET['m'];
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
								<span class="subHeadingLabelClass">Create Qualification Form</span>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<?php echo Form::open(array('url' => 'had/addQualificationDetail?m='.$m.'','id'=>'qualificationForm'));?>
						<input type="hidden" name="_token" value="{{ csrf_token() }}">
						<input type="hidden" name="pageType" value="<?php echo $_GET['pageType']?>">
						<input type="hidden" name="parentCode" value="<?php echo $_GET['parentCode']?>">
						<div class="panel">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<input type="hidden" name="qualificationSection[]" class="form-control" id="qualificationSection" value="1" />
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label>Institute Name:</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<select name="institute_name_1" class="form-control requiredField">
											@foreach($institutes as $key => $i)
												<option value="{{ $i->id}}">{{ $i->institute_name}}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
										<label>Qualification Name:</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<input type="text" name="qualification_name_1" id="qualification_name_1" placeholder="Qualification Name" value="" class="form-control requiredField" />
									</div>
								</div>

								<div class="row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<label>Country</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<select name="country_1" id="country_1" class="form-control requiredField">
											<option value="">Select Country</option>
											@foreach($countries as $key => $y)
												<option value="{{ $y->id}}">{{ $y->nicename}}</option>
											@endforeach
										</select>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<label>State</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<span id="state_area_1">
											<select name="state_1" class="form-control" id="state_1" onchange="changeCity(this.value)">
												<option value="">Select State</option>
											</select>
										</span>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
										<label>City</label>
										<span class="rflabelsteric"><strong>*</strong></span>
										<span id="city_area_1">
											<select name="city_1" id="city_1" class="form-control requiredField">
												<option value="">Select City</option>
											</select>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="lineHeight">&nbsp;</div>
						<div class="qualificationSection"></div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-right">
								{{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
								<button type="reset" id="reset" class="btn btn-danger">Clear Form</button>
								<input type="button" class="btn btn-primary addMoreQualificationSection" value="Add More Qualification's Section" />
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
		var qualification = 1;
		$('.addMoreQualificationSection').click(function (e){
			e.preventDefault();
        	qualification++;
			
			$.ajax({
				url: '<?php echo url('/')?>/hmfal/makeFormQualificationDetail',
				type: "GET",
				data: { id:qualification},
				success:function(data) {
					$('.qualificationSection').append('<div id="sectionQualification_'+qualification+'"><a href="#" onclick="removeQualificationSection('+qualification+')" class="btn btn-sm btn-danger">Remove</a><div class="lineHeight">&nbsp;</div><div class="panel"><div class="panel-body">'+data+'</div></div></div>');
              	}
          	});
		});
		
		// Wait for the DOM to be ready
		$(".btn-success").click(function(e){
			var qualification = new Array();
			var val;
			$("input[name='qualificationSection[]']").each(function(){
    			qualification.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of qualification) {
				
				jqueryValidationCustom();
				if(validate == 0){
					//alert(response);
				}else{
					return false;
				}
			}
			
		});

        $("#country_1").on('change', function() {

            var countryID = $('#country_1').val();

            if(countryID) {
                $.ajax({
                    url: '<?php echo url('/')?>/slal/stateLoadDependentCountryId',
                    type: "GET",
                    data: { id:countryID},
                    success:function(data) {
						$('#state_1').html(data);

                    }
                });
            }
        });


    });



	function changeCity(id){
		var res = id.split("_");
		var stateID = $('#'+id+'').val();
		if(stateID) {
			$.ajax({
				url: '<?php echo url('/')?>/slal/cityLoadDependentStateId',
				type: "GET",
				data: { id:stateID},
				success:function(data) {
					$('#city_1').empty();
                    $('#city_1').html(data);
					//$('#city_'+res[1]+'').html(data);
                }
            });
        }else{
        	$('#city_'+res[1]+'').empty();
        }
	}

	function removeQualificationSection(id){
		var elem = document.getElementById('sectionQualification_'+id+'');
    	elem.parentNode.removeChild(elem);
	}
</script>
@endsection