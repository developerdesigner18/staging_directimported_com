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
                        <div class="row mb-3">
                            <!-- Make -->
                            <div class="col-md-4">
                                <label for="manufacturer_id"
                                    class="form-label">{{ admin_label('car_form', 'make', 'Make') }}</label>
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
                                    class="form-label">{{ admin_label('car_form', 'model', 'Model') }}</label>
                                <input type="text" class="form-control" id="model" name="model"
                                    placeholder="Enter model name">
                                <label id="model-error" class="text-danger error" for="model" style="display: none"></label>
                            </div>

                            <!-- Year -->
                            <div class="col-md-4">
                                <label for="year" class="form-label">{{ admin_label('car_form', 'year', 'Year') }}</label>
                                <select class="form-select select2" id="year" name="year">
                                    <option value="">Select Year</option>
                                    @for($y = date('Y') + 1; $y >= 1970; $y--)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endfor
                                </select>
                                <label id="year-error" class="text-danger error" for="year" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id"
                                class="form-label">{{ admin_label('car_form', 'category', 'Category') }}</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <label id="category_id-error" class="text-danger error" for="category_id"
                                style="display: none"></label>
                        </div>

                        <!-- Pricing -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="less_four_days_price"
                                    class="form-label">{{ admin_label('car_form', '1_4_days_price', '1-4 Days Price') }}</label>
                                <input type="number" step="0.01" class="form-control" id="less_four_days_price"
                                    name="less_four_days_price" placeholder="Enter price">
                                <label id="less_four_days_price-error" class="text-danger error"
                                    style="display: none"></label>
                            </div>

                            <div class="col-md-6">
                                <label for="five_six_days_price"
                                    class="form-label">{{ admin_label('car_form', '5_6_days_price', '5-6 Days Price') }}</label>
                                <input type="number" step="0.01" class="form-control" id="five_six_days_price"
                                    name="five_six_days_price" placeholder="Enter price">
                                <label id="five_six_days_price-error" class="text-danger error"
                                    style="display: none"></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="week_price"
                                    class="form-label">{{ admin_label('car_form', 'weekly_price', 'Weekly Price') }}</label>
                                <input type="number" step="0.01" class="form-control" id="week_price" name="week_price"
                                    placeholder="Enter price">
                                <label id="week_price-error" class="text-danger error" style="display: none"></label>
                            </div>

                            <div class="col-md-6">
                                <label for="month_price"
                                    class="form-label">{{ admin_label('car_form', 'monthly_price', 'Monthly Price') }}</label>
                                <input type="number" step="0.01" class="form-control" id="month_price" name="month_price"
                                    placeholder="Enter price">
                                <label id="month_price-error" class="text-danger error" style="display: none"></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="max_price"
                                    class="form-label">{{ admin_label('car_form', 'maximum_price', 'Maximum Price') }}</label>
                                <input type="number" step="0.01" class="form-control" id="max_price" name="max_price"
                                    placeholder="Enter maximum price">
                                <label id="max_price-error" class="text-danger error" style="display: none"></label>
                            </div>

                            <div class="col-md-6">
                                <label for="insurance_price"
                                    class="form-label">{{ admin_label('car_form', 'insurance_price', 'Insurance Price') }}</label>
                                <input type="number" step="0.01" class="form-control" id="insurance_price"
                                    name="insurance_price" placeholder="Enter insurance price">
                                <label id="insurance_price-error" class="text-danger error" style="display: none"></label>
                            </div>
                        </div>

                        <!-- Accessories + Number Plate -->
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label
                                        class="form-label">{{ admin_label('car_form', 'included_accessory', 'Included Accessory') }}</label>
                                    <select class="form-select select2-multiple" name="free_accessory[]" multiple>
                                        @foreach($freeAccessories as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <label id="free_accessory-error" class="text-danger error"
                                        style="display: none"></label>
                                </div>

                                <div class="col-lg-6">
                                    <label
                                        class="form-label">{{ admin_label('car_form', 'extra_accessory', 'Extra Accessory') }}</label>
                                    <select class="form-select select2-multiple" name="extra_accessory[]" multiple>
                                        @foreach($extraAccessories as $acc)
                                            <option value="{{ $acc->id }}">{{ $acc->name }}</option>
                                        @endforeach
                                    </select>
                                    <label id="extra_accessory-error" class="text-danger error"
                                        style="display: none"></label>
                                </div>


                            </div>
                        </div>

                        <!-- Number Plate + Location -->
                        <div class="mb-3">
                            <div class="row">

                                <!-- Number Plate -->
                                <div class="col-lg-6">
                                    <label for="numberPlate"
                                        class="form-label">{{ admin_label('car_form', 'number_plate', 'Number Plate') }}</label>
                                    <input type="text" class="form-control" id="numberPlate" name="number_plate"
                                        placeholder="Enter number plate number">
                                    <label id="number_plate-error" class="text-danger error" style="display: none"></label>
                                </div>

                                <!-- Location -->
                                <div class="col-lg-6">
                                    <label class="form-label">{{ admin_label('car_form', 'location', 'Location') }}</label>
                                    <select class="form-select select2" name="location">
                                        @foreach($locations ?? [] as $location)
                                            <option value="{{ optional($location)->id }}" {{ old('location') == optional($location)->id ? 'selected' : '' }}>
                                                {{ optional($location)->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                        </div>

                        <!-- Vehicle ID + Status + Auction Grade -->
                        <div class="mb-3">
                            <div class="row">
                                <!-- Vehicle ID -->
                                <div class="col-lg-4">
                                    <label for="vehicle_id"
                                        class="form-label">{{ admin_label('car_form', 'vehicle_id', 'Vehicle ID / Chassis No') }}</label>
                                    <input type="text" class="form-control" id="vehicle_id" name="vehicle_id"
                                        placeholder="Enter Vehicle ID">
                                    <label id="vehicle_id-error" class="text-danger error" style="display: none"></label>
                                </div>

                                <!-- Status -->
                                <div class="col-lg-4">
                                    <label for="status"
                                        class="form-label">{{ admin_label('car_form', 'status', 'Status') }}</label>
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
                                <div class="col-lg-4">
                                    <label for="auction_grade_id"
                                        class="form-label">{{ admin_label('car_form', 'auction_grade', 'Auction Grade') }}</label>
                                    <select class="form-select select2" id="auction_grade_id" name="auction_grade_id">
                                        <option value="">Select Auction Grade</option>
                                        @foreach($auctionGrades ?? [] as $grade)
                                            <option value="{{ $grade->id }}">
                                                {{ $grade->grade }} {{ $grade->remarks }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label id="auction_grade_id-error" class="text-danger error"
                                        style="display: none"></label>
                                </div>
                            </div>
                        </div>

                        <!-- Recommended -->
                        <div class="mb-3">
                            <label
                                class="form-label">{{ admin_label('car_form', 'recommended_car', 'Recommended Car') }}</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_recommended" name="is_recommended"
                                    value="1">
                                <label class="form-check-label" for="is_recommended">Mark as recommended</label>
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
            initFilepond();



            $("#addForm").validate({
                rules: {

                    manufacturer_id: { required: true },
                    model: { required: true },
                    year: { required: true },
                    category_id: { required: true },
                    less_four_days_price: { required: true, number: true, min: 0 },
                    five_six_days_price: { required: true, number: true, min: 0 },
                    week_price: { required: true, number: true, min: 0 },
                    month_price: { required: true, number: true, min: 0 },
                    max_price: { required: true, number: true, min: 0 },
                    vehicle_id: { required: true },
                    status: { required: true },
                    auction_grade_id: { required: true },
                    'images[]': { required: true },
                    description: { required: true },
                    number_plate: { required: true },
                    card_header: { required: true },
                    card_subtitle: { required: true },
                    banner: { required: false },


                },
                messages: {
                    manufacturer_id: { required: "Please select a make." },
                    model: { required: "The model field is required." },
                    year: { required: "Please select a year." },
                    category_id: { required: "Please select a category." },
                    less_four_days_price: {
                        required: "Price for 1-3 days is required.",
                        number: "Please enter a valid number.",
                        min: "Price must be a positive number."
                    },
                    five_six_days_price: {
                        required: "Price for 4-6 days is required.",
                        number: "Please enter a valid number.",
                        min: "Price must be a positive number."
                    },
                    week_price: {
                        required: "Weekly price is required.",
                        number: "Please enter a valid number.",
                        min: "Price must be a positive number."
                    },
                    month_price: {
                        required: "Monthly price is required.",
                        number: "Please enter a valid number.",
                        min: "Price must be a positive number."
                    },
                    max_price: {
                        required: "Maximum price is required.",
                        number: "Please enter a valid number.",
                        min: "Price must be a positive number."
                    },
                    vehicle_id: { required: "The Vehicle ID field is required." },
                    status: { required: "Please select a status." },
                    auction_grade_id: { required: "Please select an auction grade." },
                    'images[]': { required: "Please upload at least one image." },
                    description: { required: "Description is required." },
                    number_plate: { required: "The number plate  is required." },
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