@extends('admin.master')
@section('title', 'Create Car')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Create Car</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Car</a></li>
                        <li class="breadcrumb-item active">Create Car</li>
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

                        <!-- Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Car Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter car name">
                            <label id="name-error" class="text-danger error" for="name" style="display: none"></label>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <label id="category_id-error" class="text-danger error" for="category_id"
                                style="display: none"></label>
                        </div>


                        <!-- Vehicle ID -->
                        <div class="mb-3">
                            <label for="vehicle_id" class="form-label">Vehicle ID</label>
                            <input type="text" class="form-control" id="vehicle_id" name="vehicle_id"
                                placeholder="Auto generated">
                            <label id="vehicle_id-error" class="text-danger error" style="display: none"></label>

                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" name="status">
                                @foreach(\App\Enum\VehicleStatus::cases() as $status)
                                    <option value="{{ $status->value }}">
                                        {{ $status->label() }}
                                    </option>
                                @endforeach
                            </select>
                            <label id="status-error" class="text-danger error" style="display: none"></label>

                        </div>
                        <div class="col-12">
                            <label class="form-label">Auction Grade</label>
                            <select class="form-select select2" name="auction_grade_id">
                                <option value="">Select Auction Grade</option>
                                @foreach($auctionGrades as $grade)
                                    <option value="{{ $grade->id }}">
                                        {{ $grade->grade }} {{ $grade->remarks }}
                                    </option>
                                @endforeach
                            </select>
                            <label id="auction_grade_id-error" class="text-danger error" style="display: none"></label>

                        </div>
                        <!-- Recommended -->
                        <div class=" mb-3">
                            <label class="form-label">Recommended Car</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_recommended" name="is_recommended"
                                    value="1">
                                <label class="form-check-label" for="is_recommended">Mark as recommended</label>
                            </div>
                        </div>

                        <!-- Banner -->
                        <div class="col-md-12 mb-2">
                            <label for="banner" class="form-label">Banner</label>
                            <label for="banner" class="custom-file-label w-100">
                                <input type="file" id="banner" class="form-control file-preview" name="banner"
                                    accept="image/*">
                                <label id="banner-error" class="text-danger error" style="display: none"></label>
                                <div class="uploaded-preview mt-2" style="width: 240px;"></div>
                            </label>
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">Car Images</label>
                            <input type="file" class="filepond" id="images" name="images[]" multiple
                                data-allow-reorder="true">
                            <label id="images-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description_editor" rows="5"></textarea>
                            <input type="hidden" id="description" name="description">
                            <label id="description-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- ================= Technical Specifications ================= -->
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Technical Specifications</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Make</label>
                                        <input type="text" name="make" class="form-control" placeholder="e.g. Toyota">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Exterior Color</label>
                                        <input type="text" name="exterior_color" class="form-control"
                                            placeholder="e.g. Pearl White">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Body Type</label>
                                        <input type="text" name="body_type" class="form-control" placeholder="e.g. SUV">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Fuel Type</label>
                                        <input type="text" name="fuel_type" class="form-control" placeholder="e.g. Hybrid">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Engine</label>
                                        <input type="text" name="engine" class="form-control" placeholder="e.g. 2.0L Turbo">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Odometer (km)</label>
                                        <input type="number" name="odometer" class="form-control" placeholder="e.g. 50000">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Model Year</label>
                                        <input type="number" name="model_year" class="form-control" placeholder="e.g. 2022">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Interior Color</label>
                                        <input type="text" name="interior_color" class="form-control"
                                            placeholder="e.g. Black Leather">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Transmission</label>
                                        <input type="text" name="transmission" class="form-control"
                                            placeholder="e.g. Automatic / Manual">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ========================================================== -->

                        <!-- ================= Card Settings Section ================= -->
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Card Settings (Frontend Display)</h5>
                            </div>
                            <div class="card-body">

                                <!-- Card Header -->
                                <div class="mb-3">
                                    <label for="card_header" class="form-label">Card Header</label>
                                    <input type="text" class="form-control" id="card_header" name="card_header"
                                        placeholder="Enter card title (Frontend)">
                                    <label id="card_header-error" class="text-danger error" style="display: none"></label>
                                </div>

                                <!-- Card Subtitle -->
                                <div class="mb-3">
                                    <label for="card_subtitle" class="form-label">Card Subtitle</label>
                                    <input type="text" class="form-control" id="card_subtitle" name="card_subtitle"
                                        placeholder="Enter card subtitle (Frontend)">
                                    <label id="card_subtitle-error" class="text-danger error" style="display: none"></label>
                                </div>

                            </div>
                        </div>
                        <!-- ========================================================== -->

                        <button type="submit" class="btn btn-primary">Submit</button>

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
                    allowMultiple: true,
                    maxFiles: 60, // Increased to 60 images
                });
            }
        }

        $(document).ready(function () {
            initFilepond();



            $("#addForm").validate({
                rules: {
                    name: { required: true },
                    category_id: { required: true },

                    'images[]': { required: true },
                    description: { required: true },
                    number_plate: { required: true },
                    card_header: { required: true },
                    card_subtitle: { required: true },
                    banner: { required: false },

                    vehicle_id: { required: true },
                    status: { required: true },
                    auction_grade_id: { required: true },
                },
                messages: {
                    name: { required: "The car name is required." },
                    category_id: { required: "Please select a category." },

                    'images[]': { required: "Please upload at least one image." },
                    description: { required: "Description is required." },
                    number_plate: { required: "The number plate  is required." },
                    card_header: { required: "Card header is required." },
                    card_subtitle: { required: "Card subtitle is required." },
                    vehicle_id: { required: "The vehical id is required." },
                    status: { required: "Please select a status." },
                    auction_grade_id: { required: "Please select an auction grade." },

                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('admin.car.store') }}", // Update with your route
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
                            sendSuccess(result.message || 'Car created successfully!');
                            form.reset();
                            const pond = FilePond.find(document.querySelector('input.filepond'));
                            if (pond) {
                                pond.removeFiles();
                            }
                            // Reset TinyMCE editors
                            tinymce.get('description_editor').setContent('');
                            window.location.href = "{{route('admin.car.index')}}";
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('errors')) {
                                $.each(data.errors, function (key, value) {
                                    let errorKey = key.includes('.') ? key.split('.')[0] : key;
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + errorKey + "-error").html(errorMessage).show();
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                sendError(data.message);
                            } else {
                                sendError("An error occurred. Please check file sizes or try again.");
                            }
                        },
                        complete: function () {
                            $('button[type="submit"]').attr('disabled', false);
                            $('button[type="submit"]').html('Create Car');
                        }
                    });
                }
            });
        });
    </script>
@endsection