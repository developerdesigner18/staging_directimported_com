@extends('admin.master')
@section('title','System Settings')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">System Settings</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">System</a></li>
                        <li class="breadcrumb-item active">Settings</li>
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
                            <label for="RECEIVER_MAIL" class="form-label">System Receiver Mail</label>
                            <input type="text" class="form-control" id="RECEIVER_MAIL" name="RECEIVER_MAIL"
                                   placeholder="Enter reciver email of system"
                                   value="{{ env('RECEIVER_MAIL') }}">
                            <label id="RECEIVER_MAIL-error" class="text-danger error" for="RECEIVER_MAIL"
                                   style="display: none"></label>
                        </div>

                        <!-- MAIL_HOST -->
                        <div class="mb-3">
                            <label for="MAIL_HOST" class="form-label">Mail Host</label>
                            <input type="text" class="form-control" id="MAIL_HOST" name="MAIL_HOST"
                                   placeholder="Enter mail host (e.g., smtp.mailtrap.io)"
                                   value="{{ env('MAIL_HOST') }}">
                            <label id="MAIL_HOST-error" class="text-danger error" for="MAIL_HOST"
                                   style="display: none"></label>
                        </div>

                        <!-- MAIL_PORT -->
                        <div class="mb-3">
                            <label for="MAIL_PORT" class="form-label">Mail Port</label>
                            <input type="number" class="form-control" id="MAIL_PORT" name="MAIL_PORT"
                                   placeholder="Enter mail port (e.g., 2525)"
                                   value="{{ env('MAIL_PORT') }}">
                            <label id="MAIL_PORT-error" class="text-danger error" for="MAIL_PORT"
                                   style="display: none"></label>
                        </div>

                        <!-- MAIL_USERNAME -->
                        <div class="mb-3">
                            <label for="MAIL_USERNAME" class="form-label">Mail Username</label>
                            <input type="text" class="form-control" id="MAIL_USERNAME" name="MAIL_USERNAME"
                                   placeholder="Enter mail username"
                                   value="{{ env('MAIL_USERNAME') }}">
                            <label id="MAIL_USERNAME-error" class="text-danger error" for="MAIL_USERNAME"
                                   style="display: none"></label>
                        </div>

                        <!-- MAIL_PASSWORD -->
                        <div class="mb-3">
                            <label for="MAIL_PASSWORD" class="form-label">Mail Password</label>
                            <input type="password" class="form-control" id="MAIL_PASSWORD" name="MAIL_PASSWORD"
                                   placeholder="Enter mail password"
                                   value="{{ env('MAIL_PASSWORD') }}">
                            <label id="MAIL_PASSWORD-error" class="text-danger error" for="MAIL_PASSWORD"
                                   style="display: none"></label>
                        </div>

                        <!-- MAIL_ENCRYPTION -->
                        <div class="mb-3">
                            <label for="MAIL_ENCRYPTION" class="form-label">Mail Encryption</label>
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
                            <label for="MAIL_FROM_ADDRESS" class="form-label">From Address</label>
                            <input type="email" class="form-control" id="MAIL_FROM_ADDRESS" name="MAIL_FROM_ADDRESS"
                                   placeholder="Enter from address (e.g., no-reply@example.com)"
                                   value="{{ env('MAIL_FROM_ADDRESS') }}">
                            <label id="MAIL_FROM_ADDRESS-error" class="text-danger error" for="MAIL_FROM_ADDRESS"
                                   style="display: none"></label>
                        </div>

                        <!-- MAIL_FROM_NAME -->
                        <div class="mb-3">
                            <label for="MAIL_FROM_NAME" class="form-label">From Name</label>
                            <input type="text" class="form-control" id="MAIL_FROM_NAME" name="MAIL_FROM_NAME"
                                   placeholder="Enter from name (e.g., Your App Name)"
                                   value="{{ env('MAIL_FROM_NAME') }}">
                            <label id="MAIL_FROM_NAME-error" class="text-danger error" for="MAIL_FROM_NAME"
                                   style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $("#settingsForm").validate({
                rules: {
                    MAIL_HOST: {required: true},
                    MAIL_PORT: {required: true, number: true},
                    MAIL_USERNAME: {required: true},
                    MAIL_PASSWORD: {required: true},
                    MAIL_ENCRYPTION: {required: true},
                    MAIL_FROM_ADDRESS: {required: true, email: true},
                    MAIL_FROM_NAME: {required: true}
                },
                messages: {
                    MAIL_HOST: {required: "The mail host field is required."},
                    MAIL_PORT: {
                        required: "The mail port field is required.",
                        number: "Please enter a valid port number"
                    },
                    MAIL_USERNAME: {required: "The mail username field is required."},
                    MAIL_PASSWORD: {required: "The mail password field is required."},
                    MAIL_ENCRYPTION: {required: "Please select an encryption method."},
                    MAIL_FROM_ADDRESS: {
                        required: "The from address field is required.",
                        email: "Please enter a valid email address"
                    },
                    MAIL_FROM_NAME: {required: "The from name field is required."}
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
                            if(result.success) {
                                sendToast(result.message || 'Settings updated successfully!');
                            } else {
                                sendToast(result.message || 'An error occurred.','danger');
                            }
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('errors')) {
                                $.each(data.errors, function (key, value) {
                                    $("#" + key + "-error").html(value[0]).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                sendToast(data.message,'danger');
                            } else {
                                sendToast("An error occurred. Please try again.",'danger');
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Update Settings');
                        }
                    });
                }
            });
        });
    </script>
@endsection
