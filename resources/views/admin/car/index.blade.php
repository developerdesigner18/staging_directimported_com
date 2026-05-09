@extends('admin.master')
@section('title', 'Car')
@push('modal')
    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="bannerModal" tabindex="-1" aria-labelledby="bannerModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg">
                <div class="modal-header">
                    <h5 class="modal-title" id="bannerModalLabel">Upload Banner Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="bannerUploadForm" enctype="multipart/form-data">
                        @csrf

                        <!-- Banner Title -->
                        <div class="mb-3">
                            <label for="bannerTitle" class="form-label">Banner Title</label>
                            <input type="text" class="form-control" id="bannerTitle" name="title"
                                placeholder="Enter banner title">
                            <p id="title-error" class="text-danger mt-1" style="display: none"></p>
                        </div>

                        <!-- Select New Banner -->
                        <div class="mb-3">
                            <label for="bannerImage" class="form-label">Select New Banner</label>
                            <input class="form-control" type="file" id="bannerImage" name="banner_image"
                                accept="image/webp">
                            <p id="banner_image-error" class="text-danger mt-1" style="display: none"></p>
                        </div>

                        <!-- Banner Preview at Bottom -->
                        <div class="text-center mt-4 position-relative">
                            <h6 class="images-preview-title">Current Banner</h6>
                            <img id="currentBanner" src="" alt="Banner" class="img-fluid rounded"
                                style="max-height: 100px; width: auto; object-fit: cover; border-radius: 8px;">

                            <!-- Delete button at bottom center -->
                            <button type="button" id="deleteBannerBtn" class="btn btn-danger position-absolute"
                                style="bottom: 0; left: 50%; transform: translateX(-50%); border-radius:50%; padding:2px 6px; font-size:0.7rem;">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="uploadBannerBtn">Upload</button>
                </div>
                </form>
            </div>
        </div>
    </div>

