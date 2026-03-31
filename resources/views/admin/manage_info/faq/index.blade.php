@extends('admin.master')
@section('title','Booking')

@push('modal')
    <div class="modal fade" id="addFaqModal" tabindex="-1" aria-labelledby="faqModalLabel"
         aria-modal="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Add FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="faqForm">
                    @csrf
                    <div class="modal-body">

                        <!-- FAQ Title -->
                        <div class="mb-3">
                            <label for="faqTitle" class="form-label">FAQ Title</label>
                            <input type="text" class="form-control" id="faqTitle" name="faqTitle"
                                   placeholder="Enter FAQ title">
                            <label id="faqTitle-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <!-- Description (TinyMCE Editor) -->
                        <div class="mb-3">
                            <label for="faqDescription" class="form-label">Description</label>

                            <textarea id="faqDescription" name="faqDescription" style="display:none;"></textarea>

                            <textarea class="tinymce_editor" id="faqDescription_editor" rows="6"></textarea>

                            <label id="faqDescription-error" class="text-danger error" style="display: none"></label>
                        </div>

                        <div class="faqData"></div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary" id="btnAddFaq">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm flex-shrink-0 me-2" id="faqSpinner" role="status"></span>
                                <span class="flex-grow-1" id="btnText">Submit</span>
                            </span>
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>

    <div class="modal fade" id="editFaqModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Edit FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form id="editFaqForm">
                    @csrf
                    <input type="hidden" id="editFaqId" name="faqId">

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">FAQ Title</label>
                            <input type="text" class="form-control" id="editFaqTitle" name="editFaqTitle">
                            <label id="editFaqTitle-error" class="text-danger error" style="display:none;"></label>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Description</label>

                            <textarea id="edit_faqDescription" name="editFaqDescription" style="display:none;"></textarea>

                            <textarea class="tinymce_editor" id="edit_faqDescription_editor" rows="6"></textarea>

                            <label id="edit_faqDescription-error" class="text-danger error" style="display:none;"></label>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <button type="submit" class="btn btn-primary" id="btnEditFaq">
                            <span class="d-flex align-items-center">
                                <span class="d-none spinner-border spinner-border-sm me-2" id="editFaqSpinner"></span>
                                <span>Update</span>
                            </span>
                        </button>

                    </div>
                </form>

            </div>
        </div>
    </div>

@endpush

