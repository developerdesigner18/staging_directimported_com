@extends('admin.master')
@section('main')
@section('title','View User Booking')
<input type='hidden' value="{{ $id }}" id="user_id">

<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">Users</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Bookings</a></li>
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
                <table id="userDT" class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer"></table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
$(document).ready(function () {
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
                next: '<i class=\"bx bx-chevron-right\">',
                previous: '<i class=\"bx bx-chevron-left\">',
            },
        },
        columns: [
            {data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center'},
            {data: 'booking_id', name: 'booking_id', title: 'Booking Id', class: 'text-center'},
            {data: 'name', name: 'name', title: 'Name', class: 'text-center'},
            {data: 'email', name: 'email', title: 'Email', class: 'text-center'},
            {data: 'start_date', name: 'start_date', title: 'Start Date', class: 'text-center'},
            {data: 'end_date', name: 'end_date', title: 'End Date', class: 'text-center'},
            {data: 'location', name: 'location', title: 'Location', class: 'text-center'},
            {data: 'status', name: 'status', title: 'Status', class: 'text-center', searchable: false,
                render: function (data, type, row) {
                  let select = `<select class="form-select form-select-sm status-dropdown" 
                            data-booking-id="${row.id}" style="width: 200px;">

            <option value="processing" ${data === 'processing' ? 'selected' : ''}>
                Processing - Approval pending
            </option>

            <option value="approved" ${data === 'approved' ? 'selected' : ''}>
                Approved - Payment remaining
            </option>

            <option value="cancelled" ${data === 'cancelled' ? 'selected' : ''}>
                Not approved / Cancelled
            </option>

            <option value="confirmed" ${data === 'confirmed' ? 'selected' : ''}>
                Confirmed & Paid
            </option>

            <option value="finished" ${data === 'finished' ? 'selected' : ''}>
                Finished & Returned
            </option>

        </select>`;
                    return select;
                }
            },
            {data: 'comment', name: 'comment', title: 'Comment', class: 'text-center'},
            {data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center'},
            // {data: 'action', name: 'action', title: 'Action', class: 'text-center'},

        ],
        ajax: {
            url: '{{ route("admin.booking.bookings-list-user") }}',
            type: "POST",
            dataType: "JSON",
            data: function (f) {
             f.id = $('#user_id').val();
                f._token = "{{ csrf_token() }}";
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


});
</script>
@endsection
