@extends('landing.master')
@section('title','Contact Us')
@push('style-src')
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endpush
@push('style')
<style>
    .rid-menubar ul li a {
        font-size: 15px !important;
        margin-right: 20px !important;
    }
</style>
@endpush
@section('main')
    <section class="rid-contact sec-space">
        <div class="container">
            <div class="contact-title">
                <h2>Contact Information</h2>
                <p>If you require any information whatsoever, please fill out the contact form below. Please do have a
                    look at our FAQs as most information is on there but we are happy to answer any questions you may
                    have. We aim to answer all inquiries within 24hours however this may take a little longer at busier
                    times so please bear with us. For rental or tour quotes, please complete the booking request form
                    and we will contact you as soon as possible.
                </p>
            </div>
            <div class="row">
                <div class="col-lg-5">
                    <div class="contact-address">
                        <div class="phone">
                            <div class="rid-info-box-icon ">
                                <div class="rid-info-box-icon position-relative">
                                    <i class="flaticon-smartphone"></i>
                                </div>
                            </div>
                            <div class="rid-info-box-text">
                                <h4>Our Phone</h4>
                                <span class="d-block">+81 6 4864 2081</span>
                            </div>
                        </div>

                        <div class="address">
                            <div class="rid-info-box-icon ">
                                <div class="rid-info-box-icon position-relative">
                                    <i class="flaticon-pin"></i>
                                </div>
                            </div>
                            <div class="rid-info-box-text">
                                <h4>Address</h4>
                                <span>4-10 Senrioka Shimo, Suita Shi, Osaka 565-0813</span>
                            </div>
                        </div>

                        <div class="email">
                            <div class="rid-info-box-icon ">
                                <div class="rid-info-box-icon position-relative">
                                    <i class="flaticon-earth"></i>
                                </div>
                            </div>
                            <div class="rid-info-box-text">
                                <h4>Online Support</h4>
                                <span><a href="https://m.me/carrentaljapan">Facebook</a></span>
                                <span><a href="mailto:rental@carrentaljapan.com">rental@carrentaljapan.com</a></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-form">
                        <h4>Write a Message</h4>
                        <p>You have any questions or need additional information? <strong>Register.</strong></p>
                        <form id="contactUs">
                            @csrf
                            <div class="mb-4">
                                <input type="text" class="form-control" placeholder="Name" name="name">
                            </div>
                            <div class="mb-4">
                                <input type="email" class="form-control" placeholder="Email" name="email">
                            </div>
                            <div class="mb-4">
                                <input type="number" class="form-control" placeholder="Contact Number" name="contactNumber">
                            </div>
                            <div class="mb-4">
                                <textarea class="form-control" rows="4" placeholder="Write Your Message" name="message"></textarea>
                            </div>
                            <div class="form-group">
                                <div data-callback="contactValidationCorrect" class="g-recaptcha custom-width"
                                     data-sitekey="{{env('CAPTCHA_SITE_KEY')}}"></div>
                            </div>
                            <button class="btn" type="submit">Send Message</button>
                            <span><strong>All queries are replied</strong> usually within 24hrs.</span>
                        </form>
                      
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script !src="">
        $(document).ready(function() {
            $("#contactUs").validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    contactNumber: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    },
                    message: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    name: {
                        required: "Please enter your name",
                        minlength: "Name must be at least 2 characters long"
                    },
                    email: {
                        required: "Please enter your email address",
                        email: "Please enter a valid email address"
                    },
                    contactNumber: {
                        required: "Please enter your contact number",
                        digits: "Please enter only digits",
                        minlength: "Contact number must be at least 10 digits",
                        maxlength: "Contact number cannot exceed 15 digits"
                    },
                    message: {
                        required: "Please enter your message",
                        minlength: "Message must be at least 10 characters long"
                    }
                },
                errorClass: 'text-danger error',
                errorPlacement: function(error, element) {
                    element.after(error);
                },
                submitHandler: function(form, e) {
                    e.preventDefault();

                    // Verify reCAPTCHA first
                    var recaptchaResponse = grecaptcha.getResponse();
                    if (recaptchaResponse.length === 0) {
                        sendToast("Please complete the reCAPTCHA verification",'danger');
                        return false;
                    }

                    $.ajax({
                        url: "{{route('contact.post')}}", // Update with your endpoint
                        method: "POST",
                        dataType: "json",
                        data: $(form).serialize(),
                        beforeSend: function() {
                            $('button[type="submit"]').attr('disabled', true);
                            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Sending...');
                        },
                        success: function(result) {
                            // Handle success
                            sendSuccess(result.message || 'Message sent successfully!');
                            form.reset();
                            grecaptcha.reset();
                        },
                        error: function(xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('errors')) {
                                $.each(data.errors, function(key, value) {
                                    $("#" + key + "-error").html(value[0]).show();
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                sendError(data.message);
                            } else {
                                sendError("An error occurred. Please try again.");
                            }
                        },
                        complete: function() {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Send Message');
                        }
                    });
                }
            });
        });
    </script>
@endsection