@section('main')
    <!-- start page title -->
    <div class="row">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
            <h4 class="mb-sm-0">Faq Management</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">Manage Information</a></li>
                    <li class="breadcrumb-item active">FAQ Management</li>
                </ol>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header rounded-0">
                    <div class="row align-items-center gy-3">
                        <div class="col-sm">
                            <h5 class="card-title mb-0">FAQ</h5>
                        </div>
                        <div class="col-sm-auto">
                            <div class="d-flex gap-1 flex-wrap">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addFaqModal">
                                    <i class="ri-add-fill align-bottom"></i>
                                    <span class="d-none d-sm-inline-block">Add FAQ</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="databaseTable" class="table w-100 pt-2 datatable dataTable no-footer"></table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        tinymce.init({
            selector: '#faqDescription_editor,#edit_faqDescription_editor',
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
        var dataTable = $('#databaseTable').DataTable({
            processing: true,
            serverSide: true,
            info: true,
            select: false,
            dom: "Bfrtip",
            lengthMenu: [
                [10, 25, 50, 75],
                ["10 rows", "25 rows", "50 rows", "75 rows"]
            ],
            buttons: ["pageLength"],
            language: {
                zeroRecords: zeroRecords,
                search: "",
                searchPlaceholder: "Search Here",
                processing: processing,
                emptyTable: emptyTable,
                paginate: {
                    next: '<i class="ri-arrow-right-s-line"></i>',
                    previous: '<i class="ri-arrow-left-s-line"></i>'
                }
            },
            columns: [
                { data: 'DT_RowIndex', name: 'id', title: 'ID', class: 'text-center' },
                { data: 'key', name: 'key', title: 'Title', class: 'text-center' },
                { data: 'created_at', name: 'created_at', title: 'Create Date', class: 'text-center' },
                { data: 'updated_at', name: 'updated_at', title: 'Update Date', class: 'text-center' },
                { data: 'action', name: 'action', title: 'Action', class: 'text-center' }
            ],
            ajax: {
                url: '{{ route("admin.faq.list") }}',
                type: "GET",
                dataType: "JSON",
                data: function (f) {
                    f._token = "{{csrf_token()}}";
                },
                error: function (xhr) {
                    dataTableError("openCallTable", xhr.responseJSON.message);
                    actionError(xhr);
                }
            },
            responsive: {
                breakpoints: [
                    { name: "desktop", width: Infinity },
                    { name: "tablet", width: 1024 },
                    { name: "fablet", width: 768 },
                    { name: "phone", width: 480 }
                ]
            }
        });

        $(document).ready(function () {

            $("#faqForm").validate({
                rules: {
                    faqTitle: {required: true},
                },
                messages: {
                    faqTitle: {required: "The title field is required."},

                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $('#faqDescription').val(tinymce.get('faqDescription_editor').getContent());

                    $.ajax({
                        url: "{{ route('admin.faq.create') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnAddFaq').attr('disabled', true);
                            $("#faqSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then((result) => {
                                tinymce.get('faqDescription_editor').setContent('');
                                $('#faqForm')[0].reset();
                                dataTable.ajax.reload(null, false); // false preserves the current page

                                $('#addFaqModal').modal('hide');

                            });


                        },

                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnAddFaq').attr('disabled', false);
                            $("#faqSpinner").addClass('d-none');
                        },
                    });
                }
            });
            $("#editFaqForm").validate({
                rules: {
                    editFaqTitle: {required: true},
                },
                messages: {
                    editFaqTitle: {required: "The Title field is required."},

                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $('#editFaqDescription').val(tinymce.get('edit_faqDescription_editor').getContent());

                    $.ajax({
                        url: "{{ route('admin.faq.update') }}",
                        method: "post",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#btnEditFaq').attr('disabled', true);
                            $("#editFaqSpinner").removeClass('d-none');
                        },
                        success: function (result) {
                            sendSuccess(result.message).then((result) => {
                                tinymce.get('edit_faqDescription_editor').setContent('');
                                $('#editFaqForm')[0].reset();
                                dataTable.ajax.reload(null, false);
                                $('#editFaqModal').modal('hide');
                            });
                        },

                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnEditFaq').attr('disabled', false);
                            $("#editFaqSpinner").addClass('d-none');
                        },
                    });
                }
            });

        });
        function editFaq(id) {
            var edit = "{{ route('admin.faq.edit', ':id') }}";

            $.ajax({
                url: edit.replace(':id', id),
                type: 'GET',
                success: function(response) {
                    console.log(response);
                    // Fill the form fields
                    $('#editFaqId').val(response.data.id);
                    $('#editFaqTitle').val(response.data.key);
                    tinymce.get('edit_faqDescription_editor').setContent(response.data.value);

                    // update hidden textarea
                    $('#editFaqDescription').val(response.data.value);
                    // Open the modal
                    $('#editFaqModal').modal('show');
                },
                error: function(xhr) {
                    let data = xhr.responseJSON;
                    if (data.hasOwnProperty('message')) {
                        actionError(xhr, data.message)
                    } else {
                        actionError(xhr);
                    }
                }
            });
        }
        function removeFaq(id, element) {
            var originalHtml = $(element).html();
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this category?",
                icon: "warning",
                showCancelButton: !0,
                confirmButtonText: "Yes, remove",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: !1,
            }).then(function (t) {
                if (t.value) {
                    $.ajax({
                        url: "{{route('admin.faq.delete')}}",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": "{{csrf_token()}}",
                        },
                        beforeSend: function () {
                            $(element).attr('disabled', true);
                            $(element).html('<i class="spinner-border spinner-border-sm text-danger"></i>');
                        },
                        success: function (data) {
                            sendSuccess(data.message).then(() => {
                                dataTable.ajax.reload(null, false);
                            });

                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data?.error) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data?.message) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $(element).attr('disabled', false);
                            $(element).html(originalHtml);
                        }
                    });
                }
            });


        };


    </script>
@endsection
