@extends('admin.master')
@section('title', 'Manage Labels')

@push('modal')
    <!-- Edit Label Modal -->
    <div class="modal fade" id="editLabelModal" tabindex="-1" aria-labelledby="editLabelModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editLabelModalLabel">Edit Admin Label</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editLabelForm">
                    @csrf
                    <input type="hidden" id="editLabelId" name="labelId">
                    <div class="modal-body">
                        <!-- Read-only Page -->
                        <div class="mb-3">
                            <label class="form-label">{{ admin_label('labels_form', 'page', 'Page') }}</label>
                            <input type="text" class="form-control bg-light" id="editLabelPage" readonly>
                        </div>
                        <!-- Read-only Key -->
                        <div class="mb-3">
                            <label class="form-label">{{ admin_label('labels_form', 'key', 'Key') }}</label>
                            <input type="text" class="form-control bg-light" id="editLabelKey" readonly>
                        </div>
                        <!-- Editable Value -->
                        <div class="mb-3">
                            <label for="editLabelValue"
                                class="form-label">{{ admin_label('labels_form', 'value', 'Value') }} <span
                                    class="text-danger">*</span></label>
                            <textarea class="form-control" id="editLabelValue" name="labelValue" rows="4" required
                                placeholder="Enter translation value"></textarea>
                            <label id="labelValue-error" class="text-danger error" style="display:none;"></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btnEditLabel">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm me-2" id="editLabelSpinner"></span>
                                <span>Update</span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush

@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Manage Admin Labels</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">System settings</a></li>
                        <li class="breadcrumb-item active">Manage Labels</li>
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
                            <h5 class="card-title mb-0">Admin Labels</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="labelsTable" class="table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var dataTable = $('#labelsTable').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            select: false,
            dom: "Bfrtip",
            lengthMenu: [
                [10, 25, 50, 75],
                ["10 rows", "25 rows", "50 rows", "75 rows"]
            ],
            buttons: ["pageLength"],
            language: {
                zeroRecords: zeroRecords,
                search: "",
                searchPlaceholder: "Search Here",
                processing: processing,
                emptyTable: emptyTable,
                paginate: {
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>'
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center', width: '5%' },
                { data: 'page', name: 'page', title: 'Page', class: 'text-center', width: '15%' },
                { data: 'key', name: 'key', title: 'Key', class: 'text-center', width: '20%' },
                { data: 'value', name: 'value', title: 'Value', class: 'text-center', width: '30%' },
                { data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center', width: '10%' },
                { data: 'updated_at', name: 'updated_at', title: 'Updated At', class: 'text-center', width: '10%' },
                { data: 'action', name: 'action', title: 'Action', class: 'text-center', width: '10%', orderable: false, searchable: false }
            ],
            ajax: {
                url: '{{ route("admin.labels.list") }}',
                type: "POST",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{ csrf_token() }}";
                },
                error: function (xhr) {
                    dataTableError("labelsTable", xhr.responseJSON.message);
                    actionError(xhr);
                }
            },
            responsive: {
                breakpoints: [
                    { name: "desktop", width: Infinity },
                    { name: "tablet", width: 1024 },
                    { name: "fablet", width: 768 },
                    { name: "phone", width: 480 }
                ]
            }
        });

        $(document).ready(function () {
            $("#editLabelForm").validate({
                rules: {
                    labelValue: { required: true },
                },
                messages: {
                    labelValue: { required: "The value field is required." },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.labels.update') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnEditLabel').attr('disabled', true);
                            $("#editLabelSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then(() => {
                                $('#editLabelForm')[0].reset();
                                dataTable.ajax.reload(null, false);
                                $('#editLabelModal').modal('hide');
                            });
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
                            $('#btnEditLabel').attr('disabled', false);
                            $("#editLabelSpinner").addClass('d-none');
                        },
                    });
                }
            });
        });

        function editLabel(id) {
            var url = "{{ route('admin.labels.edit', ':id') }}";
            $.ajax({
                url: url.replace(':id', id),
                type: 'GET',
                success: function (response) {
                    $('#editLabelId').val(response.data.id);
                    $('#editLabelPage').val(response.data.page);
                    $('#editLabelKey').val(response.data.key);
                    $('#editLabelValue').val(response.data.value);
                    $('#editLabelModal').modal('show');
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message)
                    } else {
                        actionError(xhr);
                    }
                }
            });
        }
    </script>
@endsection