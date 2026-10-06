@extends('admin.master')
@section('title', 'Car')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Car Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Car Management</a></li>
                        <li class="breadcrumb-item active">Car List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <a href="{{ route('admin.car.create') }}" class="btn btn-success">
                    <i class="ri-add-line align-bottom me-1"></i> Add New
                </a>
            </div>
        </div>

        <div class="col-sm">
            <form id="gridSearchForm">
                <div class="d-flex justify-content-sm-end gap-2 flex-wrap">

                    <div class="search-box ms-2" id="txtSearch">
                        <input type="text" name="search" class="form-control" placeholder="Search..." value="{{ $search }}">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-info" id="btnSearch">Search</button>
                    </div>
                    <!-- Added dropdown menu here -->
                    <div class="btn-group">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            {{ $range && $range != 'all' ? $range : 'Filter' }}
                        </button>
                        <div class="dropdown-menu dropdownmenu-danger">
                            <a class="dropdown-item filter-range cursor-pointer" data-range="all">All</a>
                            <a class="dropdown-item filter-range cursor-pointer" data-range="750-1300cc">750-1300cc</a>
                            <a class="dropdown-item filter-range cursor-pointer" data-range="150-350cc">150-350cc</a>
                            <a class="dropdown-item filter-range cursor-pointer" data-range="0-125cc">0-125cc</a>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Layout
                        </button>
                        <div class="dropdown-menu dropdownmenu-danger">
                            <a class="dropdown-item" href="javascript:void(0)" id="gridViewBtn">Grid View</a>
                            <a class="dropdown-item" href="javascript:void(0)" id="tableViewBtn">Table View</a>
                        </div>
                    </div>
                    <!-- End dropdown menu -->
                </div>
            </form>
        </div>
    </div>

    {{-- ===== VEHICLE FILTER STYLES ===== --}}
    <style>
        .vf-field {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .vf-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            color: #6b7280;
        }

        .vf-select,
        .vf-input {
            height: 38px;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13.5px;
            color: #374151;
            background: #f9fafb;
            padding: 0 12px;
            transition: border-color .2s, box-shadow .2s, background .2s;
            outline: none;
        }

        .vf-select {
            min-width: 160px;
            appearance: none;
            -webkit-appearance: none;
            padding-right: 32px;
        }

        .vf-select-wrap {
            position: relative;
        }

        .vf-select-wrap .vf-select-icon {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
            pointer-events: none;
        }

        .vf-input {
            min-width: 170px;
        }

        .vf-input-wrap {
            position: relative;
        }

        .vf-input-wrap .vf-input {
            padding-left: 34px;
        }

        .vf-input-wrap .vf-input-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 15px;
        }

        .vf-select:focus,
        .vf-input:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, .12);
            background: #fff;
        }

        .vf-btn-apply {
            height: 38px;
            background: #0ab39c;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 0 20px;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: opacity .2s;
            white-space: nowrap;
        }

        .vf-btn-apply:hover {
            opacity: .85;
        }

        .vf-btn-clear {
            height: 38px;
            background: #fff;
            color: #6b7280;
            border: 1.5px solid #e5e7eb;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            padding: 0 16px;
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background .2s, color .2s;
            white-space: nowrap;
        }

        .vf-btn-clear:hover {
            background: #f3f4f6;
            color: #374151;
        }

        .car-table-thumb {
            width: 50px;
            height: 50px;
            object-fit: cover;
        }
    </style>

    <div class="row" id="vfFilterCard">
        <div class="col-12">
            <div class="card mb-2" style="border:1px solid #e9ebec; box-shadow:none; border-radius:8px;">
                <div class="card-body py-3 px-4">
                    <div class="d-flex align-items-flex-end gap-3 flex-wrap">

                        {{-- Make --}}
                        <div class="vf-field">
                            <span class="vf-label">Make</span>
                            <div class="vf-select-wrap">
                                <select class="vf-select" id="tableFilterMake">
                                    <option value="">All Makes</option>
                                    @foreach($manufacturers as $manufacturer)
                                        <option value="{{ $manufacturer->id }}">{{ $manufacturer->name }}</option>
                                    @endforeach
                                </select>
                                <i class="ri-arrow-drop-down-line vf-select-icon"></i>
                            </div>
                        </div>

                        {{-- Model --}}
                        <div class="vf-field">
                            <span class="vf-label">Model</span>
                            <div class="vf-select-wrap">
                                <select class="vf-select" id="tableFilterModel">
                                    <option value="">All Models</option>
                                    @foreach($modelsList as $m)
                                        <option value="{{ $m }}">{{ $m }}</option>
                                    @endforeach
                                </select>
                                <i class="ri-arrow-drop-down-line vf-select-icon"></i>
                            </div>
                        </div>

                        {{-- Vehicle ID --}}
                        <div class="vf-field">
                            <span class="vf-label">Vehicle ID</span>
                            <div class="vf-input-wrap">
                                <i class="ri-hashtag vf-input-icon"></i>
                                <input type="text" class="vf-input" id="tableFilterVid" placeholder="e.g. VH000001">
                            </div>
                        </div>

                        {{-- Buttons --}}
                        <div class="vf-field">
                            <span class="vf-label" style="visibility:hidden;">act</span>
                            <div style="display:flex;gap:8px;">
                                <button class="vf-btn-apply" id="tableApplyFilters">
                                    <i class="ri-search-line"></i> Apply
                                </button>
                                <button class="vf-btn-clear" id="tableClearFilters">
                                    <i class="ri-close-line"></i> Clear
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== DRAFTS SECTION ===== --}}
    @if(isset($draftCars) && $draftCars->count() > 0)
        <div class="row mb-4" id="draftsSection">
            <div class="col-12">
                <div class="card border-warning border-start border-4 shadow-sm">
                    <div class="card-header bg-warning-subtle d-flex align-items-center justify-content-between py-3">
                        <h5 class="card-title text-warning-emphasis mb-0 d-flex align-items-center gap-2">
                            <i class="ri-draft-line fs-18"></i>
                            <span>Car Drafts</span>
                            <span class="badge bg-warning text-dark rounded-pill fs-12">{{ $draftCars->count() }}</span>
                        </h5>
                        <small class="text-muted">Auto-saved car drafts pending publication</small>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle table-hover table-nowrap mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="width: 70px;" class="text-center">Image</th>
                                        <th scope="col">Car Name</th>
                                        <th scope="col">Vehicle ID</th>
                                        <th scope="col" class="text-center">Status</th>
                                        <th scope="col">Last Saved</th>
                                        <th scope="col" class="text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($draftCars as $draftItem)
                                        @php
                                            $dImg = (!empty($draftItem->images) && is_array($draftItem->images) && isset($draftItem->images[0]) && !empty($draftItem->images[0])) ? $draftItem->images[0] : null;
                                            $dSrc = $dImg ? asset(CAR_PATH . $dImg) : asset('uploads/default/default.jpg');
                                        @endphp
                                        <tr id="slider-card-{{ $draftItem->id }}">
                                            <td class="text-center">
                                                <img src="{{ $dSrc }}" alt="Draft" class="car-table-thumb rounded shadow-sm">
                                            </td>
                                            <td>
                                                <h6 class="fs-14 mb-0 fw-semibold">{{ $draftItem->name ?: 'Untitled Car Draft' }}</h6>
                                                <small class="text-muted">{{ $draftItem->manufacturer->name ?? '' }} {{ $draftItem->model ?? '' }} {{ $draftItem->year ?? '' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-body fs-12">{{ $draftItem->vehicle_id ?: 'Pending' }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark px-2 py-1 fs-12">
                                                    <i class="ri-draft-line me-1"></i> {{ \App\Enum\CarStatus::DRAFT->label() }}
                                                </span>
                                            </td>
                                            <td>{{ $draftItem->updated_at ? $draftItem->updated_at->diffForHumans() : '-' }}</td>
                                            <td class="text-center">
                                                <div class="d-flex justify-content-center gap-2">
                                                    <a href="{{ route('admin.car.draft.edit', $draftItem) }}"
                                                        class="btn btn-warning btn-sm d-inline-flex align-items-center gap-1 fw-medium me-1">
                                                        <i class="ri-edit-box-line"></i> Continue Editing
                                                    </a>
                                                    <a href="{{ route('admin.car.edit', $draftItem->id) }}" class="btn btn-success btn-sm me-1" title="Edit">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <button class="btn btn-danger btn-sm" onclick="deleteCar({{ $draftItem->id }}, this)" title="Delete">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="gridView">
                        @include('admin.car.grid_list')
                    </div>

                    <div id="tableView" style="display:none;" class="table-responsive">
                        <table id="carTable"
                            class="listDatatable tableview table align-middle table-nowrap w-100 pt-2 datatable dataTable no-footer">

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function makeGridSortable() {
            $(".car-sortable-group").sortable({
                items: "> div.sortable-item",
                placeholder: "ui-state-highlight",
                update: function (event, ui) {
                    let order = [];
                    $(this).children(".sortable-item").each(function (index, element) {
                        order.push({
                            id: $(element).data("id"),
                            position: index + 1
                        });
                    });

                    $.ajax({
                        url: "{{ route('admin.car.sort') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            order: order
                        },
                        success: function (response) {
                            console.log("Sorting updated:", response);
                        }
                    });
                }
            });
        }
        $(document).on('click', '.filter-range', function () {
            let range = $(this).data('range');
            let rangeText = $(this).text();
            let keyword = $('input[name="search"]').val();

            // Update dropdown button text
            // $('.filter-range').closest('.btn-group').find('.dropdown-toggle').text(rangeText);
            $(this).closest('.btn-group').find('.dropdown-toggle').text(rangeText);
            if ($('#gridView').is(':visible')) {
                let make = $('#tableFilterMake').val();
                let model = $('#tableFilterModel').val();
                let vehicle_id = $('#tableFilterVid').val();
                $.ajax({
                    url: "{{ route('admin.car.index') }}",
                    type: "GET",
                    data: {
                        range: range,
                        search_keyword: keyword,
                        make: make,
                        model: model,
                        vehicle_id: vehicle_id,
                        view_type: 'grid'
                    },
                    success: function (response) {
                        $('#gridView').html(response);
                        makeGridSortable();
                    }
                });
            } else {
                // Table View is visible, reload DataTable with the new range
                // The range will be stored in session by the controller
                $.ajax({
                    url: "{{ route('admin.car.index') }}",
                    type: "GET",
                    data: {
                        range: range,
                        search_keyword: keyword,
                    },
                    success: function (response) {
                        $('#carTable').DataTable().ajax.reload();
                    }
                });
            }
        });
        function deleteCar(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this car?",
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
                        url: "{{route('admin.car.delete')}}",
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
                            $(`#slider-card-${id}`).remove();
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

        $(function () {
            makeGridSortable();
        });

        $(document).ready(function () {
            $('#tableApplyFilters').click(function () {
                if ($('#gridView').is(':visible')) {
                    reloadGridView();
                } else {
                    $('#carTable').DataTable().ajax.reload();
                }
            });

            $('#tableClearFilters').click(function () {
                $('#tableFilterMake').val('');
                $('#tableFilterModel').val('');
                $('#tableFilterVid').val('');

                if ($('#gridView').is(':visible')) {
                    reloadGridView();
                } else {
                    $('#carTable').DataTable().ajax.reload();
                }
            });


            // ============ GRID VIEW BUTTON ============
            $('#gridViewBtn').click(function () {
                $('#carTable').DataTable().clear().destroy();

                $('#gridView').show();
                $('#tableView').hide();
                $('#txtSearch').show();
                $('#btnSearch').show();

                // Load grid with current search keyword and filters
                reloadGridView();
            });

            // ============ TABLE VIEW BUTTON ============
            $('#tableViewBtn').click(function () {
                $('#txtSearch').hide();
                $('#btnSearch').hide();
                $('#gridView').hide();
                $('#tableView').show();

                if (!$.fn.dataTable.isDataTable('#carTable')) {

                    var dataTable = $('#carTable').DataTable({
                        processing: true,
                        serverSide: true,
                        order: [], // disable default ordering
                        ordering: false,
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
                                next: '<i class="ri-arrow-right-s-line"></i>',
                                previous: '<i class="ri-arrow-left-s-line"></i>',
                            },
                        },
                        columns: [
                            { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center' },
                            { data: 'image', name: 'image', title: 'Image', class: 'text-center', orderable: false, searching: false },
                            { data: 'name', name: 'name', title: 'Name', class: 'text-center' },
                            { data: 'status', name: 'status', title: 'Status', class: 'text-center' },
                            { data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center' },
                            {
                                data: 'action',
                                name: 'action',
                                title: 'Action',
                                class: 'text-center',
                                searching: false
                            },
                        ],
                        ajax: {
                            url: '{{ route("admin.car.index") }}',
                            type: "GET",
                            dataType: "JSON",
                            data: function (f) {
                                f._token = "{{csrf_token()}}",
                                    // Vehicle filters
                                    f.make = $('#tableFilterMake').val();
                                f.model = $('#tableFilterModel').val();
                                f.vehicle_id = $('#tableFilterVid').val();
                            },
                            error: function (xhr) {
                                dataTableError("openCallTable", xhr.responseJSON.message);
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
                        rowId: 'id',

                        drawCallback: function () {
                            makeTableSortable();
                        },
                    });

                } else {
                    $('#carTable').DataTable().ajax.reload(null, false);
                }
            });

            // Helper to reload grid view dynamically
            function reloadGridView() {
                let keyword = $('input[name="search"]').val();
                let make = $('#tableFilterMake').val();
                let model = $('#tableFilterModel').val();
                let vehicle_id = $('#tableFilterVid').val();

                $.ajax({
                    url: "{{ route('admin.car.index') }}",
                    type: "GET",
                    data: {
                        search_keyword: keyword,
                        make: make,
                        model: model,
                        vehicle_id: vehicle_id,
                        view_type: 'grid'
                    },
                    success: function (response) {
                        $('#gridView').html(response);
                        makeGridSortable();
                    }
                });
            }

            $('#btnSearch').click(function (e) {
                e.preventDefault();
                reloadGridView();
            });

            $('#gridSearchForm').submit(function (e) {
                e.preventDefault();
                reloadGridView();
            });

            // Add click handler for pagination links in grid view
            $(document).on('click', '#gridView .pagination a', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let search = $('input[name="search"]').val();
                let make = $('#tableFilterMake').val();
                let model = $('#tableFilterModel').val();
                let vehicle_id = $('#tableFilterVid').val();

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        view_type: 'grid',
                        search_keyword: search,
                        make: make,
                        model: model,
                        vehicle_id: vehicle_id
                    },
                    success: function (response) {
                        $('#gridView').html(response);
                        makeGridSortable();
                    }
                });
            });


            $('#tableViewBtn').trigger('click');

            // ============ FUNCTION: Make Table Sortable ============
            function makeTableSortable() {
                $('#carTable tbody').sortable({
                    helper: fixWidthHelper,
                    update: function (event, ui) {
                        let order = [];

                        $('#carTable tbody tr').each(function (index, element) {
                            order.push({
                                id: $(element).attr('id'),
                                position: index + 1
                            });
                        });

                        $.ajax({
                            url: "{{ route('admin.car.sort') }}",
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

            // Helper: keeps columns the same width while dragging
            function fixWidthHelper(e, ui) {
                ui.children().each(function () {
                    $(this).width($(this).width());
                });
                return ui;
            }



        });
    </script>
@endsection