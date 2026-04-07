@extends('landing.master')
@section('title','Register')
@push('style-src')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endpush
@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
        .auth-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            display: flex;
            align-items: center;
            padding: 60px 0;
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
            min-height: 700px;
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
            min-height: 700px;
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
            margin-bottom: 20px;
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

        .form-check-input:checked {
            background-color: #053C7C;
            border-color: #053C7C;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(243, 54, 79, 0.15);
        }

        .terms-checkbox {
            margin-bottom: 30px;
        }

        .terms-checkbox .form-check-label {
            font-size: 0.9rem;
            color: #666;
        }

        .terms-checkbox .form-check-label a {
            color: #053C7C;
            text-decoration: none;
        }

        .terms-checkbox .form-check-label a:hover {
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

        .password-requirements {
            font-size: 0.85rem;
            color: #666;
            margin-top: 5px;
        }

        .password-requirements ul {
            margin: 5px 0 0 0;
            padding-left: 20px;
        }

        .password-requirements li {
            margin-bottom: 2px;
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

            .form-group {
                margin-bottom: 18px;
            }
        }

        @media (max-width: 576px) {
            .auth-form {
                padding: 30px 20px;
            }

            .auth-header h2 {
                font-size: 1.8rem;
            }

            .form-group {
                margin-bottom: 15px;
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
                                <h3 class="mb-4">Join Our Community!</h3>
                                <p class="mb-0">Create your account and start exploring Japan on two wheels. Access
                                    premium motorcycles and unforgettable touring experiences.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="auth-form">
                            <div class="auth-header">
                                <h2>Register</h2>
                                <p>Create your new account</p>
                            </div>

                            <form action="javascript:void(0);" id="registerForm" method="POST">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name" class="form-label">First Name</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="first_name"
                                                   name="first_name"
                                                   value="{{ old('first_name') }}"
                                                   autocomplete="given-name"
                                                   autofocus
                                                   placeholder="First name">
                                            <label id="first_name-error" class="text-danger error" for="first_name"
                                                   style="display: none"></label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="form-label">Last Name</label>
                                            <input type="text"
                                                   class="form-control"
                                                   id="last_name"
                                                   name="last_name"
                                                   value="{{ old('last_name') }}"
                                                   autocomplete="family-name"
                                                   placeholder="Last name">
                                            <label id="last_name-error" class="text-danger error" for="last_name"
                                                   style="display: none"></label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="reg_email" class="form-label">Email Address</label>
                                    <input type="email"
                                           class="form-control"
                                           id="reg_email"
                                           name="email"
                                           value="{{ old('email') }}"
                                           autocomplete="email"
                                           placeholder="Enter your email address">
                                    <label id="reg_email-error" class="text-danger error" for="reg_email"
                                           style="display: none"></label>
                                </div>

                                <div class="form-group">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel"
                                           class="form-control"
                                           id="phone"
                                           name="phone"
                                           value="{{ old('phone') }}"
                                           autocomplete="tel"
                                           placeholder="Enter your phone number">
                                    <label id="phone-error" class="text-danger error" for="phone"
                                           style="display: none"></label>
                                </div>

                                <div class="form-group">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text"
                                           class="form-control"
                                           id="address"
                                           name="address"
                                           value="{{ old('address') }}"
                                           autocomplete="address"
                                           placeholder="Enter your address">
                                    <label id="address-error" class="text-danger error" for="address"
                                           style="display: none"></label>
                                </div>

                                <div class="form-group">
                                    <label for="country" class="form-label">Country</label>
                                    <input type="text"
                                           class="form-control"
                                           id="country"
                                           name="country"
                                           value="{{ old('country') }}"
                                           autocomplete="country"
                                           placeholder="Enter your country">
                                    <label id="country-error" class="text-danger error" for="country"
                                           style="display: none"></label>
                                </div>

                                <div class="form-group">
                                    <div class="align-items-baseline d-flex justify-content-between">
                                        <label for="reg_password" class="form-label">Password</label>
                                        <button type="button" class="btn btn-primary text-capitalize fs-6 p-2" onclick="generatePassword()">Suggest Password</button>
                                    </div>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control"
                                               id="reg_password"
                                               name="password"
                                               autocomplete="new-password"
                                               placeholder="Create a strong password">
                                        <button type="button" class="bg-transparent border btn btn-outline-secondary text-black" onclick="togglePassword('reg_password', this)">
                                            <i class="bx bx-eye"></i>
                                        </button>
                                    </div>
                                    <label id="reg_password-error" class="text-danger error" for="reg_password"
                                           style="display: none"></label>
                                </div>

                                <div class="form-group">
                                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                                    <div class="input-group">
                                        <input type="password"
                                               class="form-control"
                                               id="password_confirmation"
                                               name="password_confirmation"
                                               autocomplete="new-password"
                                               placeholder="Confirm your password">
                                        <button type="button" class="bg-transparent border btn btn-outline-secondary text-black" onclick="togglePassword('password_confirmation', this)">
                                            <i class="bx bx-eye"></i>
                                        </button>
                                    </div>
                                    <label id="password_confirmation-error" class="text-danger error"
                                           for="password_confirmation" style="display: none"></label>
                                </div>

                                <div class="terms-checkbox">
                                    <div class="form-check">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="terms"
                                               id="terms">
                                        <label class="form-check-label" for="terms">
                                            I agree to the <a href="#" target="_blank">Terms of Service</a> and
                                            <a href="#" target="_blank">Privacy Policy</a>
                                        </label>
                                        <label id="terms-error" class="text-danger error" for="terms"
                                               style="display: none"></label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <span id="g-recaptcha-response-error" class="text-danger error"
                                          style="display: none">BUND</span>
                                    <div data-callback="contactValidationCorrect" class="g-recaptcha custom-width"
                                         data-sitekey="{{env('CAPTCHA_SITE_KEY')}}"></div>
                                </div>

                                <button type="submit" class="btn btn-primary w-100" id="registerBtn">
                                    <i class="bx bx-loader spinner me-2" style="display: none"
                                       id="registerBtnSpinner"></i>
                                    Create Account
                                </button>
                            </form>


                            <div class="divider">
                                <span>or</span>
                            </div>

                            <div class="auth-links">
                                <p class="mb-0">Already have an account?
                                    <a href="{{ route('login') }}">Sign In</a>
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


            $("#registerForm").validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true,
                        minlength: 10
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#reg_password"
                    },
                    terms: {
                        required: true
                    }
                },
                messages: {
                    first_name: {
                        required: "The first name field is required.",
                        minlength: "First name must be at least 2 characters long."
                    },
                    last_name: {
                        required: "The last name field is required.",
                        minlength: "Last name must be at least 2 characters long."
                    },
                    email: {
                        required: "The email field is required.",
                        email: "Please enter a valid email address."
                    },
                    phone: {
                        required: "The phone number field is required.",
                        minlength: "Phone number must be at least 10 digits."
                    },
                    password: {
                        required: "The password field is required.",
                        minlength: "Password must be at least 8 characters long.",
                    },
                    password_confirmation: {
                        required: "The password confirmation field is required.",
                        equalTo: "Passwords do not match."
                    },
                    terms: {
                        required: "You must agree to the terms and conditions."
                    }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    if (element.attr('name') === 'terms') {
                        element.closest('.form-check').after(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('register.action') }}", // Update with your register route
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#registerBtn').attr('disabled', true);
                            $("#registerBtnSpinner").show();
                            // Clear previous errors
                            $('.text-danger.error').hide().text('');
                        },
                        success: function (result) {
                            sendSuccess(result.message || 'Account created successfully!');
                            // Redirect to login or dashboard
                            setTimeout(function () {
                                window.location.href = "{{route('login')}}";
                            }, 2000);
                            
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    let fieldId = key;
                                    // Handle email field ID difference
                                    if (key === 'email') {
                                        fieldId = 'reg_email';
                                    }
                                    if (key === 'password') {
                                        fieldId = 'reg_password';
                                    }
                                    $("#" + fieldId + "-error").html(value[0]).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#registerBtn').attr('disabled', false);
                            $("#registerBtnSpinner").hide();
                        },
                    });
                }
            });

            function resetRegisterForm() {
                $("#registerForm")[0].reset();
                $('.text-danger.error').hide().text('');
            }
        });

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

        function generatePassword() {
            const charset = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()";
            let password = "";
            for (let i = 0; i < 12; i++) {
                password += charset.charAt(Math.floor(Math.random() * charset.length));
            }
            $('#reg_password').val(password);
            $('#password_confirmation').val(password);
        }
    </script>
@endsection

