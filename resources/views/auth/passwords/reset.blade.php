@extends('layouts.app')
@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500;600&display=swap');
.rp-wrap { display: flex; justify-content: center; padding: 60px 20px; font-family: 'Inter', sans-serif; }
.rp-card { width: 100%; max-width: 440px; background: #fff; border-radius: 20px; padding: 44px 40px; box-shadow: 0 20px 50px rgba(19,27,46,0.08); border: 1px solid #EEF0F7; }
.rp-card h3 { font-family: 'Sora', sans-serif; font-weight: 800; font-size: 24px; color: #1B2333; margin: 0 0 4px; }
.rp-card .sub { color: #8A93A6; font-size: 13.5px; margin-bottom: 28px; }
.rp-field { margin-bottom: 18px; }
.rp-field label { display: block; font-size: 13px; font-weight: 600; color: #4A5268; margin-bottom: 6px; }
.rp-field input { width: 100% !important; padding: 12px 15px !important; border: 1.5px solid #E7EAF3 !important; border-radius: 12px !important; font-size: 14px !important; background: #FBFCFE !important; box-shadow: none !important; }
.rp-field input:focus { border-color: #7C5CFC !important; background: #fff !important; outline: none !important; }
.rp-error{color:#E5484D;font-size:12.5px;margin-top:5px;display:block;}
.rp-btn{width:100%;border:none;padding:13px;border-radius:12px;background: transparent linear-gradient(90deg, #173CA7 0%, #0B1F59 100%) 0% 0% no-repeat padding-box;color:#fff;font-weight:700;font-size:14.5px;cursor:pointer;margin-top:8px;box-shadow:0 10px 22px rgba(124,92,252,0.28);}
.nav.navbar.navbar-default.navbar-static-top{display:none !important;}
.navbar-default{display:none !important;}
</style>

<div class="rp-wrap">
    <div class="rp-card">
        <h3>Reset Password</h3>
        <div class="sub">Apna naya password set karein</div>

        <form method="POST" action="{{ url('/password/reset') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="rp-field">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ $email or old('email') }}">
                @if ($errors->has('email'))
                    <span class="rp-error">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="rp-field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password">
                @if ($errors->has('password'))
                    <span class="rp-error">{{ $errors->first('password') }}</span>
                @endif
            </div>

            <div class="rp-field">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation">
                @if ($errors->has('password_confirmation'))
                    <span class="rp-error">{{ $errors->first('password_confirmation') }}</span>
                @endif
            </div>

            <button type="submit" class="rp-btn">
                <i class="fa fa-btn fa-refresh"></i> Reset Password
            </button>
        </form>
    </div>
</div>
@endsection