@extends('admin.master')
@section('title', 'System Settings')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">System Settings Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">System</a></li>
                        <li class="breadcrumb-item active">System Settings Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="settingsForm" method="POST" action="{{ route('admin.system.settings.update') }}">
                        @csrf
                        <h5 class="mb-3">Email Configuration</h5>

                        <!-- MAIL_HOST -->
                        <div class="mb-3">
                            <label for="RECEIVER_MAIL"
                                class="form-label">{{ admin_label('settings_form', 'receiver_mail', 'System Receiver Mail') }}</label>
                            <input type="text" class="form-control" id="RECEIVER_MAIL" name="RECEIVER_MAIL"
                                placeholder="Enter reciver email of system" value="{{ env('RECEIVER_MAIL') }}">
                            <label id="RECEIVER_MAIL-error" class="text-danger error" for="RECEIVER_MAIL"
                                style="display: none"></label>
                        </div>

                        <!-- MAIL_HOST -->
                        <div class="mb-3">
                            <label for="MAIL_HOST"
                                class="form-label">{{ admin_label('settings_form', 'mail_host', 'Mail Host') }}</label>
                            <input type="text" class="form-control" id="MAIL_HOST" name="MAIL_HOST"
                                placeholder="Enter mail host (e.g., smtp.mailtrap.io)" value="{{ env('MAIL_HOST') }}">
                            <label id="MAIL_HOST-error" class="text-danger error" for="MAIL_HOST"
                                style="display: none"></label>
                        </div>

                        <!-- MAIL_PORT -->
                        <div class="mb-3">
                            <label for="MAIL_PORT"
                                class="form-label">{{ admin_label('settings_form', 'mail_port', 'Mail Port') }}</label>
                            <input type="number" class="form-control" id="MAIL_PORT" name="MAIL_PORT"
                                placeholder="Enter mail port (e.g., 2525)" value="{{ env('MAIL_PORT') }}">
                            <label id="MAIL_PORT-error" class="text-danger error" for="MAIL_PORT"
                                style="display: none"></label>
                        </div>

                        <!-- MAIL_USERNAME -->
                        <div class="mb-3">
                            <label for="MAIL_USERNAME"
                                class="form-label">{{ admin_label('settings_form', 'mail_username', 'Mail Username') }}</label>
                            <input type="text" class="form-control" id="MAIL_USERNAME" name="MAIL_USERNAME"
                                placeholder="Enter mail username" value="{{ env('MAIL_USERNAME') }}">
                            <label id="MAIL_USERNAME-error" class="text-danger error" for="MAIL_USERNAME"
                                style="display: none"></label>
                        </div>

                        <!-- MAIL_PASSWORD -->
                        <div class="mb-3">
                            <label for="MAIL_PASSWORD"
                                class="form-label">{{ admin_label('settings_form', 'mail_password', 'Mail Password') }}</label>
                            <input type="password" class="form-control" id="MAIL_PASSWORD" name="MAIL_PASSWORD"
                                placeholder="Enter mail password" value="{{ env('MAIL_PASSWORD') }}">
                            <label id="MAIL_PASSWORD-error" class="text-danger error" for="MAIL_PASSWORD"
                                style="display: none"></label>
                        </div>

                        <!-- MAIL_ENCRYPTION -->
                        <div class="mb-3">
                            <label for="MAIL_ENCRYPTION"
                                class="form-label">{{ admin_label('settings_form', 'mail_encryption', 'Mail Encryption') }}</label>
                            <select class="form-control" id="MAIL_ENCRYPTION" name="MAIL_ENCRYPTION">
                                <option value="tls" {{ env('MAIL_ENCRYPTION') == 'tls' ? 'selected' : '' }}>TLS</option>
                                <option value="ssl" {{ env('MAIL_ENCRYPTION') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="" {{ empty(env('MAIL_ENCRYPTION')) ? 'selected' : '' }}>None</option>
                            </select>
                            <label id="MAIL_ENCRYPTION-error" class="text-danger error" for="MAIL_ENCRYPTION"
                                style="display: none"></label>
                        </div>

                        <!-- MAIL_FROM_ADDRESS -->
                        <div class="mb-3">
                            <label for="MAIL_FROM_ADDRESS"
                                class="form-label">{{ admin_label('settings_form', 'from_address', 'From Address') }}</label>
                            <input type="email" class="form-control" id="MAIL_FROM_ADDRESS" name="MAIL_FROM_ADDRESS"
                                placeholder="Enter from address (e.g., no-reply@example.com)"
                                value="{{ env('MAIL_FROM_ADDRESS') }}">
                            <label id="MAIL_FROM_ADDRESS-error" class="text-danger error" for="MAIL_FROM_ADDRESS"
                                style="display: none"></label>
                        </div>

                        <!-- MAIL_FROM_NAME -->
                        <div class="mb-3">
                            <label for="MAIL_FROM_NAME"
                                class="form-label">{{ admin_label('settings_form', 'from_name', 'From Name') }}</label>
                            <input type="text" class="form-control" id="MAIL_FROM_NAME" name="MAIL_FROM_NAME"
                                placeholder="Enter from name (e.g., Your App Name)" value="{{ env('MAIL_FROM_NAME') }}">
                            <label id="MAIL_FROM_NAME-error" class="text-danger error" for="MAIL_FROM_NAME"
                                style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="POST" id="siteSettings" enctype="multipart/form-data">
                        @csrf

                        <div class="row">

                            <!-- SOCIAL LINKS -->
                            <h5 class="mb-3">Social Links</h5>

                            <!-- Facebook -->
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label">{{ admin_label('settings_form', 'facebook_url', 'Facebook URL') }}</label>
                                <input type="text" name="facebook_url" class="form-control"
                                    value="{{ $settings->facebook_url ?? '' }}">
                            </div>

                            <!-- Instagram -->
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label">{{ admin_label('settings_form', 'instagram_url', 'Instagram URL') }}</label>
                                <input type="text" name="instagram_url" class="form-control"
                                    value="{{ $settings->instagram_url ?? '' }}">
                            </div>

                            <!-- Twitter -->
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label">{{ admin_label('settings_form', 'twitter_url', 'Twitter URL') }}</label>
                                <input type="text" name="twitter_url" class="form-control"
                                    value="{{ $settings->twitter_url ?? '' }}">
                            </div>

                            <!-- YouTube -->
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label">{{ admin_label('settings_form', 'youtube_url', 'YouTube URL') }}</label>
                                <input type="text" name="youtube_url" class="form-control"
                                    value="{{ $settings->youtube_url ?? '' }}">
                            </div>


                            <!-- LOGOS -->
                            <h5 class="mt-4 mb-3">Logos</h5>

                            <!-- Logo -->
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label">{{ admin_label('settings_form', 'main_logo', 'Main Logo') }}</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <p id="logo-error" class="text-danger mt-1" style="display: none"></p>
                                @if(!empty($settings->logo))
                                    <div class="mt-2">
                                        <img src="{{ asset('assets/logo/' . $settings->logo) }}" alt="site main logo"
                                            width="120">
                                    </div>
                                @endif
                            </div>

                            <!-- Admin Logo -->
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label">{{ admin_label('settings_form', 'admin_logo', 'Admin Logo') }}</label>
                                <input type="file" name="admin_logo" class="form-control" accept="image/*">
                                <p id="admin_logo-error" class="text-danger mt-1" style="display: none"></p>
                                @if(!empty($settings->admin_logo))
                                    <div class="mt-2">
                                        <img src="{{ asset('assets/logo/' . $settings->admin_logo) }}" alt="site admin logo"
                                            width="120">
                                    </div>
                                @endif
                            </div>

                            <!-- Footer Logo -->
                            <div class="col-md-6 mb-3">
                                <label
                                    class="form-label">{{ admin_label('settings_form', 'footer_logo', 'Footer Logo') }}</label>
                                <input type="file" name="footer_logo" class="form-control" accept="image/*">
                                <p id="footer_logo-error" class="text-danger mt-1" style="display: none"></p>
                                @if(!empty($settings->footer_logo))
                                    <div class="mt-2">
                                        <img src="{{ asset('assets/logo/' . $settings->footer_logo) }}" alt="site footer logo"
                                            width="120">
                                    </div>
                                @endif
                            </div>

                            <!-- Favicon -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">{{ admin_label('settings_form', 'favicon', 'Favicon') }}</label>
                                <input type="file" name="favicon" class="form-control" accept="image/*">
                                <p id="favicon-error" class="text-danger mt-1" style="display: none"></p>
                                @if(!empty($settings->favicon))
                                    <div class="mt-2">
                                        <img src="{{ asset('assets/logo/' . $settings->favicon) }}" alt="site favicon"
                                            width="120">
                                    </div>
                                @endif
                            </div>

                        </div>

                        <!-- BUTTON -->
                        <div class="mt-3">
                            <button class="btn btn-primary">Save Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {


            $("#settingsForm").validate({
                rules: {
                    MAIL_HOST: { required: true },
                    MAIL_PORT: { required: true, number: true },
                    MAIL_USERNAME: { required: true },
                    MAIL_PASSWORD: { required: true },
                    MAIL_ENCRYPTION: { required: true },
                    MAIL_FROM_ADDRESS: { required: true, email: true },
                    MAIL_FROM_NAME: { required: true }
                },
                messages: {
                    MAIL_HOST: { required: "The mail host field is required." },
                    MAIL_PORT: {
                        required: "The mail port field is required.",
                        number: "Please enter a valid port number"
                    },
                    MAIL_USERNAME: { required: "The mail username field is required." },
                    MAIL_PASSWORD: { required: "The mail password field is required." },
                    MAIL_ENCRYPTION: { required: "Please select an encryption method." },
                    MAIL_FROM_ADDRESS: {
                        required: "The from address field is required.",
                        email: "Please enter a valid email address"
                    },
                    MAIL_FROM_NAME: { required: "The from name field is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    $.ajax({
                        url: $(form).attr('action'),
                        method: "POST",
                        dataType: "json",
                        data: $(form).serialize(),
                        beforeSend: function () {
                            $('button[type="submit"]').attr('disabled', true);
                            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Update Settings');
                        }
                    });
                }
            });

            $('#siteSettings').validate({
                rules: {
                    facebook_url: { required: true, url: true },
                    instagram_url: { required: true, url: true },
                    twitter_url: { required: true, url: true },
                    youtube_url: { required: true, url: true },
                    logo: { required: false },
                    admin_logo: { required: false },
                    footer_logo: { required: false },
                    favicon: { required: false }
                },
                messages: {
                    facebook_url: { required: "Please enter a facebook url.", url: "Please enter a valid URL." },
                    instagram_url: { required: "Please enter a instagram url.", url: "Please enter a valid URL." },
                    twitter_url: { required: "Please enter a twitter url.", url: "Please enter a valid URL." },
                    youtube_url: { required: "Please enter a youtube url.", url: "Please enter a valid URL." },
                },
                errorPlacement: function (error, element) {
                    $("#" + element.attr("name") + "-error").html(error.text()).show();
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                    $("#" + $(element).attr("name") + "-error").hide();
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    let formData = new FormData(form);
                    let element = $(form).find('button[type="submit"]');

                    $.ajax({
                        url: "{{ route('admin.site.update') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        beforeSend: function () {
                            element.html('<i class="spinner-border spinner-border-sm"></i> Saving...');
                            element.attr('disabled', true);
                            $('.text-danger').hide();
                        },
                        success: function (response) {
                            if (response.status) {
                                sendSuccess(response.message);
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            }
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.error) {
                                $.each(data.error, function (key, value) {
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + key + "-error").html(errorMessage).show();
                                });
                            } else {
                                sendError("Something went wrong.");
                            }
                        },
                        complete: function () {
                            element.html('Save Settings');
                            element.attr('disabled', false);
                        }
                    });
                }
            });
        });
    </script>
@endsection