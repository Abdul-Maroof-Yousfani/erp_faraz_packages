@include('includes._normalUserNavigation')
<style>
@import url('https://fonts.googleapis.com/css2?family=Sora:wght@600;700;800&family=Inter:wght@400;500;600&display=swap');

.auth-wrap{min-height:100vh;display:flex !important;font-family:'Inter',sans-serif;}
.auth-wrap *{box-sizing:border-box;}
/* LEFT — brand panel */
.auth-brand{flex:1.1;position:relative;background:linear-gradient(150deg,#131B2E 0%,#1E2A46 55%,#2B2158 100%);display:flex;flex-direction:column;justify-content:center;padding:60px 70px;overflow:hidden;color:#fff;min-height:100vh;}
.auth-brand::before,.auth-brand::after{content:"";position:absolute;border-radius:50%;filter:blur(60px);opacity:.35;}
.auth-brand::before{width:340px;height:340px;background:#FF7A45;top:-90px;right:-90px;}
.auth-brand::after{width:300px;height:300px;background:#7C5CFC;bottom:-80px;left:-60px;}
.auth-brand-logo{font-family:'Sora',sans-serif;font-weight:800;font-size:40px;letter-spacing:1px;position:relative;z-index:2;}
.auth-brand-logo span{color:#FF7A45;}
.auth-brand-tag{font-size:13px;color:#9AA6C4;letter-spacing:3px;text-transform:uppercase;margin-bottom:50px;position:relative;z-index:2;}
.auth-brand-heading{font-family:'Sora',sans-serif;font-weight:700;font-size:34px;line-height:1.35;max-width:420px;position:relative;z-index:2;margin-bottom:40px;}
.auth-mini-cards{display:flex;gap:16px;position:relative;z-index:2;}
.auth-mini-card{background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);backdrop-filter:blur(6px);border-radius:14px;padding:16px 18px;width:160px;}
.auth-mini-card .dot{width:10px;height:10px;border-radius:50%;margin-bottom:10px;}
.auth-mini-card:nth-child(1) .dot{background:#FF7A45;}
.auth-mini-card:nth-child(2) .dot{background:#7C5CFC;}
.auth-mini-card p{font-size:12.5px;color:#C4CBE0;margin:0;line-height:1.5;}
/* RIGHT — form panel */
.auth-form-side{flex:1;background:#F7F8FC;display:flex;align-items:center;justify-content:center;padding:40px;min-height:100vh;}
.auth-card{width:100%;max-width:400px;background:#fff;border-radius:20px;padding:46px 42px;box-shadow:0 20px 50px rgba(19,27,46,0.08);}
.auth-card h3{font-family:'Sora',sans-serif;font-weight:700;font-size:26px;color:#1B2333;margin:0 0 6px;}
.auth-card .sub{color:#8A93A6;font-size:14px;margin-bottom:32px;}
.auth-field{margin-bottom:20px;}
.auth-field label{display:block;font-size:13px;font-weight:600;color:#4A5268;margin-bottom:7px;}
.auth-input-wrap{position:relative;}
.auth-input-wrap i{position:absolute;left:15px;top:50%;transform:translateY(-50%);color:#B4BACC;font-size:14px;}
.auth-input-wrap input,.auth-input-wrap select{width:100% !important;padding:12px 15px 12px 42px !important;border:1.5px solid #E7EAF3 !important;border-radius:12px !important;font-size:14px !important;font-family:'Inter',sans-serif !important;background:#FBFCFE !important;color:#1B2333 !important;transition:all .2s ease;height:auto !important;box-shadow:none !important;}
.auth-input-wrap input:focus,.auth-input-wrap select:focus{border-color:#7C5CFC !important;background:#fff !important;outline:none !important;}
.auth-row{display:flex;align-items:center;justify-content:space-between;margin:18px 0 26px;}
.auth-remember{display:flex;align-items:center;gap:8px;font-size:13px;color:#4A5268;}
.auth-remember input{accent-color:#7C5CFC;}
.auth-forgot{font-size:13px;color:#7C5CFC !important;font-weight:600;text-decoration:none !important;}
.auth-btn{width:100%;border:none;padding:13px;border-radius:12px;background:linear-gradient(135deg,#FF7A45,#7C5CFC);color:#fff;font-weight:700;font-size:14.5px;letter-spacing:.3px;cursor:pointer;transition:transform .15s ease,box-shadow .15s ease;box-shadow:0 10px 22px rgba(124,92,252,0.28);}
.auth-btn:hover{transform:translateY(-1px);box-shadow:0 14px 26px rgba(124,92,252,0.36);}
.auth-error{color:#E5484D;font-size:12.5px;margin-top:6px;display:block;}
@media (max-width:860px){.auth-brand{display:none;}
.auth-form-side{flex:1 1 100%;}
}
.auth-input-wrap .toggle-eye{position:absolute;right:15px;left:auto;top:50%;transform:translateY(-50%);color:#B4BACC;font-size:14px;cursor:pointer;background:none;border:none;padding:0;}
.auth-input-wrap .toggle-eye:hover{color:#7C5CFC;}
.auth-input-wrap input[type="password"],.auth-input-wrap input[type="text"].pw-field{padding-right:42px !important;}

</style>

<div class="auth-wrap">
    <!-- LEFT BRAND PANEL -->
    <div class="auth-brand">
        <div class="auth-brand-logo">ERP<span>:</span></div>
        <div class="auth-brand-tag">Powered by INPL</div>
        <div class="auth-brand-heading">Ek dashboard mein sales, finance aur inventory ka pura control.</div>
        <div class="auth-mini-cards">
            <div class="auth-mini-card">
                <div class="dot"></div>
                <p>Real-time sales &amp; collection tracking</p>
            </div>
            <div class="auth-mini-card">
                <div class="dot"></div>
                <p>Multi-company financial reports</p>
            </div>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth-form-side">
        <div class="auth-card">
            <h3>Hello! Welcome Back</h3>
            <div class="sub">Apna account access karne ke liye sign in karein</div>

            <form action="{{ url('/login') }}" method="POST">
                {{ csrf_field() }}

                <div class="auth-field">
                    <label for="email">Email</label>
                    <div class="auth-input-wrap">
                        <i class="glyphicon glyphicon-user"></i>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Company email">
                    </div>
                    @if ($errors->has('email'))
                        <span class="auth-error">{{ $errors->first('email') }}</span>
                    @endif
                </div>

               <div class="auth-field">
					<label for="password">Password</label>
					<div class="auth-input-wrap">
						<i class="glyphicon glyphicon-lock"></i>
						<input id="password" type="password" name="password" placeholder="Password">
						<button type="button" class="toggle-eye" onclick="togglePassword('password', this)">
							<i class="glyphicon glyphicon-eye-close"></i>
						</button>
					</div>
					@if ($errors->has('password'))
						<span class="auth-error">{{ $errors->first('password') }}</span>
					@endif
				</div>
				<script>
				function togglePassword(fieldId, btn) {
					var field = document.getElementById(fieldId);
					var icon = btn.querySelector('i');
					if (field.type === 'password') {
						field.type = 'text';
						icon.classList.remove('glyphicon-eye-close');
						icon.classList.add('glyphicon-eye-open');
					} else {
						field.type = 'password';
						icon.classList.remove('glyphicon-eye-open');
						icon.classList.add('glyphicon-eye-close');
					}
				}
				</script>

                <div class="auth-field">
                    <label for="company">Company</label>
                    <div class="auth-input-wrap">
                        <i class="glyphicon glyphicon-briefcase"></i>
                        <select name="company" id="company">
                            <?php $data = DB::Connection('mysql2')->table('company')->select('id','name')->get(); ?>
                            @foreach($data as $row)
                                <option value="{{ $row->id }}">{{ $row->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="auth-row">
                    <label class="auth-remember">
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                    <a class="auth-forgot" href="{{ url('/password/reset') }}">Forgot Password?</a>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fa fa-btn fa-sign-in"></i> Login
                </button>
            </form>
        </div>
    </div>
</div>