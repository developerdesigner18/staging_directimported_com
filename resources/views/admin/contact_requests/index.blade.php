@extends('admin.master')
@section('title','Contact Requests')

@push('modal')
    <!-- View Contact Request Modal -->
    <div class="modal fade" id="viewRequestMD" tabindex="-1" aria-labelledby="viewRequestMDLabel" aria-modal="true"
         data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Full Name:</label>
                            <p id="view_full_name"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email:</label>
                            <p id="view_email"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number:</label>
                            <p id="view_phone_number"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Preferred Contact Method:</label>
                            <p id="view_preferred_contact_method"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vehicle ID / Name:</label>
                            <p id="view_vehicle_id"></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Destination Country:</label>
                            <p id="view_destination_country"></p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Nearest Major Port / Postal Code:</label>
                            <p id="view_nearest_port_or_postal_code"></p>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Message:</label>
                            <div class="p-3 bg-light rounded" id="view_message"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Submitted At:</label>
                            <p id="view_created_at"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
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
                <h4 class="mb-sm-0">Contact Requests</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Admin</a></li>
                        <li class="breadcrumb-item active">Contact Requests</li>
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
                            <h5 class="card-title mb-0">Submitted Requests List</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="contactRequestDT"
                           class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var dataTable = $('#contactRequestDT').DataTable({
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
                {data: 'full_name', name: 'full_name', title: 'Name', class: 'text-center'},
                {data: 'email', name: 'email', title: 'Email', class: 'text-center'},
                {data: 'phone_number', name: 'phone_number', title: 'Phone', class: 'text-center'},
                {data: 'vehicle_id', name: 'vehicle_id', title: 'Vehicle ID', class: 'text-center'},
                {data: 'created_at', name: 'created_at', title: 'Date', class: 'text-center'},
                {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
            ],
            ajax: {
                url: '{{ route("admin.contact_requests.list") }}',
                type: "POST",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                },
                error: function (xhr) {
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

        function viewRequest(id, element) {
            $.ajax({
                url: "{{route('admin.contact_requests.show')}}",
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
                    let req = data.data;
                    $("#view_full_name").text(req.full_name || '-');
                    $("#view_email").text(req.email || '-');
                    $("#view_phone_number").text(req.phone_number || '-');
                    $("#view_preferred_contact_method").text(req.preferred_contact_method || '-');
                    $("#view_vehicle_id").text(req.vehicle_id || '-');
                    $("#view_destination_country").text(req.destination_country || '-');
                    $("#view_nearest_port_or_postal_code").text(req.nearest_port_or_postal_code || '-');
                    $("#view_message").text(req.message || 'No message provided.');
                    $("#view_created_at").text(formatDateTime(req.created_at));
                    
                    $("#viewRequestMD").modal('show');
                },
                error: function (xhr) {
                    actionError(xhr);
                },
                complete: function () {
                    $(element).attr('disabled', false);
                    $(element).html('<i class="ri-eye-fill fs-16"></i>');
                }
            });
        }

        function removeRequest(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this contact request?",
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
                        url: "{{route('admin.contact_requests.delete')}}",
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
                            sendSuccess(data.message);
                            dataTable.ajax.reload();
                        },
                        error: function (xhr) {
                            actionError(xhr);
                        },
                        complete: function () {
                            $(element).attr('disabled', false);
                            $(element).html('<i class="ri-delete-bin-5-fill fs-16"></i>');
                        }
                    });
                }
            });
        }
    </script>
@endsection
