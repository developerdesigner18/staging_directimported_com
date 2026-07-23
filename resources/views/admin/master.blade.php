<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg"
    data-sidebar-image="none" data-preloader="enable">

<head>

    <meta charset="utf-8" />
    <title>@yield('title') | {{env('APP_NAME')}}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    @include('admin.layouts.header-links')
    @stack('style-link')
    @stack('style')
    @yield('style')

    <style>
        .select2-container .select2-selection--multiple .select2-selection__choice {
            background-color: #405189 !important;
        }

        /* Select2 Style Overrides to make it look exactly like Velzon theme inputs */
        .select2-container {
            display: block;
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: var(--vz-input-bg, #fff) !important;
            border: 1px solid var(--vz-input-border, #ced4da) !important;
            border-radius: 0.25rem !important;
            height: 37.5px !important;
            padding: 0.25rem 0.6rem !important;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            display: flex;
            align-items: center;
            position: relative;
        }

        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--single {
            border-color: #a2accd !important;
            outline: 0;
            box-shadow: 0 0 0 0.2rem rgba(64, 81, 137, 0.25) !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--vz-body-color, #212529) !important;
            padding-left: 0 !important;
            padding-right: 20px !important;
            font-size: 13px !important;
            line-height: 1.5 !important;
            width: 100%;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #878a99 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
            right: 8px !important;
            width: 20px !important;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow b {
            border-color: #878a99 transparent transparent transparent !important;
            border-width: 5px 4px 0 4px !important;
            margin-left: 0 !important;
            margin-top: 0 !important;
            position: relative !important;
        }

        .select2-container--default.select2-container--open .select2-selection--arrow b {
            border-color: transparent transparent #878a99 transparent !important;
            border-width: 0 4px 5px 4px !important;
        }

        /* Clear button alignment (the 'x' icon) */
        .select2-container--default .select2-selection--single .select2-selection__clear {
            position: absolute !important;
            right: 25px !important;
            /* Keep it on the right side next to the arrow */
            top: 50% !important;
            transform: translateY(-50%) !important;
            float: none !important;
            margin-right: 0 !important;
            font-weight: bold !important;
            font-size: 14px !important;
            color: #878a99 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__clear:hover {
            color: #f06548 !important;
        }

        /* Dropdown Styling */
        .select2-dropdown {
            background-color: var(--vz-input-bg, #fff) !important;
            border: 1px solid var(--vz-input-border, #ced4da) !important;
            border-radius: 0.25rem !important;
            box-shadow: 0 5px 10px rgba(30, 32, 37, 0.12) !important;
            font-size: 13px !important;
            z-index: 1056 !important;
        }

        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid var(--vz-input-border, #ced4da) !important;
            background-color: var(--vz-input-bg, #fff) !important;
            color: var(--vz-body-color, #212529) !important;
            border-radius: 0.25rem !important;
            padding: 5px 10px !important;
            font-size: 13px !important;
        }

        .select2-container--default .select2-results__option[aria-selected] {
            padding: 6px 12px !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #405189 !important;
            color: #fff !important;
        }

        .select2-container--default .select2-results__option[aria-selected="true"] {
            background-color: rgba(64, 81, 137, 0.1) !important;
            color: #405189 !important;
        }

        label.error {
            display: block;
            width: 100%;
            margin-top: 4px;
            font-size: 13px;
            font-weight: 500;
            color: #f06548;
        }

        .input-group+label.error {
            margin-top: 4px;
        }
    </style>
</head>

<body>

    <!-- Begin page -->
    <div id="layout-wrapper">

        @include('admin.layouts.header')

        <!-- removeNotificationModal -->
        @stack('modal')
        @stack('offcanvas')
        <div class="offcanvas offcanvas-end" tabindex="-1" id="changeNameCanvas"
            aria-labelledby="changeNameCanvasLabel">
            <div class="offcanvas-header">
                <h5 id="changeNameCanvasLabel">Change Name</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="changeNameForm">
                    @csrf
                    <div class="profile-user position-relative d-inline-block mx-auto text-center mb-4">
                        <img src="{{Auth::user()->profile_img}}"
                            class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                            alt="user-profile-image">
                        <div class="avatar-xs p-0 rounded-circle profile-photo-edit">
                            <input id="profile-img-file-input" type="file" name="profile_img"
                                class="profile-img-file-input">
                            <label for="profile-img-file-input" class="profile-photo-edit avatar-xs">
                                <span class="avatar-title rounded-circle bg-light text-body material-shadow">
                                    <i class="ri-camera-fill"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="newName" class="form-label">New Name</label>
                        <input type="text" class="form-control" id="newName" name="name" value="{{Auth::user()->name}}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">E-mail</label>
                        <input type="text" class="form-control" id="email" value="{{Auth::user()->email}}" readonly>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Change Password Offcanvas -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="changePasswordCanvas"
            aria-labelledby="changePasswordCanvasLabel">
            <div class="offcanvas-header">
                <h5 id="changePasswordCanvasLabel">Change Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="changePasswordForm">
                    @csrf
                    <div class="mb-3">
                        <label for="currentPassword" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="currentPassword" name="current_password">
                    </div>
                    <div class="mb-3">
                        <label for="newPassword" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="newPassword" name="password">
                    </div>
                    <div class="mb-3">
                        <label for="confirmPassword" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirmPassword" name="password_confirmation">
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
        <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                            id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 text-center">
                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                                colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4>Are you sure ?</h4>
                                <p class="text-muted mx-4 mb-0">Are you sure you want to remove this Notification ?</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="button" class="btn w-sm btn-danger" id="delete-notification">Yes, Delete It!
                            </button>
                        </div>
                    </div>

                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <!-- ========== App Menu ========== -->
        @include('admin.layouts.sidebar')
        <!-- Left Sidebar End -->
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">
                    @yield('main')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('admin.layouts.footer')
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!--start back-to-top-->
    <button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
        <i class="ri-arrow-up-line"></i>
    </button>
    <!--end back-to-top-->

    <!--preloader-->
    <div id="preloader">
        <div id="status">
            <div class="spinner-border text-primary avatar-sm" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    </div>

    @include('admin.layouts.theme-setting')

    @include('admin.layouts.footer-links')
    @include('admin.layouts.common-js')
    @stack('script-link')
    @stack('script')
    @yield('script')
    <script !src="">
        $(document).ready(function () {

            $('#changeNameForm').validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    },
                },
                messages: {
                    name: {
                        required: "Please enter your new name.",
                        minlength: "Name must be at least 3 characters."
                    },
                },
                errorClass: "text-danger error",
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: "{{ route('admin.update-name') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {

                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            var myOffcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('changeNameCanvas'));
                            myOffcanvas.hide();
                            window.location.reload();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    sendError(value);
                                });
                            } else {
                                actionError(xhr);
                            }
                        }
                    });
                }
            });

            $('#changePasswordForm').validate({
                rules: {
                    current_password: {
                        required: true,
                        minlength: 8
                    },
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        equalTo: "#newPassword"
                    },
                },
                messages: {
                    current_password: {
                        required: "Please enter your current password.",
                        minlength: "Password must be at least 8 characters."
                    },
                    password: {
                        required: "Please enter a new password.",
                        minlength: "Password must be at least 8 characters."
                    },
                    password_confirmation: {
                        required: "Please confirm your new password.",
                        minlength: "Password must be at least 8 characters.",
                        equalTo: "New password and confirm password must match."
                    },
                },
                errorClass: "text-danger error",
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: "{{ route('admin.update-password') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {

                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            var myOffcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('changePasswordCanvas'));
                            myOffcanvas.hide();
                            window.location.reload();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    var errorLabel = $("#" + key + "-error");
                                    if (key == 'password') {
                                        errorLabel = $("#newPassword-error");
                                    }
                                    if (errorLabel.length) {
                                        errorLabel.html(value).show();
                                    } else {
                                        sendError(value);
                                    }
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        }
                    });
                }
            });

            $('#profile-img-file-input').change(function (e) {
                // Check if any file is selected
                if (this.files && this.files[0]) {
                    var reader = new FileReader();

                    // Set up the file reader
                    reader.onload = function (e) {
                        // Update the src attribute of the profile image with the new image data
                        $('.user-profile-image').attr('src', e.target.result);
                    }
                    // Read the selected file as a data URL
                    reader.readAsDataURL(this.files[0]);
                }
            });
        });
    </script>
</body>

</html>