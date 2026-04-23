@extends('admin.master')
@section('title', 'Manage Car Specs')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Manage Specifications: {{ $bike->name }}</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.bike.index') }}">Car Management</a></li>
                        <li class="breadcrumb-item active">Manage Specs</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form id="specsForm">
                        @csrf
                        <div class="row">
                            <!-- Technical Specs -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Make</label>
                                <input type="text" name="make" class="form-control"
                                    value="{{ $bike->spec->make ?? '' }}" placeholder="e.g. Toyota">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Exterior Color</label>
                                <input type="text" name="exterior_color" class="form-control" value="{{ $bike->spec->exterior_color ?? '' }}"
                                    placeholder="e.g. Pearl White">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Body Type</label>
                                <input type="text" name="body_type" class="form-control"
                                    value="{{ $bike->spec->body_type ?? '' }}" placeholder="e.g. SUV">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Fuel Type</label>
                                <input type="text" name="fuel_type" class="form-control"
                                    value="{{ $bike->spec->fuel_type ?? '' }}" placeholder="e.g. Hybrid">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Engine</label>
                                <input type="text" name="engine" class="form-control"
                                    value="{{ $bike->spec->engine ?? '' }}" placeholder="e.g. 2.0L Turbo">
                            </div>



                            <!-- Car Specific Specs -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Odometer (km)</label>
                                <input type="number" name="odometer" class="form-control"
                                    value="{{ $bike->spec->odometer ?? '' }}" placeholder="e.g. 50000">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Model Year</label>
                                <input type="number" name="model_year" class="form-control"
                                    value="{{ $bike->spec->model_year ?? '' }}" placeholder="e.g. 2022">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Interior Color</label>
                                <input type="text" name="interior_color" class="form-control"
                                    value="{{ $bike->spec->interior_color ?? '' }}" placeholder="e.g. Black Leather">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Transmission</label>
                                <input type="text" name="transmission" class="form-control"
                                    value="{{ $bike->spec->transmission ?? '' }}" placeholder="e.g. Automatic / Manual">
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Specifications</button>
                            <a href="{{ route('admin.bike.index') }}" class="btn btn-light">Back to List</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            $("#specsForm").submit(function (e) {
                e.preventDefault();
                let form = $(this);
                let submitBtn = form.find('button[type="submit"]');

                $.ajax({
                    url: "{{ route('admin.bike.specs.update', $bike->id) }}",
                    method: "POST",
                    data: form.serialize(),
                    beforeSend: function () {
                        submitBtn.attr('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Updating...');
                    },
                    success: function (response) {
                        sendSuccess(response.message);
                    },
                    error: function (xhr) {
                        let data = xhr.responseJSON;
                        if (data && data.errors) {
                            $.each(data.errors, function (key, value) {
                                sendError(value[0]);
                            });
                        } else {
                            sendError(data.message || "Something went wrong");
                        }
                    },
                    complete: function () {
                        submitBtn.attr('disabled', false).html('Update Specifications');
                    }
                });
            });
        });
    </script>
@endsection