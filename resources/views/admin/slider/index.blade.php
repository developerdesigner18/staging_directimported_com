    @extends('admin.master')
@section('title','Slider')
@push('modal')
<!-- Modal -->
<div class="modal fade" id="colorModal" tabindex="-1" aria-labelledby="colorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="" method="POST" id="frmChangeColor">
        @csrf

        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="colorModalLabel">Select Background Color</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <input type="color" name="background_color" value="{{ $color->slider_backcolor ?? '#fff545' }}" class="form-control form-control-color">
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-success" id="btnChange">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
    </form>
  </div>
</div>

@endpush
@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Slider Management</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Slides</a></li>
                        <li class="breadcrumb-item active">Slider Management</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-3">
        <div class="col-sm-auto">
            <div>
                <a href="{{route('admin.slider.create')}}" class="btn btn-success">
                    <i class="ri-add-line align-bottom me-1"></i>Add New
                </a>
       <!-- Button to open modal -->
       <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#colorModal">
           Change Background Color
       </button>
            </div>

        </div>
        <div class="col-sm">
            <form method="GET" action="{{ route('admin.slider.index') }}">
                <div class="d-flex justify-content-sm-end gap-2">
                    <div class="search-box ms-2">
                        <input type="text" name="search" class="form-control" placeholder="Search..."
                               value="{{ request('search') }}">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-secondary">Search</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        @forelse($sliders as $slider)
            <div class="col-xxl-3 col-lg-6" id="slider-card-{{$slider->id}}">
                <div class="card overflow-hidden blog-grid-card">
                    <div class="position-relative overflow-hidden">
                        <img src="{{ $slider->image }}" alt="{{ @$slider->title }}" class="blog-img object-fit-cover"
                             style="height: 200px; width: 100%;">
                        <div class="position-absolute top-0 end-0 p-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="ri-more-2-fill"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('admin.slider.edit', $slider->id) }}">Edit</a>
                                    </li>
                                    <li>
                                        <button class="dropdown-item text-danger"
                                                onclick="deleteSlider('{{$slider->id}}',this)">Delete
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ @$slider->title }}</h5>
                        @if($slider->link)
                            <a href="{{ $slider->link }}" class="btn btn-sm btn-primary" target="_blank">
                                {{ $slider->button_text ?: 'View' }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h4 class="text-muted">No sliders found</h4>
                        <p class="text-muted">Create your first slider by clicking the "Add New" button</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    {{$sliders->withQueryString()->links('admin.partials.pagination')}}
@endsection

@section('script')
    <script>
        function deleteSlider(id, element) {
            Swal.fire({
                title: "Are you sure?",
                text: "Are you sure you want to remove this slider?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, remove",
                cancelButtonText: "No, cancel!",
                confirmButtonClass: "btn btn-danger mt-2 text-white rounded px-4 fs-16",
                cancelButtonClass: "btn btn-light ms-2 mt-2 border rounded px-4 fs-16",
                buttonsStyling: false,
            }).then(function (t) {
                if (t.value) {
                    $.ajax({
                        url: "{{route('admin.slider.delete')}}",
                        dataType: "JSON",
                        method: "POST",
                        data: {
                            "id": id,
                            "_token": "{{csrf_token()}}",
                        },
                        beforeSend: function () {
                            $(element).html('<i class="spinner-border fs-10 spinner-border-sm m-1 mx-0"></i>');
                            $(element).attr('disabled', true);
                        },
                        success: function (data) {
                            sendSuccess(data.message);
                            $(`#slider-card-${id}`).remove();
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message)
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $(element).attr('disabled', false);
                        }
                    });
                }
            });
        }
$(document).ready(function(){
$('#frmChangeColor').on('submit',function(e){
e.preventDefault();
let formData=new FormData(this);
$.ajax({
                        url: "{{route('admin.color.update-color')}}",
                        dataType: "JSON",
                                    processData: false,
                                    contentType: false,
                        method: "POST",
                        data: formData,
                        beforeSend: function () {
                            $('#btnChange').html('<i class="spinner-border fs-10 spinner-border-sm m-1 mx-0"></i>');
                            $('#btnChange').attr('disabled', true);
                        },
                        success: function (data) {
                            sendSuccess(data.message);
$('#colorModal').trigger('reset');
                                $('#colorModal').modal('hide');  // hides the modal

                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value).show();
                                });
                            } else if (data.hasOwnProperty('message')) {
                                actionError(xhr, data.message)
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#btnChange').attr('disabled', false);
                        }
                    });

});
});
    </script>
@endsection
