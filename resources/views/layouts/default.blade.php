<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<meta name="description" content="" />
	<meta name="author" content="" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />

	<script>
		function updateTime() {
			var now = new Date();

			// Time
			var hours = now.getHours();
			var minutes = now.getMinutes();
			var ampm = hours >= 12 ? 'PM' : 'AM';
			hours = hours % 12;
			hours = hours ? hours : 12; // 12-hour clock
			minutes = minutes < 10 ? '0' + minutes : minutes;
			var timeString = "<h3>" + hours + ':' + minutes + ' <span>' + ampm + "</span></h3>";

			// Date
			var options = { year: 'numeric', month: 'long', day: 'numeric' };
			var dateString = now.toLocaleDateString('en-US', options);
			var dateElement = "<p class='date-head'>" + dateString + "</p>";

			// Update the HTML
			document.querySelector('.tim').innerHTML = timeString + dateElement;
		}

		// Update the time every minute
		document.addEventListener("DOMContentLoaded", function () {
			updateTime(); // Call the function initially to set the time and date

			setInterval(function () {
				updateTime();
			}, 60000);
		});
	</script>

	<!--[if IE]>
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<![endif]-->
	<title>
		{{env('APP_NAME')}}
	</title>
	<!-- BOOTSTRAP CORE STYLE  -->

	<link href="{{ URL::asset('app-assets/vendors/css/vendors.min.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('app-assets/css/colors.css') }}">
	<link href="{{ URL::asset('assets/custom/css/loader.css') }}" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
		integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<!-- FONT AWESOME STYLE  -->
	<!--<link href="{{ URL::asset('assets/css/font-awesome.css') }}" rel="stylesheet" />-->
	<!-- CUSTOM STYLE  -->
	<link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/css/style2.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/css/main.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/css/main2.css') }}" rel="stylesheet" />

	<link href="{{ URL::asset('assets/css/theme.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/css/color-one.css') }}" id="changeStyle" rel="stylesheet">
	<!-- GOOGLE FONT -->
	<link href="{{ URL::asset('assets/css/fa.css') }}" rel='stylesheet' type='text/css' />
	<link href="{{ URL::asset('assets/css/arrows.css') }}" rel='stylesheet' type='text/css' />
	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ URL::asset('assets/js/jquery-1.10.2.js') }}"></script>

	<script src="{{ URL::asset('assets/js/custom.js') }}"></script>


	<script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
	<link href="{{ URL::asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/MegaMenu/demo.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/MegaMenu/webslidemenu.css') }}" rel="stylesheet">
	<link href="{{ URL::asset('assets/custom/css/jquery.dataTables.min.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/charts/apexcharts.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/charts/chart-apex.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('app-assets/css/components.css') }}" rel="stylesheet" />

	<script src="{{ URL::asset('assets/MegaMenu/webslidemenu.js') }}"></script>
	<!-- <script src="{{ URL::asset('assets/custom/js/customFunctions.js') }}"></script> -->
	<script src="{{ URL::asset('assets/custom/js/customMainFunction.js') }}"></script>
	<script src="{{ URL::asset('assets/custom/js/jquery.cookie.min.js') }}"></script>


	<script src="{{ URL::asset('assets/custom/js/jquery.dataTables.min.js') }}"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
	<script src="https://cdn.ckeditor.com/4.8.0/full-all/ckeditor.js"></script>
	<style type="text/css" media="print">
		@media print {
			@page {
				margin-top: 0;
			}

			body {
				padding-top: 72px;
			}
		}
	</style>
	<style type="text/css">
		/*Abdul Qadir*/
		#rotate,
		#rotate2 {
			width: 100px;
			height: 100px;
			background: red;
			border-top: 10px solid black;
			transition: transform .2s ease;
			margin: 20px auto;
			color: #fff;
		}

		.rotated {
			-webkit-transform: rotate(90deg);
			-moz-transform: rotate(90deg);
			-o-transform: rotate(90deg);
			-ms-transform: rotate(90deg);
			transform: rotate(90deg);
		}



		/*  bhoechie tab */
		div.bhoechie-tab-container {
			z-index: 10;
			background-color: #ffffff;
			padding: 0 !important;
			border-radius: 4px;
			-moz-border-radius: 4px;
			background-clip: padding-box;
			opacity: 0.97;
			filter: alpha(opacity=97);
		}

		div.bhoechie-tab-menu {
			padding-right: 0;
			padding-left: 0;
			padding-bottom: 0;
		}

		div.bhoechie-tab-menu div.list-group {
			margin-bottom: 0;
		}

		div.bhoechie-tab-menu div.list-group>a {
			margin-bottom: 7px;
		}

		div.bhoechie-tab-menu div.list-group>a .glyphicon,
		div.bhoechie-tab-menu div.list-group>a .fa {
			color: #31b0d5;
		}

		div.bhoechie-tab-menu div.list-group>a:first-child {
			border-top-right-radius: 0;
			-moz-border-top-right-radius: 0;
		}

		div.bhoechie-tab-menu div.list-group>a:last-child {
			border-bottom-right-radius: 0;
			-moz-border-bottom-right-radius: 0;
		}

		div.bhoechie-tab-menu div.list-group>a.active,
		div.bhoechie-tab-menu div.list-group>a.active .glyphicon,
		div.bhoechie-tab-menu div.list-group>a.active .fa,
		.list-group-item.active,
		.list-group-item.active:hover,
		.list-group-item.active:focus {
			background-color: #31b0d5;
			background-image: #31b0d5;
			color: #ffffff;
			padding: 4px;
		}

		div.bhoechie-tab-menu div.list-group>a.active:after,
		{
		content: '';
		position: absolute;
		left: 100%;
		top: 50%;
		margin-top: -13px;
		border-left: 0;
		border-bottom: 13px solid transparent;
		border-top: 13px solid transparent;
		border-left: 10px solid #31b0d5;
		}

		div.bhoechie-tab-content {
			background-color: #ffffff;
			/* border: 1px solid #eeeeee; */
			padding-left: 20px;
			padding-top: 10px;
		}

		div.bhoechie-tab div.bhoechie-tab-content:not(.active) {
			display: none;
		}

		.list-group-item-collaps {
			margin-bottom: 0;
			border-bottom-right-radius: 4px;
			border-bottom-left-radius: 4px;
			color: #9170E4;
			font-size: 15px;
			border-bottom: 5px double #f3961c;
			padding: 5px;
		}

		.rflabelsteric {
			font-size: 17px !important;
			color: red !important;
		}

		a.list-group-item {
			color: #555;
			padding: 5px;
		}

		.triangle-isosceles.right:after {
			top: 12px;
		}

		.modalWidth {
			width: 80%
		}

		#content {
			display: table;
		}

		#pageFooter {
			display: table-footer-group;
		}

		#pageFooter:after {
			counter-increment: page;
			content: counter(page);
		}

		tr:hover {
			background-color: yellow;
		}
	</style>

