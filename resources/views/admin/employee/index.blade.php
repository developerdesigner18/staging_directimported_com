@extends('admin.master')
@section('title','Employee Management')

@push('modal')

    <!-- Modal: Add Employee -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" id="frmAddEmp">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <!--First Name -->
                        <div class="mb-3">
                            <label for="employee_name" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="employee_first_name" name="first_name">
                        </div>
                        <!--Last Name -->
                        <div class="mb-3">
                            <label for="employee_name" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="employee_last_name" name="last_name">
                        </div>
                        <!-- Email -->
                        <div class="mb-3">
                            <label for="employee_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="employee_email" name="email">
                        </div>

                        <!-- Role/Department Select -->
                        <div class="mb-3">
                            <label for="employee_permissions" class="form-label">Permissions</label>
                            <select class="form-select select2-multiple" id="employee_permissions" name="permission[]" multiple>
                                @foreach($AllPermissions as $acc)
                                    <option value="{{ $acc->id }}">{{ $acc->name }}</option>
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
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-labelledby="editEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="frmEditEmp">
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
                        <label for="edit_employee_first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="edit_employee_first_name" name="edit_first_name">
                    </div>
                    <!-- Last Name -->
                    <div class="mb-3">
                        <label for="edit_employee_last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="edit_employee_last_name" name="edit_last_name">
                    </div>
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="edit_employee_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="edit_employee_email" name="edit_email">
                    </div>

                    <!-- Permissions -->
                    <div class="mb-3">
                        <label for="edit_employee_permissions" class="form-label">Permissions</label>
                        <select class="form-select select2-multiple" name="edit_permission[]" id="edit_employee_permissions" multiple>
                            @foreach($AllPermissions as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }}</option>
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">
                                    <i class="bx bxs-user-badge"></i>
                                    <span class="d-none d-sm-inline-block">Add Employee</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="employeeDT" class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer"></table>
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
                    next: '<i class="bx bx-chevron-right">',
                    previous: '<i class="bx bx-chevron-left">',
                },
            },
            columns: [
                {data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center'},
                {data: 'first_name', name: 'first_name', title: 'First name', class: 'text-center'},
                {data: 'last_name', name: 'last_name', title: 'Last name', class: 'text-center'},
                {data: 'email', name: 'email', title: 'Email', class: 'text-center'},
                {data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center'},
                {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
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
                    {name: "desktop", width: Infinity},
                    {name: "tablet", width: 1024},
                    {name: "fablet", width: 768},
                    {name: "phone", width: 480},
                ],
            },
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

            // Reset Select2 when modals are hidden
            $('#addEmployeeModal').on('hidden.bs.modal', function () {
                $('#employee_permissions').val(null).trigger('change');
            });

            $('#editEmployeeModal').on('hidden.bs.modal', function () {
                $('#edit_employee_permissions').val(null).trigger('change');
            });

            $("#frmAddEmp").validate({
                rules: {
                    first_name: {required: true},
                    last_name: {required: true},
                    permission: {required: true},

                },
                messages: {
                    first_name: {required: "The name field is required."},
                    last_name: {required: "The email field is required."},
                    permission: {required: "Please select the permission"},
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.employee.create') }}",
                        method: "post",
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
                            sendSuccess(result.message).then((result) => {
                                $('#frmAddEmp')[0].reset();
                                $('#employee_permissions').val(null).trigger('change'); // Reset Select2
                                dataTable.ajax.reload(null, false); // false preserves the current page
                                $('#addEmployeeModal').modal('hide');

                            });

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
                            $('#btnAddEmp').attr('disabled', false);
                            $("#btnAddEmpSpinner").addClass('d-none');
                        },
                    });
                }
            });
            $("#frmEditEmp").validate({

                rules: {
                    edit_first_name: {required: true},
                    edit_last_name: {required: true},
                    edit_email: {required: true},
                    edit_permission: {required: true},

                },
                messages: {
                    edit_first_name: {required: "The name field is required."},
                    edit_last_name: {required: "The email field is required."},
                    edit_email: {required: "The email field is required."},
                    edit_permission: {required: "Please select the permission"},
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.employee.updates') }}",
                        method: "POST",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            // Disable the button and show spinner
                            $('#btnEditEmp').attr('disabled', true);
                            $("#btnEditEmpSpinner").show();
                        },
                        success: function (result) {
                            // Show success message
                            sendSuccess(result.message);

                            // Reload DataTable
                            dataTable.ajax.reload(null, false); // false preserves the current page

                            // Reset form and hide modal
                            $('#frmEditEmp')[0].reset();
                            $('#editEmployeeModal').modal('hide');
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message)
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            // Always re-enable button and hide spinner (even on error)
                            $('#btnEditEmp').attr('disabled', false);
                            $("#btnEditEmpSpinner").hide();
                            $('#editEmployeeModal').modal('hide');
                        }
                    });
                }
            });

        })
        function editEmployee(id) {

            var edit = "{{ route('admin.employee.edit', ':id') }}";

            $.ajax({
                url: edit.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    // Fill the form fields
                    $('#edit_employee_id').val(response.data.id);
                    $('#edit_employee_first_name').val(response.data.first_name);
                    $('#edit_employee_last_name').val(response.data.last_name);
                    $('#edit_employee_email').val(response.data.email);
                    // $('#edit_employee_permissions').val(response.data.google_map_link);
                    $('#edit_employee_permissions').val(response.data.permissions).trigger('change');

                    // Open the modal
                    $('#editEmployeeModal').modal('show');
                },
                error: function(xhr) {
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
                text: "Are you sure you want to remove this category?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, remove",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: !1,
            }).then(function (t) {
                if (t.value) {
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
                            setTimeout(function() {
                                location.reload();
                            }, 2500);

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
