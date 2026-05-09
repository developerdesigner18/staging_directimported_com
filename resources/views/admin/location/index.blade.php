@extends('admin.master')
@section('title','Booking')

@push('modal')
{{--Add modal--}}
    <div class="modal fade" id="addLocationMD" tabindex="-1" aria-labelledby="addAccessoryMDLabel" aria-modal="true"
         data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Accessory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);" id="addLocationForm">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <label for="addLocationName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="addLocationName" name="name"
                                           placeholder="Enter location name">
                                    <label id="name-error" class="text-danger error" for="name"
                                           style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div>
                                    <label for="addLocationLink" class="form-label">Location Embeded code</label>
                                    <input type="text"  class="form-control" id="addLocationLink" name="locationcode"
                                           placeholder="Enter location embeded code">
                                    <label id="locationcode-error" class="text-danger error" for="addLocationLink"
                                           style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="addLocationBtn">
                                        <i class="bx bx-loader spinner me-2" style="display: none"
                                           id="addLocationBtnSpinner"></i>Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{--Add edit--}}
<div class="modal fade" id="editLocationMD" tabindex="-1" aria-labelledby="editLocationLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="editLocationForm">
                    @csrf
                    <input type="hidden" id="editLocationId" name="id">
                    <div class="mb-3">
                        <label for="editLocationName" class="form-label">Name</label>
                        <input type="text" id="editLocationName" name="edit_name" class="form-control">
                        <label id="edit_name-error" class="text-danger error" for="name"
                               style="display: none"></label>
                    </div>
                    <div class="mb-3">
                        <label for="editLocationCode" class="form-label">Embed Code</label>
                        <input type="text" id="editLocationCode" name="edit_locationcode" class="form-control">
                        <label id="edit_locationcode-error" class="text-danger error" for="name"
                               style="display: none"></label>
                    </div>
                    <div class="col-lg-12 mt-4">
                        <div class="hstack gap-2 justify-content-end">
                            <button type="button" class="btn btn-light"
                                    data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="editLocationBtn">
                                <i class="bx bx-loader spinner me-2" style="display: none"
                                   id="editLocationBtnSpinner"></i>Submit
                            </button>
                        </div>
                    </div>                </form>
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
                <h4 class="mb-sm-0">Location Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Location</a></li>
                        <li class="breadcrumb-item active">Location Management</li>
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
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addLocationMD">
                                    <i class="ri-map-pin-add-line"></i>
                                    <span class="d-none d-sm-inline-block">Add Location</span>
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
                    {data: 'name', name: 'name', title: 'Name', class: 'text-center'},
                    {data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center'},
                    {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
                ],
                ajax: {
                    url: '{{ route("admin.location.list") }}',
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

            $("#addLocationForm").validate({
                rules: {
                    name: {required: true},
                    locationcode: {required: true}
                },
                messages: {
                    name: {required: "The name field is required."},
                    locationcode: {required: "The location code field is required."},
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{route('admin.location.add')}}",
                        method: "post",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#addLocationBtn').attr('disabled', true);
                            $("#addLocationBtnSpinner").show();
                        },
                        success: function (result) {
                            // Show success message
                            sendSuccess(result.message);

                            setTimeout(function() {
                                location.reload();
                            }, 2500);

                            // Reset the form
                            $('#addLocationForm')[0].reset();

                            // Hide the modal using jQuery
                            $('#addLocationMD').modal('hide');

                            // Re-enable button & hide spinner
                            $('#addLocationBtn').attr('disabled', false);
                            $("#addLocationBtnSpinner").hide();
                        },
                        error: function (xhr) {
                            $('#addLocationBtn').attr('disabled', false);
                            $("#addLocationBtnSpinner").hide();
                        }
                    });
                }
            });

            $("#editLocationForm").validate({
                rules: {
                    edit_name: { required: true },
                    edit_locationcode: { required: true }
                },
                messages: {
                    edit_name: { required: "The name field is required." },
                    edit_locationcode: { required: "The location code field is required." },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('admin.location.update') }}",
                        method: "POST",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            // Disable the button and show spinner
                            $('#editLocationBtn').attr('disabled', true);
                            $("#editLocationBtnSpinner").show();
                        },
                        success: function (result) {
                            // Show success message
                            sendSuccess(result.message);

                            // Reload DataTable
                            location.reload();

                            // Reset form and hide modal
                            $('#editLocationForm')[0].reset();
                            $('#editLocationMD').modal('hide');
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
                            $('#editLocationBtn').attr('disabled', false);
                            $("#editLocationBtnSpinner").hide();
                            $('#editLocationMD').modal('hide');
                        }
                    });
                }
            });

        });
        function editLocation(id) {

            var edit = "{{ route('admin.location.edit', ':id') }}";

            $.ajax({
                url: edit.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    // Fill the form fields
                    $('#editLocationId').val(response.data.id);
                    $('#editLocationName').val(response.data.name);
                    $('#editLocationCode').val(response.data.google_map_link);

                    // Open the modal
                    $('#editLocationMD').modal('show');
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
        function removeLocation(id, element) {
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
                        url: "{{route('admin.location.delete')}}",
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


    </script>
@endsection
