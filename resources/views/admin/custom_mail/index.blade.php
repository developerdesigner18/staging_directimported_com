@extends('admin.master')
@section('title','Email')
@push('modal')
    <div class="modal fade" id="sendMailModal" tabindex="-1" aria-labelledby="sendMailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="sendMailModalLabel">Send Custom Mail</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="sendMailForm">
                    @csrf
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="to" class="form-label">To Email</label>
                            <input type="email" class="form-control" id="to" name="to"
                                   placeholder="recipient@example.com" required>
                            <span class="text-sm text-danger" id="to-error"></span>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject</label>
                            <input type="text" class="form-control" id="subject" name="subject"
                                   placeholder="Enter subject" required>
                            <span class="text-sm text-danger" id="subject-error"></span>

                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label">Message</label>
                            <textarea class="form-control" id="body" name="body" rows="6"
                                      placeholder="Write your message..." required></textarea>
                            <span class="text-sm text-danger" id="body-error"></span>

                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Send Mail</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endpush


@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Custom Email Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Emails</a></li>
                        <li class="breadcrumb-item active">Custom Email Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>



    {{--    <div class="row">--}}
    {{--        <div class="col-12">--}}
    {{--            <div class="card">--}}
    {{--                <div class="card-body">--}}
    {{--                    <table id="databaseTable" class="table w-100 pt-2 datatable dataTable no-footer"></table>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header rounded-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">Custom Email List</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#sendMailModal">
                                    <i class="ri-mail-send-line align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Send Mail</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="databaseTable" class="table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#sendMailForm").validate({
                rules: {
                    to: {required: true},
                    body: {required: true},
                    subject: {required: true},

                },

                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.custom-mails.create') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('button[type="submit"]').attr('disabled', true);
                            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
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
                            $('button[type="submit"]').html('Submit');
                        }
                    });
                }
            });

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
                    {data: 'to', name: 'to', title: 'To', class: 'text-center'},
                    {data: 'subject', name: 'subject', title: 'subject', class: 'text-center'},
                    {data: 'body', name: 'body', title: 'body', class: 'text-center'},
                    {data: 'sent_at', name: 'sent_at', title: 'Sent At', class: 'text-center'},
                    // {data: 'updated_at', name: 'updated_at', title: 'Updated At', class: 'text-center'},
                    // {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
                ],
                ajax: {
                    url: '{{ route("admin.custom-mails.list") }}',
                    type: "GET",
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
            $(document).on('click', '.btn-view', function () {
                let emailId = $(this).data('id');

                $.ajax({
                    url: "{{ route('admin.email.view') }}", // Your route
                    method: "GET",
                    dataType: "json",
                    data: {id: emailId},
                    success: function (result) {
                        if (result.data) {
                            // Populate modal fields
                            $("#key").text(result.data.key || '-');
                            $("#subject_name").text(result.data.subject || '-');
                            $("#description").html(result.data.body || '-');

                            // Show the modal
                            $('#viewModal').modal('show');
                        } else {
                            sendError("No data found for this email.");
                        }
                    },
                    error: function (xhr) {
                        sendError("An error occurred. Please try again.");
                    }
                });


            });
        });
    </script>
@endsection
