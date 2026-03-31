@extends('landing.master')
@section('title','ResetPassword')
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
            border-color: #F3364F;
            box-shadow: 0 0 0 0.2rem rgba(243, 54, 79, 0.15);
            background: white;
        }

        .btn-primary {
            background: linear-gradient(45deg, #F3364F, #d12a47);
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
            background: linear-gradient(45deg, #d12a47, #b8243e);
        }

        .auth-links {
            text-align: center;
            margin-top: 30px;
        }

        .auth-links a {
            color: #F3364F;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .auth-links a:hover {
            color: #d12a47;
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
            background-color: #F3364F;
            border-color: #F3364F;
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
            color: #F3364F;
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
            color: #F3364F;
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
                        <!-- Left Side Image/Promo -->
                        <div class="col-lg-6 auth-image">
                            <div>
                                <h3 class="mb-4">Forgot Your Password?</h3>
                                <p class="mb-0">No worries! Enter your email address and we’ll send you a link to reset your password securely.</p>
                            </div>
                        </div>

                        <!-- Right Side Form -->
                        <div class="col-lg-6">
                            <div class="auth-form">
                                <div class="auth-header">
                                    <h2>Reset Password</h2>
                                    <p>Enter your email to get reset instructions</p>
                                </div>

                                <form action="javascript:void(0);" id="forgotPasswordForm" method="POST">
                                    @csrf

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" name="email" id="email"
                                               class="form-control" placeholder="your@email.com" required>
                                        <label id="email-error" class="text-danger error"
                                               for="email" style="display:none"></label>
                                    </div>

                                    <!-- Submit -->
                                    <button type="submit" class="btn btn-primary w-100" id="forgotBtn"> <i class="bx bx-loader spinner me-2" style="display: none" id="forgotBtnSpinner"></i>Send Reset Link</button>

                                    <!-- Links -->
                                    <div class="auth-links">
                                        <p><a href="{{ route('login') }}">
                                        Back to Login</a></p>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Form -->
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


            $("#forgotPasswordForm").validate({
                rules: {

                    email: {
                        required: true,
                        email: true
                    }

                },
                messages: {

                    email: {
                        required: "The email field is required.",
                        email: "Please enter a valid email address."
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
                        url: "{{ route('password.email') }}", // Update with your register route
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#forgotBtn').attr('disabled', true);
                            $("#forgotBtnSpinner").show();
                            // Clear previous errors
                            $('.text-danger.error').hide().text('');
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                     

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
                            $('#forgotBtn').attr('disabled', false);
                            $("#forgotBtnSpinner").hide();
                        },
                    });
                }
            });

            function resetRegisterForm() {
                $("#registerForm")[0].reset();
                $('.text-danger.error').hide().text('');
            }
        });


    </script>
@endsection
