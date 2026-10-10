
@extends('admin.master')
@section('title','User')
@section('style')
<style>
    #docPreviewModal {
        z-index: 1065;
    }

    .modal-backdrop.show:nth-of-type(2) {
        z-index: 1060;
    }
</style>
@endsection
@push('modal')
    <!-- Modal -->
    <div class="modal fade" id="sectionsModal" tabindex="-1" aria-labelledby="sectionsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" >
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="sectionsModalLabel">Select Sections Visible to Users</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @foreach($visiblePermissions as $permission)
                            <div class="form-check mb-2">
                                <input class="chk-permission form-check-input" type="checkbox"
                                       id="permission_{{ $permission->id }}"
                                       data-id="{{ $permission->id }}"
                                        {{ $permission->allowed ? 'checked' : '' }}>
                                <label class="form-check-label" for="permission_{{ $permission->id }}">
                                    {{ $permission->label }}
                                </label>
                            </div>
                        @endforeach
                    </div>


{{--                    <div class="modal-footer">--}}
{{--                        <button type="submit" class="btn btn-primary">Set Permission</button>--}}
{{--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>--}}
{{--                    </div>--}}
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">User Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="detailsData"></div>
                    <div class="statusBtns">
                        <div class="btnData"></div>
                        <form action="javascript:void(0);" class="d-none" id="detailsRejectForm">
                            @csrf
                            <input type="hidden" name="id" value="">
                            <input type="hidden" name="user_id" value="">
                            <input type="hidden" name="user_doc_reject">
                            <input type="hidden" name="field">

                            <div class="mb-2">
                                <label for="rejectionReason" class="form-label">{{ admin_label('user_form', 'rejection_reason', 'Rejection Reason') }}</label>
                                <textarea rows="3" class="form-control" name="message" placeholder="Enter Your Reason"></textarea>
                                <label id="reason-error" class="text-danger error" for="reason" style="display: none"></label>
                            </div>
                            <button type="submit" class="btn btn-primary" id="submitReasonBtn">
                                <i class="bx bx-loader spinner me-2" style="display: none" id="reasonBtnSpinner"></i>Submit
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    Veryfy Document Model--}}
    <div class="modal fade" id="docPreviewModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Document Preview</h5>
                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body text-center">

                    <img id="docPreviewImage"
                         style="max-height:500px;width:100%;object-fit:contain;">

                    <h6 class="mt-3">
                        Document No : <span id="docPreviewNumber"></span>
                    </h6>

                    <div class="mt-4">
                        <button type="button"
                                class="btn btn-success me-2"
                                id="docVerifyBtn">
                            Verify
                        </button>

                        <button type="button"
                                class="btn btn-danger"
                                id="docRejectBtn">
                            Reject
                        </button>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- User Add/Edit Modal -->
    <div class="modal fade" id="userMD" tabindex="-1" aria-labelledby="userMDLabel" aria-modal="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);" id="userForm">
                        @csrf
                        <input type="hidden" name="id" id="user_id">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div>
                                    <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" maxlength="191">
                                    <label id="first_name-error" class="text-danger error" for="first_name" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div>
                                    <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" maxlength="191">
                                    <label id="last_name-error" class="text-danger error" for="last_name" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="userEmail" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="userEmail" name="email" placeholder="Enter email address" maxlength="191">
                                    <label id="email-error" class="text-danger error" for="userEmail" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="userMobile" class="form-label">Mobile Number</label>
                                    <input type="text" class="form-control" id="userMobile" name="mobile" placeholder="Enter mobile number" maxlength="20">
                                    <label id="mobile-error" class="text-danger error" for="userMobile" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12" id="passwordFieldGroup">
                                <div>
                                    <label for="userPassword" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="userPassword" name="password" placeholder="Enter password (min. 8 characters)" minlength="8" maxlength="191">
                                    <label id="password-error" class="text-danger error" for="userPassword" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="userSubmitBtn">
                                        <i class="bx bx-loader spinner me-2" style="display: none" id="userBtnSpinner"></i>Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">User Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">User Management</a></li>
                        <li class="breadcrumb-item active">User List</li>
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
                            <h5 class="card-title mb-0">User List</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                {{-- <button onclick="openAddModal();" type="button" class="btn btn-primary">
                                    <i class="ri-add-line align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Add User</span>
                                </button> --}}
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#sectionsModal" data-bs-toggle="tooltip" title="Set Permission">
                                    <i class="ri-mail-send-line align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Set Permission</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="userDT" class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script>
        var dataTable = $('#userDT').DataTable({
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
                { data: 'image', name: 'image', title: 'Image', class: 'text-center', orderable: false, searchable: false },
                { data: 'name', name: 'name', title: 'Name', class: 'text-center' },
                { data: 'email', name: 'email', title: 'Email', class: 'text-center' },
                { data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center' },
                { data: 'action', name: 'action', title: 'Actions', class: 'text-center', orderable: false, searchable: false },
            ],
            order: [[0, 'desc']],
            ajax: {
                url: '{{ route("admin.user.list") }}',
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

        function resetUserForm() {
            $("#userForm").trigger('reset');
            $("#user_id").val('');
            $("#userForm label.error").hide().text('');
            $("#userModalTitle").text("Add User");
            $("#passwordFieldGroup").show();
            $("#userPassword").prop('disabled', false);
            $("#userSubmitBtn").html('<i class="bx bx-loader spinner me-2" style="display: none" id="userBtnSpinner"></i>Submit');
            $("#userSubmitBtn").removeClass('btn-warning').addClass('btn-primary');
        }

        function openAddModal() {
            resetUserForm();
            $("#userMD").modal('show');
        }

        function editUser(id, element) {
            $.ajax({
                url: "{{ route('admin.user.edit') }}",
                dataType: "JSON",
                method: "POST",
                data: {
                    "id": id,
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(element).html('<i class="spinner-border fs-10 spinner-border-sm m-1 mx-0"></i>');
                    $(element).attr('disabled', true);
                },
                success: function (data) {
                    resetUserForm();
                    let u = data.data;
                    $("#user_id").val(u.id);
                    $("#first_name").val(u.first_name);
                    $("#last_name").val(u.last_name);
                    $("#userEmail").val(u.email);
                    $("#userMobile").val(u.mobile || '');
                    $("#passwordFieldGroup").hide();
                    $("#userPassword").val('').prop('disabled', true);
                    $("#userModalTitle").text("Edit User");
                    $("#userSubmitBtn").removeClass('btn-primary').addClass('btn-warning');
                    $("#userSubmitBtn").html('<i class="bx bx-loader spinner me-2" style="display: none" id="userBtnSpinner"></i>Save Changes');
                    $("#userMD").modal('show');
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data && data.hasOwnProperty('error')) {
                        if (data.error.hasOwnProperty('id')) {
                            sendError(data.error.id);
                        }
                    } else if (data && data.hasOwnProperty('message')) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                },
                complete: function () {
                    $(element).attr('disabled', false);
                    $(element).html('<i class="ri-pencil-fill fs-16"></i>');
                }
            });
        }

        function deleteUser(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to delete this user?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: false,
            }).then(function (t) {
                if (t.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.delete') }}",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function () {
                            $(element).html('<i class="spinner-border fs-10 spinner-border-sm m-1 mx-0"></i>');
                            $(element).attr('disabled', true);
                        },
                        success: function (data) {
                            sendSuccess(data.message);
                            dataTable.ajax.reload();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    sendError(Array.isArray(value) ? value[0] : value);
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $(element).attr('disabled', false);
                            $(element).html('<i class="ri-delete-bin-5-fill fs-16"></i>');
                        }
                    });
                }
            });
        }

        function getDetails(id, element) {
                $.ajax({
                    url: "{{ route('admin.user.details') }}",
                    dataType: "JSON",
                    method: "POST",
                    data: {
                        "id": id,
                        "_token": "{{csrf_token()}}",
                    },
                    beforeSend: function () {
                        $(element).html('<i class="spinner-border fs-10 spinner-border-sm m-1 mx-0"></i>');
                        $(element).attr('disabled', true);
                    },
                    success: function (data) {
                        $(".detailsData").html(data.message.html);
                        $(".btnData").html(data.message.btn);
                        $("#detailsModal").modal('show');
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data.hasOwnProperty('error')) {
                            if (data.error.hasOwnProperty('id')) {
                                sendError(data.error.id);
                            }
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message)
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $(element).attr('disabled', false);
                        $(element).html('<i class="ri-eye-fill fs-16"></i>');
                    }
                });
        }

        $('.chk-permission').on('change', function() {
            let id = $(this).data('id');
            let value = $(this).is(':checked') ? 1 : 0;
            let element = this;
            $.ajax({
                url: "{{ route('admin.permission.toggle') }}",
                dataType: "JSON",
                method: "POST",
                data: {
                    "id": id,
                    "allowed": value,                 // send 1 or 0
                    "_token": "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(element).attr('disabled', true);
                },
                success: function (result) {
                    sendSuccess(result.message);
                },
                error: function (xhr) {
                    actionError(xhr, data.message)
                },
                complete: function () {
                    $(element).attr('disabled', false);
                }
            });
        });

