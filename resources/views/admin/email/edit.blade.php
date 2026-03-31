@extends('admin.master')
@section('title','Edit-Email')


@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Edit Email</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Edit</a></li>
                        <li class="breadcrumb-item active">Edit Email</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form id="editForm" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="mb-3">
                        <input type="hidden" class="form-control" name="id" value="{{ $data->id ?? '-' }}">
                    </div>
                    <!-- Name (Disabled, not editable) -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control" name="key" value="{{ $data->key ?? '-' }}" disabled>
                    </div>



                    <!-- Subject (Editable) -->
                    <div class="mb-3">
                        <label for="subject_name" class="form-label">Subject <span class="text text-danger">*</span></label>
                        <input type="text" class="form-control" name="subject_name" value="{{ $data->subject }}">

                    </div>

                    <!-- Description (Editable) -->
                    <div class="mb-3">
                        <label for="description_editor" class="form-label">Description  <span class="text text-danger">*</span></label>
                        <textarea class="form-control" id="description_editor" name="description" rows="5">{{ $data->body }}</textarea>
                        <label id="description-error" class="text-danger error d-none" for="subject_name">The Subject is required.</label>
                    </div>
<div class="mb-3">
<p class='text text-danger'>Please donot edit <strong>{{ $data->placeholder }}</strong>. You may remove it if needed, but do not change the text.</p>

</div>
                    <!-- Submit Button -->
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
    height: 250,
    menubar: false, // hide top menu
    plugins: 'lists link', // only basic features
    toolbar: 'undo redo | bold italic underline | forecolor backcolor | fontsizeselect | bullist numlist | link | removeformat',
    branding: false, // remove "Powered by TinyMCE"
    setup: function (editor) {
        editor.on('init', function () {
            const textareaId = editor.id.replace('_editor', '');
            editor.setContent(document.getElementById(textareaId).value);
        });
        editor.on('change', function () {
            const textareaId = editor.id.replace('_editor', '');
            document.getElementById(textareaId).value = editor.getContent();
        });
    }
});
$(document).ready(function(){
let id=$('#id').val();
            $("#editForm").validate({
                rules: {
                    subject_name: {required: true},
                     description: {required: true},
                },
                messages: {
                      subject_name: {required: "The Subject is required."},
                       description: {required: "The Description is required."},
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                submitHandler: function (form, e) {
                    e.preventDefault();

                    $.ajax({
                        url: "{{ route('admin.email.update') }}",
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
                            sendSuccess(result.message || 'Bike updated successfully!').then(() => {
                                                                                                   window.location.href = "{{ route('admin.email.index') }}";
                                                                                               });
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
