@extends('admin.master')
@section('title', 'FAQ Management')

@push('modal')
    <!-- Add FAQ Modal -->
    <div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="faqModalLabel" aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="faqForm">
                    @csrf
                    <div class="modal-body">

                        <!-- FAQ Category -->
                        <div class="mb-3">
                            <label for="faqCategory" class="form-label">Category</label>
                            <select class="form-select faqCategorySelect" id="faqCategory" name="faq_category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <label id="faq_category_id-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- FAQ Title -->
                        <div class="mb-3">
                            <label for="faqTitle"
                                class="form-label">{{ admin_label('faq_form', 'faq_title', 'FAQ Title') }}</label>
                            <input type="text" class="form-control" id="faqTitle" name="faqTitle"
                                placeholder="Enter FAQ title">
                            <label id="faqTitle-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Description (TinyMCE Editor) -->
                        <div class="mb-3">
                            <label for="faqDescription"
                                class="form-label">{{ admin_label('faq_form', 'description', 'Description') }}</label>

                            <textarea id="faqDescription" name="faqDescription" style="display:none;"></textarea>

                            <textarea class="tinymce_editor" id="faqDescription_editor" rows="6"></textarea>

                            <label id="faqDescription-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <div class="faqData"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary" id="btnAddFaq">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm flex-shrink-0 me-2" id="faqSpinner"
                                    role="status"></span>
                                <span class="flex-grow-1" id="btnText">Submit</span>
                            </span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <!-- Edit FAQ Modal -->
    <div class="modal fade" id="editFaqModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editFaqForm">
                    @csrf
                    <input type="hidden" id="editFaqId" name="faqId">

                    <div class="modal-body">

                        <!-- FAQ Category -->
                        <div class="mb-3">
                            <label for="editFaqCategory" class="form-label">Category</label>
                            <select class="form-select faqCategorySelect" id="editFaqCategory" name="faq_category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <label id="editFaq_category_id-error" class="text-danger error" style="display:none;"></label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ admin_label('faq_form', 'faq_title', 'FAQ Title') }}</label>
                            <input type="text" class="form-control" id="editFaqTitle" name="editFaqTitle">
                            <label id="editFaqTitle-error" class="text-danger error" style="display:none;"></label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ admin_label('faq_form', 'description', 'Description') }}</label>

                            <textarea id="edit_faqDescription" name="editFaqDescription" style="display:none;"></textarea>

                            <textarea class="tinymce_editor" id="edit_faqDescription_editor" rows="6"></textarea>

                            <label id="edit_faqDescription-error" class="text-danger error" style="display:none;"></label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary" id="btnEditFaq">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm me-2" id="editFaqSpinner"></span>
                                <span>Update</span>
                            </span>
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Manage FAQ Categories Modal -->
    <div class="modal fade" id="manageFaqCategoryModal" tabindex="-1" aria-labelledby="manageFaqCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageFaqCategoryModalLabel">Manage FAQ Categories</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Form to Add New Category -->
                    <form id="addCategoryFormModal" class="mb-4">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-sm-9">
                                <label for="categoryName" class="form-label">Category Name</label>
                                <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter category name">
                                <label id="categoryName-error" class="text-danger error" style="display:none;"></label>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary w-100" id="btnAddCategoryModal">
                                    <span class="spinner-border spinner-border-sm me-1 d-none" id="addCategorySpinner"></span>
                                    <span>Add Category</span>
                                </button>
                            </div>
                        </div>
                    </form>

                    <hr>

                    <h6>Existing Categories</h6>
                    <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                        <table class="table table-bordered align-middle table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 60px;" class="text-center">#</th>
                                    <th>Category Name</th>
                                    <th style="width: 120px;" class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody id="categoryTableBody">
                                <!-- Populated dynamically via JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Category Modal -->
    <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Edit FAQ Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editCategoryFormModal">
                    @csrf
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editCategoryNameInput" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="editCategoryNameInput" name="name">
                            <label id="editCategoryNameInput-error" class="text-danger error" style="display:none;"></label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btnUpdateCategory">
                            <span class="spinner-border spinner-border-sm me-1 d-none" id="updateCategorySpinner"></span>
                            <span>Save Changes</span>
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
                <h4 class="mb-sm-0">FAQ Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Information</a></li>
                        <li class="breadcrumb-item active">FAQ Management</li>
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
                            <h5 class="card-title mb-0">FAQ</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-2 flex-wrap">
                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                    data-bs-target="#manageFaqCategoryModal">
                                    <i class="ri-add-fill align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Add FAQ Category</span>
                                </button>

                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addFaqModal">
                                    <i class="ri-add-fill align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Add FAQ</span>
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
        tinymce.init({
            selector: '#faqDescription_editor,#edit_faqDescription_editor',
            height: 300,
            menubar: true,
            plugins: 'lists link image help wordcount code media table',
            toolbar: 'code | formatselect fontsizeselect | insertfile a11ycheck | numlist bullist | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image media tinydrive | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
            file_picker_types: 'file image media',
            images_upload_url: "{{route('admin.tinymce.image.upload')}}",
            images_upload_handler: function (blobInfo, success, failure) {
                uploadFilePond(blobInfo.blob(), 'image').then(function (url) {
                    success(url);
                }).catch(function (error) {
                    failure('Image upload failed: ' + error);
                });
            },
            file_picker_callback: function (callback, value, meta) {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');

                if (meta.filetype === 'image') {
                    input.setAttribute('accept', 'image/*');
                } else if (meta.filetype === 'media') {
                    input.setAttribute('accept', 'video/*,audio/*');
                } else {
                    input.setAttribute('accept', '*');
                }

                input.onchange = function () {
                    const file = this.files[0];

                    uploadFilePond(file, meta.filetype).then(function (url) {
                        callback(url);
                    }).catch(function (error) {
                        alert('File upload failed: ' + error);
                    });
                };

                input.click();
            },
            media_live_embeds: true,
            media_url_resolver: function (data, resolve) {
                if (data.url.match(/youtube\.com|youtu\.be/)) {
                    let videoId = '';
                    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
                    const match = data.url.match(regExp);

                    if (match && match[2].length === 11) {
                        videoId = match[2];
                    } else if (data.url.includes('youtu.be/')) {
                        videoId = data.url.split('youtu.be/')[1].split(/[?&]/)[0];
                    }

                    if (videoId) {
                        const embedUrl = 'https://www.youtube.com/embed/' + videoId;
                        const embedHtml = '<iframe src="' + embedUrl +
                            '" width="560" height="314" allowfullscreen="allowfullscreen"></iframe>';
                        resolve({ html: embedHtml });
                    } else {
                        resolve({ html: '' });
                    }
                } else {
                    resolve({ html: '' });
                }
            },
            setup: function (editor) {
                editor.on('init', function () {
                    var textareaId = editor.id.replace('_editor', '');
                    editor.setContent(document.getElementById(textareaId).value);
                });
                editor.on('change', function () {
                    var textareaId = editor.id.replace('_editor', '');
                    document.getElementById(textareaId).value = editor.getContent();
                });
            }
        });

        var dataTable = $('#databaseTable').DataTable({
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
                { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center' },
                { data: 'key', name: 'key', title: 'Title', class: 'text-center' },
                { data: 'category', name: 'category.name', title: 'Category', class: 'text-center' },
                { data: 'created_at', name: 'created_at', title: 'Create Date', class: 'text-center' },
                { data: 'updated_at', name: 'updated_at', title: 'Update Date', class: 'text-center' },
                { data: 'action', name: 'action', title: 'Action', class: 'text-center' }
            ],
            ajax: {
                url: '{{ route("admin.faq.list") }}',
                type: "GET",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                },
                error: function (xhr) {
                    dataTableError("openCallTable", xhr.responseJSON.message);
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

        // Function to refresh categories in selects and category management table
        function refreshFaqCategories() {
            $.ajax({
                url: "{{ route('admin.faq.category.list') }}",
                type: 'GET',
                success: function (res) {
                    if (res.success) {
                        let categories = res.data;

                        // 1. Refresh Select dropdowns
                        let selectHtml = '<option value="">Select Category</option>';
                        let tableRowsHtml = '';

                        $.each(categories, function (i, cat) {
                            selectHtml += `<option value="${cat.id}">${cat.name}</option>`;
                            tableRowsHtml += `
                                <tr>
                                    <td class="text-center">${i + 1}</td>
                                    <td>${cat.name}</td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-info me-1" onclick="openEditCategoryModal(${cat.id}, '${cat.name.replace(/'/g, "\\'")}')">
                                            <i class="ri-pencil-fill"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${cat.id})">
                                            <i class="ri-delete-bin-fill"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                        });

                        if (categories.length === 0) {
                            tableRowsHtml = '<tr><td colspan="3" class="text-center text-muted">No categories found.</td></tr>';
                        }

                        // Store current select values before replacing
                        let currentAddVal = $('#faqCategory').val();
                        let currentEditVal = $('#editFaqCategory').val();

                        $('.faqCategorySelect').html(selectHtml);
                        $('#faqCategory').val(currentAddVal);
                        $('#editFaqCategory').val(currentEditVal);

                        $('#categoryTableBody').html(tableRowsHtml);
                    }
                }
            });
        }

        function openEditCategoryModal(id, name) {
            $('#editCategoryId').val(id);
            $('#editCategoryNameInput').val(name);
            $('#editCategoryNameInput-error').hide();
            $('#editCategoryModal').modal('show');
        }

        function deleteCategory(id) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this category?",
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
                        url: "{{ route('admin.faq.category.delete') }}",
                        method: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            sendSuccess(res.message).then(() => {
                                refreshFaqCategories();
                                dataTable.ajax.reload(null, false);
                            });
                        },
                        error: function (xhr) {
                            actionError(xhr);
                        }
                    });
                }
            });
        }

        $(document).ready(function () {
            // Load category table on page load
            refreshFaqCategories();

            // Add FAQ Category AJAX Form
            $("#addCategoryFormModal").validate({
                rules: {
                    name: { required: true }
                },
                messages: {
                    name: { required: "The category name is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.faq.category.store') }}",
                        method: "POST",
                        data: $(form).serialize(),
                        beforeSend: function () {
                            $('#btnAddCategoryModal').attr('disabled', true);
                            $('#addCategorySpinner').removeClass('d-none');
                        },
                        success: function (res) {
                            sendSuccess(res.message).then(() => {
                                $('#addCategoryFormModal')[0].reset();
                                refreshFaqCategories();
                            });
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.error) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data && data.message) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnAddCategoryModal').attr('disabled', false);
                            $('#addCategorySpinner').addClass('d-none');
                        }
                    });
                }
            });

            // Edit FAQ Category AJAX Form
            $("#editCategoryFormModal").validate({
                rules: {
                    name: { required: true }
                },
                messages: {
                    name: { required: "The category name is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('admin.faq.category.update') }}",
                        method: "POST",
                        data: $(form).serialize(),
                        beforeSend: function () {
                            $('#btnUpdateCategory').attr('disabled', true);
                            $('#updateCategorySpinner').removeClass('d-none');
                        },
                        success: function (res) {
                            sendSuccess(res.message).then(() => {
                                $('#editCategoryModal').modal('hide');
                                refreshFaqCategories();
                                dataTable.ajax.reload(null, false);
                            });
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.error) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data && data.message) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnUpdateCategory').attr('disabled', false);
                            $('#updateCategorySpinner').addClass('d-none');
                        }
                    });
                }
            });

            // Add FAQ Form Validation & Submission
            $("#faqForm").validate({
                rules: {
                    faqTitle: { required: true },
                },
                messages: {
                    faqTitle: { required: "The title field is required." },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $('#faqDescription').val(tinymce.get('faqDescription_editor').getContent());

                    $.ajax({
                        url: "{{ route('admin.faq.create') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnAddFaq').attr('disabled', true);
                            $("#faqSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then((result) => {
                                tinymce.get('faqDescription_editor').setContent('');
                                $('#faqForm')[0].reset();
                                dataTable.ajax.reload(null, false); // false preserves current page
                                $('#addFaqModal').modal('hide');
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
                            $('#btnAddFaq').attr('disabled', false);
                            $("#faqSpinner").addClass('d-none');
                        },
                    });
                }
            });

            // Edit FAQ Form Validation & Submission
            $("#editFaqForm").validate({
                rules: {
                    editFaqTitle: { required: true },
                },
                messages: {
                    editFaqTitle: { required: "The Title field is required." },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $('#edit_faqDescription').val(tinymce.get('edit_faqDescription_editor').getContent());

                    $.ajax({
                        url: "{{ route('admin.faq.update') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnEditFaq').attr('disabled', true);
                            $("#editFaqSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then((result) => {
                                tinymce.get('edit_faqDescription_editor').setContent('');
                                $('#editFaqForm')[0].reset();
                                dataTable.ajax.reload(null, false);
                                $('#editFaqModal').modal('hide');
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
                            $('#btnEditFaq').attr('disabled', false);
                            $("#editFaqSpinner").addClass('d-none');
                        },
                    });
                }
            });
        });

        function editFaq(id) {
            var edit = "{{ route('admin.faq.edit', ':id') }}";

            $.ajax({
                url: edit.replace(':id', id),
                type: 'GET',
                success: function (response) {
                    // Fill the form fields
                    $('#editFaqId').val(response.data.id);
                    $('#editFaqTitle').val(response.data.key);
                    $('#editFaqCategory').val(response.data.faq_category_id || '');
                    tinymce.get('edit_faqDescription_editor').setContent(response.data.value || '');

                    // update hidden textarea
                    $('#edit_faqDescription').val(response.data.value || '');
                    // Open the modal
                    $('#editFaqModal').modal('show');
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message);
                    } else {
                        actionError(xhr);
                    }
                }
            });
        }

        function removeFaq(id, element) {
            var originalHtml = $(element).html();
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this FAQ?",
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
                        url: "{{route('admin.faq.delete')}}",
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
                            sendSuccess(data.message).then(() => {
                                dataTable.ajax.reload(null, false);
                            });
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
        };
    </script>
@endsection