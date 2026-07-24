@extends('admin.master')
@section('title', 'About Us')

@section('style')
    <style>
        .point-row {
            transition: all 0.3s ease;
        }

        .point-row:hover {
            border-color: #405189 !important;
            transform: translateY(-2px);
        }

        .bg-light-subtle {
            background-color: rgba(var(--vz-light-rgb), .5) !important;
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
@endsection

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">About Us Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">About Us Management</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header border-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Home AboutUs Section Management</h5>
                    </div>
                </div>
                <div class="card-header">
                    <ul class="nav nav-tabs-custom card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#generalInfo" role="tab">
                                <i class="ri-information-line align-bottom me-1"></i> General Information
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#pointsInfo" role="tab">
                                <i class="ri-list-check align-bottom me-1"></i> Feature Points
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <form id="editForm" enctype="multipart/form-data">
                        @csrf
                        <div class="tab-content">
                            <!-- General Info Tab -->
                            <div class="tab-pane active" id="generalInfo" role="tabpanel">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="title"
                                                class="form-label fw-bold">{{ admin_label('home_section_form', 'section_title', 'Section Title') }}</label>
                                            <input type="text" class="form-control form-control-lg bg-light border-light"
                                                id="title" name="title" value="{{ $homeSection->title }}"
                                                placeholder="Enter title">
                                            <label id="title-error" class="text-danger error" style="display:none"></label>
                                        </div>

                                        <div class="mb-0">
                                            <label for="short_description_editor"
                                                class="form-label fw-bold">{{ admin_label('home_section_form', 'short_description', 'Short Description') }}</label>
                                            <textarea class="form-control" id="short_description_editor"
                                                rows="10">{{ $homeSection->short_description }}</textarea>
                                            <input type="hidden" id="short_description" name="short_description"
                                                value="{{ $homeSection->short_description }}">
                                            <label id="short_description-error" class="text-danger error"
                                                style="display:none"></label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Points Tab -->
                            <div class="tab-pane" id="pointsInfo" role="tabpanel">
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-grow-1">
                                        <h6 class="fw-bold mb-1">Feature Points</h6>
                                        <p class="text-muted mb-0">Add or manage key points for this section.</p>
                                    </div>
                                    <button type="button" class="btn btn-success btn-label waves-effect waves-light"
                                        id="addMorePoint">
                                        <i class="ri-add-line label-icon align-middle fs-16 me-2"></i> Add Point
                                    </button>
                                </div>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div id="pointsContainer">
                                            @if(count($homeSection->points) > 0)
                                                @foreach($homeSection->points as $index => $point)
                                                    <div
                                                        class="point-row mb-3 p-3 bg-light rounded border border-dashed position-relative">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <div class="flex-grow-1">
                                                                <div class="input-group">
                                                                    <span class="input-group-text bg-white text-primary"><i
                                                                            class="ri-checkbox-circle-line"></i></span>
                                                                    <textarea name="points[]" class="form-control" rows="2"
                                                                        placeholder="Enter point text...">{{ $point->point_text }}</textarea>
                                                                </div>
                                                                <label class="text-danger error point-error mt-1 fs-12"
                                                                    style="display:none"></label>
                                                            </div>
                                                            <button type="button"
                                                                class="btn btn-soft-danger btn-icon waves-effect waves-light remove-point mt-1"
                                                                title="Remove">
                                                                <i class="ri-close-line"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="no-points-msg text-center my-5 text-muted">
                                                    <i class="ri-list-check-2 display-5 text-primary"></i>
                                                    <p class="mt-2 text-dark fw-medium">No points added yet</p>
                                                    <p class="fs-12">Click "Add Point" to get started.</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 border-top border-top-dashed pt-4 text-end">
                            <button type="submit" class="btn btn-primary btn-load py-2 px-4 fs-15 shadow-sm" id="btnUpdate">
                                <span class="d-flex align-items-center">
                                    <span class="spinner-border flex-shrink-0 d-none me-2" id="btnUpdateSpinner"
                                        role="status"></span>
                                    <span class="flex-grow-1"><i class="ri-save-3-line align-middle me-1"></i> Save All
                                        Changes</span>
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
        tinymce.init({
            selector: '#short_description_editor',
            height: 300,
            menubar: true,
            plugins: 'lists link image help wordcount code media table',
            toolbar: 'code | formatselect fontsizeselect | insertfile a11ycheck | numlist bullist | bold italic | forecolor backcolor | template codesample | alignleft aligncenter alignright alignjustify | bullist numlist | link image media tinydrive | table tabledelete | tableprops tablerowprops tablecellprops | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
            file_picker_types: 'file image media',
            images_upload_url: "{{ route('admin.tinymce.image.upload') }}",
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

        $(document).ready(function () {
            // Add More Point
            $('#addMorePoint').click(function () {
                $('.no-points-msg').hide();
                let pointHtml = `
                            <div class="point-row mb-3 p-3 bg-light rounded border border-dashed position-relative animate__animated animate__fadeInUp">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-grow-1">
                                        <div class="input-group">
                                            <span class="input-group-text bg-white text-primary"><i class="ri-checkbox-circle-line"></i></span>
                                            <textarea name="points[]" class="form-control" rows="2" placeholder="Enter point text..."></textarea>
                                        </div>
                                        <label class="text-danger error point-error mt-1 fs-12" style="display:none"></label>
                                    </div>
                                    <button type="button" class="btn btn-soft-danger btn-icon waves-effect waves-light remove-point mt-1" title="Remove">
                                        <i class="ri-close-line"></i>
                                    </button>
                                </div>
                            </div>
                        `;
                $('#pointsContainer').append(pointHtml);
            });

            // Remove Point
            $(document).on('click', '.remove-point', function () {
                $(this).closest('.point-row').remove();
                if ($('.point-row').length === 0) {
                    $('.no-points-msg').show();
                }
            });

            $("#editForm").validate({
                rules: {
                    title: { required: true },
                    short_description: { required: true },
                },
                messages: {
                    title: { required: "The title field is required." },
                    short_description: { required: "The short description field is required." },
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    if (element.attr("id") == "short_description_editor") {
                        element.parent().append(error);
                    } else {
                        element.after(error);
                    }
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('admin.home_section.update') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnUpdate').attr('disabled', true);
                            $('#btnUpdateSpinner').removeClass('d-none');
                            $(".error").html('').hide();
                        },
                        success: function (result) {
                            sendSuccess(result.message || 'Updated successfully!');
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    // Handle array errors for points (e.g., points.0)
                                    if (key.startsWith('points.')) {
                                        let index = key.split('.')[1];
                                        let $row = $('.point-row').eq(index);
                                        $row.find('.point-error').html(value).show();

                                        // Switch to points tab if there's an error there
                                        $('.nav-tabs-custom a[href="#pointsInfo"]').tab('show');
                                    } else {
                                        $("#" + key + "-error").html(value).show();
                                        // Switch to general tab if there's an error there
                                        $('.nav-tabs-custom a[href="#generalInfo"]').tab('show');
                                    }
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnUpdate').attr('disabled', false);
                            $('#btnUpdateSpinner').addClass('d-none');
                        }
                    });
                }
            });
        });
    </script>
@endsection