<?php
$accType = Auth::user()->acc_type;
if($accType == 'client'){
	$m = $_GET['m'];
}else{
	$m = Auth::user()->company_id;
}
use App\Helpers\CommonHelper;
use App\Helpers\FinanceHelper;

use App\Helpers\ReuseableCode;


$edit=ReuseableCode::check_rights(146);
$delete=ReuseableCode::check_rights(147);
$export=ReuseableCode::check_rights(223);


$company=ReuseableCode::get_account_year_from_to(Session::get('run_company'));

$from_date = $company[0];
$to_date = $company[1];


?>
@extends('layouts.default')

@section('content')
	<style>
		#myInput {

			background-position: 10px 10px;
			background-repeat: no-repeat;
			width: 100%;
			font-size: 16px;
			padding: 12px 20px 12px 40px;
			border: 1px solid #ddd;
			margin-bottom: 12px;
		}
	</style>
	<?php  $_SERVER['REMOTE_ADDR']; ?>
	<div class="well_N">
	<div class="dp_sdw">
		<div class="panel">
			<div class="panel-body">
				<div class="row">
					<div class="col-lg-2 col-md-2 col-sm-2 col-xs-12" style="display: none;">
						@include('Finance.'.$accType.'financeMenu')
					</div>
					<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
						<div class="well ">
						<div class='headquid'>
						<div class="row">
							
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
								<span class="subHeadingLabelClass">View Chart of Account</span>
							</div>
							<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12 text-right">
								<?php echo CommonHelper::displayPrintButtonInBlade('printAccountList','','1');?>
								<?php if($export == true):?>
									<button type="button" class="btn btn-warning" onclick="tableToExcel('myTable', 'Chart of Account List')">Export to CSV</button>
								<?php endif;?>
							</div>
						</div>
						</div>
						
							<div class="panel">
								<div class="panel-body">
									<div class="row" id="printAccountList">
										<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
											<div class="table-responsive" style="height: 800px !important;">
												<input type="text" id="myInput" onkeyup="myFunction()" placeholder="Search for names.." title="Type in a name" class="hidden-print">
												<table id="myTable" class="userlittab table table-bordered sf-table-list">
													<thead>
													<th class="text-center col-sm-1">S.No</th>
													<th class="text-center col-sm-1">Code</th>
													<th class="text-center">Account Name</th>
													<th class="text-center">Nature Of Account</th>
													<th class="text-center">Current Balance</th>
													<th class="text-center col-sm-1 hidden-print">Edit</th>
													<th class="text-center col-sm-1 hidden-print">Delete</th>
													</thead>
													<tbody>
													<?php $counter = 1;?>
													@foreach($accounts as $key => $y)



														<?php


														$array = explode('-',$y->code);
														$level = count($array);
														$nature = $array[0];
														?>

														<tr title="{{$y->id}}" @if($y->type==1)style="background-color:lightblue" @endif
														@if($y->type==4)style="background-color:lightgray"  @endif
														id="{{$y->id}}">
															<td class="text-center"><?php echo $counter++;?></td>
															<td>{{ '`'.$y->code}}</td>
															<td style="cursor: pointer" onclick="newTabOpen('<?php echo $from_date?>','<?php echo $to_date?>','<?php echo $y->code?>')">
																@if($level == 1)
																	<b style="font-size: 15px;font-weight: 600">{{ strtoupper($y->name)}}</b>
																@elseif($level == 2)
																	{{ '&emsp;&emsp;'. $y->name}}
																@elseif($level == 3)
																	{{ '&emsp;&emsp;&emsp;&emsp;'. $y->name}}
																@elseif($level == 4)
																	{{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
																@elseif($level == 5)
																	{{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
																@elseif($level == 6)
																	{{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
																@elseif($level == 7)
																	{{ '&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;'. $y->name}}
																@endif


															</td>
															<td>
																@if($nature == 01)
																	Assets
																@elseif($nature == 02)
																Liabilties

																@elseif($nature == 03)
																Equity
																@elseif($nature == 04)
																Expenses
																@elseif($nature == 05)
																Revenue
																@elseif($nature == 06)
																	Other Income
																@elseif($nature == 07)
																	Cost of Goods Sold
																@endif
															</td>
															<td class="text-right"><?php echo number_format(FinanceHelper::ChartOfAccountCurrentBalance($m,$level,$y->code),2);?></td>

															<td class="text-center hidden-print">
																<?php if($y->type!=0):?>
																	<span class="badge badge-success" style="background-color: #428bca !important">Link To Master</span>
																<?php endif?>
																@if ($y->id!=1 && $y->id!=2 && $y->id!=1 && $y->id!=3 && $y->id!=4 && $y->id!=5 && $y->type!=2)
																<?php if($edit == true):?>
																	<button    onclick="showDetailModelOneParamerter('fdc/editChartOfAccountForm/<?php echo $y->id ?>')" class="btn btn-primary btn-xs">Edit</button>
																<?php endif;?>
																@endif
															</td>
															<td class="hidden-print text-center">
																@if ($y->type==0 && $y->id!=1  && $y->id!=2 && $y->id!=1 && $y->id!=3 && $y->id!=4 && $y->id!=5)
																	<?php if($delete == true):?>
																	<button onclick="delete_record('{{$y->id}}')" type="button" class="btn btn-danger btn-xs">Delete</button>
																	<?php endif;?>
																@endif


															</td>
														</tr>
													@endforeach
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


	<script>
		function delete_record(id)
		{

			if (confirm('Are you sure you want to delete this request')) {
				$.ajax({
					url: '/fd/deletechartofaccount',
					type: 'Get',
					data: {id: id},

					success: function (response) {


						$('#' + id).remove();

					}
				});
			}
			else{}
		}
	</script>

	<script>
		function myFunction() {
			var input, filter, table, tr, td, i, txtValue;
			input = document.getElementById("myInput");
			filter = input.value.toUpperCase();
			table = document.getElementById("myTable");
			tr = table.getElementsByTagName("tr");
			for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[2];
				if (td) {
					txtValue = td.textContent || td.innerText;
					if (txtValue.toUpperCase().indexOf(filter) > -1) {
						tr[i].style.display = "";
					} else {
						tr[i].style.display = "none";
					}
				}
			}
		}

		var tableToExcel = (function() {
			var uri = 'data:application/vnd.ms-excel;base64,'
					, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
					, base64 = function(s) {
						return window.btoa(unescape(encodeURIComponent(s))) }
					, format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
			return function(table, name) {

				if (!table.nodeType)
					table = document.getElementById(table)
				var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
					console.log(table);
				window.location.href = uri + base64(format(template, ctx))
			}
		})()

        function newTabOpen(FromDate,ToDate,AccCode)
        {

            var Url = '<?php echo url('finance/viewTrialBalanceReportAnotherPage?')?>';
            window.open(Url+'from='+FromDate+'&&to='+ToDate+'&&acc_code='+AccCode, '_blank');
        }
	</script>


@endsection