@endpush
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
                <button type="button" class="btn btn-success btn-label" data-bs-toggle="modal"
                    data-bs-target="#bannerModal"><i class="ri-image-add-line label-icon align-middle fs-16 me-2"></i>Car
                    Banner</button>
            </div>
        </div>

        <div class="col-sm">
            {{-- <form method="GET" action="{{ route('admin.car.index') }}">--}}
                <form id="gridSearchForm">
                    <div class="d-flex justify-content-sm-end gap-2">

                        <div class="search-box ms-2" id="txtSearch">
                            <input type="text" name="search" class="form-control" placeholder="Search..."
                                value="{{ $search }}">
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
                                <a class="dropdown-item filter-range cursor-pointer" data-range="400-700cc">400-700cc</a>
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

    <div id="gridView">
        @include('admin.car.grid_list')
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div id="tableView" style="display:none;">
                        <table class="table table-bordered" id="carTable">

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
                $.ajax({
                    url: "{{ route('admin.car.index') }}",
                    type: "GET",
                    data: {
                        range: range,
                        search_keyword: keyword,
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
            loadBanner();

            // Load current banner image
            function loadBanner() {
                $.get("{{ route('admin.banner.get') }}", function (response) {
                    if (response.status) {
                        $('#currentBanner').attr('src', response.data.image_url);
                        $('#bannerTitle').val(response.data.title);
                        // Show delete button only if banner record exists (not default)
                        if (response.data.exists) {
                            $('#deleteBannerBtn').show();
                        } else {
                            $('.images-preview-title').text("Default Banner");

                            $('#deleteBannerBtn').hide();
                        }
                    }
                });
            }

            // ============ GRID VIEW BUTTON ============
            $('#gridViewBtn').click(function () {
                $('#carTable').DataTable().clear().destroy();

                $('#gridView').show();
                $('#tableView').hide();
                $('#txtSearch').show();
                $('#btnSearch').show();

                // Load grid with current search keyword
                let keyword = $('input[name="search"]').val();
                $.ajax({
                    url: "{{ route('admin.car.index') }}",
                    type: "GET",
                    data: {
                        search_keyword: keyword,
                        view_type: 'grid'
                    },
                    success: function (response) {
                        $('#gridView').html(response);
                        makeGridSortable();
                    }
                });
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
                                next: '<i class="ri-arrow-right-s-line">',
                                previous: '<i class="ri-arrow-left-s-line">',
                            },
                        },
                        columns: [
                            { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center' },
                            { data: 'name', name: 'name', title: 'Name', class: 'text-center' },
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
                                f._token = "{{csrf_token()}}";
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
                    $('#carTable').DataTable().ajax.reload(null, false);
                }
            });

            $('#btnSearch').click(function (e) {
                e.preventDefault();
                let keyword = $('input[name="search"]').val();

                $.ajax({
                    url: "{{ route('admin.car.index') }}",
                    type: "GET",
                    data: {
                        search_keyword: keyword,
                        view_type: 'grid'
                    },
                    success: function (response) {
                        $('#gridView').html(response);
                        // Re-initialize sortable if needed
                        makeGridSortable();
                    }
                });
            });

            // Add click handler for pagination links in grid view
            $(document).on('click', '#gridView .pagination a', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                let search = $('input[name="search"]').val();

                $.ajax({
                    url: url,
                    type: "GET",
                    data: {
                        view_type: 'grid',
                        search_keyword: search
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
            $.validator.addMethod('fileType', function (value, element, param) {
                return this.optional(element) || (element.files[0].type.match(param));
            }, 'The image must be of type: webp.');

            $.validator.addMethod('fileSize', function (value, element, param) {
                return this.optional(element) || (element.files[0].size <= param);
            }, 'The image size must not exceed 2MB.');

            $('#bannerUploadForm').validate({
                rules: {
                    title: {
                        required: true
                    },
                    banner_image: {
                        required: true,
                        fileType: "image/webp",
                        fileSize: 2097152 // 2MB in bytes
                    }
                },
                messages: {
                    title: {
                        required: "Please enter a title."
                    },
                    banner_image: {
                        required: "Please upload a banner image.",
                        fileType: "The image must be of type: webp.",
                        fileSize: "The image size must not exceed 2MB."
                    }
                },
                errorPlacement: function (error, element) {
                    $("#" + element.attr("name") + "-error").html(error.text()).show();
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                    $("#" + $(element).attr("name") + "-error").hide();
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    let formData = new FormData(form);
                    let element = $('#uploadBannerBtn');

                    $.ajax({
                        url: "{{ route('admin.banner.add') }}",
                        method: "POST",
                        data: formData,
                        contentType: false,
                        processData: false,
                        dataType: "JSON",
                        beforeSend: function () {
                            element.html('<i class="spinner-border spinner-border-sm"></i> Uploading...');
                            element.attr('disabled', true);
                            $('.text-danger').hide();
                        },
                        success: function (response) {
                            if (response.status) {
                                $('#currentBanner').attr('src', response.data.image_url);
                                $('#bannerTitle').val(response.data.title);

                                setTimeout(() => {
                                    $('#bannerModal').modal('hide');
                                }, 1000);
                            }
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.error) {
                                $.each(data.error, function (key, value) {
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + key + "-error").html(errorMessage).show();
                                });
                            } else {
                                sendError("Something went wrong.");
                            }
                        },
                        complete: function () {
                            element.html('Upload');
                            element.attr('disabled', false);
                        }
                    });
                }
            });
            $("#deleteBannerBtn").click(function () {
                Swal.fire({
                    title: "Are you sure?",
                    text: "Are you sure you want to delete this banner?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes",
                    cancelButtonText: "No",
                    confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                    cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                    buttonsStyling: false,
                }).then(function (t) {
                    if (t.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.banner.delete') }}",
                            method: "POST",   // Use POST for delete
                            dataType: "json",
                            data: {
                                _token: "{{ csrf_token() }}"  // send CSRF token
                            },
                            cache: true,
                            beforeSend: function () {
                                $('#deleteBannerBtn').attr('disabled', true);
                            },
                            success: function (result) {

                                sendSuccess(result.message);
                                loadBanner();
                                // $('#detailsRejectForm').addClass('d-none');
                                // $("#detailsRejectForm").trigger('reset');
                                // $("label.error").hide();
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
                                $('#deleteBannerBtn').attr('disabled', false);
                                // $("#reasonBtnSpinner").hide();
                            },
                        });
                    }
                });

            });


        });
    </script>
@endsection