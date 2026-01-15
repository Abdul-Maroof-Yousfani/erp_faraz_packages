<body class="mainb" style="background-image:url(assets/img/banner1.png);">
@include('includes._normalUserNavigation')
<style>
    .mainb {
        background-repeat: no-repeat;
        background-size: cover;
    }
    .marquee {
        width: 100%;
        position: absolute;
        text-align: center;
        animation: marquee 2000ms linear infinite;
    }

    @keyframes marquee {
        0% { top: 100%; }
        100% { top: -30px; }
    }
    .navbar-login {
        display: none;
    }
    .padb {padding-bottom: 37px;}
</style>
<section class="login-sec">
    <img class="circle" src="./assets/img/animation/circledot.png">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center padb">
                <img src="assets/img/white-logo.png">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="login-fwrp p1">
                    <form action="{{ url('/login') }}" method="POST" class="form-signin" style="opacity: 0.9;">
                        {{ csrf_field() }}
                        {{--<h3 class="form-signin-heading">Welcome To  <span>Gudia Group</span></h3>--}}
                        <div class="text-center">
                            <!--<h3>Welcome To <span>ERP</span> 👋</h3>-->
                            <h1>Hello! <br> Welcome Back</h1>
                        </div>
                        <label for="">Email</label>
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-user"></i>
                            <input id="email" type="text" class="form-control singInput" name="email" value="{{ old('email') }}" placeholder="Company email" />
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                        <label for="">Password</label>
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-lock"></i>
                            <input id="password" type="password" class="form-control singInput" name="password"  placeholder="Password" />
                        </div>
                        @if ($errors->has('password'))
                            <span class="help-block">
    						<strong>{{ $errors->first('password') }}</strong>
    					</span>
                        @endif
                        <div id="remember" class="checkbox">
                            <label>
                                <input type="checkbox" name="remember"> Remember Me                            
                            </label>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn login-btn" onclick="loader()">LOGIN <i class="fa fa-btn fa-sign-in"></i></button>
                            <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    function loader()
    {
        var div = document.getElementById('colorgraph');
        div.innerHTML = '';
        div.innerHTML += '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><div class="loader"></div></div></div>';
    }
</script>