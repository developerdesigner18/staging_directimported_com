@extends('admin.master')
@section('title','Gallery')
@push('offcanvas')
    <!-- Add Gallery Canvas -->
    <!-- Add Gallery Canvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="galleryCanvas" aria-labelledby="galleryCanvasLabel"
         style="width: 50%;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="galleryCanvasLabel">Add New Gallery</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="addGalleryForm">
                @csrf
                <div class="text-center mb-4">
                    <div class="gallery-image-container mx-auto mb-3"
                         style="width: 300px; height: 300px; border: 2px dashed #dee2e6; border-radius: 8px; overflow: hidden; position: relative; cursor: pointer;"
                         onclick="document.getElementById('galleryImageInput').click()">
                        <img id="galleryImagePreview" src="{{ asset('assets/images/placeholder.jpg') }}"
                             alt="Gallery Image Preview"
                             class="img-fluid h-100 w-100 object-fit-cover" style="display: none;">
                        <div id="galleryImagePlaceholder"
                             class="h-100 d-flex flex-column justify-content-center align-items-center">
                            <i class="ri-image-line" style="font-size: 48px; color: #adb5bd;"></i>
                            <span class="mt-2 text-muted">Click to upload image</span>
                        </div>
                        <input type="file" id="galleryImageInput" name="image" class="d-none" accept="image/*">
                    </div>
                    <div class="form-text">Recommended size: 800x800px (1:1 ratio)</div>
                    <div id="galleryImageError" class="invalid-feedback d-block"></div>
                </div>
                <div class="mb-3">
                    <label for="galleryTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="galleryTitle" name="title" required>
                    <div id="galleryTitleError" class="invalid-feedback"></div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Gallery</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Gallery Canvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="editGalleryCanvas" aria-labelledby="editGalleryCanvasLabel"
         style="width: 50%;">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="editGalleryCanvasLabel">Edit Gallery</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form id="editGalleryForm">
                @csrf
                <input type="hidden" id="editGalleryId" name="id">
                <div class="text-center mb-4">
                    <div class="gallery-image-container mx-auto mb-3"
                         style="width: 300px; height: 300px; border: 2px dashed #dee2e6; border-radius: 8px; overflow: hidden; position: relative;">
                        <img id="editGalleryImagePreview" src="" alt="Gallery Image Preview"
                             class="img-fluid h-100 w-100 object-fit-cover">
                        <div class="position-absolute top-0 end-0 m-2">
                            <button type="button" class="btn btn-sm btn-light"
                                    onclick="document.getElementById('editGalleryImageInput').click()">
                                <i class="ri-edit-2-line"></i>
                            </button>
                        </div>
                        <input type="file" id="editGalleryImageInput" name="image" class="d-none" accept="image/*">
                    </div>
                    <div class="form-text">Recommended size: 800x800px (1:1 ratio)</div>
                    <div id="editGalleryImageError" class="invalid-feedback d-block"></div>
                </div>
                <div class="mb-3">
                    <label for="editGalleryTitle" class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="editGalleryTitle" name="title" required>
                    <div id="editGalleryTitleError" class="invalid-feedback"></div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-light" data-bs-dismiss="offcanvas">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Gallery</button>
                </div>
            </form>
        </div>
    </div>
