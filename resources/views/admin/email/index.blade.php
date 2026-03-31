@extends('admin.master')
@section('title','Email')
@push('modal')
<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-between align-items-center">
                <h5 class="modal-title" id="viewModalLabel">Email Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="viewEmailData">

                    <!-- Name Section -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Name:</strong></label>
                        <div class="d-flex align-items-center">
                            <p id="key" class="fs-5 text-muted"></p>
                            <span class="ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="The name of the recipient or user associated with the booking">
                                <i class="bi bi-info-circle"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Subject Section -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Subject:</strong></label>
                        <div class="d-flex align-items-center">
                            <p id="subject_name" class="fs-5 text-muted"></p>
                            <span class="ms-2" data-bs-toggle="tooltip" data-bs-placement="top" title="The subject of the email for reference">
                                <i class="bi bi-info-circle"></i>
                            </span>
                        </div>
                    </div>

                    <!-- Description Section -->
                    <div class="mb-3">
                        <label class="form-label"><strong>Description:</strong></label>
                        <div id="description" class="p-3 border rounded bg-light">
                            <!-- Use div for HTML formatting, you can dynamically load content here -->
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


@endpush


@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Email Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Emails</a></li>
                        <li class="breadcrumb-item active">Email Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>



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
                {data: 'email', name: 'email', title: 'Email', class: 'text-center'},
                {data: 'subject', name: 'subject', title: 'subject', class: 'text-center'},
                {data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center'},
                {data: 'updated_at', name: 'updated_at', title: 'Updated At', class: 'text-center'},
                {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
            ],
            ajax: {
                url: '{{ route("admin.email.list") }}',
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
        $(document).on('click', '.btn-view', function() {
            let emailId = $(this).data('id');

            $.ajax({
                url: "{{ route('admin.email.view') }}", // Your route
                method: "GET",
                dataType: "json",
                data: { id: emailId },
                success: function(result) {
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
                error: function(xhr) {
                    sendError("An error occurred. Please try again.");
                }
            });
        });

        </script>
@endsection
