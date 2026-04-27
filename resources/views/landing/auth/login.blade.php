@extends('landing.master')
@section('title','Login')

@push('style')
    <style>
        .auth-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            padding: 60px 0;
        }
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
#password{
    padding: 15px 20px !important;
}
        .auth-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            max-width: 1000px;
            margin: 0 auto;
        }

        .auth-image {
            background: linear-gradient(45deg, rgba(243, 54, 79, 0.9), rgba(243, 54, 79, 0.7)),
            url("{{asset('assets/logo/main.png')}}") center/cover;
            min-height: 600px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 40px;
        }

        .auth-form {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            min-height: 600px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .auth-header h2 {
            color: #333;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .auth-header p {
            color: #666;
            font-size: 1.1rem;
            margin-bottom: 0;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 2px solid #e9ecef;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-control:focus {
            border-color: #053C7C;
            box-shadow: 0 0 0 0.2rem rgba(243, 54, 79, 0.15);
            background: white;
        }

        .btn-primary {
            background: linear-gradient(45deg, #053C7C, #141733);
            border: none;
            border-radius: 12px;
            padding: 15px 30px;
            font-weight: 600;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(243, 54, 79, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(243, 54, 79, 0.4);
            background: linear-gradient(45deg, #8A1821, #b8243e);
        }

        .auth-links {
            text-align: center;
            margin-top: 30px;
        }

        .auth-links a {
            color: #053C7C;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-links a:hover {
            color: #8A1821;
            text-decoration: underline;
        }

        .divider {
            text-align: center;
            margin: 30px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background: #e9ecef;
        }

        .divider span {
            background: white;
            padding: 0 20px;
            color: #666;
            font-size: 0.9rem;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-check-input:checked {
            background-color: #053C7C;
            border-color: #053C7C;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(243, 54, 79, 0.15);
        }

        .forgot-password {
            color: #053C7C;
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .forgot-password:hover {
            color: #8A1821;
            text-decoration: underline;
        }

        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .alert-danger {
            background: rgba(243, 54, 79, 0.1);
            color: #053C7C;
        }

        @media (max-width: 768px) {
            .auth-form {
                padding: 40px 30px;
            }

            .auth-header h2 {
                font-size: 2rem;
            }

            .auth-image {
                min-height: 300px;
                padding: 30px 20px;
            }

            .remember-forgot {
                flex-direction: column;
                gap: 15px;
                align-items: flex-start;
            }
        }

        @media (max-width: 576px) {
            .auth-form {
                padding: 30px 20px;
            }

            .auth-header h2 {
                font-size: 1.8rem;
            }
        }
    </style>
@endpush

@section('main')
    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="row g-0">
                    <div class="col-lg-6 auth-image">
                        <div class="">
                            <div>
                                <h3 class="mb-4">Welcome Back!</h3>
                                <p class="mb-0">Start your car adventure in Japan. Login to access your bookings
                                    and explore our premium car collection.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="auth-form">
                            <div class="auth-header">
                                <h2>Login</h2>
                                <p>Sign in to your account</p>
                            </div>

                            <form action="{{ route('login.action') }}" id="loginForm" method="POST">
                                @csrf

                                <div class="form-group">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           id="email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           autocomplete="username"
                                           autofocus
                                           placeholder="Enter your email address">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group">
                                    <label for="password" class="form-label">Password</label>
                                    <div class="input-group">

                                    <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           autocomplete="current-password"
                                           placeholder="Enter your password">
                                    <button type="button" class="bg-transparent border btn btn-outline-secondary text-black" onclick="togglePassword('password', this)">
                                        <i class="bx bx-eye"></i>
                                    </button>
                                    </div>
                                    @error('password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                                <div class="remember-forgot">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="remember"
                                               id="remember"
                                                {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">
                                            Remember me
                                        </label>
                                    </div>

                                    <a class="forgot-password" href="{{ route('password.request')}}">
                                        Forgot Password?
                                    </a>
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id="loginBtn">
                                    <i class="bx bx-loader spinner me-2" style="display: none" id="loginBtnSpinner"></i>
                                    Login to Account
                                </button>
                            </form>


                            <div class="divider">
                                <span>or</span>
                            </div>

                            <div class="auth-links">
                                <p class="mb-0">Don't have an account?
                                    <a href="{{ route('register') }}">Create Account</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        function togglePassword(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector("i");

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bx-eye");
                icon.classList.add("bx-eye-slash");
            } else {
                input.type = "password";
                icon.classList.remove("bx-eye-slash");
                icon.classList.add("bx-eye");
            }
        }
        $(document).ready(function () {

            // Add focus effects
            $('.form-control').on('focus', function () {
                $(this).parent().addClass('focused');
            });

            $('.form-control').on('blur', function () {
                if ($(this).val() === '') {
                    $(this).parent().removeClass('focused');
                }
            });

            // Auto-focus first input with error
            if ($('.is-invalid').length > 0) {
                $('.is-invalid').first().focus();
            }

            function resetLoginForm() {
                $("#loginForm")[0].reset();
                $('.text-danger.error').hide().text('');
            }


            $("#loginForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    email: {
                        required: "The email field is required.",
                        email: "Please enter a valid email address."
                    },
                    password: {
                        required: "The password field is required.",
                        minlength: "Password must be at least 6 characters long."
                    }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    // If inside an input-group (e.g. password with eye toggle), place error after the group wrapper
                    if (element.parent('.input-group').length) {
                        element.parent('.input-group').after(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form) {
                    $('#loginBtn').attr('disabled', true);
                    $("#loginBtnSpinner").show();
                    form.submit(); // Standard HTML submit — lets Chrome detect login & save password
                }
            });
        });


    </script>
@endsection

