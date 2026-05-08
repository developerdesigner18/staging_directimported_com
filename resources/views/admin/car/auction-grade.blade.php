@extends('admin.master')
@section('title', 'Auction Grade')

@push('modal')
    <div class="modal fade" id="addAuctionGradeMD" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add Auction Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="addAuctionGradeForm">
                        @csrf

                        <div class="row g-3">

                            <!-- Grade -->
                            <div class="col-12">
                                <label class="form-label">Auction Grade</label>
                                <input type="text" class="form-control" name="grade" placeholder="Enter grade (S, 1–5, R)">
                                <label class="text-danger error" id="grade-error" style="display:none"></label>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" name="remarks" rows="3"
                                    placeholder="Enter remarks (optional)"></textarea>
                                <label class="text-danger error" id="remarks-error" style="display:none"></label>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 mt-3 text-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary" id="addAuctionGradeBtn">
                                    Submit
                                    <span class="spinner-border spinner-border-sm" id="addAuctionGradeBtnSpinner"
                                        style="display:none"></span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editAuctionGradeMD" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Auction Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <form id="editAuctionGradeForm">
                        @csrf
                        <input type="hidden" name="id" id="auction_grade_id">

                        <div class="row g-3">

                            <!-- Grade -->
                            <div class="col-12">
                                <label class="form-label">Auction Grade</label>
                                <input type="text" class="form-control" id="edit_grade" name="grade">
                                <label class="text-danger error" id="edit_grade-error" style="display:none"></label>
                            </div>

                            <!-- Remarks -->
                            <div class="col-12">
                                <label class="form-label">Remarks</label>
                                <textarea class="form-control" id="edit_remarks" name="remarks" rows="3"></textarea>
                                <label class="text-danger error" id="edit_remarks-error" style="display:none"></label>
                            </div>

                            <!-- Buttons -->
                            <div class="col-12 mt-3 text-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-warning" id="editAuctionGradeBtn">
                                    Save Changes
                                    <span class="spinner-border spinner-border-sm" id="editAuctionGradeBtnSpinner"
                                        style="display:none"></span>
                                </button>
                            </div>

                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
@endpush

@section('main')
    <!-- Page Title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Auction Grade Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">Auction</li>
                        <li class="breadcrumb-item active">Auction Grade</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Section -->
    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="card-header rounded-0">
                    <div class="row align-items-center gy-3">

                        <div class="col-sm">
                            <h5 class="card-title mb-0">Auction Grade List</h5>
                        </div>

                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">

                                <!-- Add Button -->
                                <button onclick="resetForm();" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addAuctionGradeMD">

                                    <i class="bx bx-plus align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Add Auction Grade</span>
                                </button>

                            </div>
                        </div>

                    </div>
                </div>

                <div class="card-body">

                    <table id="auctionGradeDT" class="table table-bordered w-100">
                    </table>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var dataTable = $('#auctionGradeDT').DataTable({
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
                    next: '<i class="bx bx-chevron-right">',
                    previous: '<i class="bx bx-chevron-left">',
                },
            },
            columns: [
                // { data: 'DT_RowIndex', title: 'ID', class: 'text-center' },
                { data: 'grade', title: 'Grade', class: 'text-center' },
                { data: 'remarks', title: 'Remarks', class: 'text-center' },
                { data: 'created_at', title: 'Created At', class: 'text-center' },
                { data: 'action', title: 'Action', class: 'text-center', orderable: false }
            ],

            ajax: {
                url: '{{ route("admin.auctiongrade.list") }}',
                type: "POST",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                }
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
            $("#addAuctionGradeForm").trigger('reset');
            $("#editAuctionGradeForm").trigger('reset');
            $("label.error").hide();
        }

        function removeAuctionGrade(id, element) {
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
                        url: "{{route('admin.auctiongrade.delete')}}",
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

        function editAuctionGradeMD(id, element) {
            $.ajax({
                url: "{{route('admin.auctiongrade.edit')}}",
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
                    $("#auction_grade_id").val(id);
                    $('#edit_grade').val(data.data.grade);
                    $('#edit_remarks').val(data.data.remarks);
                    $("#editAuctionGradeMD").modal('show');
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
                    $(element).html('<i class="bx bx-edit fs-16"></i>');
                }
            });
        }

        $(document).ready(function () {
            // Add Category Form
            $("#addAuctionGradeForm").validate({
                rules: {
                    grade: { required: true },
                },
                messages: {
                    grade: { required: "The grade field is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{route('admin.auctiongrade.add')}}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#addAuctionGradeBtn').attr('disabled', true);
                            $("#addAuctionGradeBtnSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            dataTable.ajax.reload();
                            $("#addAuctionGradeMD").modal('hide');
                            resetForm();
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
                            $('#addAuctionGradeBtn').attr('disabled', false);
                            $("#addAuctionGradeBtnSpinner").hide();
                        },
                    });
                }
            });

            // Update Auction Grade
            $("#editAuctionGradeForm").validate({
                rules: {
                    grade: { required: true }
                },
                messages: {
                    grade: { required: "The grade field is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{route('admin.auctiongrade.update')}}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#editAuctionGradeBtn').attr('disabled', true);
                            $("#editAuctionGradeBtnSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            dataTable.ajax.reload();
                            $("#editAuctionGradeMD").modal('hide');
                            resetForm();
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
                            $('#editAuctionGradeBtn').attr('disabled', false);
                            $("#editAuctionGradeBtnSpinner").hide();
                        },
                    });
                }
            });
        })
    </script>
@endsection