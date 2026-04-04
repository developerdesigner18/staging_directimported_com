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
                            actionError(xhr);
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
                            if (data.hasOwnProperty('message')) {
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
