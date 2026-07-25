@extends('admin.master')
@section('title', 'Contact Requests')

@push('modal')
    <!-- View Details Modal -->
    <div class="modal fade" id="viewDetailsMD" tabindex="-1" aria-labelledby="viewDetailsMDLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Contact Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th style="width: 30%;">Full Name</th>
                                    <td id="detail_name"></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td id="detail_email"></td>
                                </tr>
                                <tr>
                                    <th>Phone Number</th>
                                    <td id="detail_phone"></td>
                                </tr>
                                <tr>
                                    <th>Preferred Contact Method</th>
                                    <td id="detail_method"></td>
                                </tr>
                                <tr>
                                    <th>Vehicle</th>
                                    <td id="detail_vehicle"></td>
                                </tr>
                                <tr>
                                    <th>Destination Country</th>
                                    <td id="detail_country"></td>
                                </tr>
                                <tr>
                                    <th>Nearest Port / Postal Code</th>
                                    <td id="detail_port"></td>
                                </tr>
                                <tr>
                                    <th>Submission Date</th>
                                    <td id="detail_date"></td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td>
                                        <div id="detail_message" style="white-space: pre-wrap;"></div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Contact Requests</a></li>
                        <li class="breadcrumb-item active">Request List</li>
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
                            <h5 class="card-title mb-0">List of Requests</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="contactRequestDT"
                        class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer">
                    </table>
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
            ajax: {
                url: '{{ route("admin.contact_requests.list") }}',
                type: "POST",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                },
                error: function (xhr) {
                    console.error(xhr);
                },
            },
            columns: [
                { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center' },
                { data: 'full_name', name: 'full_name', title: 'Full Name', class: 'text-center' },
                { data: 'email', name: 'email', title: 'Email', class: 'text-center' },
                { data: 'phone_number', name: 'phone_number', title: 'Phone Number', class: 'text-center' },
                { data: 'preferred_contact_method', name: 'preferred_contact_method', title: 'Preferred Method', class: 'text-center' },
                { data: 'vehicle_id', name: 'vehicle_id', title: 'Vehicle', class: 'text-center' },
                { data: 'created_at', name: 'created_at', title: 'Submitted At', class: 'text-center' },
                { data: 'action', name: 'action', title: 'Action', class: 'text-center', orderable: false, searchable: false },
            ]
        });

        function viewRequest(id, element) {
            $.ajax({
                url: "{{ route('admin.contact_requests.show') }}",
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
                success: function (res) {
                    if (res.status) {
                        var data = res.data;
                        $('#detail_name').text(data.full_name || '-');
                        $('#detail_email').text(data.email || '-');
                        $('#detail_phone').text(data.phone_number || '-');
                        $('#detail_method').text(data.preferred_contact_method || '-');
                        $('#detail_vehicle').text(data.vehicle_id || '-');
                        $('#detail_country').text(data.destination_country || '-');
                        $('#detail_port').text(data.nearest_port_or_postal_code || '-');
                        $('#detail_message').text(data.message || '-');
                        $('#detail_date').text(res.message ? res.message : (data.created_at ? new Date(data.created_at).toLocaleString() : '-'));

                        $("#viewDetailsMD").modal('show');
                    } else {
                        sendError(res.message);
                    }
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
                showCancelButton: true,
                confirmButtonText: "Yes, remove",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: false,
            }).then(function (t) {
                if (t.isConfirmed) {
                    $.ajax({
                        url: "{{ route('admin.contact_requests.delete') }}",
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
                            if (data.status) {
                                sendSuccess(data.message);
                                dataTable.ajax.reload();
                            } else {
                                sendError(data.message);
                            }
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