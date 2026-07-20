<body class="mainb" style="background-image:url(assets/img/banner1.png);">
@include('includes._normalUserNavigation')
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500;600&display=swap');
.mainb{background-repeat:no-repeat;background-size:cover;background-position:center;position:relative;min-height:100vh;font-family:'Inter',sans-serif;}
/* dark overlay so the banner doesn't fight with the card */
.mainb::before{content:"";position:absolute;inset:0;background:linear-gradient(150deg,rgba(19,27,46,0.88) 0%,rgba(30,42,70,0.82) 55%,rgba(43,33,88,0.85) 100%);z-index:0;}
.navbar-login{display:none;}
.login-sec{position:relative;z-index:2;min-height:100vh;display:flex;align-items:center;padding:40px 20px;}
.login-sec .circle{position:absolute;top:-60px;right:-60px;width:260px;opacity:.35;animation:spin 30s linear infinite;z-index:1;}
@keyframes spin{100%{transform:rotate(360deg);}
}
/* marquee kept,repurposed as a subtle floating accent dot behind the card */
.marquee{width:10px;height:10px;border-radius:50%;background:#FF7A45;box-shadow:0 0 24px 6px rgba(255,122,69,0.6);position:absolute;left:50%;animation:marquee 4000ms ease-in-out infinite;z-index:1;}
@keyframes marquee{0%{top:15%;opacity:0;}
10%{opacity:1;}
90%{opacity:1;}
100%{top:85%;opacity:0;}
}
.padb{padding-bottom:28px;}
.padb img{max-width:150px;}
.login-fwrp{position:relative;z-index:2;max-width:420px;margin:0 auto;background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.16);backdrop-filter:blur(14px);border-radius:22px;padding:44px 40px;box-shadow:0 25px 60px rgba(0,0,0,0.35);}
.login-fwrp h1{font-family:'Sora',sans-serif;font-weight:800;color:#fff;font-size:28px;line-height:1.35;margin-bottom:28px;}
.login-fwrp label{display:block;text-align:left;font-size:12.5px;font-weight:600;color:#C4CBE0;margin-bottom:6px;margin-top:16px;}
.inner-addon{position:relative;}
.inner-addon i{position:absolute;left:14px;top:50%;transform:translateY(-50%);color:rgba(255,255,255,0.55);font-size:14px;z-index:2;}
.singInput{width:100% !important;padding:12px 15px 12px 40px !important;border-radius:12px !important;border:1.5px solid rgba(255,255,255,0.18) !important;background:rgba(255,255,255,0.08) !important;color:#fff !important;font-size:14px !important;box-shadow:none !important;}
.singInput::placeholder{color:rgba(255,255,255,0.45);}
.singInput:focus{border-color:#FF7A45 !important;background:rgba(255,255,255,0.14) !important;outline:none !important;}
.login-fwrp .help-block strong{display:block;color:#FF8A8A;font-size:12px;margin-top:5px;font-weight:500;}
#remember{text-align:left;margin-top:18px;}
#remember label{display:flex;align-items:center;gap:8px;font-weight:500;color:#C4CBE0;font-size:13px;margin:0;}
#remember input{accent-color:#FF7A45;}
.login-btn{width:100%;border:none;padding:13px;border-radius:12px;background:linear-gradient(135deg,#FF7A45,#7C5CFC);color:#fff !important;font-weight:700;letter-spacing:.3px;font-size:14.5px;margin-top:24px;box-shadow:0 10px 25px rgba(124,92,252,0.35);transition:transform .15s ease;}
.login-btn:hover{transform:translateY(-1px);}
.btn-link{display:inline-block;margin-top:16px;color:#C4CBE0 !important;font-size:13px;text-decoration:none !important;}
.btn-link:hover{color:#FF7A45 !important;}
#colorgraph{margin-top:14px;}
.loader{width:22px;height:22px;border:3px solid rgba(255,255,255,0.25);border-top-color:#FF7A45;border-radius:50%;margin:0 auto;animation:loaderspin 700ms linear infinite;}
@keyframes loaderspin{100%{transform:rotate(360deg);}
}
.toggle-eye{position:absolute;right:14px;left:auto;top:50%;transform:translateY(-50%);width:20px;height:20px;display:flex;align-items:center;justify-content:center;color:rgba(255,255,255,0.55);/* dark theme ke liye;white card mein #B4BACC use karein */
 cursor:pointer;background:none !important;border:none !important;padding:0 !important;box-shadow:none !important;z-index:3;}
.toggle-eye:hover{color:#FF7A45;}
.toggle-eye svg{width:18px;height:18px;display:block;}
.singInput[type="password"],.singInput[type="text"].pw-toggled{padding-right:40px !important;}
.login-fwrp label{color:#818387 !important;}
#remember label{color:#818387 !important;padding:0 !important;}
.login-btn{margin-top:0px !important;}
.btn-link{margin-top:5px !important;color:#818387 !important;}
.toggle-eye svg{color:#818387 !important;}
.toggle-eye svg:hover{color:#d9534f !important;}
</style>

<section class="login-sec">
    <img class="circle" src="./assets/img/animation/circledot.png">
    <span class="marquee"></span>

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center padb">
                <img src="assets/img/white-logo.png">
            </div>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="login-fwrp">
                    <form action="{{ url('/login') }}" method="POST" class="form-signin">
                        {{ csrf_field() }}
                        <div class="text-center">
                            <h1>Welcome Back !</h1>
                        </div>

                        <label for="email">Email</label>
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-user"></i>
                            <input id="email" type="text" class="form-control singInput" name="email" value="{{ old('email') }}" placeholder="Company email" />
                        </div>
                        @if ($errors->has('email'))
                            <span class="help-block">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif

                      <label for="password">Password</label>
                        <div class="inner-addon left-addon">
                            <i class="glyphicon glyphicon-lock"></i>
                            <input id="password" type="password" class="form-control singInput" name="password" placeholder="Password" />
                            <button type="button" class="toggle-eye" onclick="togglePassword('password', this)" aria-label="Show password">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
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
                            <div id="colorgraph"></div>
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
<script>
function togglePassword(fieldId, btn) {
    var field = document.getElementById(fieldId);
    var isHidden = field.type === 'password';
    field.type = isHidden ? 'text' : 'password';

    btn.innerHTML = isHidden
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.94 10.94 0 0 1 12 20c-7 0-11-8-11-8a18.5 18.5 0 0 1 5.06-5.94M9.9 4.24A10.94 10.94 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
}
</script>