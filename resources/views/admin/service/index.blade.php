@extends('admin.master')
@section('title','Services Management')
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Service Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Service Management</a></li>
                        <li class="breadcrumb-item active">Service List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Pills Between Dedicated Services & Home Services -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.service.index') }}" class="btn btn-primary">
                    <i class="ri-list-settings-line align-bottom me-1"></i> Dedicated Services Page (/services)
                </a>
                <a href="{{ route('admin.service.home') }}" class="btn btn-outline-primary">
                    <i class="ri-home-4-line align-bottom me-1"></i> Home Services Section
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Services Page Header</h5>
                    <p class="text-muted mb-0 fs-12">Heading shown at the top of the dedicated Services page (<code>/services</code>). Manage the detailed procurement services listed on the Services page below.</p>
                </div>
                <div class="card-body">
                    <form id="pageHeaderForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="services_page_badge" class="form-label">{{ admin_label('service_form', 'page_badge', 'Badge Text') }}</label>
                                <input type="text" class="form-control" id="services_page_badge" name="services_page_badge" maxlength="150"
                                    value="{{ $settings->services_page_badge }}" placeholder="e.g. Licensed Motor Vehicle Trader in Japan">
                                <label id="services_page_badge-error" class="text-danger error" style="display:none"></label>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="services_page_title" class="form-label">{{ admin_label('service_form', 'page_title', 'Page Title') }}</label>
                                <input type="text" class="form-control" id="services_page_title" name="services_page_title" maxlength="255"
                                    value="{{ $settings->services_page_title }}" placeholder="e.g. End-to-End Procurement & Export">
                                <label id="services_page_title-error" class="text-danger error" style="display:none"></label>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="services_page_description" class="form-label">{{ admin_label('service_form', 'page_description', 'Page Description') }}</label>
                                <textarea class="form-control" id="services_page_description" name="services_page_description" rows="2" maxlength="1000"
                                    placeholder="Short introduction under the title">{{ $settings->services_page_description }}</textarea>
                                <label id="services_page_description-error" class="text-danger error" style="display:none"></label>
                            </div>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary" id="btnPageHeader">Save Header</button>
                        </div>
                    </form>
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
        $(document).on("submit", "#pageHeaderForm", function (e) {
            e.preventDefault();
            const $btn = $("#btnPageHeader");
            $.ajax({
                url: "{{ route('admin.service.page_header.update') }}",
                method: "POST",
                dataType: "json",
                data: $(this).serialize(),
                beforeSend: function () {
                    $btn.attr("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
                    $("#pageHeaderForm .error").html("").hide();
                },
                success: function (result) {
                    sendSuccess(result.message);
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data && data.hasOwnProperty("error")) {
                        $.each(data.error, function (key, value) {
                            $("#" + key + "-error").html(Array.isArray(value) ? value[0] : value).show();
                        });
                    } else if (data && data.hasOwnProperty("message")) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                },
                complete: function () {
                    $btn.attr("disabled", false).html("Save Header");
                }
            });
        });

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
