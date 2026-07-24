@extends('admin.master')
@section('title', 'Slider')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Create Slider</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.slider.index') }}">Slider Management</a></li>
                        <li class="breadcrumb-item active">Create Slider</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="addForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ admin_label('slider_form', 'title', 'Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title" placeholder="Enter title">
                            <label id="title-error" class="text-danger error" for="title" style="display: none"></label>
                        </div>

                        <!-- Image Upload -->
                        <div class="mb-3">
                            <label for="image"
                                class="form-label">{{ admin_label('slider_form', 'slider_image', 'Slider Image') }}</label>
                            <input type="file" class="filepond" id="image" name="image" data-allow-reorder="true">
                            <label id="image-error" class="text-danger error" for="image" style="display: none"></label>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description"
                                class="form-label">{{ admin_label('slider_form', 'description', 'Description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="5"
                                placeholder="Enter description"></textarea>
                            <label id="description-error" class="text-danger error" for="description"
                                style="display: none"></label>
                        </div>

                        <!-- Link/Href -->
                        <div class="mb-3">
                            <label for="link" class="form-label">{{ admin_label('slider_form', 'link', 'Link') }}</label>
                            <input type="text" class="form-control" id="link" name="link"
                                placeholder="Enter link (e.g., https://example.com)">
                            <label id="link-error" class="text-danger error" for="link" style="display: none"></label>
                        </div>

                        <!-- Button Text -->
                        <div class="mb-3">
                            <label for="button_text"
                                class="form-label">{{ admin_label('slider_form', 'button_text', 'Button Text') }}</label>
                            <input type="text" class="form-control" id="button_text" name="button_text"
                                placeholder="Enter button text (e.g., Shop Now)">
                            <label id="button_text-error" class="text-danger error" for="button_text"
                                style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        function initFilepond() {
            FilePond.registerPlugin(
                FilePondPluginFileEncode,
                FilePondPluginFileValidateSize,
                FilePondPluginImageExifOrientation,
                FilePondPluginImagePreview,
                FilePondPluginFileValidateType
            );

            const inputElement = document.querySelector('input.filepond');
            if (inputElement) {
                FilePond.create(inputElement, {

                });
            }
        }

        $(document).ready(function () {
            initFilepond();

            $("#addForm").validate({
                rules: {
                    title: { required: false },
                    image: { required: true },
                    description: { required: false },
                    link: {
                        required: false,
                        url: true
                    },
                    button_text: { required: false }
                },
                messages: {
                    title: { required: "The title field is required." },
                    image: { required: "Please upload an image for the slider." },
                    description: { required: "The description field is required." },
                    link: {
                        required: "The link field is required.",
                        url: "Please enter a valid URL (e.g., https://example.com)"
                    },
                    button_text: { required: "The button text field is required." }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('admin.slider.store') }}", // Update with your route
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('button[type="submit"]').attr('disabled', true);
                            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...');
                        },
                        success: function (result) {
                            // Replace with your success handling
                            sendSuccess(result.message || 'Slider created successfully!');
                            // Reset form
                            form.reset();
                            // Reset filepond
                            const pond = FilePond.find(document.querySelector('input.filepond'));
                            if (pond) {
                                pond.removeFiles();
                            }
                            // Redirect if needed
                            window.location.href = "{{route('admin.slider.index')}}";
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + key + "-error").html(errorMessage).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Submit');
                        }
                    });
                }
            });
        });
    </script>
@endsection