function verifyDocument(id, field, element) {
    let openModals = document.querySelectorAll('.modal.show');

    let topModal = openModals.length
        ? openModals[openModals.length - 1]
        : document.body;
    Swal.fire({
        title: "Are you sure?",
        text: "Verify this " + field.replace('_', ' ') + "?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, Verify",
        cancelButtonText: "Cancel",
        confirmButtonClass: "btn btn-success mt-2 text-white rounded px-4 fs-16",
        cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
        buttonsStyling: false,
        target: topModal
    }).then(function (t) {
        if (t.isConfirmed) {
            $.ajax({
           url: "{{ route('admin.user.status.verify') }}",
                method: "POST",
                dataType: "JSON",
                data: {
                    id: id,
                    field: field,
                    _token: "{{ csrf_token() }}",
                },
                beforeSend: function () {
                    $(element).html('<i class="spinner-border spinner-border-sm fs-10 m-1 mx-0"></i>');
                    $(element).attr('disabled', true);
                },
                success: function (result) {
                    sendSuccess(result.message);

                },
                error: function (xhr) {
                    actionError(xhr);
                },
            });
        }
    });
}


        $(document).ready(function () {

            $(document).delegate('.detailsRejectBtn','click',function (e){
                $('#detailsRejectForm').removeClass('d-none');

                $("#detailsRejectForm input[name=id]").val($(this).data('id'));
                $("#detailsRejectForm input[name=user_id]").val($(this).data('user_id'));
                $("#detailsRejectForm input[name=field]").val($(this).data('field'));
            });


            // Reject User Details Form Validation & Submit
            $("#detailsRejectForm").validate({
                rules: {
                    message: {required: true},
                },
                messages: {
                    message: {required: "The reason field is required."},
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    Swal.fire({
                        title: "Are you sure?",
                        text: "Are you sure you want to Rejected this Details?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Rejected",
                        cancelButtonText: "No, cancel!",
                        confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                        cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                        buttonsStyling: false,
                    }).then(function (t) {
                        if (t.isConfirmed) {
                            $.ajax({
                                url: "{{ route('admin.user.status.rejected.single') }}",
                                method: "post",
                                dataType: "json",
                                data: new FormData(form),
                                processData: false,
                                contentType: false,
                                cache: false,
                                beforeSend: function () {
                                    $('#submitReasonBtn').attr('disabled', true);
                                    $("#reasonBtnSpinner").show();
                                },
                                success: function (result) {
                                    sendSuccess(result.message);
                                    // $('.statusBtns').remove();
                                    $('#detailsRejectForm').addClass('d-none');
                                    $("#detailsRejectForm").trigger('reset');
                                    $("label.error").hide();
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
                                    $('#submitReasonBtn').attr('disabled', false);
                                    $("#reasonBtnSpinner").hide();
                                },
                            });
                        }
                    });
                }
            });

            // Veryfy Document Model
            $(document).delegate('.openPreview', 'click', function () {

                let img = $(this).data('img');
                let docno = $(this).data('docno');
                let id = $(this).data('id');
                let user_id = $(this).data('user_id');
                let field = $(this).data('field');
                let status = $(this).data('status');
                let has_image = $(this).data('has-image');

                $('#docPreviewImage').attr('src', img);
                $('#docPreviewNumber').text(docno);

                $('#docVerifyBtn')
                    .off('click')
                    .on('click', function () {
                        verifyDocument(id, field, this);
                    });

                $('#docRejectBtn')
                    .off('click')
                    .on('click', function () {

                        $('#docPreviewModal').modal('hide');

                        $('#detailsRejectForm').removeClass('d-none');
                        $("#detailsRejectForm input[name=id]").val(id);
                        $("#detailsRejectForm input[name=user_id]").val(user_id);
                        $("#detailsRejectForm input[name=field]").val(field);

                    });

                if (has_image == 1) {
                    if (status === 'VERIFIED') {
                        $('#docVerifyBtn').prop('disabled', true).text('Verified').show();
                        $('#docRejectBtn').prop('disabled', false).text('Reject').show();
                    } else if (status === 'REJECTED') {
                        $('#docVerifyBtn').prop('disabled', false).text('Verify').show();
                        $('#docRejectBtn').prop('disabled', true).text('Rejected').show();
                    } else {
                        $('#docVerifyBtn').prop('disabled', false).text('Verify').show();
                        $('#docRejectBtn').prop('disabled', false).text('Reject').show();
                    }
                } else {
                    $('#docVerifyBtn').hide();
                    $('#docRejectBtn').hide();
                }

                var previewModal = new bootstrap.Modal(document.getElementById('docPreviewModal'), {
                    backdrop: true,
                    keyboard: true
                });

                previewModal.show();

            });
            $('#docPreviewModal').on('hidden.bs.modal', function () {
                $('body').addClass('modal-open');
            });

            $("#userForm").validate({
                rules: {
                    first_name: {
                        required: true,
                        maxlength: 191
                    },
                    last_name: {
                        required: true,
                        maxlength: 191
                    },
                    email: {
                        required: true,
                        email: true,
                        maxlength: 191
                    },
                    mobile: {
                        maxlength: 20
                    },
                    password: {
                        required: function() {
                            return !$("#user_id").val();
                        },
                        minlength: {
                            param: 8,
                            depends: function() {
                                return !$("#user_id").val();
                            }
                        },
                        maxlength: 191
                    }
                },
                messages: {
                    first_name: {
                        required: "The first name field is required.",
                        maxlength: "The first name must not exceed 191 characters."
                    },
                    last_name: {
                        required: "The last name field is required.",
                        maxlength: "The last name must not exceed 191 characters."
                    },
                    email: {
                        required: "The email field is required.",
                        email: "Please enter a valid email address.",
                        maxlength: "The email must not exceed 191 characters."
                    },
                    mobile: {
                        maxlength: "The mobile number must not exceed 20 characters."
                    },
                    password: {
                        required: "The password field is required.",
                        minlength: "The password must be at least 8 characters.",
                        maxlength: "The password must not exceed 191 characters."
                    }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    let name = element.attr("name");
                    let errorLabel = $("#" + name + "-error");
                    if (errorLabel.length) {
                        errorLabel.html(error.text()).show();
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    let id = $("#user_id").val();
                    let url = id ? "{{ route('admin.user.update') }}" : "{{ route('admin.user.add') }}";

                    // Clear any previous error messages
                    $("#userForm label.error").hide().text('');

                    $.ajax({
                        url: url,
                        method: "POST",
                        dataType: "JSON",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#userSubmitBtn').attr('disabled', true);
                            $("#userBtnSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            dataTable.ajax.reload();
                            $("#userMD").modal('hide');
                            resetUserForm();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    let msg = Array.isArray(value) ? value[0] : value;
                                    let errorLabel = $("#" + key + "-error");
                                    if (errorLabel.length) {
                                        errorLabel.html(msg).show();
                                    } else {
                                        sendError(msg);
                                    }
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#userSubmitBtn').attr('disabled', false);
                            $("#userBtnSpinner").hide();
                        }
                    });
                }
            });

        });


    </script>
@endsection
