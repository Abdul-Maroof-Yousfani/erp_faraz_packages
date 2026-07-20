@extends('layouts.app')
@section('content')
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@700;800&family=Inter:wght@400;500;600&display=swap');
.fp-wrap { display: flex; justify-content: center; padding: 70px 20px; font-family: 'Inter', sans-serif; }
.fp-card { width: 100%; max-width: 420px; background: #fff; border-radius: 20px; padding: 44px 40px; box-shadow: 0 20px 50px rgba(19,27,46,0.08); border: 1px solid #EEF0F7; text-align: center; }
.fp-icon { width: 56px; height: 56px; border-radius: 16px; background: transparent linear-gradient(90deg, #173CA7 0%, #0B1F59 100%) 0% 0% no-repeat padding-box; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #fff; font-size: 22px; }
.fp-card h3 { font-family: 'Sora', sans-serif; font-weight: 800; font-size: 22px; color: #1B2333; margin: 0 0 8px; }
.fp-card .sub { color: #8A93A6; font-size: 13.5px; margin-bottom: 28px; }
.fp-alert { background: #E9F9EF; color: #1E8E4E; border-radius: 10px; padding: 10px 14px; font-size: 13px; margin-bottom: 20px; }
.fp-field { margin-bottom: 22px; text-align: left; }
.fp-field label { display: block; font-size: 13px; font-weight: 600; color: #4A5268; margin-bottom: 6px; }
.fp-field input { width: 100% !important; padding: 12px 15px !important; border: 1.5px solid #E7EAF3 !important; border-radius: 12px !important; font-size: 14px !important; background: #FBFCFE !important; box-shadow: none !important; }
.fp-field input:focus { border-color: #7C5CFC !important; background: #fff !important; outline: none !important; }
.fp-error { color: #E5484D; font-size: 12.5px; margin-top: 5px; display: block; }
.fp-btn { width: 100%; border: none; padding: 13px; border-radius: 12px; background: transparent linear-gradient(90deg, #173CA7 0%, #0B1F59 100%) 0% 0% no-repeat padding-box; color: #fff; font-weight: 700; font-size: 14.5px; cursor: pointer; box-shadow: 0 10px 22px rgba(124,92,252,0.28); }
.nav.navbar.navbar-default.navbar-static-top{display:none !important;}
.navbar-default{display:none !important;}
</style>

<div class="fp-wrap">
    <div class="fp-card">
        <div class="fp-icon"><i class="fa fa-envelope"></i></div>
        <h3>Forgot Password?</h3>
        <div class="sub">Apna registered email likhein, hum aapko reset link bhejte hain</div>

        @if (session('status'))
            <div class="fp-alert">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ url('/password/email') }}">
            {{ csrf_field() }}
            <div class="fp-field">
                <label for="email">E-Mail Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="fp-error">{{ $errors->first('email') }}</span>
                @endif
            </div>
            <button type="submit" class="fp-btn">
                <i class="fa fa-btn fa-envelope"></i> Send Password Reset Link
            </button>
        </form>
    </div>
</div>
@endsection