@extends('admin.master')
@section('title', $pageTitle)

@push('modal')
    <!-- Edit Blog Modal -->
    <div class="modal fade" id="editBlogModal" tabindex="-1" aria-labelledby="editBlogModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBlogModalLabel">Edit Blog Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editBlogForm">
                    @csrf
                    <input type="hidden" id="editBlogId" name="id">

                    <div class="modal-body">
                        <!-- Featured Image Display (Read Only - No Edit) -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Featured Image (Read-Only)</label>
                            <div class="p-2 border rounded bg-light text-center">
                                <img id="editBlogImagePreview" src="" alt="Blog Featured Image" class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover; display: none;">
                                <div id="editBlogNoImage" class="text-muted py-3" style="display: none;">
                                    <i class="ri-image-line fs-24 d-block mb-1"></i>
                                    <span>No Featured Image Available</span>
                                </div>
                            </div>
                        </div>

                        <!-- Blog Title -->
                        <div class="mb-3">
                            <label for="editTitle" class="form-label fw-bold">Blog Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editTitle" name="title" placeholder="Enter blog title">
                            <label id="title-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="editCategory" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="editCategory" name="category">
                                <option value="general">General Blogs Management</option>
                                <option value="import_regulation">Import Regulation Blogs Management</option>
                            </select>
                            <label id="category-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Description Excerpt -->
                        <div class="mb-3">
                            <label for="editDescription" class="form-label fw-bold">Short Description / Excerpt</label>
                            <textarea class="form-control" id="editDescription" name="description" rows="3" placeholder="Enter short description"></textarea>
                            <label id="description-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Full Content (TinyMCE) -->
                        <div class="mb-3">
                            <label for="editContent" class="form-label fw-bold">Content <span class="text-danger">*</span></label>
                            <textarea id="editContent" name="content" style="display:none;"></textarea>
                            <textarea class="tinymce_editor" id="edit_content_editor" rows="8"></textarea>
                            <label id="content-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Published Date -->
                        <div class="mb-3">
                            <label for="editPublishedAt" class="form-label fw-bold">Published Date</label>
                            <input type="datetime-local" class="form-control" id="editPublishedAt" name="published_at">
                            <label id="published_at-error" class="text-danger error" style="display: none"></label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="btnUpdateBlog">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm me-2" id="editBlogSpinner" role="status"></span>
                                <span>Update Blog</span>
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
                <h4 class="mb-sm-0">{{ $pageTitle }}</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Blogs</a></li>
                        <li class="breadcrumb-item active">{{ $pageTitle }}</li>
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
                            <h5 class="card-title mb-0">{{ $pageTitle }}</h5>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="blogDataTable" class="table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // TinyMCE Initialization
            if (typeof tinymce !== 'undefined') {
                tinymce.init({
                    selector: '#edit_content_editor',
                    height: 350,
                    menubar: true,
                    plugins: 'lists link image help wordcount code media table',
                    toolbar: 'code | formatselect fontsizeselect | numlist bullist | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | table | link image media',
                    setup: function (editor) {
                        editor.on('change', function () {
                            document.getElementById('editContent').value = editor.getContent();
                        });
                    }
                });
            }

            // DataTable Initialization
            var dataTable = $('#blogDataTable').DataTable({
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
                    searchPlaceholder: "Search Blogs...",
                    processing: processing,
                    emptyTable: emptyTable,
                    paginate: {
                        next: '<i class="ri-arrow-right-s-line"></i>',
                        previous: '<i class="ri-arrow-left-s-line"></i>'
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center' },
                    { data: 'image', name: 'featured_image', title: 'Image', class: 'text-center', orderable: false, searchable: false },
                    { data: 'title', name: 'title', title: 'Blog Title', class: 'text-start' },
                    { data: 'published_at', name: 'published_at', title: 'Published Date', class: 'text-center' },
                    { data: 'action', name: 'action', title: 'Action', class: 'text-center', orderable: false, searchable: false }
                ],
                ajax: {
                    url: '{{ route("admin.blogs.list") }}',
                    type: "GET",
                    dataType: "JSON",
                    data: function (d) {
                        d.category = "{{ $category }}";
                        d._token = "{{ csrf_token() }}";
                    },
                    error: function (xhr) {
                        if (typeof actionError === 'function') {
                            actionError(xhr);
                        }
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

            window.blogDataTable = dataTable;

            // jQuery Form Validation & AJAX Submission for Edit Blog
            $("#editBlogForm").validate({
                rules: {
                    title: { required: true, maxlength: 255 },
                    category: { required: true },
                    content: { required: true }
                },
                messages: {
                    title: {
                        required: "The blog title field is required.",
                        maxlength: "Title cannot exceed 255 characters."
                    },
                    category: { required: "Please select a blog category." },
                    content: { required: "The blog content field is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    if (typeof tinymce !== 'undefined' && tinymce.get('edit_content_editor')) {
                        $('#editContent').val(tinymce.get('edit_content_editor').getContent());
                    }

                    $('.error').html('').hide();

                    $.ajax({
                        url: "{{ route('admin.blogs.update') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnUpdateBlog').attr('disabled', true);
                            $("#editBlogSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then(() => {
                                $('#editBlogModal').modal('hide');
                                window.blogDataTable.ajax.reload(null, false);
                            });
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnUpdateBlog').attr('disabled', false);
                            $("#editBlogSpinner").addClass('d-none');
                        }
                    });
                }
            });
        });

        // Edit Blog Function
        function editBlog(id) {
            var editUrl = "{{ route('admin.blogs.edit', ':id') }}";
            $('.error').html('').hide();

            $.ajax({
                url: editUrl.replace(':id', id),
                type: 'GET',
                dataType: 'json',
                success: function (response) {
                    if (response && response.data) {
                        var blog = response.data;
                        $('#editBlogId').val(blog.id);
                        $('#editTitle').val(blog.title);
                        var cat = blog.category ? blog.category.toLowerCase() : 'general';
                        if (cat.includes('import')) {
                            $('#editCategory').val('import_regulation');
                        } else {
                            $('#editCategory').val('general');
                        }
                        $('#editDescription').val(blog.description || '');
                        $('#editContent').val(blog.content || '');

                        if (typeof tinymce !== 'undefined' && tinymce.get('edit_content_editor')) {
                            tinymce.get('edit_content_editor').setContent(blog.content || '');
                        }

                        if (blog.published_at) {
                            var pubDate = new Date(blog.published_at);
                            var formattedDate = pubDate.toISOString().slice(0, 16);
                            $('#editPublishedAt').val(formattedDate);
                        } else {
                            $('#editPublishedAt').val('');
                        }

                        if (blog.featured_image) {
                            $('#editBlogImagePreview').attr('src', blog.featured_image).show();
                            $('#editBlogNoImage').hide();
                        } else {
                            $('#editBlogImagePreview').attr('src', '').hide();
                            $('#editBlogNoImage').show();
                        }

                        $('#editBlogModal').modal('show');
                    }
                },
                error: function (xhr) {
                    if (typeof actionError === 'function') {
                        actionError(xhr);
                    }
                }
            });
        }

        // Delete Blog Function
        function removeBlog(id, element) {
            var originalHtml = $(element).html();
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to delete this blog post?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: false,
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('admin.blogs.delete') }}",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": "{{ csrf_token() }}"
                        },
                        beforeSend: function () {
                            $(element).attr('disabled', true);
                            $(element).html('<i class="spinner-border spinner-border-sm text-danger"></i>');
                        },
                        success: function (data) {
                            sendSuccess(data.message).then(() => {
                                window.blogDataTable.ajax.reload(null, false);
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
                            $(element).attr('disabled', false);
                            $(element).html(originalHtml);
                        }
                    });
                }
            });
        }
    </script>
@endsection
