@extends('admin.master')
@section('title','Car Configuration')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Car Configuration</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">System</a></li>
                        <li class="breadcrumb-item active">Car Configuration</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="carConfigForm" method="POST" action="{{ route('admin.car.configuration.update') }}">
                        @csrf
                        <h5 class="mb-3">Car Information Configuration</h5>

                        <!-- Rate Details -->
                        <div class="mb-3">
                            <label class="form-label">Rate Details</label>
                            <textarea id="rate_details" name="rate_details" class="form-control d-none">
                                {{ $configuration->firstWhere('key', 'rate_details')->description ?? '' }}
                            </textarea>
                            <div id="rate_details_editor" class="tinymce-editor">
                                {!! $configuration->firstWhere('key', 'rate_details')->description ?? '' !!}
                            </div>
                            <label id="rate_details-error" class="text-danger error" for="rate_details" style="display: none"></label>
                        </div>

                        <!-- What to Expect -->
                        <div class="mb-3">
                            <label class="form-label">What to Expect</label>
                            <textarea id="what_to_expect" name="what_to_expect" class="form-control d-none">
                                {{ $configuration->firstWhere('key', 'what_to_expect')->description ?? '' }}
                            </textarea>
                            <div id="what_to_expect_editor" class="tinymce-editor">
                                {!! $configuration->firstWhere('key', 'what_to_expect')->description ?? '' !!}
                            </div>
                            <label id="what_to_expect-error" class="text-danger error" for="what_to_expect" style="display: none"></label>
                        </div>

                        <!-- What's Included -->
                        <div class="mb-3">
                            <label class="form-label">What's Included</label>
                            <textarea id="what_include" name="what_include" class="form-control d-none">
                                {{ $configuration->firstWhere('key', 'what_include')->description ?? '' }}
                            </textarea>
                            <div id="what_include_editor" class="tinymce-editor">
                                {!! $configuration->firstWhere('key', 'what_include')->description ?? '' !!}
                            </div>
                            <label id="what_include-error" class="text-danger error" for="what_include" style="display: none"></label>
                        </div>

                        <!-- Requirements -->
                        <div class="mb-3">
                            <label class="form-label">Requirements</label>
                            <textarea id="requirements" name="requirements" class="form-control d-none">
                                {{ $configuration->firstWhere('key', 'requirements')->description ?? '' }}
                            </textarea>
                            <div id="requirements_editor" class="tinymce-editor">
                                {!! $configuration->firstWhere('key', 'requirements')->description ?? '' !!}
                            </div>
                            <label id="requirements-error" class="text-danger error" for="requirements" style="display: none"></label>
                        </div>

                        <!-- Useful Links -->
                        <div class="mb-3">
                            <label class="form-label">Useful Links</label>
                            <textarea id="useful_links" name="useful_links" class="form-control d-none">
                                {{ $configuration->firstWhere('key', 'useful_links')->description ?? '' }}
                            </textarea>
                            <div id="useful_links_editor" class="tinymce-editor">
                                {!! $configuration->firstWhere('key', 'useful_links')->description ?? '' !!}
                            </div>
                            <label id="useful_links-error" class="text-danger error" for="useful_links" style="display: none"></label>
                        </div>

                        <button type="submit" class="btn btn-primary">Update Configuration</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            // Initialize TinyMCE for all editor fields
            tinymce.init({
                selector: '#rate_details_editor, #what_to_expect_editor, #what_include_editor, #requirements_editor, #useful_links_editor',
                height: 300,
                menubar: true,
                plugins: 'lists link image help wordcount code media table',
                toolbar: 'code | formatselect fontsizeselect | insertfile a11ycheck | numlist bullist | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image media tinydrive | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
                file_picker_types: 'file image media',
                images_upload_url: "{{route('admin.tinymce.image.upload')}}",
                images_upload_handler: function (blobInfo, success, failure) {
                    uploadFilePond(blobInfo.blob(), 'image').then(function (url) {
                        console.log('niho!', url);
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
                    console.log('media', data)
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
                    editor.on('init', function() {
                        // This ensures the content is properly loaded when the editor initializes
                        var textareaId = editor.id.replace('_editor', '');
                        editor.setContent(document.getElementById(textareaId).value);
                    });
                    editor.on('change', function () {
                        // Update the corresponding hidden textarea when editor content changes
                        var textareaId = editor.id.replace('_editor', '');
                        document.getElementById(textareaId).value = editor.getContent();
                    });
                }
            });

            // Rest of your validation and form submission code...
            $("#carConfigForm").validate({
                rules: {
                    rate_details: {required: true},
                    what_to_expect: {required: true},
                    what_include: {required: true},
                    requirements: {required: true},
                    useful_links: {required: true}
                },
                messages: {
                    rate_details: {required: "The rate details field is required."},
                    what_to_expect: {required: "The what to expect field is required."},
                    what_include: {required: "The what's included field is required."},
                    requirements: {required: "The requirements field is required."},
                    useful_links: {required: "The useful links field is required."}
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    // Update all hidden textareas with TinyMCE content before submission
                    var editorIds = ['rate_details_editor', 'what_to_expect_editor', 'what_include_editor', 'requirements_editor', 'useful_links_editor'];

                    editorIds.forEach(function(editorId) {
                        var editor = tinymce.get(editorId);
                        if (editor) {
                            var textareaId = editorId.replace('_editor', '');
                            document.getElementById(textareaId).value = editor.getContent();
                        }
                    });

                    $.ajax({
                        url: $(form).attr('action'),
                        method: "POST",
                        dataType: "json",
                        data: $(form).serialize(),
                        beforeSend: function () {
                            $('button[type="submit"]').attr('disabled', true);
                            $('button[type="submit"]').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
                        },
                        success: function (result) {
                            if (result.success) {
                                sendToast(result.message || 'Car configuration updated successfully!');
                            } else {
                                sendToast(result.message || 'An error occurred.', 'danger');
                            }
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('errors')) {
                                $.each(data.errors, function (key, value) {
                                    $("#" + key + "-error").html(value[0]).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                sendToast(data.message, 'danger');
                            } else {
                                sendToast("An error occurred. Please try again.", 'danger');
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Update Configuration');
                        }
                    });
                }
            });
        });
    </script>
@endsection
