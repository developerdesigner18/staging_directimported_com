@extends('admin.master')
@section('title','Accessories')


@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Create Email</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Create</a></li>
                        <li class="breadcrumb-item active">Creat Email</li>
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

                            <!-- Description -->
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                             <textarea class="form-control" id="description_editor" rows="5"  aria-hidden="true"></textarea>
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
</script>
@endsection
