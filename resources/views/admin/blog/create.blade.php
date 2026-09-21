@extends('admin.master')
@section('title', $pageTitle)

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
                
                <div class="card-body">
                    <form id="addBlogForm" enctype="multipart/form-data">
                        @csrf
                        

                        <!-- Blog Title -->
                        <div class="mb-3">
                            <label for="addTitle" class="form-label fw-bold">Blog Title <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="addTitle" name="title" placeholder="Enter blog title">
                            <label id="title-error" class="text-danger error" style="display: none"></label>
                        </div>
                        <!-- Featured Image Upload (WEBP Only) -->
                        <div class="mb-3">
                            <label for="addFeaturedImage" class="form-label fw-bold">Featured Image <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="addFeaturedImage" name="featured_image" accept=".webp">
                            <small class="text-muted d-block mt-1">Only <strong>.webp</strong> images are allowed (max 2MB).</small>
                            <label id="featured_image-error" class="text-danger error" style="display: none"></label>

                            <div class="p-2 border rounded bg-light text-center mt-2" style="max-width: 300px;">
                                <img id="addBlogImagePreview" src="" alt="Blog Featured Image Preview" class="img-fluid rounded shadow-sm" style="max-height: 200px; object-fit: cover; display: none;">
                                <div id="addBlogNoImage" class="text-muted py-3">
                                    <i class="ri-image-line fs-24 d-block mb-1"></i>
                                    <span>No Image Selected</span>
                                </div>
                            </div>
                        </div>
                        <!-- Category (Strictly Existing Categories Only) -->
                        <div class="mb-3">
                            <label for="addCategory" class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select class="form-select" id="addCategory" name="category">
                                <option value="">Select Category</option>
                                <option value="general">General Blogs Management</option>
                                <option value="import_regulation">Import Regulation Blogs Management</option>
                            </select>
                            <label id="category-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Short Description / Excerpt -->
                        <div class="mb-3">
                            <label for="addDescription" class="form-label fw-bold">Short Description / Excerpt</label>
                            <textarea class="form-control" id="addDescription" name="description" rows="3" placeholder="Enter short description"></textarea>
                            <label id="description-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Full Content (TinyMCE) -->
                        <div class="mb-3">
                            <label for="addContent" class="form-label fw-bold">Content <span class="text-danger">*</span></label>
                            <textarea id="addContent" name="content" style="display:none;"></textarea>
                            <textarea class="tinymce_editor" id="add_content_editor" rows="10"></textarea>
                            <label id="content-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Published Date -->
                        <div class="mb-3">
                            <label for="addPublishedAt" class="form-label fw-bold">Published Date</label>
                            <input type="datetime-local" class="form-control" id="addPublishedAt" name="published_at">
                            <label id="published_at-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="{{ route('admin.blogs.general') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary" id="btnAddBlog">
                                <span class="d-flex align-items-center">
                                    <span class="d-none spinner-border spinner-border-sm me-2" id="addBlogSpinner" role="status"></span>
                                    <span>Create Custom Blog</span>
                                </span>
                            </button>
                        </div>
                    </form>
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
                    selector: '#add_content_editor',
                    height: 350,
                    menubar: true,
                    plugins: 'lists link image help wordcount code media table',
                    toolbar: 'code | formatselect fontsizeselect | numlist bullist | bold italic | forecolor backcolor | alignleft aligncenter alignright alignjustify | table | link image media',
                    setup: function (editor) {
                        editor.on('change', function () {
                            document.getElementById('addContent').value = editor.getContent();
                        });
                    }
                });
            }

            // Image input client-side webp validation & preview
            $('#addFeaturedImage').on('change', function () {
                var file = this.files[0];
                if (file) {
                    var extension = file.name.split('.').pop().toLowerCase();
                    if (extension !== 'webp') {
                        $('#featured_image-error').html('Only .webp images are allowed.').show();
                        $(this).val('');
                        $('#addBlogImagePreview').attr('src', '').hide();
                        $('#addBlogNoImage').show();
                    } else if (file.size > 2 * 1024 * 1024) {
                        $('#featured_image-error').html('Image size must not exceed 2MB.').show();
                        $(this).val('');
                        $('#addBlogImagePreview').attr('src', '').hide();
                        $('#addBlogNoImage').show();
                    } else {
                        $('#featured_image-error').html('').hide();
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            $('#addBlogImagePreview').attr('src', e.target.result).show();
                            $('#addBlogNoImage').hide();
                        };
                        reader.readAsDataURL(file);
                    }
                }
            });

            // jQuery Form Validation & AJAX Submission for Add Blog
            $("#addBlogForm").validate({
                rules: {
                    title: { required: true, maxlength: 255 },
                    category: { required: true },
                    content: { required: true },
                    featured_image: { required: true }
                },
                messages: {
                    title: {
                        required: "The blog title field is required.",
                        maxlength: "Title cannot exceed 255 characters."
                    },
                    category: { required: "Please select a blog category." },
                    content: { required: "The blog content field is required." },
                    featured_image: { required: "The blog featured image is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    if (element.attr("name") == "featured_image") {
                        $("#featured_image-error").html(error.text()).show();
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    if (typeof tinymce !== 'undefined' && tinymce.get('add_content_editor')) {
                        $('#addContent').val(tinymce.get('add_content_editor').getContent());
                    }

                    // Strict webp extension and size check on submit
                    var fileInput = $('#addFeaturedImage')[0];
                    if (fileInput.files.length === 0) {
                        $('#featured_image-error').html('The blog featured image is required.').show();
                        return false;
                    } else {
                        var file = fileInput.files[0];
                        var ext = file.name.split('.').pop().toLowerCase();
                        if (ext !== 'webp') {
                            $('#featured_image-error').html('Only .webp images are allowed.').show();
                            return false;
                        }
                        if (file.size > 2 * 1024 * 1024) {
                            $('#featured_image-error').html('Image size must not exceed 2MB.').show();
                            return false;
                        }
                    }

                    $('.error').html('').hide();

                    $.ajax({
                        url: "{{ route('admin.blogs.store') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnAddBlog').attr('disabled', true);
                            $("#addBlogSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then(() => {
                                var selectedCat = $('#addCategory').val();
                                if (selectedCat === 'import_regulation') {
                                    window.location.href = "{{ route('admin.blogs.import_regulation') }}";
                                } else {
                                    window.location.href = "{{ route('admin.blogs.general') }}";
                                }
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
                            $('#btnAddBlog').attr('disabled', false);
                            $("#addBlogSpinner").addClass('d-none');
                        }
                    });
                }
            });
        });
    </script>
@endsection
