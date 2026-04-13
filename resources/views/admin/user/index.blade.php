
@extends('admin.master')
@section('title','User')
<style>
    #docPreviewModal {
        z-index: 1065;
    }

    .modal-backdrop.show:nth-of-type(2) {
        z-index: 1060;
    }
</style>
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
                                <label for="rejectionReason" class="form-label">Rejection Reason</label>
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
                            <h5 class="card-title mb-0"></h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#sectionsModal" data-bs-toggle="tooltip" title="Set Permission">
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
                {data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center'},
                {data: 'image', name: 'image', title: 'image', class: 'text-center'},
                {data: 'name', name: 'name', title: 'Name', class: 'text-center'},
                {data: 'email', name: 'email', title: 'Email', class: 'text-center'},
                // {data: 'mobile', name: 'mobile', title: 'Mobile', class: 'text-center'},
                // {data: 'address', name: 'address', title: 'Address', class: 'text-center'},
                // {data: 'country', name: 'country', title: 'Country', class: 'text-center'},
                {data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center'},
                {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
            ],
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

            $(document).delegate('.btnSendLoginDetail','click',function(){

                var bookingId = $(this).data('booking-id');
                var id = $(this).data('id');
                var email = $(this).data('email');
                // var status=$(this).data('status');
//                         var fname = $(this).data('fname');
//                         var lname = $(this).data('lname');


                $.ajax({
                    url: "{{ route('admin.booking.send-login-detail') }}",
                    method: "post",
                    dataType: "json",

                    data: {
                        id:id,
                        booking_id: bookingId,
                        email: email,
                        "_token": "{{csrf_token()}}"



                    },

                    beforeSend: function () {
                        $('#btnSendLoginDetail').attr('disabled', true);
                        $("#btnSendLoginDetailSpinner").show();
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

                            if (data.error.hasOwnProperty('bike_ids')) {
                                sendError(data.error.bike_ids);
                            }
                        } else if (data.hasOwnProperty('message')) {
                            actionError(xhr, data.message);
                        } else {
                            actionError(xhr);
                        }
                    },
                    complete: function () {
                        $('#btnSendLoginDetail').attr('disabled', false);
                        $("#btnSendLoginDetailSpinner").hide();
                    },

                });

            });
        });


    </script>
@endsection
