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

                        <!-- Make + Model + Year -->
                        <div class="row g-3 mb-4">
                            <!-- Make -->
                            <div class="col-md-4">
                                <label for="manufacturer_id"
                                    class="form-label mb-2">{{ admin_label('car_form', 'make', 'Make') }}</label>
                                <select class="form-select select2" id="manufacturer_id" name="manufacturer_id">
                                    <option value="">Select Make</option>
                                    @foreach($manufacturers ?? [] as $manufacturer)
                                        <option value="{{ optional($manufacturer)->id }}">{{ optional($manufacturer)->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="manufacturer_id-error" class="text-danger error" for="manufacturer_id"
                                    style="display: none"></label>
                            </div>

                            <!-- Model -->
                            <div class="col-md-4">
                                <label for="model"
                                    class="form-label mb-2">{{ admin_label('car_form', 'model', 'Model') }}</label>
                                <input type="text" class="form-control" id="model" name="model"
                                    placeholder="Enter model name">
                                <label id="model-error" class="text-danger error" for="model" style="display: none"></label>
                            </div>

                            <!-- Year -->
                            <div class="col-md-4">
                                <label for="year"
                                    class="form-label mb-2">{{ admin_label('car_form', 'year', 'Year') }}</label>
                                <select class="form-select select2" id="year" name="year">
                                    <option value="">Select Year</option>
                                    @for($y = date('Y') + 1; $y >= 1970; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                                <label id="year-error" class="text-danger error" for="year" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Category + Status + Auction Grade + Location -->
                        <div class="row g-3 mb-4">
                            <!-- Category -->
                            <div class="col-lg-3">
                                <label for="category_id"
                                    class="form-label mb-2">{{ admin_label('car_form', 'category', 'Category') }}</label>
                                <select class="form-select select2" id="category_id" name="category_id">
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <label id="category_id-error" class="text-danger error" for="category_id"
                                    style="display: none"></label>
                            </div>

                            <!-- Status -->
                            <div class="col-lg-3">
                                <label for="status"
                                    class="form-label mb-2">{{ admin_label('car_form', 'status', 'Status') }}</label>
                                <select class="form-select select2" id="status" name="status">
                                    <option value="">Select Status</option>
                                    @foreach(\App\Enum\VehicleStatus::cases() as $status)
                                        <option value="{{ $status->value }}">
                                            {{ $status->label() }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="status-error" class="text-danger error" style="display: none"></label>
                            </div>

                            <!-- Auction Grade -->
                            <div class="col-lg-3">
                                <label for="auction_grade_id"
                                    class="form-label mb-2">{{ admin_label('car_form', 'auction_grade', 'Auction Grade') }}</label>
                                <select class="form-select select2" id="auction_grade_id" name="auction_grade_id">
                                    <option value="">Select Auction Grade</option>
                                    @foreach($auctionGrades ?? [] as $grade)
                                        <option value="{{ $grade->id }}">
                                            {{ $grade->grade }} {{ $grade->remarks }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="auction_grade_id-error" class="text-danger error" style="display: none"></label>
                            </div>

                            <!-- Location -->
                            <div class="col-lg-3">
                                <div class="d-flex align-items-center justify-content-between mb-2"
                                    style="min-height: 21px;">
                                    <label
                                        class="form-label mb-0">{{ admin_label('car_form', 'location', 'Location') }}</label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="form-check form-check-inline mb-0 me-0">
                                            <input class="form-check-input" type="radio" name="location_type"
                                                id="location_type_list" value="list" checked>
                                            <label class="form-check-label small" for="location_type_list">
                                                {{ admin_label('car_form', 'select_from_list', 'Select from List') }}
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline mb-0 me-0">
                                            <input class="form-check-input" type="radio" name="location_type"
                                                id="location_type_manual" value="manual">
                                            <label class="form-check-label small" for="location_type_manual">
                                                {{ admin_label('car_form', 'manual_entry', 'Manual Entry') }}
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dropdown for List selection -->
                                <div id="location_list_container">
                                    <select class="form-select select2" id="location_id" name="location_id">
                                        <option value="">Select Location</option>
                                        @foreach($locations ?? [] as $loc)
                                            <option value="{{ optional($loc)->id }}" {{ old('location_id') == optional($loc)->id ? 'selected' : '' }}>
                                                {{ optional($loc)->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label id="location_id-error" class="text-danger error" style="display: none"></label>
                                </div>

                                <!-- Text Input for Manual Entry -->
                                <div id="location_manual_container" style="display: none;">
                                    <input type="text" class="form-control" id="location_manual" name="location_manual"
                                        value="{{ old('location_manual') }}" placeholder="Enter location">
                                    <label id="location_manual-error" class="text-danger error"
                                        style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <!-- Vehicle ID + Recommended Switch -->
                        <div class="row g-3 mb-4">
                            <!-- Vehicle ID Type + Vehicle ID -->
                            <div class="col-lg-6">
                                <label
                                    class="form-label d-block mb-2">{{ admin_label('car_form', 'vehicle_id_type', 'Vehicle ID Type') }}</label>
                                <div class="d-flex align-items-center gap-4 py-1">
                                    <div class="form-check me-2">
                                        <input class="form-check-input" type="radio" name="vehicle_id_type"
                                            id="vehicle_id_type_auto" value="auto" checked>
                                        <label class="form-check-label" for="vehicle_id_type_auto">
                                            Auto Generate
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="vehicle_id_type"
                                            id="vehicle_id_type_manual" value="manual">
                                        <label class="form-check-label" for="vehicle_id_type_manual">
                                            Manual Entry
                                        </label>
                                    </div>
                                </div>

                                <div id="vehicle_id_container" class="mt-3" style="display: none;">
                                    <label for="vehicle_id"
                                        class="form-label mb-2">{{ admin_label('car_form', 'vehicle_id', 'Vehicle ID / Chassis No') }}</label>
                                    <input type="text" class="form-control" id="vehicle_id" name="vehicle_id"
                                        placeholder="Enter Vehicle ID">
                                    <label id="vehicle_id-error" class="text-danger error" style="display: none"></label>
                                </div>
                            </div>

                            <!-- Recommended Car Toggle -->
                            <div class="col-lg-6">
                                <label
                                    class="form-label d-block mb-2">{{ admin_label('car_form', 'recommended_car', 'Recommended Car') }}</label>
                                <div class="form-check form-switch py-1">
                                    <input type="checkbox" class="form-check-input" id="is_recommended"
                                        name="is_recommended" value="1">
                                    <label class="form-check-label ms-2" for="is_recommended">Mark as
                                        recommended</label>
                                </div>
                            </div>
                        </div>

                        <!-- Banner -->
                        <div class="col-md-12 mb-2">
                            <label for="banner" class="form-label">{{ admin_label('car_form', 'banner', 'Banner') }}</label>
                            <label for="banner" class="custom-file-label w-100">
                                <input type="file" id="banner" class="form-control file-preview" name="banner"
                                    accept="image/*">
                                <label id="banner-error" class="text-danger error" style="display: none"></label>
                                <div class="uploaded-preview mt-2" style="width: 240px;"></div>
                            </label>
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label for="images"
                                class="form-label">{{ admin_label('car_form', 'car_images', 'Car Images') }}</label>
                            <input type="file" class="filepond" id="images" name="images[]" multiple
                                data-allow-reorder="true">
                            <label id="images-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Description -->
                        <div class="mb-3">
                            <label class="form-label">{{ admin_label('car_form', 'description', 'Description') }}</label>
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
                                        <label
                                            class="form-label">{{ admin_label('car_form', 'exterior_color', 'Exterior Color') }}</label>
                                        <input type="text" name="exterior_color" class="form-control"
                                            placeholder="e.g. Pearl White">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label">{{ admin_label('car_form', 'body_type', 'Body Type') }}</label>
                                        <input type="text" name="body_type" class="form-control" placeholder="e.g. SUV">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label">{{ admin_label('car_form', 'fuel_type', 'Fuel Type') }}</label>
                                        <input type="text" name="fuel_type" class="form-control" placeholder="e.g. Hybrid">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ admin_label('car_form', 'engine', 'Engine') }}</label>
                                        <input type="text" name="engine" class="form-control" placeholder="e.g. 2.0L Turbo">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label">{{ admin_label('car_form', 'odometer', 'Odometer (km)') }}</label>
                                        <input type="number" name="odometer" class="form-control" placeholder="e.g. 50000">
                                    </div>



                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label">{{ admin_label('car_form', 'interior_color', 'Interior Color') }}</label>
                                        <input type="text" name="interior_color" class="form-control"
                                            placeholder="e.g. Black Leather">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label
                                            class="form-label">{{ admin_label('car_form', 'transmission', 'Transmission') }}</label>
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
                                    <label for="card_header"
                                        class="form-label">{{ admin_label('car_form', 'card_header', 'Card Header') }}</label>
                                    <input type="text" class="form-control" id="card_header" name="card_header"
                                        placeholder="Enter card title (Frontend)">
                                    <label id="card_header-error" class="text-danger error" style="display: none"></label>
                                </div>

                                <!-- Card Subtitle -->
                                <div class="mb-3">
                                    <label for="card_subtitle"
                                        class="form-label">{{ admin_label('car_form', 'card_subtitle', 'Card Subtitle') }}</label>
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
            $('#manufacturer_id').select2({
                width: '100%',
                placeholder: 'Select Make',
                allowClear: true
            });
            $('#year').select2({
                width: '100%',
                placeholder: 'Select Year',
                allowClear: true
            });
            $('#category_id').select2({
                width: '100%',
                placeholder: 'Select Category',
                allowClear: true
            });
            $('#status').select2({
                width: '100%',
                placeholder: 'Select Status',
                allowClear: true
            });
            $('#auction_grade_id').select2({
                width: '100%',
                placeholder: 'Select Auction Grade',
                allowClear: true
            });
            $('#location_id').select2({
                width: '100%',
                placeholder: 'Select Location',
                allowClear: true
            });
            initFilepond();

            $('input[name="vehicle_id_type"]').on('change', function () {
                if ($(this).val() === 'manual') {
                    $('#vehicle_id_container').slideDown();
                } else {
                    $('#vehicle_id_container').slideUp();
                    $('#vehicle_id').val('');
                    $('#vehicle_id-error').hide();
                }
            });

            $('input[name="location_type"]').on('change', function () {
                if ($(this).val() === 'manual') {
                    $('#location_list_container').hide();
                    $('#location_id').val('').trigger('change');
                    $('#location_manual_container').show();
                    $('#location_id-error').hide();
                } else {
                    $('#location_manual_container').hide();
                    $('#location_manual').val('');
                    $('#location_list_container').show();
                    $('#location_manual-error').hide();
                }
            });

            $("#addForm").validate({
                rules: {

                    manufacturer_id: { required: true },
                    model: { required: true },
                    year: { required: true },
                    category_id: { required: true },
                    vehicle_id: {
                        required: function () {
                            return $('input[name="vehicle_id_type"]:checked').val() === 'manual';
                        }
                    },
                    location_id: {
                        required: function () {
                            return $('input[name="location_type"]:checked').val() === 'list';
                        }
                    },
                    location_manual: {
                        required: function () {
                            return $('input[name="location_type"]:checked').val() === 'manual';
                        }
                    },
                    status: { required: true },
                    auction_grade_id: { required: true },
                    'images[]': { required: true },
                    description: { required: true },
                    // Hidden based on client request.
                    // number_plate: { required: true },
                    card_header: { required: true },
                    card_subtitle: { required: true },
                    banner: { required: false },


                },
                messages: {
                    manufacturer_id: { required: "Please select a make." },
                    model: { required: "The model field is required." },
                    year: { required: "Please select a year." },
                    category_id: { required: "Please select a category." },
                    vehicle_id: { required: "Please enter a Vehicle ID." },
                    location_id: { required: "Please select a location." },
                    location_manual: { required: "Please enter a location." },
                    category_id: { required: "Please select a category." },
                    // Hidden based on client request.
                    // less_four_days_price: {
                    //     required: "Price for 1-3 days is required.",
                    //     number: "Please enter a valid number.",
                    //     min: "Price must be a positive number."
                    // },
                    // five_six_days_price: {
                    //     required: "Price for 4-6 days is required.",
                    //     number: "Please enter a valid number.",
                    //     min: "Price must be a positive number."
                    // },
                    // week_price: {
                    //     required: "Weekly price is required.",
                    //     number: "Please enter a valid number.",
                    //     min: "Price must be a positive number."
                    // },
                    // month_price: {
                    //     required: "Monthly price is required.",
                    //     number: "Please enter a valid number.",
                    //     min: "Price must be a positive number."
                    // },
                    // max_price: {
                    //     required: "Maximum price is required.",
                    //     number: "Please enter a valid number.",
                    //     min: "Price must be a positive number."
                    // },
                    vehicle_id: { required: "The Vehicle ID field is required." },
                    status: { required: "Please select a status." },
                    auction_grade_id: { required: "Please select an auction grade." },
                    'images[]': { required: "Please upload at least one image." },
                    description: { required: "Description is required." },
                    // Hidden based on client request.
                    // number_plate: { required: "The number plate  is required." },
                    card_header: { required: "Card header is required." },
                    card_subtitle: { required: "Card subtitle is required." },

                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    if (element.hasClass('select2') && element.next('.select2-container').length) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        element.after(error);
                    }
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
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    let errorKey = key.includes('.') ? key.split('.')[0] : key;
                                    let errorMessage = Array.isArray(value) ? value[0] : value;
                                    $("#" + errorKey + "-error").html(errorMessage).show();
                                });
                            } else if (data && data.hasOwnProperty('errors')) {
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