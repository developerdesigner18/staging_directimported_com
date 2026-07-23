@extends('admin.master')
@section('title', 'Manufacturers')

@push('modal')
    <div class="modal fade" id="manufacturerMD" tabindex="-1" aria-labelledby="manufacturerMDLabel" aria-modal="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Manufacturer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);" id="manufacturerForm">
                        @csrf
                        <input type="hidden" name="id" id="manufacturer_id">
                        <div class="row g-3">
                            <div class="col-12">
                                <div>
                                    <label for="manufacturerName" class="form-label">Name</label>
                                    <input type="text" class="form-control" id="manufacturerName" name="name"
                                        placeholder="Enter manufacturer name">
                                    <label id="name-error" class="text-danger error" for="manufacturerName"
                                        style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="manufacturerBtn">
                                        <i class="bx bx-loader spinner me-2" style="display: none"
                                            id="manufacturerBtnSpinner"></i>Submit
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
                <h4 class="mb-sm-0">Manufacturer Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Cars</a></li>
                        <li class="breadcrumb-item active">Manufacturer Management</li>
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
                            <h5 class="card-title mb-0">Manufacturer List</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button onclick="openAddModal();" type="button" class="btn btn-primary">
                                    <i class="ri-add-line align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Add Manufacturer</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="manufacturerDT"
                        class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var dataTable = $('#manufacturerDT').DataTable({
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
                { data: 'name', name: 'name', title: 'Name', class: 'text-center' },
                { data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center' },
                { data: 'action', name: 'action', title: 'Action', class: 'text-center', orderable: false, searchable: false },
            ],
            ajax: {
                url: '{{ route("admin.manufacturer.list") }}',
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
                    { name: "desktop", width: Infinity },
                    { name: "tablet", width: 1024 },
                    { name: "fablet", width: 768 },
                    { name: "phone", width: 480 },
                ],
            },
        });

        function resetForm() {
            $("#manufacturerForm").trigger('reset');
            $("#manufacturer_id").val('');
            $("label.error").hide();
            $("#modalTitle").text("Add Manufacturer");
            $("#manufacturerBtn").html('<i class="bx bx-loader spinner me-2" style="display: none" id="manufacturerBtnSpinner"></i>Submit');
            $("#manufacturerBtn").removeClass('btn-warning').addClass('btn-primary');
        }

        function openAddModal() {
            resetForm();
            $("#manufacturerMD").modal('show');
        }

        function removeManufacturer(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this manufacturer?",
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
                        url: "{{ route('admin.manufacturer.delete') }}",
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
                            $(element).attr('disabled', false);
                        }
                    });
                }
            });
        }

        function getManufacturer(id, element) {
            $.ajax({
                url: "{{ route('admin.manufacturer.edit') }}",
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
                    resetForm();
                    $("#manufacturer_id").val(id);
                    $('#manufacturerName').val(data.data.name);
                    $("#modalTitle").text("Edit Manufacturer");
                    $("#manufacturerBtn").removeClass('btn-primary').addClass('btn-warning');
                    $("#manufacturerBtn").html('<i class="bx bx-loader spinner me-2" style="display: none" id="manufacturerBtnSpinner"></i>Save Changes');
                    $("#manufacturerMD").modal('show');
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
                    $(element).html('<i class="ri-pencil-fill fs-16"></i>');
                }
            });
        }

        $(document).ready(function () {
            $("#manufacturerForm").validate({
                rules: {
                    name: { required: true }
                },
                messages: {
                    name: { required: "The name field is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    let id = $("#manufacturer_id").val();
                    let url = id ? "{{ route('admin.manufacturer.update') }}" : "{{ route('admin.manufacturer.add') }}";

                    $.ajax({
                        url: url,
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#manufacturerBtn').attr('disabled', true);
                            $("#manufacturerBtnSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            dataTable.ajax.reload();
                            $("#manufacturerMD").modal('hide');
                            resetForm();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    let errorLabel = $("#" + key + "-error");
                                    if (errorLabel.length) {
                                        errorLabel.html(value).show();
                                    } else {
                                        sendError(value);
                                    }
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#manufacturerBtn').attr('disabled', false);
                            $("#manufacturerBtnSpinner").hide();
                        },
                    });
                }
            });
        });
    </script>
@endsection