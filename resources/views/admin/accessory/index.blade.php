@extends('admin.master')
@section('title', 'Accessories')
<style>
    .icon-picker {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
        gap: 10px;
        max-height: 300px;
        overflow-y: auto;
    }

    .icon-option {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 1px;
        text-align: center;
        cursor: pointer;
        transition: 0.2s;
        background: #f8f9fa;
    }

    .icon-option i {
        font-size: 22px;
        display: block;
        margin-bottom: 5px;
    }

    .icon-option small {
        font-size: 11px;
        color: #666;
    }

    .icon-option:hover {
        border-color: #053C7C;
    }

    .icon-option.active {
        border-color: #053C7C;
        background: #eef5ff;
    }
</style>
@push('modal')
    <div class="modal fade" id="addAccessoryMD" tabindex="-1" aria-labelledby="addAccessoryMDLabel" aria-modal="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Accessory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);" id="addAccessoryForm">
                        @csrf
                        <div class="row g-3">
                            @php
                                use App\Enum\AccessoryType;
                            @endphp

                            <div class="col-12 mt-3">
                                <label
                                    class="form-label">{{ admin_label('accessory_form', 'accessory_type', 'Accessory Type') }}</label>
                                <select class="form-control" id="addAccessoryType" name="type">
                                    @foreach(AccessoryType::cases() as $type)

                                        <option value="{{ $type->value }}" {{ $type->value === 'EXTRA' ? 'selected' : '' }}>
                                            {{ ucfirst($type->value) }}
                                        </option>
                                    @endforeach

                                </select>
                                <label id="type-error" class="text-danger error" style="display: none"></label>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="addAccessoryName"
                                        class="form-label">{{ admin_label('accessory_form', 'name', 'Name') }}</label>
                                    <input type="text" class="form-control" id="addAccessoryName" name="name"
                                        placeholder="Enter accessory name">
                                    <label id="name-error" class="text-danger error" for="addAccessoryName"
                                        style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div>
                                    <label for="addAccessoryPrice"
                                        class="form-label">{{ admin_label('accessory_form', 'price', 'Price') }}</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="addAccessoryPrice"
                                        name="price" placeholder="Enter price">
                                    <label id="price-error" class="text-danger error" for="addAccessoryPrice"
                                        style="display: none"></label>
                                </div>
                            </div>

                            <div class="col-12 mt-3">
                                <div>
                                    <label for="addAccessoryAdditionalPrice"
                                        class="form-label">{{ admin_label('accessory_form', 'following_day_price', 'Following day price') }}</label>
                                    <input type="number" min="0" class="form-control" id="addAccessoryAdditionalPrice"
                                        name="additional_day_price" placeholder="Enter Additional price ">
                                    <label id="price-error" class="text-danger error" for="addAccessoryAdditionalPrice"
                                        style="display: none"></label>
                                </div>
                            </div>

                            {{-- Icon Select --}}
                            <div class="col-12 mt-3">
                                <div>
                                    <label for="iconSearch"
                                        class="form-label">{{ admin_label('accessory_form', 'select_icon', 'Select Icon') }}</label>
                                    <input type="text" class="form-control" id="iconSearch" placeholder="Search Icon ...">
                                    <label class="text-danger error" for="iconSearch" style="display: none"></label>

                                    <div class="icon-picker border rounded p-2"></div>

                                    <input type="hidden" name="icon" id="add_selectedIcon">

                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary" id="addAccessoryBtn">
                                        <i class="bx bx-loader spinner me-2" style="display: none"
                                            id="addAccessoryBtnSpinner"></i>Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Accessory Modal -->
    <div class="modal fade" id="editAccessoryMD" tabindex="-1" aria-labelledby="editAccessoryMDLabel" aria-modal="true"
        data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Accessory</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="javascript:void(0);" id="editAccessoryForm">
                        @csrf
                        <input type="hidden" name="id" id="accessory_id">
                        <div class="row g-3">
                            <div class="col-12 mt-3">
                                <label
                                    class="form-label">{{ admin_label('accessory_form', 'accessory_type', 'Accessory Type') }}</label>
                                <select class="form-control" id="editAccessoryType" name="editType">
                                    @foreach(AccessoryType::cases() as $type)

                                        <option value="{{ $type->value }}" {{ $type->value }}>
                                            {{ ucfirst($type->value) }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="editType-error" class="text-danger error" style="display: none"></label>
                            </div>
                            <div class="col-12">
                                <div>
                                    <label for="editAccessoryName"
                                        class="form-label">{{ admin_label('accessory_form', 'name', 'Name') }}</label>
                                    <input type="text" class="form-control" id="edit_name" name="name"
                                        placeholder="Enter accessory name">
                                    <label id="edit_name-error" class="text-danger error" for="edit_name"
                                        style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div>
                                    <label for="editAccessoryPrice"
                                        class="form-label">{{ admin_label('accessory_form', 'price', 'Price') }}</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="edit_price"
                                        name="price" placeholder="Enter price">
                                    <label id="edit_price-error" class="text-danger error" for="edit_price"
                                        style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div>
                                    <label for="editAccessoryAdditionalPrice"
                                        class="form-label">{{ admin_label('accessory_form', 'following_day_price', 'Following day price') }}</label>
                                    <input type="number" step="0.01" min="0" class="form-control"
                                        id="editAccessoryAdditionalPrice" name="additional_day_price"
                                        placeholder="Enter Additional price">
                                    <label id="edit_price-error" class="text-danger error"
                                        for="editAccessoryAdditionalPrice" style="display: none"></label>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div>
                                    <label for="iconSearch"
                                        class="form-label">{{ admin_label('accessory_form', 'select_icon', 'Select Icon') }}</label>
                                    <input type="text" class="form-control" id="iconSearch" placeholder="Search Icon ...">
                                    <label class="text-danger error" for="iconSearch" style="display: none"></label>

                                    <div class="icon-picker border rounded p-2"></div>

                                    <input type="hidden" name="icon" id="edit_selectedIcon">

                                </div>
                            </div>
                            <div class="col-lg-12 mt-4">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-warning" id="editAccessoryBtn">
                                        <i class="bx bx-loader spinner me-2" style="display: none"
                                            id="editAccessoryBtnSpinner"></i>Save Changes
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
                <h4 class="mb-sm-0">Accessory Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Accessories</a></li>
                        <li class="breadcrumb-item active">Accessory Management</li>
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
                            <h5 class="card-title mb-0">Accessory List</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <div class="btn-group">
                                    <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        Accessory Type
                                    </button>
                                    <div class="dropdown-menu dropdownmenu-success">
                                        <a class="dropdown-item" href="javascript:void(0)" id="freeAccessory">Free</a>
                                        <a class="dropdown-item" href="javascript:void(0)" id="extraAccessory">Extra</a>
                                    </div>
                                </div>
                                <button onclick="resetForm();" type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addAccessoryMD" aria-controls="addAccessoryMD">
                                    <i class="ri-add-line align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Add Accessory</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="accessoryDT" class="listDatatable tableview table w-100 pt-2 datatable dataTable no-footer">
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{asset('assets/js/remix-icons/icons.js')}}"></script>
    <script>
        // Search Icon Logic
        function renderIcons(container, filter = '') {
            container.innerHTML = '';
            carAccessoryIcons
                .filter(icon => icon.includes(filter))
                .forEach(icon => {
                    const option = document.createElement('div');
                    option.className = 'icon-option';
                    option.dataset.icon = icon;
                    option.innerHTML = `

                            <i class="${icon}"></i>
    <!--                        <small>${icon.replace('ri-', '').replace('-line' ,'')}</small>-->
                        `;
                    container.appendChild(option);
                });
        }

        // Initialize pickers for both modals
        document.querySelectorAll('.modal').forEach(modal => {
            const container = modal.querySelector('.icon-picker');
            const searchInput = modal.querySelector('input[placeholder="Search Icon ..."]');
            const selectedInput = modal.querySelector('input[name="icon"]');

            if (container) {
                renderIcons(container);

                if (searchInput) {
                    searchInput.addEventListener('keyup', function () {
                        renderIcons(container, this.value.toLowerCase());
                    });
                }

                container.addEventListener('click', function (e) {
                    const option = e.target.closest('.icon-option');
                    if (!option) return;

                    container.querySelectorAll('.icon-option').forEach(el => el.classList.remove('active'));
                    option.classList.add('active');
                    selectedInput.value = option.dataset.icon;
                });
            }
        });

        function selectIconInPicker(iconClass, modalId) {
            const modal = document.querySelector(modalId);
            const picker = modal.querySelector('.icon-picker');
            const hiddenInput = modal.querySelector('input[name="icon"]');

            // First render all icons to ensure we can find the one we want
            renderIcons(picker);

            picker.querySelectorAll('.icon-option').forEach(el => {
                if (el.dataset.icon === iconClass) {
                    el.classList.add('active');
                    // Scroll to the active icon
                    setTimeout(() => {
                        el.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                    }, 100);
                } else {
                    el.classList.remove('active');
                }
            });
            hiddenInput.value = iconClass;
        }
        var selectedType = null;
        var dataTable = $('#accessoryDT').DataTable({

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
            ajax: {
                url: '{{ route("admin.accessory.list") }}',
                type: "POST",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                    f.type = selectedType;
                },
                error: function (xhr) {
                    console.error(xhr);
                },
            },

            columns: [
                // {data: 'id', name: 'id', title: 'ID', visible: true}, // hidden for reordering reference
                { data: 'name', name: 'name', title: 'Name', class: 'text-center' },
                { data: 'price', name: 'price', title: 'Price', class: 'text-center' },
                { data: 'created_at', name: 'created_at', title: 'Created At', class: 'text-center' },
                { data: 'action', name: 'action', title: 'Action', class: 'text-center', orderable: false, searchable: false },
            ],

            // ✅ Enable row reordering
            rowReorder: {
                dataSrc: 'name', // you can change to 'id' if you prefer
            },
        });
        // Handle filter click
        $("#freeAccessory").on("click", function () {
            selectedType = "FREE";
            dataTable.ajax.reload();
        });

        $("#extraAccessory").on("click", function () {
            selectedType = "EXTRA";
            dataTable.ajax.reload();
        });

        dataTable.on('row-reorder', function (e, diff, edit) {
            let order = [];

            for (let i = 0; i < diff.length; i++) {
                let rowData = dataTable.row(diff[i].node).data();
                order.push({
                    id: rowData.id,
                    position: diff[i].newPosition + 1
                });
            }

            if (order.length) {
                $.ajax({
                    url: "{{ route('admin.accessory.reorder') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        order: order
                    },
                    success: function (res) {
                        console.log(res.message);
                    },
                    error: function (err) {
                        console.error(" Failed to update order", err);
                    }
                });
            }
        });
        function resetForm() {
            $("#addAccessoryForm").trigger('reset');
            $("#editAccessoryForm").trigger('reset');
            $("label.error").hide();
        }

        function removeAccessory(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this accessory?",
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
                        url: "{{ route('admin.accessory.delete') }}",
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

        function getAccessory(id, element) {
            $.ajax({
                url: "{{ route('admin.accessory.edit') }}",
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
                    console.log(data)
                    resetForm();
                    $("#accessory_id").val(id);
                    $('#edit_name').val(data.data.name);
                    $('#edit_price').val(data.data.price);
                    $('#editAccessoryType').val(data.data.type).trigger('change');
                    $("#editAccessoryMD").modal('show');

                    $('#editAccessoryMD').off('shown.bs.modal').on('shown.bs.modal', function () {
                        selectIconInPicker(data.data.icon, '#editAccessoryMD');
                    });
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
            $('#addAccessoryType').on('change', function () {
                if ($(this).val() === 'FREE') {
                    $('#addAccessoryPrice').val(0).closest('.col-12').hide();
                } else {
                    $('#addAccessoryPrice').val('').closest('.col-12').show();
                }
            });

            $('#editAccessoryType').on('change', function () {
                if ($(this).val() === 'FREE') {
                    $('#edit_price').val(0).closest('.col-12').hide();
                } else {
                    $('#edit_price').closest('.col-12').show();
                }
            });
            // Add Accessory Form Validation & Submit
            $("#addAccessoryForm").validate({
                rules: {
                    name: { required: true },
                    price: { required: true, number: true, min: 0 },
                    type: { required: true },
                    icon: { required: true }
                },
                messages: {
                    name: { required: "The name field is required." },
                    price: { required: "The price field is required.", number: "Please enter a valid number", min: "Price must be positive" },
                    additional_day_price: { required: "The additional price field is required.", number: "Please enter a valid number", min: "Price must be positive" },
                    type: { required: "Please select the type." },
                    icon: { required: "Please select an icon." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.accessory.add') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#addAccessoryBtn').attr('disabled', true);
                            $("#addAccessoryBtnSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            dataTable.ajax.reload();
                            $("#addAccessoryMD").modal('hide');
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
                            $('#addAccessoryBtn').attr('disabled', false);
                            $("#addAccessoryBtnSpinner").hide();
                        },
                    });
                }
            });

            // Update Accessory Form Validation & Submit
            $("#editAccessoryForm").validate({
                rules: {
                    name: { required: true },
                    price: { required: true, number: true, min: 0 },
                    editType: { required: true },
                    icon: { required: true }
                },
                messages: {
                    name: { required: "The name field is required." },
                    price: { required: "The price field is required.", number: "Please enter a valid number", min: "Price must be positive" },
                    additional_day_price: { required: "The additional price field is required.", number: "Please enter a valid number", min: "Price must be positive" },
                    editType: { required: "The Type field is required." },
                    icon: { required: "Please select an icon." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.accessory.update') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#editAccessoryBtn').attr('disabled', true);
                            $("#editAccessoryBtnSpinner").show();
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            dataTable.ajax.reload();
                            $("#editAccessoryMD").modal('hide');
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
                            $('#editAccessoryBtn').attr('disabled', false);
                            $("#editAccessoryBtnSpinner").hide();
                        },
                    });
                }
            });

            function resetForm() {
                $("#addAccessoryForm").trigger('reset');
                $("#editAccessoryForm").trigger('reset');
                $(".icon-option").removeClass('active');
                $("#add_selectedIcon").val('');
                $("#edit_selectedIcon").val('');
                // re-render icon pickers to clear search filters
                document.querySelectorAll('.icon-picker').forEach(picker => renderIcons(picker));
                $("label.error").hide();
            }
        })
    </script>
@endsection