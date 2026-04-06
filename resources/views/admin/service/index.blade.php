@extends('admin.master')
@section('title','Services Management')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Service Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Services</a></li>
                        <li class="breadcrumb-item active">Service Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <a href="{{ route('admin.service.create') }}" class="btn btn-success">
                    <i class="ri-add-line align-bottom me-1"></i> Add New
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="tableView">
                        <table class="table table-bordered w-100" id="serviceTable">
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function deleteService(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this service?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, remove",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: false,
            }).then(function (t) {
                if (t.value) {
                    $.ajax({
                        url: "{{ route('admin.service.delete') }}",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}",
                        },
                        beforeSend: function () {
                            $(element).html('<i class="spinner-border fs-10 spinner-border-sm m-1 mx-0"></i>');
                            $(element).attr('disabled', true);
                        },
                        success: function (data) {
                            sendSuccess(data.message);
                            $('#serviceTable').DataTable().ajax.reload(null, false);
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

        $(document).ready(function () {
            if (!$.fn.dataTable.isDataTable('#serviceTable')) {
                var dataTable = $('#serviceTable').DataTable({
                    processing: true,
                    serverSide: true,
                    order: [],
                    ordering: false,
                    info: true,
                    select: false,
                    dom: "Bfrtip",
                    lengthMenu: [[10, 25, 50, 75], ["10 rows", "25 rows", "50 rows", "75 rows"]],
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
                        {data: 'title', name: 'title', title: 'Title', class: 'text-center'},
                        {data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center'},
                        {data: 'action', name: 'action', title: 'Action', class: 'text-center', searching: false},
                    ],
                    ajax: {
                        url: '{{ route("admin.service.index") }}',
                        type: "GET",
                        dataType: "JSON",
                        data: function (f) {
                            f._token = "{{ csrf_token() }}";
                        },
                        error: function (xhr) {
                            dataTableError("openCallTable", xhr.responseJSON.message);
                            actionError(xhr);
                        },
                    },
                    rowId: 'id',
                    drawCallback: function () {
                        makeTableSortable();
                    },
                });
            } else {
                $('#serviceTable').DataTable().ajax.reload(null, false);
            }

            function makeTableSortable() {
                $('#serviceTable tbody').sortable({
                    helper: fixWidthHelper,
                    update: function (event, ui) {
                        let order = [];
                        $('#serviceTable tbody tr').each(function (index, element) {
                            order.push({
                                id: $(element).attr('id'),
                                position: index + 1
                            });
                        });

                        $.ajax({
                            url: "{{ route('admin.service.sort') }}",
                            method: "POST",
                            data: {
                                _token: "{{ csrf_token() }}",
                                order: order
                            },
                            success: function (response) {
                                sendSuccess(response.message);
                            },
                            error: function (xhr) {
                                actionError(xhr);
                            }
                        });
                    }
                }).disableSelection();
            }

            function fixWidthHelper(e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            }
        });
    </script>
@endsection
