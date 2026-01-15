<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
     <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
	<meta name="csrf-token" content="{{ csrf_token() }}" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title> {{env('APP_NAME')}} || ERP </title>
    <!-- BOOTSTRAP CORE STYLE  -->
    <link href="{{ URL::asset('assets/css/bootstrap.css') }}" rel="stylesheet" />
    <!-- FONT AWESOME STYLE  -->
    <link href="{{ URL::asset('assets/css/font-awesome.css') }}" rel="stylesheet" />
    <!-- CUSTOM STYLE  -->
    <link href="{{ URL::asset('assets/css/style.css') }}" rel="stylesheet" />
	<link href="{{ URL::asset('assets/css/main.css') }}" rel="stylesheet" />
    <!-- GOOGLE FONT -->
    <link href="{{ URL::asset('assets/css/fa.css') }}" rel='stylesheet' type='text/css' />

	<script src="{{ URL::asset('assets/js/jquery.min.js') }}"></script>
	<script src="{{ URL::asset('assets/js/jquery-1.10.2.js') }}"></script>
    <!-- BOOTSTRAP SCRIPTS  -->
    <script src="{{ URL::asset('assets/js/bootstrap.js') }}"></script>
	<link href="{{ URL::asset('assets/css/font-awesome.min.css') }}" rel="stylesheet">




	<style>
@font-face {
    font-family: erp-semiBold;
    src: url('<?= url('assets/erp-fonts/Montserrat-SemiBold.ttf') ?>');
}

@font-face {
    font-family: erp-regular;
    src: url('<?= url('assets/erp-fonts/Montserrat-Regular.ttf') ?>');
}
body{
font-family: erp-regular;
}
.nav>li>a:focus, .nav>li>a:hover {
    text-decoration: none;
    background-color:transparent;
}
.navbar-login .navbar-nav>li>a {
 	font-family: erp-semiBold;
	letter-spacing: 1px;
	color: #333;
}
.navbar-login {
    background-color: #ffffff;
    border-color: #ffffff;
    padding: 23px 0px 7px 0px;
}

.parallax{
    background-image: url('assets/img/newlogo.jpg');
    background-repeat: no-repeat;
}
.main-login-content{
	padding: 50px 0px 50px 0px;
}
.login-office-ph li i{
    font-size: 14px;
    background-color: #eee;
    padding: 8px 0px 0px 7px;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    color: #080808;
}
.navbar-brand {
    font-size: 24px;
    color: #f87178;
}
.navbar-brand:hover {
    color: #f87178;
}
.form-signin h3 span{
	color: #f87178;
}
.form-signin .singInput{
	border-radius: 0px;
    height: 38px;
	font-size: 15px;
}
.form-signin .checkbox{
    height: 39px;
    padding-top: 10px;
}
.inner-addon {
  position: relative;
}
.inner-addon .glyphicon {
  position: absolute;
  padding: 10px;
  pointer-events: none;
}
.left-addon .glyphicon  { left:  0px;}
.left-addon input  { padding-left:  30px; }


.login-footer{
	background-color: #f8f8f8;
    padding: 16px 0px 1px 0px;
    text-transform: uppercase;
}

.navbar-toggle {
    background-color: #cfd7db;
}
.navbar-toggle .icon-bar {
    background-color: #000;
}
.login-btn .fa-sign-in {
    color: #fff;
    opacity: 0;
    transform: translateX(20px);
    transition: all 0.5s ease-in-out;
}
.login-btn:hover .fa-sign-in{
    color: #fff;
    opacity: 1;
    transform: translateX(0px);
}
	</style>

</head>
<body>
<nav class="navbar navbar-login">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}"> (Pvt) Ltd</a>
          </div>
          <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
              <li><a href="{{ url('visitor/careers') }}" class="{{ Request::is('visitor/careers')? 'triangle-isosceles': '' }} ">CAREERS</a></li>
              <li><a href="{{ url('/login') }}" class="{{ Request::is('login')? 'triangle-isosceles': '' }}">LOGIN</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right login-office-ph">
              <li class="active"><a href="mailto:info@gudiagroup.com"><i class="glyphicon glyphicon-envelope" aria-hidden="true"></i> info@demo.com</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div><!--/.container-fluid -->
      </nav>
<input type="hidden" id="url" value="<?php echo url('/') ?>">
<!-- MENU SECTION END-->