@endpush
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Gallery Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Gallery Management</a></li>
                        <li class="breadcrumb-item active">Gallery List</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#galleryCanvas"
                        aria-controls="galleryCanvas">Add New
                </button>
            </div>
        </div>
        <div class="col-sm">
            <form method="GET" action="{{ route('admin.gallery.index') }}">
                <div class="d-flex justify-content-sm-end gap-2">
                    <div class="search-box ms-2">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                               value="{{ request('search') }}">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($galleries as $slider)
            <div class="col-xxl-3 col-lg-6" id="slider-card-{{$slider->id}}">
                <div class="card overflow-hidden blog-grid-card">
                    <div class="position-relative overflow-hidden" style="height: 300px; background-color: #f8f9fa;">
                        <img src="{{ $slider->image }}" alt="{{ $slider->title }}"
                             class="img-fluid h-100 w-100 object-fit-cover">
                        <div class="position-absolute top-0 end-0 p-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light rounded-circle" type="button"
                                        data-bs-toggle="dropdown"
                                        aria-expanded="false"
                                        style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="javascript:void(0)"
                                           onclick="editGallery({{$slider->id}},this)">
                                            <i class="ri-edit-line me-2"></i>Edit
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="javascript:void(0)"
                                           onclick="deleteGallery('{{$slider->id}}',this)">
                                            <i class="ri-delete-bin-line me-2"></i>Delete
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $slider->title }}</h5>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h4 class="text-muted">No gallery found</h4>
                        <p class="text-muted">Create your first gallery by clicking the "Add New" button</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    {{$galleries->withQueryString()->links('admin.partials.pagination')}}
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            const galleryImageInput = document.getElementById('galleryImageInput');
            const galleryImagePreview = document.getElementById('galleryImagePreview');

            galleryImagePreview.addEventListener('click', () => galleryImageInput.click());

            // Show selected image preview
            galleryImageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        galleryImagePreview.src = e.target.result;
                        galleryImagePreview.style.display = 'block';
                        galleryImagePlaceholder.style.display = 'none';
                        $('#galleryImageError').text('').hide();
                    }
                    reader.readAsDataURL(file);
                }
            });

            // Image preview for edit gallery
            const editGalleryImageInput = document.getElementById('editGalleryImageInput');
            const editGalleryImagePreview = document.getElementById('editGalleryImagePreview');
            editGalleryImagePreview.addEventListener('click', () => editGalleryImageInput.click());

            editGalleryImageInput.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function (e) {
                        editGalleryImagePreview.src = e.target.result;
                        $('#editGalleryImageError').text('').hide();
                    }
                    reader.readAsDataURL(file);
                }
            });



            // Add Gallery Form Validation and Submission
            $("#addGalleryForm").validate({
                rules: {
                    title: {required: true},
                    image: {
                        required: true,
                    }
                },
                messages: {
                    title: {required: "The title field is required."},
                    image: {
                        required: "Please upload an image for the gallery.",
                    }
                },
                errorClass: 'is-invalid',
                errorElement: 'div',
                errorPlacement: function (error, element) {
                    const errorId = element.attr('id') + 'Error';
                    $('#' + errorId).html(error.text()).show();
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function (form,e) {
                    const formData = new FormData(form);
                    $.ajax({
                        url: "{{ route('admin.gallery.store') }}",
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('button[type="submit"]', form).prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
                            );
                        },
                        success: function (response) {
                            sendSuccess(response.message || 'Gallery added successfully!');
                            $('#galleryCanvas').offcanvas('hide');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + key + "Error").html(errorMessage).show();
                                    $(`[name="${key}"]`).addClass('is-invalid');
                                });

                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]', form).prop('disabled', false).html('Save Gallery');
                        }
                    });
                }
            });

            // Edit Gallery Form Validation and Submission
            $("#editGalleryForm").validate({
                rules: {
                    title: {required: true},
                    image: {
                        required: false
                    }
                },
                messages: {
                    title: {required: "The title field is required."},
                    image: {
                        required: false
                    }
                },
                errorClass: 'is-invalid',
                errorElement: 'div',
                errorPlacement: function (error, element) {
                    const errorId = 'edit' + element.attr('id').replace('edit', '') + 'Error';
                    $('#' + errorId).html(error.text()).show();
                },
                highlight: function (element) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element) {
                    $(element).removeClass('is-invalid');
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    const formData = new FormData(form);
                    formData.append('galleryId', $('#editGalleryId').val());

                    $.ajax({
                        url: `{{ route('admin.gallery.update') }}`,
                        method: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('button[type="submit"]', form).prop('disabled', true).html(
                                '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...'
                            );
                        },
                        success: function (response) {
                            sendSuccess(response.message || 'Gallery updated successfully!');
                            $('#editGalleryCanvas').offcanvas('hide');
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    const errorField = $(`#edit${key.charAt(0).toUpperCase() + key.slice(1)}Error`);
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    if (errorField.length) {
                                        errorField.text(errorMessage).show();
                                        $(`#editGallery${key.charAt(0).toUpperCase() + key.slice(1)}`).addClass('is-invalid');
                                    } else {
                                        actionError(xhr, errorMessage);
                                    }
                                });

                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]', form).prop('disabled', false).html('Update Gallery');
                        }
                    });
                }
            });
        });

        // Function to edit gallery
        function editGallery(id, element) {
            // Show loading state
            $(element).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            // Fetch gallery data
            $.ajax({
                url: `{{ route('admin.gallery.edit') }}`,
                method: 'GET',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function (response) {
                    // Populate form fields
                    $('#editGalleryId').val(response.data.id);
                    $('#editGalleryTitle').val(response.data.title);
                    $('#editGalleryImagePreview').attr('src', response.data.image);

                    // Show the edit canvas
                    const editCanvas = new bootstrap.Offcanvas(document.getElementById('editGalleryCanvas'));
                    editCanvas.show();
                },
                error: function (xhr) {
                    sendError('Failed to load gallery data. Please try again.');
                },
                complete: function () {
                    $(element).prop('disabled', false).html('Edit');
                }
            });
        }

        // Function to delete gallery
        function deleteGallery(id, element) {
            if (!confirm('Are you sure you want to delete this gallery item?')) {
                return;
            }

            // Show loading state
            const originalText = $(element).html();
            $(element).prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');

            // Send delete request
            $.ajax({
                url: `{{ route('admin.gallery.delete') }}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
                    sendSuccess(response.message || 'Gallery item deleted successfully!');
                    $(`#slider-card-${id}`).fadeOut(300, function () {
                        $(this).remove();
                    });
                },
                error: function (xhr) {
                    const errorMessage = xhr.responseJSON?.message || 'Failed to delete gallery item. Please try again.';
                    sendError(errorMessage);
                },
                complete: function () {
                    $(element).prop('disabled', false).html(originalText);
                }
            });
        }
    </script>
@endsection
