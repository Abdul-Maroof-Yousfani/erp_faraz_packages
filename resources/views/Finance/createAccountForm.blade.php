


<?php
	$accType = Auth::user()->acc_type;
	if($accType == 'client'){
		$m = $_GET['m'];
	}else{
		$m = Auth::user()->company_id;
	}
    use App\Helpers\CommonHelper;
?>
@extends('layouts.default')

@section('content')
	@include('select2')
	<script>
	function	check_uncheck()
	{
		if ($("#first_level_chk").is(":checked"))
		{
			$('#account_id').fadeOut();
		}

		else
		{
			$('#account_id').fadeIn();
		}
	}
	</script>
	<div class="well">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
						@include('Finance.'.$accType.'financeMenu')
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well_N">
						<div class="dp_sdw">
							<div class="row">
								<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
                                    <span class="subHeadingLabelClass">Add Chart of Account</span>
                                </div>
                                <div class="col-sm-4 ">
                                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                                        data-target="#exampleModal" style="float: right;"> Import csv </button>
                                </div>
							</div>
							
							<div class="row">
								<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
									<div class="panel">
										<div class="panel-body">
											<div class="row">
												<?php echo Form::open(array('url' => 'fad/addAccountDetail?m='.$m.'','id'=>'chartofaccountForm'));?>
													<input type="hidden" name="_token" value="{{ csrf_token() }}">
    												<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														<input type="hidden" name="chartofaccountSection[]" class="form-control" id="chartofaccountSection" value="1" />
													</div>
													<div class="form-group">

														
														<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

															<label>
																<input type="checkbox" name="operational" value="1" checked="checked" />  <b>Operational</b>
															</label>
														</div>
													</div>
													<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
														<label>Parent Account Head:</label>
														<span class="rflabelsteric"><strong>*</strong></span>
														<select onchange="get_nature_type()" class="form-control select2" name="account_id" id="account_id">
                                    						<option value="">Select Account</option>
                                    						@foreach(CommonHelper::get_all_account_level_wise() as $key => $y)
                                    							<option value="{{ $y->code}}">{{ $y->code .' ---- '. $y->name}}</option>
                                    						@endforeach
                                    					</select>
													</div>
													<div class="col-lg-2 col-md-2 col-sm-12 col-xs-12">
														<label for="acc_name">New Account </label>
														<span class="rflabelsteric"><strong>*</strong></span>
														<input type="text" autofocus="autofocus" placeholder="New Account" class="form-control requiredField" name="acc_name" id="acc_name" value="" autocomplete="off" >
    												</div>
													<div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
														<label for="o_blnc" >Opening Balance </label>
													   <span class="rflabelsteric"><strong>*</strong></span>
													   <input type="any" name="o_blnc" maxlength="15" min="0" id="o_blnc" placeholder="Opening Balance" class="form-control requiredField" value="0" autocomplete="off"/>
												   </div>
												   <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12">
														 <label for="o_blnc_trans">Transaction </label>
													   <span class="rflabelsteric"><strong>*</strong></span>
													   <select name="o_blnc_trans" id="o_blnc_trans" class="form-control requiredField">
															 <option value="">select</option>
															 <option value="1"><strong>Debit</strong></option>
															  <option value="0"><strong>Credit</strong></option>
													   </select>
												   </div>
													<div>&nbsp;</div>
													<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
														{{ Form::submit('Submit', ['class' => 'btnn btn-success']) }}
														<button type="reset" id="reset" class="btnn btn-danger">Clear Form</button>
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
			</div>
		</div>
	</div>
	<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div id="importProducts___BV_modal_body_" class="modal-body">
                        <form action="{{ url('fad/uploadAccountDetail') }}" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="mb-3 col-sm-12 col-md-12">
                                    <fieldset class="form-group" id="__BVID__194">
                                        <div>
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="company_id" id="company_id"
                                                   value="{{ $m }}" />
                                            <input type="file" name='file' label="Choose File" required>
											<input type="hidden" name='m' value="{{ $m }}">
											
                                            <div id="File-feedback" class="d-block invalid-feedback">Field must be in csvformat</div>
                                        </div>
                                    </fieldset>
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <button type="submit" class="btn btn-primary btn-sm btn-block">Submit</button>
                                </div>
                                <div class="col-sm-6 col-md-6"><button onclick="download_csv_file()" target="_self" class="btn btn-info btn-sm btn-block">Download example</button></div>
                        
                            </div>
                        </form>

                        <div class="col-sm-12 col-md-12">
                            <table class="table table-bordered table-sm mt-4">
                                <tbody>
                                <tr>
                                    <td>Account</td>
                                    <th><span class="badge badge-outline-success">This Field is required and name must be unique</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td>Type</td>
                                    <th><span class="badge badge-outline-info">This Field is required</span></th>
                                </tr>
                                <tr>
                                    <td>Balance</td>
                                    <th><span class="badge badge-outline-info">This Field is required</span></th>
                                </tr>
                                <tr>
                                    <td>Debit/Credit</td>
                                    <th><span class="badge badge-outline-success">This Field is required</span>
                                    </th>
                                </tr>
                               
                                
                               

                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
<script>
    $(document).ready(function() {





		$(".btn-success").click(function(e){
			var chartofAccount = new Array();
			var val;
			$("input[name='chartofaccountSection[]']").each(function(){
    			chartofAccount.push($(this).val());
			});
			var _token = $("input[name='_token']").val();
			for (val of chartofAccount) {

				jqueryValidationCustom();
				if(validate == 0){
					//return false;
				}else{
					return false;
				}
			}
		});
	});


</script>
	<script type="text/javascript">

		$('#account_id').select2();

		function get_nature_type()
		{
			var nature=  $("#account_id option:selected").text();
			nature=nature.split('-');
			nature=nature[0];
			if (nature==1 ||  nature==4)
			{
				$('#o_blnc_trans').val(1);
			}

			else
			{
				$('#o_blnc_trans').val(0);
			}
		}

		var csvFileData = [
		];

		function download_csv_file() {

			var csv = 'Account,Parent Account,Balance,Debit/Credit\n';

			csvFileData.forEach(function (row) {
				csv += row.join(',');
				csv += "\n";
			});

			var hiddenElement = document.createElement('a');
			hiddenElement.href = 'data:text/csv;charset=utf-8,' + encodeURI(csv);
			hiddenElement.target = '_blank';

			hiddenElement.download = 'Chat Of Accounts file.csv';
			hiddenElement.click();
		}

	</script>
@endsection
