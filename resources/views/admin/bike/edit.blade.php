@extends('admin.master')
@section('title','Edit Bike')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="editForm" enctype="multipart/form-data">
                        @csrf
                        <!-- Bike Name -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Bike Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ $bike->name }}" placeholder="Enter bike name">
                            <label id="name-error" class="text-danger error" style="display:none"></label>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Category</label>
                            <select class="form-select" id="category_id" name="category_id">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ $bike->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            <label id="category_id-error" class="text-danger error" style="display:none"></label>
                        </div>

                        <!-- Pricing Rows -->
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="less_four_days_price" class="form-label">1-4 Days Price</label>
                                <input type="number" step="0.01" class="form-control" id="less_four_days_price"
                                       name="less_four_days_price" value="{{ $bike->less_four_days_price }}">
                                <label id="less_four_days_price-error" class="text-danger error" style="display:none"></label>
                            </div>

                            <div class="col-md-6">
                                <label for="five_six_days_price" class="form-label">5-6 Days Price</label>
                                <input type="number" step="0.01" class="form-control" id="five_six_days_price"
                                       name="five_six_days_price" value="{{ $bike->five_six_days_price }}">
                                <label id="five_six_days_price-error" class="text-danger error" style="display:none"></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="week_price" class="form-label">Weekly Price</label>
                                <input type="number" step="0.01" class="form-control" id="week_price" name="week_price"
                                       value="{{ $bike->week_price }}">
                                <label id="week_price-error" class="text-danger error" style="display:none"></label>
                            </div>

                            <div class="col-md-6">
                                <label for="month_price" class="form-label">Monthly Price</label>
                                <input type="number" step="0.01" class="form-control" id="month_price" name="month_price"
                                       value="{{ $bike->month_price }}">
                                <label id="month_price-error" class="text-danger error" style="display:none"></label>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="max_price" class="form-label">Maximum Price</label>
                                <input type="number" step="0.01" class="form-control" id="max_price" name="max_price"
                                       value="{{ $bike->max_price }}">
                                <label id="max_price-error" class="text-danger error" style="display:none"></label>
                            </div>

                            <div class="col-md-6">
                                <label for="insurance_price" class="form-label">Insurance Price</label>
                                <input type="number" step="0.01" class="form-control" id="insurance_price"
                                       name="insurance_price" value="{{ $bike->insurance_price }}">
                                <label id="insurance_price-error" class="text-danger error" style="display:none"></label>
                            </div>
                        </div>

                        <!-- Free & Extra Accessories -->
                        <div class="row">

                            <!-- Free Accessory -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Included Accessory</label>
                                <select class="form-select select2-multiple" name="free_accessory[]" multiple>
                                    @foreach($freeAccessories as $accessory)
                                        <option value="{{ $accessory['id'] }}"
                                                {{ isset($bike->free_accessory) && in_array($accessory['id'], $bike->free_accessory) ? 'selected' : '' }}>
                                            {{ $accessory['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="free_accessory-error" class="text-danger error" style="display:none"></label>
                            </div>

                            <!-- Extra Accessory -->
                            <div class="col-lg-6 mb-3">
                                <label class="form-label">Extra Accessory</label>
                                <select class="form-select select2-multiple" name="extra_accessory[]" multiple>
                                    @foreach($extraAccessories as $accessory)
                                        <option value="{{ $accessory['id'] }}"
                                                {{ isset($bike->extra_accessory) && in_array($accessory['id'], $bike->extra_accessory) ? 'selected' : '' }}>
                                            {{ $accessory['name'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="extra_accessory-error" class="text-danger error" style="display:none"></label>
                            </div>

                        </div>

                        <!-- Number Plate & Location -->
                        <div class="row">

                            <div class="col-lg-6 mb-3">
                                <label for="numberPlate" class="form-label">Number Plate</label>
                                <input type="text" class="form-control" id="numberPlate"
                                       value="{{ $bike->number_plate }}" name="number_plate">
                                <label id="number_plate-error" class="text-danger error" style="display:none"></label>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="location" class="form-label">Location</label>
                                <select class="form-select select2" name="location">
                                    <option value="">-- Select Location --</option>
                                    @foreach($locations as $location)
                                        <option value="{{ $location->id }}"
                                                {{ $bike->location_id == $location->id ? 'selected' : '' }}>
                                            {{ $location->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <label id="location-error" class="text-danger error" style="display:none"></label>
                            </div>

                        </div>

                        <!-- Recommended Switch -->
                        <div class="mb-3">
                            <label class="form-label">Recommended Bike</label>
                            <div class="form-check form-switch">
                                <input type="checkbox" class="form-check-input" id="is_recommended"
                                       name="is_recommended" value="1"
                                        {{ $bike->is_recommended ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_recommended">
                                    Mark as recommended
                                </label>
                            </div>
                        </div>

                        <!-- Banner -->
                        <div class="col-md-12 mb-2">
                            <label for="banner" class="form-label">Banner</label>
                            <label for="banner" class="custom-file-label w-100">
                                <input type="file" id="banner" class="form-control file-preview" name="banner" accept="image/*">
                                <label id="banner-error" class="text-danger error" style="display:none"></label>
                                <div class="uploaded-preview mt-2" style="width: 240px;">
                                    @if($bike->banner)
                                        <img src="{{ asset(BIKE_PATH.$bike->banner) }}" alt="" class="imgupload w-100 h-100 object-contain" id="product-img"/>
                                    @endif
                                </div>
                            </label>
                        </div>

                        <!-- Images -->
                        <div class="mb-3">
                            <label for="images" class="form-label">Bike Images</label>
                            <input type="file" class="filepond" id="images" name="images[]" multiple>

                            @if($bike->images)
                                <div id="sortable-images" class="d-flex flex-wrap">
                                    @foreach($bike->images as $image)
                                        <div class="image-preview-container me-2 mb-2"
                                             data-image="{{ $image }}"
                                             style="cursor: grab;">

                                            <img src="{{ asset(BIKE_PATH.$image) }}"
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
                            <textarea class="form-control" id="description_editor" rows="5">{{ $bike->description }}</textarea>
                            <input type="hidden" id="description" name="description" value="{{ $bike->description }}">
                        </div>

                        <!-- Technical Specifications -->
                        <!-- Simple Technical Specifications -->
                        <div class="card mt-4">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">Technical Specifications</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Engine</label>
                                        <input type="text" value="{{ $bike->engine }}" name="engine" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Power</label>
                                        <input type="text" value="{{ $bike->power }}" name="power" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Seat Height</label>
                                        <input type="text" value="{{ $bike->seat_height }}" name="seat_height" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Weight</label>
                                        <input type="text" name="weight" value="{{ $bike->weight }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tank Capacity</label>
                                        <input type="text" name="tank_capacity" value="{{ $bike->tank_capacity }}" class="form-control">
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Luggage</label>
                                        <input type="text" name="luggage" value="{{ $bike->luggage }}" class="form-control">
                                    </div>

                                </div>
                            </div>
                        </div>
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
                                           placeholder="Enter card title (Frontend)" value="{{ $bike->card_header ?? '' }}">
                                    <label id="card_header-error" class="text-danger error" style="display: none"></label>
                                </div>

                                <!-- Card Subtitle -->
                                <div class="mb-3">
                                    <label for="card_subtitle" class="form-label">Card Subtitle</label>
                                    <input type="text" class="form-control" id="card_subtitle" name="card_subtitle"
                                           placeholder="Enter card subtitle (Frontend)" value="{{ $bike->card_subtitle ?? '' }}">
                                    <label id="card_subtitle-error" class="text-danger error" style="display: none"></label>
                                </div>

                            </div>
                        </div>
                        <!-- ========================================================== -->
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
                    selector: '#description_editor, #tec_spec_editor',
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
                        FilePondPluginImagePreview,
                        FilePondPluginFileValidateType
                    );

                    const inputElement = document.querySelector('input.filepond');
                    if (inputElement) {
                        FilePond.create(inputElement, {
                            allowMultiple: true,
                            maxFiles: 60,
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

                        // Track removed images
                        let removedImages = $('#removed_images').val();
                        removedImages = removedImages ? removedImages.split(',') : [];
                        removedImages.push(imagePath);
                        $('#removed_images').val(removedImages.join(','));

                        // Refresh order after removal
                        let order = [];
                        $('#sortable-images .image-preview-container').each(function () {
                            order.push($(this).data('image'));
                        });
                        $('#image_order').val(order.join(','));
                    });



                    $("#editForm").validate({
                        rules: {
                            name: {required: true},
                            category_id: {required: true},
                            less_four_days_price: {required: true, number: true, min: 0},
                            five_six_days_price: {required: true, number: true, min: 0},
                            week_price: {required: true, number: true, min: 0},
                            month_price: {required: true, number: true, min: 0},
                            max_price: {required: true, number: true, min: 0},
                            description: {required: true},
                            number_plate: {required: true},
                            card_header: { required: true },
                            card_subtitle: { required: true },
                            banner: {required: false},
                        },
                        messages: {
                            name: {required: "The bike name is required."},
                            category_id: {required: "Please select a category."},
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
                            description: {required: "Description is required."},
                            number_plate: {required: "The number plate  is required."},
                            card_header: { required: "Card header is required." },
                            card_subtitle: { required: "Card subtitle is required." },

                        },
                        errorClass: 'text-danger error',
                        errorPlacement: function (error, element) {
                            element.after(error);
                        },
                        submitHandler: function (form, e) {
                            e.preventDefault();

                            $.ajax({
                                url: "{{ route('admin.bike.update', $bike->id) }}",
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
                                    sendSuccess(result.message || 'Bike updated successfully!');
                                    window.location.href = "{{ route('admin.bike.index') }}";
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
                                    $('button[type="submit"]').html('Update');
                                }
                            });
                        }
                    });
                });
            </script>
@endsection
