@extends('admin.master')
@section('title','Booking')

@push('modal')
    <div class="modal fade" id="accessoriesModal" tabindex="-1" aria-labelledby="accessoriesModalLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Accessories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="accessoriesData"></div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="tableDataModal" tabindex="-1" aria-labelledby="tableDataModalLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
{{--                    <h5 class="modal-title">table</h5>--}}
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="tableData"></div>
                </div>
            </div>
        </div>
    </div>
@endpush

@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Booking</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Booking</a></li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table id="databaseTable" class="table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var dataTable = $('#databaseTable').DataTable({
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
                {data: 'booking_id', name: 'booking_id', title: 'Booking Id', class: 'text-center'},
                {data: 'name', name: 'name', title: 'Name', class: 'text-center'},
                {data: 'email', name: 'email', title: 'Email', class: 'text-center'},
                {data: 'comment', name: 'comment', title: 'Comment', class: 'text-center'},
                {data: 'status', name: 'status', title: 'Status', class: 'text-center'},
//                 {data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center'},
                {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
            ],
            ajax: {
                url: '{{ route("admin.booking.list") }}',
                type: "POST",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                    f.status = $('#statusFilter').val();
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

        function getAccessories(id, element) {
            $.ajax({
                url: "{{ route('admin.booking.accessories') }}",
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
                    $(".accessoriesData").html(data.message);
                    $("#accessoriesModal").modal('show');
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
                    $(element).html('Accessories');
                }
            });
        }

        function getTable(id, element) {
            $.ajax({
                url: "{{ route('admin.booking.table.data') }}",
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
                    $(".tableData").html(data.message);
                    $("#tableDataModal").modal('show');
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
                    $(element).html('Quote');
                }
            });
        }

        function updateStatusVerified(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to Verified this Details?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, Verified",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: false,
            }).then(function (t) {
                if (t.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.user.status.verified') }}",
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
                            sendSuccess(result.message);
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
                        }
                    });
                }
            });
        }

        $(document).ready(function () {
           var statusFilter = `
    <select id="statusFilter" class="form-select ms-2 form-control-sm" style="width:10rem;height: 2rem;">
        <option value="">All Status</option>

        <option value="{{ \App\Enum\BookingStatus::PROCESSING->value }}">
            {{ \App\Enum\BookingStatus::PROCESSING->label() }}
        </option>

        <option value="{{ \App\Enum\BookingStatus::APPROVED->value }}">
            {{ \App\Enum\BookingStatus::APPROVED->label() }}
        </option>

        <option value="{{ \App\Enum\BookingStatus::CANCELLED->value }}">
            {{ \App\Enum\BookingStatus::CANCELLED->label() }}
        </option>

        <option value="{{ \App\Enum\BookingStatus::CONFIRMED->value }}">
            {{ \App\Enum\BookingStatus::CONFIRMED->label() }}
        </option>

        <option value="{{ \App\Enum\BookingStatus::FINISHED->value }}">
            {{ \App\Enum\BookingStatus::FINISHED->label() }}
        </option>

    </select>
    `;

            $("#databaseTable_filter").prepend(statusFilter);
            $("#databaseTable_filter").addClass("d-flex gap-2 justify-content-end");
            $(document).delegate('.detailsRejectBtn','click',function (e){
                var id = $(this).attr('data-id');
                var user_id = $(this).attr('data-user_id');
                $('#detailsRejectForm').removeClass('d-none');
                $("#detailsRejectForm input[name=id]").val(id)
                $("#detailsRejectForm input[name=user_id]").val(user_id)
            })
            $('#statusFilter').change(function () {
                dataTable.ajax.reload();
            });
            // Reject User Details Form Validation & Submit
            $("#detailsRejectForm").validate({
                rules: {
                    reason: {required: true},
                },
                messages: {
                    reason: {required: "The reason field is required."},
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
                                url: "{{ route('admin.user.status.rejected') }}",
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

            $(document).on('change', '.status-dropdown', function () {
                var status = $(this).val();
                var bookingId = $(this).data('booking-id');
                var element = this;

                Swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to change the status?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, change it",
                    cancelButtonText: "No, cancel!",
                    confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                    cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                    buttonsStyling: false,
                }).then(function (t) {
                    if (t.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.booking.update-status') }}",
                            method: "POST",
                            dataType: "JSON",
                            data: {
                                id: bookingId,
                                status: status,
                                _token: "{{csrf_token()}}"
                            },
                            beforeSend: function () {
                                // $(element).attr('disabled', true);
                            },
                            success: function (data) {
                                sendSuccess(data.message);
                                dataTable.ajax.reload();
                                // dataTable.ajax.reload(null, false);
                            },
                            error: function (xhr) {
                                let data = xhr.responseJSON;
                                if (data.hasOwnProperty('error')) {
                                    if (data.error.hasOwnProperty('status')) {
                                        sendError(data.error.status);
                                    }
                                } else if (data.hasOwnProperty('message')) {
                                    actionError(xhr, data.message)
                                } else {
                                    actionError(xhr);
                                }
                            },
                            complete: function () {
                                // $(element).attr('disabled', false);
                            }
                        });
                    } else {
                        // If cancelled, revert the dropdown to its previous value
                        dataTable.ajax.reload(null, false);
                    }
                });
            });

            $(document).delegate('.deleteBtn','click',function (e){
                e.preventDefault();
                var el = $(this);
                var id = $(this).attr('data-id');
                Swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to remove this Booking?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, remove",
                    cancelButtonText: "No, cancel!",
                    confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                    cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                    buttonsStyling: false,
                }).then(function (t) {
                    if (t.value) {
                        $.ajax({
                            url: "{{route('admin.booking.delete')}}",
                            dataType: "JSON",
                            method: "POST",
                            data: {
                                "id": id,
                                "_token": "{{csrf_token()}}",
                            },
                            beforeSend: function () {
                                el.html('<i class="spinner-border fs-10 spinner-border-sm m-1 mx-0"></i>');
                                el.attr('disabled', true);
                            },
                            success: function (data) {
                                sendSuccess(data.message);
                                dataTable.ajax.reload();
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
                                el.attr('disabled', false);
                            }
                        });
                    }
                });
            });
        })


    </script>
@endsection