</head>

<body>
	<?php
use App\Helpers\CommonHelper;
$page = Request::segment(2);


$accType = Auth::user()->acc_type;
CommonHelper::reconnectMasterDatabase();
 ?>
	@if($page != 'createAccountFormAjax')
		@include('includes._' . $accType . 'Navigation');
	@endif;

	<div class="container-fluid" id="mainSFContent">

		<script>

			function swalAdd() {
				swal({
					title: "Successfully Saved",
					text: "",
					type: "success",
					allowOutsideClick: true,
				});
			}
			function swalUpdate() {
				swal({
					title: "Successfully Updated",
					text: "",
					type: "success",
					allowOutsideClick: true,
				});
			}

			function swalDelete() {
				swal({
					title: "Successfully Deleted",
					text: "",
					type: "success",
					allowOutsideClick: true,
				});
			}

			function swalAlert(title, text) {
				swal(title, text);
			}
			$("body").on("submit", "form", function () {
				$(this).submit(function () {
					return false;
				});
				return true;
			});
		</script>


		@if ($errors->any())
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="alert alert-danger">
						<button type="button" class="close" data-dismiss="alert">×</button>
						@foreach ($errors->all() as $error)
							<li>{{ $error }}</li>
						@endforeach
					</div>
				</div>
			</div>
		@endif
		@if(Session::has('dataInsert'))
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="alert-success"><span class="glyphicon glyphicon-ok"></span> {!! session('dataInsert') !!}
					</div>
				</div>
			</div>

		@endif

		@if(Session::has('message'))


			<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center alert alert-success center">
				<strong>Success!</strong> {!! session('message') !!}
			</div>
		@endif
		@if(Session::has('error'))
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="alert-danger"><span class="glyphicon glyphicon-remove"></span><em>
							{!! session('error') !!}</em></div>
				</div>
			</div>
		@endif
		@if(Session::has('dataDelete'))
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="alert-danger"><span class="glyphicon glyphicon-remove"></span><em>
							{!! session('dataDelete') !!}</em></div>
				</div>
			</div>
		@endif
		@if(Session::has('dataEdit'))
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
					<div class="alert-info"><span class="glyphicon glyphicon-ok"></span><em>
							{!! session('dataEdit') !!}</em></div>
				</div>
			</div>
		@endif

		@if(Auth::user()->acc_type == 'client' || Auth::user()->acc_type == 'company')
			@yield('content')
		@else
				<?php
			$roleNo = Auth::user()->role_no;
			$getColumnName = 'right_' . app('request')->input('pageType') . '';
			$getParentCode = app('request')->input('parentCode');
			Config::set('database.default', 'mysql');
			DB::reconnect('mysql');
			//if(app('request')->input('parentCode') == ''){
			//$roleDetail = 0;
			//}else if(app('request')->input('parentCode') == 0){
			//$roleDetail = 1;
			//}else{
			//$roleDetail = DB::selectOne('select '.$getColumnName.' as columnName from `role_detail` where `menu_id` = '.$getParentCode.' and `role_no` = "'.$roleNo.'"')->columnName;
			//}
			//if($roleDetail == 1){
				?>
				@yield('content')
				<?php
			//}else{
			//echo 'No Permission';
			//}
				?>
		@endif

	</div>
	<script>

		function showDetailModelMasterTable(m, url, status, id, name, accId, tableName, modalName) {

			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
        ?>
			$.ajax({
				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { m: m, status: status, id: id, name: name, accId: accId, tableName: tableName },
				success: function (data) {
					jQuery('#showDetailModelMasterTable').modal('show', { backdrop: 'false' });
					jQuery('#showDetailModelMasterTable .modalTitle').html(modalName);
					jQuery('#showDetailModelMasterTable .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#showDetailModelMasterTable .modal-body').html(data);
					}, 1000);
				}
			});
		}

		function showDetailModelOneParamerter(url, id, modalName, m, type) {
			jQuery('#showDetailModelOneParamerter').modal('show', { backdrop: 'false' });
			//jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
			jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
			jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
			var EmailPrintSetting = 1;

			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
        ?>


			$.ajax({
				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m, EmailPrintSetting: EmailPrintSetting, type: type },
				success: function (data) {

					jQuery('#showDetailModelOneParamerter').modal('show', { backdrop: 'false' });
					//jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
					jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
					jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

					jQuery('#showDetailModelOneParamerter .modal-body').html(data);



				}
			});
		}
		function showDetailModelOneParamerterUpadted(url, id, modalName, m, type) {
			jQuery('#showDetailModelOneParamerter').modal('show', { backdrop: 'false' });
			//jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
			jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
			jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
			var EmailPrintSetting = 1;

			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
        ?>


			$.ajax({
				url: url,
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m, EmailPrintSetting: EmailPrintSetting, type: type },
				success: function (data) {

					jQuery('#showDetailModelOneParamerter').modal('show', { backdrop: 'false' });
					//jQuery('#showMasterTableEditModel').modal('show', {backdrop: 'true'});
					jQuery('#showDetailModelOneParamerter .modalTitle').html(modalName);
					jQuery('#showDetailModelOneParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');

					jQuery('#showDetailModelOneParamerter .modal-body').html(data);



				}
			});
		}
		function showMasterTableEditModel(url, id, modalName, m) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			<?php }?>
			$.ajax({
				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, m: m, pageType: pageType, parentCode: parentCode },
				success: function (data) {

					jQuery('#showMasterTableEditModel').modal('show', { backdrop: 'static', keyboard: false });
					$('#showMasterTableEditModel').modal({ backdrop: 'static', keyboard: false })
					jQuery('#showMasterTableEditModel .modalTitle').html(modalName);
					jQuery('#showMasterTableEditModel .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#showMasterTableEditModel .modal-body').html(data);
					}, 1000);


				}
			});
		}


		function showDetailModelTwoParamerter(url, id, modalName, m) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
		?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m },
				success: function (data) {

					jQuery('#showDetailModelTwoParamerter').modal('show', { backdrop: 'false' });
					jQuery('#showDetailModelTwoParamerter .modalTitle').html(modalName);
					jQuery('#showDetailModelTwoParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#showDetailModelTwoParamerter .modal-body').html(data);
					}, 1000);


				}
			});
		}

		function createJobtitle(url, id, modalName, m) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
		?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m },
				success: function (data) {

					jQuery('#createJobtitle').modal('show', { backdrop: 'false' });
					jQuery('#createJobtitle .modalTitle').html(modalName);
					jQuery('#createJobtitle .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#createJobtitle .modal-body').html(data);
					}, 1000);


				}
			});
			getJobtitle();
		}

		function createprospects(url, id, modalName, m) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
		?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m },
				success: function (data) {

					jQuery('#createprospects').modal('show', { backdrop: 'false' });
					jQuery('#createprospects .modalTitle').html(modalName);
					jQuery('#createprospects .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#createprospects .modal-body').html(data);
					}, 1000);


				}
			});
		}

		function showDetailModelTwoParamerterMaterial(url, id, modalName, m) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
		?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m },
				success: function (data) {

					jQuery('#material').modal('show', { backdrop: 'false' });
					jQuery('#material .modalTitle').html(modalName);
					jQuery('#material .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#material .modal-body').html(data);
					}, 1000);


				}
			});
		}

		function createContact(url, id, modalName, m) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
		?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m },
				success: function (data) {

					jQuery('#createContact').modal('show', { backdrop: 'false' });
					jQuery('#createContact .modalTitle').html(modalName);
					jQuery('#createContact .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#createContact .modal-body').html(data);
					}, 1000);


				}
			});
		}


		function createProduct(url, id, modalName, m, q_id) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
		?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { id: id, pageType: pageType, parentCode: parentCode, m: m },
				success: function (data) {

					jQuery('#createContact').modal('show', { backdrop: 'false' });
					jQuery('#createContact .modalTitle').html(modalName);
					jQuery('#createContact .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#createContact .modal-body').html(data);
					}, 1000);


				}
			});
		}

		function showDetailModelTwoParamerterJson(url, id, modalName, m) {
			var url = '<?php echo url('/')?>/' + url + '';
			<?php if (!empty($_GET['pageType'])) {?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php } ?>

			$.getJSON(url, { id: id, m: m }, function (result) {
				$.each(result, function (i, field) {

					jQuery('#showDetailModelTwoParamerterJson').modal('show', { backdrop: 'false' });
					jQuery('#showDetailModelTwoParamerterJson .modalTitle').html(modalName);
					jQuery('#showDetailModelTwoParamerterJson .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#showDetailModelTwoParamerterJson .modal-body').html(field);
					}, 500);

				});
			})


		}

		function showDetailModelFiveParamerter(url, modalName, pOne, pTwo, pThree) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
        ?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { pageType: pageType, parentCode: parentCode, m: m, pOne: pOne, pTwo: pTwo, pThree: pThree },
				success: function (data) {

					jQuery('#showDetailModelFiveParamerter').modal('show', { backdrop: 'false' });
					jQuery('#showDetailModelFiveParamerter .modalTitle').html(modalName);
					jQuery('#showDetailModelFiveParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#showDetailModelFiveParamerter .modal-body').html(data);
					}, 1000);


				}
			});
		}

		function showDetailModelSixParamerter(url, modalName, pOne, pTwo, pThree, pFour) {
			<?php
if (!empty($_GET['pageType'])) {
                ?>
			var pageType = '<?php echo $_GET['pageType'];?>';
			var parentCode = '<?php echo $_GET['parentCode'];?>';
			var m = '<?php echo $_GET['m'];?>';
			<?php
} else {
                ?>
			var pageType = true;
			var parentCode = true;
			var m = m;
			<?php
}
        ?>
			$.ajax({

				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { pageType: pageType, parentCode: parentCode, m: m, pOne: pOne, pTwo: pTwo, pThree: pThree, pFour: pFour },
				success: function (data) {

					jQuery('#showDetailModelSixParamerter').modal('show', { backdrop: 'false' });
					jQuery('#showDetailModelSixParamerter .modalTitle').html(modalName);
					jQuery('#showDetailModelSixParamerter .modal-body').html('<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>');
					setTimeout(function () {
						jQuery('#showDetailModelSixParamerter .modal-body').html(data);
					}, 1000);


				}
			});
		}


		var validate = 0;
		var amount_check = 0;
		function CheckDebitCredit() {
			debit = $("#d_t_amount_1").val();
			credit = $("#c_t_amount_1").val();
			if (debit == credit) {
				amount_check = 0;
				//alert("Amount Is Equal");
				return true;
			} else {
				amount_check = 1;
				//	alert("sm");
				return false;
			}
		}

		function jqueryValidationCustom() {
			var requiredField = document.getElementsByClassName('requiredField');
			var zerovalidate = document.getElementsByClassName('zerovalidate');
			for (i = 0; i < zerovalidate.length; i++) {
				var rf = zerovalidate[i].id;

				var checkType = requiredField[i].type;
				/*if(checkType == 'text'){
				 alert('Please type text');
				 }else if(checkType == 'select-one'){
				 alert('Please select one option');
				 }else if(checkType == 'number'){
				 alert('Please type number');
				 }else if(checkType == 'date'){
				 alert('Please type date');
				 }*/
				if (parseFloat($('#' + rf).val()) == 0) {

					$('#' + rf).css('border-color', 'red');
					$('#' + rf).focus();
					validate = 1;
					alert(rf + ' ' + '0 Can not Apply');
					return false;
				}
			}

			for (i = 0; i < requiredField.length; i++) {
				var rf = requiredField[i].id;
				var checkType = requiredField[i].type;
				/*if(checkType == 'text'){
				 alert('Please type text');
				 }else if(checkType == 'select-one'){
				 alert('Please select one option');
				 }else if(checkType == 'number'){
				 alert('Please type number');
				 }else if(checkType == 'date'){
				 alert('Please type date');
				 }*/
				if ($('#' + rf).val() == '') {
					$('#' + rf).css('border-color', 'red');
					$('#' + rf).focus();
					validate = 1;
					//alert(rf+' '+'Required');
					return false;
				}
				else {
					$('#' + rf).css('border-color', '#ccc');
					validate = 0;
				}
			}


			/*var requiredField1 = document.getElementsByClassName('requiredField');
			 for (i = 0; i < requiredField1.length; i++){
			 var rf1 = requiredField[i].id;
			 if($('#'+rf1+'').val() == ''){
			 validate = 1;
			 }else{
			 validate = 0;
			 }
			 }*/
			return validate;

		}



		function form_validate() {

			var requiredField = document.getElementsByClassName('requiredField');
			var zerovalidate = document.getElementsByClassName('zerovalidate');
			for (i = 0; i < zerovalidate.length; i++) {
				var rf = zerovalidate[i].id;

				var checkType = requiredField[i].type;
				/*if(checkType == 'text'){
				 alert('Please type text');
				 }else if(checkType == 'select-one'){
				 alert('Please select one option');
				 }else if(checkType == 'number'){
				 alert('Please type number');
				 }else if(checkType == 'date'){
				 alert('Please type date');
				 }*/
				if (parseFloat($('#' + rf).val()) == 0) {

					$('#' + rf).css('border-color', 'red');
					$('#' + rf).focus();
					validate = 0;
					alert(rf + ' ' + '0 Can not Apply');
					return false;
				}

				else {
					$('#' + rf).css('border-color', '#ccc');
					validate = 1;
				}
			}

			for (i = 0; i < requiredField.length; i++) {
				var rf = requiredField[i].id;
				var checkType = requiredField[i].type;
				/*if(checkType == 'text'){
				 alert('Please type text');
				 }else if(checkType == 'select-one'){
				 alert('Please select one option');
				 }else if(checkType == 'number'){
				 alert('Please type number');
				 }else if(checkType == 'date'){
				 alert('Please type date');
				 }*/
				if ($('#' + rf).val() == '') {
					$('#' + rf).css('border-color', 'red');
					$('#' + rf).focus();
					validate = 0;
					alert(rf + ' ' + 'Required');
					return false;
				}
				else {

					$('#' + rf).css('border-color', '#ccc');
					validate = 1;
				}
			}


			/*var requiredField1 = document.getElementsByClassName('requiredField');
			 for (i = 0; i < requiredField1.length; i++){
			 var rf1 = requiredField[i].id;
			 if($('#'+rf1+'').val() == ''){
			 validate = 1;
			 }else{
			 validate = 0;
			 }
			 }*/
			return validate;

		}
		var debit = 0;
		var credit = 0;
		function sum(id) {

			var sum_amount = 0;
			var sum_amount2 = 0;

			$("input[class *= 'd_amount_" + id + "']").each(function () {
				debit = $(this).val();

				console.log(debit + ' debit')
				sum_amount += +debit;


			});



			$('#d_t_amount_' + id + '').val(sum_amount.toFixed(2));

			//	$('#' + id).val(number);

			//alert(sum_amount);
			//$('#d_t_amount_'+id+'').val(parseFloat(sum_amount.toFixed(3)));

			$("input[class *= 'c_amount_" + id + "']").each(function () {
				credit = $(this).val();

				//credit=credit.replace(/,/g, "");
				sum_amount2 += +credit;


			});



			$('#c_t_amount_' + id + '').val(sum_amount2.toFixed(2));


			if ($('#d_t_amount_' + id + '').val() != $('#c_t_amount_' + id + '').val()) {
				$('#d_t_amount_' + id + '').css('background-color', '#C00');
				$('#d_t_amount_' + id + '').css('color', '#fff');
				$('#c_t_amount_' + id + '').css('background-color', '#C00');
				$('#c_t_amount_' + id + '').css('color', '#fff');
			} else {
				$('#d_t_amount_' + id + '').removeAttr('style');
				$('#c_t_amount_' + id + '').removeAttr('style');
			}

			var debit = $('#d_t_amount_1').val();

			var credit = $('#c_t_amount_1').val();

			var total_amount = debit - credit;

			$('.diff').val(total_amount.toFixed(2));
			$('#diff').val(total_amount.toFixed(2));

			toWords(1);

			if ($('.supplier_val').val() != '') {

				//	credit_calculate();

			}
		}

		// var th = ['','Thousand','Million', 'Billion','Trillion'];
		// var dg = ['Zero','One','Two','Three','Four', 'Five','Six','Seven','Eight','Nine'];
		// var tn = ['Ten','Eleven','Twelve','Thirteen', 'Fourteen','Fifteen','Sixteen', 'Seventeen','Eighteen','Nineteen'];
		// var tw = ['Twenty','Thirty','Forty','Fifty', 'Sixty','Seventy','Eighty','Ninety'];
		// function toWords(id) {

		// 	s = $('#d_t_amount_'+id+'').val();
		// 	s= parseFloat(s).toFixed(1);

		// 	s = s.toString();
		// 	s = s.replace(/[\, ]/g,'');
		// 	if (s != parseFloat(s)) return 'not a number';
		// 	var x = s.indexOf('.');
		// 	if (x == -1)
		// 		x = s.length;
		// 	if (x > 15)
		// 		return 'too big';
		// 	var n = s.split('');
		// 	var str = '';
		// 	var sk = 0;
		// 	for (var i=0;   i < x;  i++) {
		// 		if ((x-i)%3==2) {
		// 			if (n[i] == '1') {
		// 				str += tn[Number(n[i+1])] + ' ';
		// 				i++;
		// 				sk=1;
		// 			} else if (n[i]!=0) {
		// 				str += tw[n[i]-2] + ' ';
		// 				sk=1;
		// 			}
		// 		} else if (n[i]!=0) { // 0235
		// 			str += dg[n[i]] +' ';
		// 			if ((x-i)%3==0) str += 'hundred ';
		// 			sk=1;
		// 		}
		// 		if ((x-i)%3==1) {
		// 			if (sk)
		// 				str += th[(x-i-1)/3] + ' ';
		// 			sk=0;
		// 		}
		// 	}
		// 	var currency=  $('#curren :selected').text();
		// 	currency = currency.split('-');

		// 	if (x != s.length) {
		// 		var y = s.length;
		// 		// str += 'point ';
		// 		if(currency[0] == 'PKR') {
		// 			str += 'rupees ';
		// 		} else if(currency[0] == 'USD') {
		// 			str += 'cents ';
		// 		}			
		// 		for (var i=x+1; i<y; i++)
		// 			str += dg[n[i]] +' ';
		// 	}
		// 	result = str.replace(/\s+/g,' ')+'Only';

		// 	$('#rupees').text('Amount In Words:'+' '+result);
		// 	$('#rupees'+id).text('Amount In Words:'+' '+result);
		// 	$('#rupees').val(result);
		// 	$('#rupeess'+id).val(result);


		// 	var text=$('#rupees').text();
		// 	text=text +' '+'(' + currency[0] + ' ) ';

		// 	$('#rupees').text(text);

		// }
		function toWords(id) {

			var th = ['', 'Thousand', 'Million', 'Billion', 'Trillion'];
			var dg = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine'];
			var tn = ['Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eighteen', 'Nineteen'];
			var tw = ['Twenty', 'Thirty', 'Forty', 'Fifty', 'Sixty', 'Seventy', 'Eighty', 'Ninety'];

			var s = $('#d_t_amount_' + id).val();

			s = parseFloat(s).toFixed(2).toString().replace(/[\, ]/g, ''); // Ensure 2 decimal places

			if (isNaN(s)) return 'not a number';
			var x = s.indexOf('.');
			if (x == -1) x = s.length;
			if (x > 15) return 'too big';

			var n = s.split('');
			var str = '';
			var sk = 0;

			// Process the integer part of the amount
			for (var i = 0; i < x; i++) {
				if ((x - i) % 3 == 2) {
					if (n[i] == '1') {
						str += tn[Number(n[i + 1])] + ' ';
						i++;
						sk = 1;
					} else if (n[i] != '0') {
						str += tw[n[i] - 2] + ' ';
						sk = 1;
					}
				} else if (n[i] != '0') {
					str += dg[n[i]] + ' ';
					if ((x - i) % 3 == 0) str += 'Hundred ';
					sk = 1;
				}
				if ((x - i) % 3 == 1) {
					if (sk) str += th[Math.floor((x - i - 1) / 3)] + ' ';
					sk = 0;
				}
			}

			var currency = $('#curren :selected').text().split('-');
			var currencyText = currency[0] == 'PKR' ? 'Rupees ' : currency[0] == 'USD' ? 'Dollars ' : '';
			var currencyText2 = currency[0] == 'PKR' ? 'Paisa ' : currency[0] == 'USD' ? 'Cents ' : '';

			var decimalWords = '';
			if (x != s.length) {
				str += 'and ';
				var decimalPart = s.slice(x + 1);

				// Ensure decimal part has 2 digits
				while (decimalPart.length < 2) {
					decimalPart += '0';
				}

				// Handle decimal part
				if (decimalPart[0] == '1') {
					decimalWords = tn[Number(decimalPart) - 10]; // Fix: Subtract 10 to map correctly to the `tn` array
				} else {
					if (decimalPart[0] != '0') {
						decimalWords += tw[decimalPart[0] - 2] + ' ';
					}
					if (decimalPart[1] != '0') {
						decimalWords += dg[decimalPart[1]];
					}
				}

				decimalWords = decimalWords ? decimalWords.trim() : 'Zero';
				str += decimalWords + ' ' + currencyText2;
			} else {
				str += 'and Zero Paisa Only';
			}

			var result = currencyText + str.trim() + ' Only';
			$('#rupees').text('Amount In Words: ' + result);
			$('#rupees' + id).text('Amount In Words: ' + result);
			$('#rupeess').val(result);
			$('#rupeess' + id).val(result);
		}

		function credit_calculate() {

			var id = 1;
			var sum_amount = 0;
			$("input[class *= 'd_amount_" + id + "']").each(function () {
				debit = $(this).val();

				sum_amount += +debit;
				$('#c_amount_1_2').val(sum_amount);

				$('#dept_hidden_amount2').val(sum_amount);
				$('#dept_amount2').text(sum_amount);

				$('#cost_center_dept_amount2').text(sum_amount);
				$('#cost_center_dept_hidden_amount2').val(sum_amount);

				mainDisable('d_amount_1_2', 'c_amount_1_2');


			});

			debit_credit_diffrence();
		}


		function debit_credit_diffrence() {
			var id = 1;
			var sum_amount = 0;
			var sum_amount2 = 0;




			$("input[class *= 'c_amount_" + id + "']").each(function () {
				credit = $(this).val();

				//credit=credit.replace(/,/g, "");
				sum_amount2 += +credit;


			});
			$('#c_t_amount_1').val(sum_amount2);
			var debit = $('#d_t_amount_1').val();

			var credit = $('#c_t_amount_1').val();

			var total_amount = debit - credit;

			$('.diff').val(total_amount);
			$('#diff').val(total_amount);
		}
		function formate_number(id, number, hidden) {
			var val = $('#' + id).val();
			//val=val.replace(",","");
			val = val.replace(/,/g, "");
			var numbers = parseFloat(val).toFixed(2);
			if (!isNaN(numbers)) {
				val = numbers.toLocaleString('en');

				$('#' + id).val(numbers);

			}

			return false;


			$('#' + id).val(function (index, value) {

				return value
					.replace(/\D/g, "")
					.replace(/([0-9])([0-9]{2})$/, '$1.$2')
					.replace(/\B(?=(\d{3})+(?!\d)\.?)/g, ",")
					;


			});
			var val = $('#' + id).val();






			val = val.replace(/,/g, "");

			$('#' + hidden).val(val);


			calculations();
			/*
	
			 var number=$('#'+id).val();
			 number=parseFloat(number);
			 var number=number.replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
			 alert()
			 $('#'+id).val(number);
			var val=$('#'+id).val();
			//val=val.replace(",","");
			val=val.replace(/,/g, "");
			var numbers=parseFloat(val);
			if (!isNaN(numbers))
			{
				numbers = numbers.toLocaleString('en');
	
				$('#' + id).val(numbers);
			}
	
	*/
		}
		function calculations() {
			var count = 1;
			var net_amount = 0;
			$('.net_amount').each(function () {

				net_amount += +$('#net_amount_1_' + count).val();

				count++;
			});
			$('#net_amounttd').val(net_amount);


			var v = Number(net_amount.toFixed(2)).toLocaleString('en-US');
			$('#net_amounttd').val(v);



		}
		function calculation(id, debit_credit) {
			if ($('#' + id).is('[readonly]')) { return false; }

			var val = $('#' + id).val();

			val = val.replace(/,/g, "");
			if (debit_credit == 1) {
				var number = id.replace("d_amount_", "");
			}
			else {
				var number = id.replace("c_amount_", "");
				val = val * -1;
			}

			number = number.split('_');
			number = number[1];
			var current_bal = $('#current_amount_hidden' + number).val();

			if (current_bal == '' || current_bal == 0) {

			}
			else {
				val = parseFloat(val);
				current_bal = parseFloat(current_bal);
				var total = val + current_bal;

				$('#current_amount' + number).val(total);
				if (isNaN(val)) {
					var current_bal = $('#current_amount_hidden' + number).val();
					$('#current_amount' + number).val(current_bal);
				}
			}

		}
		function checkNan(value) {
			if (isNaN(value)) {
				value = 0;
			}
			return value;
		}

		function get_current_amount(id) {

			var val = $('#' + id).val();

			var number = id.replace("account_id_", "");

			number = number.split('_');

			$.ajax({
				url: '/fmfal/get_current_amount',
				type: "GET",
				data: { val: val },
				success: function (response) {

					$('#current_amount' + number[1]).val(response);
					$('#current_amount_hidden' + number[1]).val(response);

					$('#current_amount').val(response);

				}
			});
		}


		function addSeparatorsNF(nStr, inD, outD, sep) {
			nStr += '';
			var dpos = nStr.indexOf(inD);
			var nStrEnd = '';
			if (dpos != -1) {
				nStrEnd = outD + nStr.substring(dpos + 1, nStr.length);
				nStr = nStr.substring(0, dpos);
			}
			var rgx = /(\d+)(\d{3})/;
			while (rgx.test(nStr)) {
				nStr = nStr.replace(rgx, '$1' + sep + '$2');
			}
			return nStr + nStrEnd;
		}

		function deleteRowMasterTable(value, id, tableName) {
			var value;
			var id;
			var tableName;

			$.ajax({
				url: '<?php echo url('/')?>/deleteMasterTableReceord',
				type: "GET",
				data: { value: value, id: id, tableName: tableName },
				success: function (data) {
					location.reload();
				}
			});

		}

		function deleteRowCompanyHRRecords(companyId, recordId, tableName) {
			var companyId;
			var recordId;
			var tableName;
			$.ajax({
				url: '<?php echo url('/')?>/cdOne/deleteRowCompanyHRRecords',
				type: "GET",
				data: { companyId: companyId, recordId: recordId, tableName: tableName },
				success: function (data) {
					location.reload();
				}
			});
		}

		function deleteCompanyMasterTableRecord(url, id, tableName, companyId, accId) {
			var url;
			var id;
			var tableName;
			var companyId;
			var accId;
			$.ajax({
				url: '<?php echo url('/')?>/' + url + '',
				type: "GET",
				data: { companyId: companyId, id: id, tableName: tableName, accId: accId },
				success: function (data) {
					location.reload();
				}
			});
		}

		function repostCompanyTableRecord(companyId, recordId, tableName) {

			var companyId;
			var recordId;
			var tableName;

			$.ajax({
				url: '<?php echo url('/')?>/cdOne/repostOneTableRecords',
				type: "GET",
				data: { companyId: companyId, recordId: recordId, tableName: tableName },
				success: function (data) {
					location.reload();
				}
			});
		/* var url;
		 var id;
		 var tableName;
		 var companyId;
		 var accId;
		 $.ajax({
		 url: '<?php echo url('/')?>/'+url+'',
			type: "GET",
				data: { companyId: companyId, id: id, tableName: tableName, accId: accId },
			success: function(data) {
				location.reload();
			}
		}); (*/
	}

		function deleteRowCompanyRecords(companyId, recordId, tableName) {
			var companyId;
			var recordId;
			var tableName;

			$.ajax({
				url: '<?php echo url('/')?>/cdOne/deleteRowCompanyRecords',
				type: "GET",
				data: { companyId: companyId, recordId: recordId, tableName: tableName },
				success: function (data) {
					location.reload();
				}
			});

		}


		function repostOneTableRecords(companyId, recordId, tableName) {

			var companyId;
			var recordId;
			var tableName;


			$.ajax({
				url: '<?php echo url('/')?>/cdOne/repostOneTableRecords',
				type: "GET",
				data: { companyId: companyId, recordId: recordId, tableName: tableName },
				success: function (data) {
					location.reload();
				}
			});

		}
		function repostMasterTableRecords(recordId, tableName) {

			var recordId;
			var tableName;

			$.ajax({
				url: '<?php echo url('/')?>/cdOne/repostMasterTableRecords',
				type: "GET",
				data: { recordId: recordId, tableName: tableName },
				success: function (data) {
					location.reload();
				}
			});

		}
		function approveOneTableRecords(companyId, recordId, tableName, column) {
			var companyId;
			var recordId;
			var tableName;
			var column;


			$.ajax({
				url: '<?php echo url('/')?>/cdOne/approveOneTableRecords',
				type: "GET",
				data: { companyId: companyId, recordId: recordId, tableName: tableName, column: column },
				success: function (data) {

					location.reload();
				}
			});

		}

		function mainDisable(disable, enable) {
			if ($('#' + disable).val() == 0) {
				$('#' + disable).attr('readonly', 'readonly');
				$('#' + disable).removeAttr('required', 'required');
				$('#' + disable).removeClass("requiredField")
				$('#' + disable).val("");
				$('#' + enable).removeAttr('readonly');
				$('#' + enable).attr('required', 'required');
				$('#' + enable).addClass("requiredField")
			}
		}

		$(document).ready(function () {
			$('[data-toggle="tooltip"]').tooltip();
		});

		var tableToExcel = (function () {
			var uri = 'data:application/vnd.ms-excel;base64,'
				, template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--><meta http-equiv="content-type" content="text/plain; charset=UTF-8"/></head><body><table>{table}</table></body></html>'
				, base64 = function (s) { return window.btoa(unescape(encodeURIComponent(s))) }
				, format = function (s, c) { return s.replace(/{(\w+)}/g, function (m, p) { return c[p]; }) }
			return function (table, name) {
				if (!table.nodeType) table = document.getElementById(table)
				var ctx = { worksheet: name || 'Worksheet', table: table.innerHTML }
				window.location.href = uri + base64(format(template, ctx))
			}
		})()

	</script>

	<!-- Create Prospects Modal -->
	<div class="modal fade" id="createprospects">
		<div class="modal-dialog modalWidth2">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-12 col-sm-1 col-xs-12">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>

					</div>
				</div>
				<div class="modal-body"></div>

			</div>
		</div>
	</div>
	<!-- Create Prospects Modal Ends -->

	<!-- Create Jobtitle Modal -->
	<div class="modal fade" id="createJobtitle">
		<div class="modal-dialog modalWidth2">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-12 col-sm-1 col-xs-12">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>

					</div>
				</div>
				<div class="modal-body"></div>

			</div>
		</div>
	</div>
	<!-- Create Jobtitle Modal Ends -->

	<!-- Create Contact Modal -->
	<div class="modal fade" id="createContact">
		<div class="modal-dialog modalWidth2">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-12 col-sm-1 col-xs-12">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>
						<!-- <div class="col-md-3 col-sm-1 col-xs-12 text-right">
						<button type="button" class="" data-dismiss="modal" aria-hidden="true">Close</button>
					</div> -->
					</div>
				</div>
				<div class="modal-body"></div>
				<!-- <div class="modal-footer">
                <button type="button" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancele</button>
            </div> -->
			</div>
		</div>
	</div>
	<!-- Create Contact Modal Ends -->

	<!-- this is material requisatio n issue -->

	<div class="modal fade" id="material">
		<div class="modal-dialog modalWidth2">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-6 ">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>
						<div class="col-md-6  text-right">
							<button type="button" class="" data-bs-dismiss="modal" aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
				<div class="modal-body"></div>
				<!-- <div class="modal-footer">
                <button type="button" class="btn btn-primary mr-1" data-dismiss="modal">Save</button>
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Cancel</button>
            </div> -->
			</div>
		</div>
	</div>




	<div class="modal fade" id="showDetailModelMasterTable">
		<div class="modal-dialog modalWidth">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">

						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-right">
							<button type="button" class="btn btn-lg btn-danger" data-dismiss="modal"
								aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"
					style=" padding: 15px; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="text-center">

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="showDetailModelOneParamerter" data-backdrop="static">
		<div class="modal-dialog modalWidth">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">

						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-right">
							<button type="button" class="btn btn-lg btn-danger" data-dismiss="modal"
								aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
				<div class="modal-body">


				</div>
				<div class="modal-footer"
					style=" padding: 15px; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">
					<div class="row">

					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="showDetailModelFiveParamerter">
		<div class="modal-dialog modalWidth">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<a style="float: left; font-size: 15px;
                        color: #9170E4; margin-right:10px; margin: -9px 0px -31px 0px;"
								class="triangle-obtuse top">Logo Area</a>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-right">
							<button type="button" class="btn btn-lg btn-danger" data-dismiss="modal"
								aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"
					style=" padding: 15px; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">

				</div>
			</div>
		</div>
	</div>



	<div class="modal fade" id="showDetailModelSixParamerter">
		<div class="modal-dialog modalWidth">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<a style="float: left; font-size: 15px;
                        color: #9170E4; margin-right:10px; margin: -9px 0px -31px 0px;"
								class="triangle-obtuse top">Logo Area</a>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-right">
							<button type="button" class="btn btn-lg btn-danger" data-dismiss="modal"
								aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"
					style=" padding: 15px; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">
					<div class="row">

					</div>
				</div>
			</div>
		</div>
	</div>





	<div class="modal fade" id="showDetailModelTwoParamerterJson">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<a style="float: left; font-size: 15px;
                        color: #fff; margin-right:10px; margin: -9px 0px -31px 0px;" class="triangle-obtuse top">Gudia
								(Pvt) Ltd.</a>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-center">
							<span class="modalTitle subHeadingLabelClass"></span>
						</div>
						<div class="col-md-4 col-sm-1 col-xs-12 text-right">
							<button type="button" class="btn btn-lg btn-danger" data-dismiss="modal"
								aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"
					style=" padding: 15px; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">

				</div>
			</div>
		</div>
	</div>


	<div class="modal fade" id="showDetailModelTwoParamerter">
		<div class="modal-dialog modalWidth" style="width:95%;">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;"">
				<div class=" row">
					<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 text-center">
						<span class="modalTitle subHeadingLabelClass"></span>
						<button style="float: right" type="button" class="btn btn-sm btn-danger" data-dismiss="modal"
							aria-hidden="true">Close</button>
					</div>
				</div>
			</div>
			<div class="modal-body"></div>
			<div class="modal-footer"
				style=" padding: 15px; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">

			</div>
		</div>
	</div>
	</div>

	<div class="modal fade" id="showMasterTableEditModel">
		<div class="modal-dialog modalWidth" style="width:95%;">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; background-color: #f7f7f7; border-bottom: 5px solid #9170E4; width: 100%;">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 text-center">
							<span class="modalTitle subHeadingLabelClass"></span>
							<button style="float: right;" type="button" class="btn btn-sm btn-danger"
								data-dismiss="modal" aria-hidden="true">Close</button>
						</div>
					</div>
				</div>
				<div class="modal-body"></div>
				<div class="modal-footer"
					style=" padding: 15px; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">

				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="showModal">
		<div class="modal-dialog modalWidth" style="width:60%;">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; padding-right: 30px !important; background-color: #f7f7f7; border-bottom: 1px solid #000 !important; width: 100%;">
					<div class="row">
						<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
							<span class="modal-title subHeadingLabelClass"></span>
						</div>
						<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 text-right">
							<button class="btn btn-modal" type="button" class="close" data-dismiss="modal"
								aria-hidden="true">X</button>
						</div>
					</div>
				</div>
				<div class="modal-body" style="padding-right: 30px !important;"></div>
				<div class="modal-footer"
					style=" padding: 15px; padding-right: 30px !important; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="showMasterEditModal">
		<div class="modal-dialog modalWidth" style="width:60%;">
			<div class="modal-content">
				<div class="modal-header"
					style=" padding: 15px; padding-right: 30px !important; background-color: #f7f7f7; border-bottom: 1px solid #000 !important; width: 100%;">
					<div class="row">
						<div class="col-md-8 col-sm-8 col-lg-8 col-xs-12">
							<span class="modal-title subHeadingLabelClass"></span>
						</div>
						<div class="col-md-4 col-sm-4 col-lg-4 col-xs-12 text-right">
							<button class="btn btn-modal" type="button" class="close" data-dismiss="modal"
								aria-hidden="true">X</button>
						</div>
					</div>
				</div>
				<div class="modal-body" style="padding-right: 30px !important;"></div>
				<div class="modal-footer"
					style=" padding: 15px; padding-right: 30px !important; background-color: #f7f7f7; border-top: 5px solid #9170E4; width: 100%;">
				</div>
			</div>
		</div>
	</div>

	<?php  $page = Request::segment(2)  ?>


	<script>

		function showModal(url, id, modalName, m) {
			$.ajax({
				url: '{{ url('/') }}/' + url + '',
				type: "GET",
				data: { id: id, m: m },
				success: function (data) {
					jQuery('#showModal').modal('show', { backdrop: 'static', keyboard: false });
					$('#showModal').modal({ backdrop: 'static', keyboard: false })
					jQuery('#showModal .modal-title').html(modalName);
					jQuery('#showModal .modal-body').html('<div class="loader"></div>');
					setTimeout(function () {
						jQuery('#showModal .modal-body').html(data);
					}, 200);
				}
			});
		}

		function showMasterEditModal(url, id, modalName, m) {
			$.ajax({
				url: '{{ url('/') }}/' + url + '',
				type: "GET",
				data: { id: id, m: m },
				success: function (data) {
					jQuery('#showMasterEditModal').modal('show', { backdrop: 'static', keyboard: false });
					$('#showMasterEditModal').modal({ backdrop: 'static', keyboard: false })
					jQuery('#showMasterEditModal .modal-title').html(modalName);
					jQuery('#showMasterEditModal .modal-body').html('<div class="loader"></div>');
					setTimeout(function () {
						jQuery('#showMasterEditModal .modal-body').html(data);
					}, 200);
				}
			});
		}

		$(document).ready(function () {

			@if($page == 'salesorder' || $page == 'CreateDeliveryNote')
				setTimeout(function () {

					$(".sidenavnr").toggleClass("Navactive");
					$("body").toggleClass("full_with");

				}, 2000);
			@endif

			$(window).bind('keydown', function (event) {
				if (event.ctrlKey || event.metaKey) {
					switch (String.fromCharCode(event.which).toLowerCase()) {
						case 's':
							event.preventDefault();
							//windows
							window.location.href = '<?php echo url('/')?>/sales/CreateSalesOrder?pageType=add&&parentCode=89&&m=<?php echo Session::get('run_company')?>';
							break;
						case 'p':
							event.preventDefault();
							//windows
							window.location.href = '<?php echo url('/')?>/purchase/directPurchaseOrderForm?pageType=&&parentCode=44&&m=<?php echo Session::get('run_company')?>';
							break;
						case 'd':
							event.preventDefault();
							//windows
							//window.location.href = '<?php echo url('/')?>/sales/CreateDeliveryNoteList?pageType=add&&parentCode=90&&m=<?php echo Session::get('run_company')?>';
							break;


						case 'g':
							event.preventDefault();
							var rotation = 0;

							jQuery.fn.rotate = function (degrees) {
								$(this).css({
									'-webkit-transform': 'rotate(' + degrees + 'deg)',
									'-moz-transform': 'rotate(' + degrees + 'deg)',
									'-ms-transform': 'rotate(' + degrees + 'deg)',
									'transform': 'rotate(' + degrees + 'deg)'
								});
							};


							rotation += 180;
							$('body').rotate(rotation);

							break;
					}
				}
			});
		});

		function delete_records(id, TableType) {
			if (confirm('Are you sure you want to delete this request')) {
				$.ajax({
					url: '<?php echo url('/') ?>/pd/delete_records',
					type: 'GET',
					data: { id: id, TableType: TableType },
					success: function (response) {
						alert('Delete');
						$('.' + id).remove();
					}
				});
			}
		}

		function approve_voucher(table, table_data, voucher_status, voucher_date, voucher_no_txt, type, voucher_no, voucher_name = '') {
			$.ajax({
				url: '{{url('/approve_voucher')}}',
				type: 'GET',
				data: { table: table, table_data: table_data, voucher_status: voucher_status, voucher_no: voucher_no, voucher_date: voucher_date, voucher_no_txt: voucher_no_txt, type: type, voucher_name: voucher_name },

				success: function (response) {

					$('.status' + response).html('<span class="badge badge-success" style="background-color: #00c851 !important">Approved</span>')
					//$('.BtnHide'+response).css('display','none');
					$("#showDetailModelOneParamerter").modal("hide");

				}
			});
		}
		function verify_voucher(table, table_data, voucher_status, voucher_date, voucher_no_txt, type, voucher_no) {
			$.ajax({
				url: '{{url('/verify_voucher')}}',
				type: 'GET',
				data: { table: table, table_data: table_data, voucher_status: voucher_status, voucher_no: voucher_no, voucher_date: voucher_date, voucher_no_txt: voucher_no_txt, type: type },

				success: function (response) {

					$('.status' + response).html('<span class="badge badge-success" style="background-color: #00c851 !important">Approved</span>')
					//$('.BtnHide'+response).css('display','none');
					$("#showDetailModelOneParamerter").modal("hide");

				}
			});
		}

		function getSubCategoryList(category_id, sub_category_id) {

			var category = $('#' + category_id).val();
			$.ajax({
				url: '{{ url("/pdc/getSubCategoryList") }}',
				type: 'Get',
				data: { category: category },
				success: function (response) {
					$('#' + sub_category_id).empty();
					$('#' + sub_category_id).append(new Option('All', 'Select'));	
					$.each(response, function (index, element) {
						$('#' + sub_category_id).append(new Option(element['sub_category_name'], element['id']))
					});
				}
			});
		}

		function getItemBySubCategory(sub_category_id, item_id) {

			var sub_category = $('#' + sub_category_id).val();
			$.ajax({
				url: '{{ url("/pdc/getItemBySubCategory") }}',
				type: 'Get',
				data: { sub_category: sub_category },
				success: function (response) {
					$('#' + item_id).empty();
					$('#' + item_id).append(new Option('All', 'Select'));	
					$.each(response, function (index, element) {
						$('#' + item_id).append(new Option(element['item_code'] + ' -- '+ element['sub_ic'], element['id'] + '@' + element['uom_name'] + '@' + element['sub_ic']))
					});
				}
			});
		}

		function get_sub_category(category_id, sub_category_id) {

			var category = $('#' + category_id).val();
			alert("{{url('')}}");

			$.ajax({
				url: '{{ url("/pdc/get_sub_category") }}',
				type: 'Get',
				data: { category: category },
				success: function (response) {

					$('#' + sub_category_id).html(response);
				}
			});
		}

		function validate_sam() {
			var required = document.getElementsByClassName('required_sam');

			for (i = 0; i < required.length; i++) {
				var rf = required[i].id;


				if ($('#' + rf).val() == '') {
					$('#' + rf).css('border-color', 'red');
					$('#' + rf).focus();
					validate = 1;
					alert(rf + ' ' + 'Required');
					event.preventDefault();
					return false;

				}
				else {

					$('#' + rf).css('border-color', '#ccc');
					validate = 0;
				}
			}
		}
		function remove() {
			$('a').contents().unwrap();
		}



		function destroyTable(id) {
			table = $('#' + id + '').DataTable({
				paging: false,
				bDestroy: true,
			});
			table.destroy();
		}

		function get_sub_item(id) {

			var category = $('#' + id).val();
			console.log(category);
			var index_val = id.replace("category_id", "");

			$('#item_id' + index_val).html('');
			$.ajax({
				url: '{{ url("/pdc/get_sub_category") }}',
				type: 'Get',
				data: { category: category },
				success: function (response) {
					$('#item_id' + index_val).append(new Option('Select', ''))
					$.each(response, function (index, element) {
						$('#item_id' + index_val).append(new Option(element['item_code'] + ' -- '+ element['sub_ic'], element['id'] + '@' + element['uom_name'] + '@' + element['sub_ic'] + '@' + element['pack_size']))
					});
				}
			});
		}

		function get_sub_item_reports(id) {


			var category = $('#' + id).val();
			console.log(category);
			var index_val = id.replace("category_id", "");

			$('#item_id' + index_val).html('');
			$.ajax({
				url: '{{ url("/pdc/get_sub_category") }}',
				type: 'Get',
				data: { category: category },
				success: function (response) {
					$('#item_id' + index_val).append(new Option('Select', 'Select'))
					$.each(response, function (index, element) {
						$('#item_id' + index_val).append(new Option(element['sub_ic'], element['id'] + '@' + element['uom_name'] + '@' + element['sub_ic']))
					});
				}
			});
		}


	</script>

	<script>
		function Datavalidate(id) {
			var data = $(id).find(':selected').val();
			data = data.split(',');
			var data = data[2];
			var closestContainer = $(id).closest('.AutoNo').find('.sub_department_id');
			if (data[0] == 4 || data[0] == 5) {
				closestContainer.addClass('requiredField');
			} else {
				closestContainer.removeClass('requiredField');
			}
		}

	</script>




	<!-- BEGIN: Page JS-->

	<script src="{{ URL::asset('app-assets/vendors/js/charts/apexcharts.min.js') }}"></script>
	<script src="{{ URL::asset('app-assets/vendors/js/pickers/flatpickr/flatpickr.min.js') }}"></script>
	<script src="{{ URL::asset('app-assets/js/scripts/pages/dashboard-analytics.js') }}"></script>
	<script src="{{ URL::asset('app-assets/js/scripts/pages/dashboard-ecommerce.js') }}"></script>
	<script src="{{ URL::asset('app-assets/js/core/app-menu.js') }}"></script>
	<script src="{{ URL::asset('app-assets/js/core/app.js') }}"></script>

	<script src="{{ URL::asset('app-assets/js/scripts/charts/chart-apex.js') }}"></script>
	<script src="{{ URL::asset('app-assets/vendors/js/charts/chart.min.js') }}"></script>
	<script src="{{ URL::asset('assets/custom/js/customlayout.js') }}"></script>
	<script src="{{ URL::asset('app-assets/js/scripts/charts/chart-chartjs.js') }}"></script>
	<script src="{{ URL::asset('app-assets/js/submit.js') }}"></script>
	<link href="{{ URL::asset('assets/custom/css/customMain.css') }}" rel="stylesheet">




	<!-- END: Page JS-->
</body>