@extends('admin.master')
@section('title','Edit Service')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="editForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Service Title</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ $service->title }}" placeholder="Enter service title">
                            <label id="title-error" class="text-danger error" style="display:none"></label>
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">Service Images</label>
                            <input type="file" class="filepond" id="images" name="images[]" multiple>

                            @if($service->images)
                                <div id="sortable-images" class="d-flex flex-wrap">
                                    @foreach($service->images as $image)
                                        <div class="image-preview-container me-2 mb-2"
                                             data-image="{{ $image }}"
                                             style="cursor: grab;">

                                            <img src="{{ asset(SERVICE_PATH.$image) }}"
                                                 class="img-thumbnail"
                                                 style="height:100px;"
                                                 draggable="false">

                                            <button type="button"
                                                    class="btn btn-danger btn-sm remove-image"
                                                    data-image="{{ $image }}">×</button>
                                        </div>
                                    @endforeach
                                </div>

                                <input type="hidden" name="image_order" id="image_order">
                            @endif

                            <input type="hidden" id="removed_images" name="removed_images">
                            <label id="images-error" class="text-danger error" style="display:none"></label>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label for="description_editor" class="form-label">Description</label>
                            <textarea class="form-control" id="description_editor" rows="5">{{ $service->description }}</textarea>
                            <input type="hidden" id="description" name="description" value="{{ $service->description }}">
                            <label id="description-error" class="text-danger error" style="display:none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update</button>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        tinymce.init({
            selector: '#description_editor',
            height: 300,
            menubar: true,
            plugins: 'lists link image help wordcount code media table',
            toolbar: 'code | formatselect fontsizeselect | insertfile a11ycheck | numlist bullist | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image media tinydrive | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
            file_picker_types: 'file image media',
            images_upload_url: "{{ route('admin.tinymce.image.upload') }}",
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
                        resolve({html: embedHtml});
                    } else {
                        resolve({html: ''});
                    }
                } else {
                    resolve({html: ''});
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

        function initFilepond() {
            FilePond.registerPlugin(
                FilePondPluginFileEncode,
                FilePondPluginFileValidateSize,
                FilePondPluginImageExifOrientation,
                FilePondPluginImagePreview
            );

            const inputElement = document.querySelector('input.filepond');
            if (inputElement) {
                FilePond.create(inputElement, {
                    allowMultiple: true,
                    maxFiles: 5,
                    acceptedFileTypes: ['image/*']
                });
            }
        }

        $(document).ready(function () {
            const sortableContainer = document.getElementById('sortable-images');

            if (sortableContainer) {
                new Sortable(sortableContainer, {
                    animation: 150,
                    ghostClass: 'sortable-ghost',
                    onEnd: function () {
                        let order = [];
                        document.querySelectorAll('#sortable-images .image-preview-container')
                            .forEach(function (el) {
                                order.push(el.getAttribute('data-image'));
                            });
                        document.getElementById('image_order').value = order.join(',');
                    }
                });
            }
            initFilepond();

            $(document).on('click', '.remove-image', function () {
                const imagePath = $(this).data('image');
                $(this).closest('.image-preview-container').remove();

                let removedImages = $('#removed_images').val();
                removedImages = removedImages ? removedImages.split(',') : [];
                removedImages.push(imagePath);
                $('#removed_images').val(removedImages.join(','));

                let order = [];
                $('#sortable-images .image-preview-container').each(function () {
                    order.push($(this).data('image'));
                });
                $('#image_order').val(order.join(','));
            });

            $("#editForm").validate({
                rules: {
                    title: {required: true},
                    description: {required: true},
                },
                messages: {
                    title: {required: "The service title is required."},
                    description: {required: "Description is required."},
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('admin.service.update', $service->id) }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('button[type="submit"]').attr('disabled', true);
                            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
                        },
                        success: function (result) {
                            sendSuccess(result.message || 'Service updated successfully!');
                            window.location.href = "{{ route('admin.service.index') }}";
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('errors')) {
                                $.each(data.errors, function (key, value) {
                                    $("#" + key + "-error").html(value[0]).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                sendError(data.message);
                            } else {
                                sendError("An error occurred. Please try again.");
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Update');
                        }
                    });
                }
            });
        });
    </script>
@endsection
