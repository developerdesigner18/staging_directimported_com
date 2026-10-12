@extends('admin.master')
@section('title', 'Employee Management')

@push('modal')

    <!-- Modal: Add Employee -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="javascript:void(0);" method="POST" id="frmAddEmp">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!--First Name -->
                        <div class="mb-3">
                            <label for="employee_name"
                                class="form-label">{{ admin_label('employee_form', 'first_name', 'First Name') }}</label>
                            <input type="text" class="form-control" id="employee_first_name" name="first_name">
                        </div>
                        <!--Last Name -->
                        <div class="mb-3">
                            <label for="employee_name"
                                class="form-label">{{ admin_label('employee_form', 'last_name', 'Last Name') }}</label>
                            <input type="text" class="form-control" id="employee_last_name" name="last_name">
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="employee_email"
                                class="form-label">{{ admin_label('employee_form', 'email', 'Email') }}</label>
                            <input type="email" class="form-control" id="employee_email" name="email">
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="employee_password"
                                class="form-label">{{ admin_label('employee_form', 'password', 'Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="employee_password" name="password"
                                    placeholder="Enter password">
                                <button type="button" class="btn btn-outline-secondary toggle-password-btn"
                                    data-target="#employee_password" title="Show/Hide Password">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-generate-password"
                                    data-target="#employee_password">
                                    <i class="ri-key-2-line me-1"></i>Generate Password
                                </button>
                            </div>
                        </div>

                        <!-- Role/Department Select -->
                        <div class="mb-3">
                            <label for="employee_permissions"
                                class="form-label">{{ admin_label('employee_form', 'permissions', 'Permissions') }}</label>
                            <select class="form-select select2-multiple" id="employee_permissions" name="permission[]"
                                multiple>
                                @php
                                    $permissionTitles = [
                                        'dashboard' => 'Dashboards',
                                        'contact_requests' => 'Contact Requests',
                                        'cars' => 'Cars',
                                        'services' => 'Services',
                                        'about_us' => 'About Us',
                                        'blogs' => 'Blogs',
                                        'users' => 'Users',
                                        'employee' => 'Employee',
                                        'gallery' => 'Gallery',
                                        'slider' => 'Slider',
                                        'manage_information' => 'Manage Information',
                                        'newsletters' => 'Newsletters',
                                        'emails' => 'Emails',
                                        'system_settings' => 'System Settings',
                                    ];
                                @endphp
                                @foreach($AllPermissions as $acc)
                                    <option value="{{ $acc->id }}" {{ $acc->name === 'dashboard' ? 'selected' : '' }}>
                                        {{ $permissionTitles[$acc->name] ?? ucwords(str_replace('_', ' ', $acc->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary" id="btnAddEmp">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm me-2" id="btnAddEmpSpinner"></span>
                                <span>Add</span>
                            </span>
                        </button>

                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Modal: Edit Employee -->
    <div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form action="javascript:void(0);" method="POST" id="frmEditEmp">
                @csrf
                <input type="hidden" name="employee_id" id="edit_employee_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editEmployeeModalLabel">Edit Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!-- First Name -->
                        <div class="mb-3">
                            <label for="edit_employee_first_name"
                                class="form-label">{{ admin_label('employee_form', 'first_name', 'First Name') }}</label>
                            <input type="text" class="form-control" id="edit_employee_first_name" name="edit_first_name">
                        </div>
                        <!-- Last Name -->
                        <div class="mb-3">
                            <label for="edit_employee_last_name"
                                class="form-label">{{ admin_label('employee_form', 'last_name', 'Last Name') }}</label>
                            <input type="text" class="form-control" id="edit_employee_last_name" name="edit_last_name">
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="edit_employee_email"
                                class="form-label">{{ admin_label('employee_form', 'email', 'Email') }}</label>
                            <input type="email" class="form-control" id="edit_employee_email" name="edit_email">
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="edit_employee_password"
                                class="form-label">{{ admin_label('employee_form', 'password', 'Password') }}</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="edit_employee_password" name="edit_password"
                                    placeholder="Leave blank to keep current password">
                                <button type="button" class="btn btn-outline-secondary toggle-password-btn"
                                    data-target="#edit_employee_password" title="Show/Hide Password">
                                    <i class="ri-eye-line"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-generate-password"
                                    data-target="#edit_employee_password">
                                    <i class="ri-key-2-line me-1"></i>Generate Password
                                </button>
                            </div>
                            <small class="text-muted d-block mt-1">Leave empty to keep existing password.</small>
                        </div>

                        <!-- Permissions -->
                        <div class="mb-3">
                            <label for="edit_employee_permissions"
                                class="form-label">{{ admin_label('employee_form', 'permissions', 'Permissions') }}</label>
                            <select class="form-select select2-multiple" name="edit_permission[]"
                                id="edit_employee_permissions" multiple>
                                @foreach($AllPermissions as $acc)
                                    <option value="{{ $acc->id }}">
                                        {{ $permissionTitles[$acc->name] ?? ucwords(str_replace('_', ' ', $acc->name)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary" id="btnEditEmp">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm me-2" id="btnEditEmpSpinner"></span>
                                <span>Update</span>
                            </span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

@endpush
@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Employee Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Employee Management</a></li>
                        <li class="breadcrumb-item active">Employee List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header rounded-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0"></h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addEmployeeModal">
                                    <i class="ri-user-star-fill"></i>
                                    <span class="d-none d-sm-inline-block">Add Employee</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="employeeDT" class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer">
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var dataTable = $('#employeeDT').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            select: false,
            dom: "Bfrtip",
            lengthMenu: [
                [10, 25, 50, 75],
                ["10 rows", "25 rows", "50 rows", "75 rows"],
            ],
            buttons: ["pageLength"],
            language: {
                zeroRecords: zeroRecords,
                search: "",
                searchPlaceholder: "Search Here",
                processing: processing,
                emptyTable: emptyTable,
                paginate: {
                    next: '<i class="ri-arrow-right-s-line">',
                    previous: '<i class="ri-arrow-left-s-line">',
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center' },
                { data: 'first_name', name: 'first_name', title: 'First name', class: 'text-center' },
                { data: 'last_name', name: 'last_name', title: 'Last name', class: 'text-center' },
                { data: 'email', name: 'email', title: 'Email', class: 'text-center' },
                { data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center' },
                { data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false },
            ],
            ajax: {
                url: '{{ route("admin.employee.list") }}',
                type: "POST",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                },
                error: function (xhr) {
                    dataTableError("openCallTable", xhr.responseJSON.message);
                    actionError(xhr);
                },
            },
            responsive: {
                breakpoints: [
                    { name: "desktop", width: Infinity },
                    { name: "tablet", width: 1024 },
                    { name: "fablet", width: 768 },
                    { name: "phone", width: 480 },
                ],
            },
        });

        // Re-initialize Bootstrap tooltips after each DataTable redraw
        $('#employeeDT').on('draw.dt', function () {
            var tooltipEls = [].slice.call(document.querySelectorAll('#employeeDT [data-bs-toggle="tooltip"]'));
            tooltipEls.forEach(function (el) {
                new bootstrap.Tooltip(el, { trigger: 'hover' });
            });
        });

        $(document).ready(function () {

            // Initialize Select2 for permissions dropdowns
            $('#employee_permissions').select2({
                placeholder: 'Select permissions',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#addEmployeeModal'),
                dropdownAutoWidth: true
            });

            $('#edit_employee_permissions').select2({
                placeholder: 'Select permissions',
                allowClear: true,
                width: '100%',
                dropdownParent: $('#editEmployeeModal'),
                dropdownAutoWidth: true
            });

            // Reset Select2 and password fields when modals are hidden
            $('#addEmployeeModal').on('hidden.bs.modal', function () {
                $('#frmAddEmp')[0].reset();
                $('#employee_permissions').val(null).trigger('change');
                $('#employee_password').val('').attr('type', 'password');
                $('#addEmployeeModal .toggle-password-btn i').removeClass('ri-eye-off-line').addClass('ri-eye-line');
                if ($('#frmAddEmp').data('validator')) {
                    $('#frmAddEmp').data('validator').resetForm();
                }
                $('#frmAddEmp label.error').hide().text('');
            });

            $('#editEmployeeModal').on('hidden.bs.modal', function () {
                $('#frmEditEmp')[0].reset();
                $('#edit_employee_permissions').val(null).trigger('change');
                $('#edit_employee_password').val('').attr('type', 'password');
                $('#editEmployeeModal .toggle-password-btn i').removeClass('ri-eye-off-line').addClass('ri-eye-line');
                if ($('#frmEditEmp').data('validator')) {
                    $('#frmEditEmp').data('validator').resetForm();
                }
                $('#frmEditEmp label.error').hide().text('');
            });

            // Password Generator helper
            function generateRandomPassword(length) {
                length = length || 12;
                var uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
                var lowercase = "abcdefghijklmnopqrstuvwxyz";
                var numbers = "0123456789";
                var special = "!@#$%^&*";
                var allChars = uppercase + lowercase + numbers + special;

                var chars = [
                    uppercase.charAt(Math.floor(Math.random() * uppercase.length)),
                    lowercase.charAt(Math.floor(Math.random() * lowercase.length)),
                    numbers.charAt(Math.floor(Math.random() * numbers.length)),
                    special.charAt(Math.floor(Math.random() * special.length))
                ];

                while (chars.length < length) {
                    chars.push(allChars.charAt(Math.floor(Math.random() * allChars.length)));
                }

                for (var i = chars.length - 1; i > 0; i--) {
                    var j = Math.floor(Math.random() * (i + 1));
                    var temp = chars[i];
                    chars[i] = chars[j];
                    chars[j] = temp;
                }

                return chars.join('');
            }

            // Generate password button click handler
            $(document).on('click', '.btn-generate-password', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var targetSelector = $(this).data('target');
                var $input = $(targetSelector);
                var pwd = generateRandomPassword(12);

                $input.val(pwd).attr('type', 'text').trigger('change').trigger('input');

                // Switch toggle button icon to visible
                var $toggleBtn = $(this).siblings('.toggle-password-btn');
                $toggleBtn.find('i').removeClass('ri-eye-line').addClass('ri-eye-off-line');

                // Clear any validation error on the input
                var name = $input.attr('name');
                $('#' + name + '-error').hide().text('');
                $input.removeClass('error');
            });

            // Toggle password visibility handler
            $(document).on('click', '.toggle-password-btn', function (e) {
                e.preventDefault();
                e.stopPropagation();

                var targetSelector = $(this).data('target');
                var $input = $(targetSelector);
                var $icon = $(this).find('i');

                if ($input.attr('type') === 'password') {
                    $input.attr('type', 'text');
                    $icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
                } else {
                    $input.attr('type', 'password');
                    $icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
                }
            });

            $("#frmAddEmp").validate({
                ignore: ":hidden:not(.select2-multiple)",
                rules: {
                    first_name: { required: true },
                    last_name: { required: true },
                    email: { required: true, email: true },
                    password: { required: true, minlength: 6 },
                    "permission[]": { required: true },
                },
                messages: {
                    first_name: { required: "The first name field is required." },
                    last_name: { required: "The last name field is required." },
                    email: {
                        required: "The email field is required.",
                        email: "Please enter a valid email address."
                    },
                    password: {
                        required: "The password field is required.",
                        minlength: "The password must be at least 6 characters."
                    },
                    "permission[]": { required: "Please select the permission" },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        element.parent('.input-group').after(error);
                    } else if (element.hasClass('select2-multiple') || element.hasClass('select2-hidden-accessible')) {
                        element.next('.select2-container').after(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: "{{ route('admin.employee.create') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnAddEmp').attr('disabled', true);
                            $("#btnAddEmpSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then(() => {
                                $('#frmAddEmp')[0].reset();
                                $('#employee_permissions').val(null).trigger('change');
                                $('#employee_password').val('').attr('type', 'password');
                                $('#addEmployeeModal .toggle-password-btn i').removeClass('ri-eye-off-line').addClass('ri-eye-line');
                                dataTable.ajax.reload(null, false);
                                $('#addEmployeeModal').modal('hide');
                            });
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.error) {
                                let errorObj = {};
                                let firstMsg = null;
                                $.each(data.error, function (key, value) {
                                    let msg = Array.isArray(value) ? value[0] : value;
                                    errorObj[key] = msg;
                                    if (!firstMsg) firstMsg = msg;
                                });
                                $("#frmAddEmp").validate().showErrors(errorObj);
                                if (firstMsg) {
                                    sendError(firstMsg);
                                }
                            } else if (data && data.message) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnAddEmp').attr('disabled', false);
                            $("#btnAddEmpSpinner").addClass('d-none');
                        },
                    });
                    return false;
                }
            });

            $("#frmEditEmp").validate({
                ignore: ":hidden:not(.select2-multiple)",
                rules: {
                    edit_first_name: { required: true },
                    edit_last_name: { required: true },
                    edit_email: { required: true, email: true },
                    edit_password: { minlength: 6 },
                    "edit_permission[]": { required: true },
                },
                messages: {
                    edit_first_name: { required: "The first name field is required." },
                    edit_last_name: { required: "The last name field is required." },
                    edit_email: {
                        required: "The email field is required.",
                        email: "Please enter a valid email address."
                    },
                    edit_password: { minlength: "The password must be at least 6 characters." },
                    "edit_permission[]": { required: "Please select the permission" },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    if (element.parent('.input-group').length) {
                        element.parent('.input-group').after(error);
                    } else if (element.hasClass('select2-multiple') || element.hasClass('select2-hidden-accessible')) {
                        element.next('.select2-container').after(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form) {
                    $.ajax({
                        url: "{{ route('admin.employee.updates') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnEditEmp').attr('disabled', true);
                            $("#btnEditEmpSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then(() => {
                                $('#frmEditEmp')[0].reset();
                                $('#edit_employee_permissions').val(null).trigger('change');
                                $('#edit_employee_password').val('').attr('type', 'password');
                                $('#editEmployeeModal .toggle-password-btn i').removeClass('ri-eye-off-line').addClass('ri-eye-line');
                                dataTable.ajax.reload(null, false);
                                $('#editEmployeeModal').modal('hide');
                            });
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.error) {
                                let errorObj = {};
                                let firstMsg = null;
                                $.each(data.error, function (key, value) {
                                    let msg = Array.isArray(value) ? value[0] : value;
                                    errorObj[key] = msg;
                                    if (!firstMsg) firstMsg = msg;
                                });
                                $("#frmEditEmp").validate().showErrors(errorObj);
                                if (firstMsg) {
                                    sendError(firstMsg);
                                }
                            } else if (data && data.message) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnEditEmp').attr('disabled', false);
                            $("#btnEditEmpSpinner").addClass('d-none');
                        }
                    });
                    return false;
                }
            });

        })
        function editEmployee(id) {

            var edit = "{{ route('admin.employee.edit', ':id') }}";

            $.ajax({
                url: edit.replace(':id', id),
                type: 'GET',
                success: function (response) {
                    // Fill the form fields
                    $('#edit_employee_id').val(response.data.id);
                    $('#edit_employee_first_name').val(response.data.first_name);
                    $('#edit_employee_last_name').val(response.data.last_name);
                    $('#edit_employee_email').val(response.data.email);
                    $('#edit_employee_password').val('').attr('type', 'password');
                    $('#editEmployeeModal .toggle-password-btn i').removeClass('ri-eye-off-line').addClass('ri-eye-line');
                    $('#edit_employee_permissions').val(response.data.permissions).trigger('change');

                    // Open the modal
                    $('#editEmployeeModal').modal('show');
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message)
                    } else {
                        actionError(xhr);
                    }
                }
            });
        }
        function removeEmployee(id, element) {
            var originalHtml = $(element).html();
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to delete the employee record?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: false,
            }).then(function (t) {
                if (t.isConfirmed || t.value) {
                    $.ajax({
                        url: "{{route('admin.employee.delete')}}",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": "{{csrf_token()}}",
                        },
                        beforeSend: function () {
                            $(element).attr('disabled', true);
                            $(element).html('<i class="spinner-border spinner-border-sm text-danger"></i>');
                        },
                        success: function (data) {
                            sendSuccess(data.message);
                            dataTable.ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data?.error) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data?.message) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $(element).attr('disabled', false);
                            $(element).html(originalHtml);
                        }
                    });
                }
            });
        }
        function sendLoginDetail(id, element) {
            var originalHtml = $(element).html();
            $.ajax({
                url: "{{route('admin.employee.send-mail')}}",
                dataType: "JSON",
                method: "POST",
                data: {
                    "id": id,
                    "_token": "{{csrf_token()}}",
                },
                beforeSend: function () {
                    $(element).attr('disabled', true);
                    $(element).html('<i class="spinner-border spinner-border-sm text-info"></i>');
                },
                success: function (data) {
                    sendSuccess(data.message);
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data?.error) {
                        $.each(data.error, function (key, value) {
                            $("#" + key + "-error").html(value).show();
                        });
                    } else if (data?.message) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                },
                complete: function () {
                    $(element).attr('disabled', false);
                    $(element).html(originalHtml);
                }
            });

        }


    </script>
@endsection