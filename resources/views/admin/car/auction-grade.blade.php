@extends('admin.master')
@section('title', 'Auction Grades')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Auction Grade Management</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Auction Grades</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">Auction Grades</h5>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addAuctionGradeModal">
                        <i class="ri-add-line align-bottom me-1"></i> Add New
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="auctionGradeTable">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">Grade</th>
                                <th class="text-center">Remarks</th>
                                <th class="text-center">Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Modal --}}
    <div class="modal fade" id="addAuctionGradeModal" tabindex="-1" aria-labelledby="addAuctionGradeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAuctionGradeModalLabel">Add Auction Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="addAuctionGradeForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="grade" class="form-label">Grade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="grade" name="grade" placeholder="e.g. 4.5, S, 3B">
                            <p id="grade-error" class="text-danger mt-1" style="display: none;"></p>
                        </div>
                        <div class="mb-3">
                            <label for="remarks" class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="remarks" name="remarks" rows="3"
                                placeholder="Description of this grade..."></textarea>
                            <p id="remarks-error" class="text-danger mt-1" style="display: none;"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" id="addAuctionGradeBtn">
                            <span class="btn-text">Save</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editAuctionGradeModal" tabindex="-1" aria-labelledby="editAuctionGradeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAuctionGradeModalLabel">Edit Auction Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editAuctionGradeForm">
                    @csrf
                    <input type="hidden" id="edit_id" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_grade" class="form-label">Grade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_grade" name="grade">
                            <p id="edit_grade-error" class="text-danger mt-1" style="display: none;"></p>
                        </div>
                        <div class="mb-3">
                            <label for="edit_remarks" class="form-label">Remarks <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="edit_remarks" name="remarks" rows="3"></textarea>
                            <p id="edit_remarks-error" class="text-danger mt-1" style="display: none;"></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="editAuctionGradeBtn">
                            <span class="btn-text">Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {

            // ─── DataTable ────────────────────────────────────────────────
            var table = $('#auctionGradeTable').DataTable({
                processing: true,
                serverSide: true,
                order: [],
                ordering: false,
                dom: "Bfrtip",
                lengthMenu: [[10, 25, 50], ["10 rows", "25 rows", "50 rows"]],
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
                    { data: 'DT_RowIndex', name: 'id', title: '#', class: 'text-center' },
                    { data: 'grade', name: 'grade', title: 'Grade', class: 'text-center' },
                    { data: 'remarks', name: 'remarks', title: 'Remarks', class: 'text-center' },
                    { data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center' },
                    { data: 'action', name: 'action', title: 'Action', class: 'text-center', searchable: false },
                ],
                ajax: {
                    url: '{{ route("admin.auctiongrade.list") }}',
                    type: "POST",
                    dataType: "JSON",
                    data: function (d) {
                        d._token = "{{ csrf_token() }}";
                    },
                    error: function (xhr) { actionError(xhr); },
                },
                rowId: 'id',
            });

            // ─── Add Form Submit ──────────────────────────────────────────
            $('#addAuctionGradeForm').validate({
                rules: {
                    grade: { required: true },
                    remarks: { required: true },
                },
                messages: {
                    grade: { required: "Please enter a grade." },
                    remarks: { required: "Please enter remarks." },
                },
                errorPlacement: function (error, element) {
                    $('#' + element.attr('name') + '-error').html(error.text()).show();
                },
                highlight: function (element) { $(element).addClass('is-invalid'); },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                    $('#' + $(element).attr('name') + '-error').hide();
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    let btn = $('#addAuctionGradeBtn');
                    $.ajax({
                        url: '{{ route("admin.auctiongrade.add") }}',
                        method: "POST",
                        dataType: "JSON",
                        data: $(form).serialize(),
                        beforeSend: function () {
                            btn.html('<i class="spinner-border spinner-border-sm"></i>').attr('disabled', true);
                        },
                        success: function (res) {
                            sendSuccess(res.message);
                            form.reset();
                            $('#addAuctionGradeModal').modal('hide');
                            table.ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.errors) {
                                $.each(data.errors, function (key, value) {
                                    $('#' + key + '-error').html(value[0]).show();
                                });
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            btn.html('<span class="btn-text">Save</span>').attr('disabled', false);
                        }
                    });
                }
            });

            // ─── Edit Button ──────────────────────────────────────────────
            // editAuctionGradeMD is called from the DataTable action button
            window.editAuctionGradeMD = function (id, el) {
                $.ajax({
                    url: '{{ route("admin.auctiongrade.edit") }}',
                    method: "POST",
                    dataType: "JSON",
                    data: { _token: "{{ csrf_token() }}", id: id },
                    beforeSend: function () {
                        $(el).html('<i class="spinner-border spinner-border-sm"></i>').attr('disabled', true);
                    },
                    success: function (res) {
                        if (res.status) {
                            $('#edit_id').val(res.data.id);
                            $('#edit_grade').val(res.data.grade);
                            $('#edit_remarks').val(res.data.remarks);
                            $('#editAuctionGradeModal').modal('show');
                        } else {
                            sendError(res.message);
                        }
                    },
                    error: function (xhr) { actionError(xhr); },
                    complete: function () {
                        $(el).html('<i class="ri-pencil-fill fs-16"></i>').attr('disabled', false);
                    }
                });
            };

            // ─── Edit Form Submit ─────────────────────────────────────────
            $('#editAuctionGradeForm').validate({
                rules: {
                    grade: { required: true },
                    remarks: { required: true },
                },
                messages: {
                    grade: { required: "Please enter a grade." },
                    remarks: { required: "Please enter remarks." },
                },
                errorPlacement: function (error, element) {
                    $('#edit_' + element.attr('name') + '-error').html(error.text()).show();
                },
                highlight: function (element) { $(element).addClass('is-invalid'); },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                    $('#edit_' + $(element).attr('name') + '-error').hide();
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    let btn = $('#editAuctionGradeBtn');
                    $.ajax({
                        url: '{{ route("admin.auctiongrade.update") }}',
                        method: "POST",
                        dataType: "JSON",
                        data: $(form).serialize(),
                        beforeSend: function () {
                            btn.html('<i class="spinner-border spinner-border-sm"></i>').attr('disabled', true);
                        },
                        success: function (res) {
                            sendSuccess(res.message);
                            $('#editAuctionGradeModal').modal('hide');
                            table.ajax.reload(null, false);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.errors) {
                                $.each(data.errors, function (key, value) {
                                    $('#edit_' + key + '-error').html(value[0]).show();
                                });
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            btn.html('<span class="btn-text">Update</span>').attr('disabled', false);
                        }
                    });
                }
            });

            // ─── Delete ───────────────────────────────────────────────────
            window.removeAuctionGrade = function (id, el) {
                Swal.fire({
                    title: "Are you sure?",
                    text: "This auction grade will be permanently removed.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, remove",
                    cancelButtonText: "Cancel",
                    confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                    cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                    buttonsStyling: false,
                }).then(function (result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '{{ route("admin.auctiongrade.delete") }}',
                            method: "POST",
                            dataType: "JSON",
                            data: { _token: "{{ csrf_token() }}", id: id },
                            beforeSend: function () {
                                $(el).html('<i class="spinner-border spinner-border-sm"></i>').attr('disabled', true);
                            },
                            success: function (res) {
                                sendSuccess(res.message);
                                table.ajax.reload(null, false);
                            },
                            error: function (xhr) { actionError(xhr); },
                            complete: function () {
                                $(el).html('<i class="ri-delete-bin-5-fill fs-16"></i>').attr('disabled', false);
                            }
                        });
                    }
                });
            };

        });
    </script>
@endsection