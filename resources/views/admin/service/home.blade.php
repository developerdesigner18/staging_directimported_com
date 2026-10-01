@extends('admin.master')
@section('title', 'Home Services Section')

@section('style')
    <style>
        .repeater-row {
            transition: all 0.25s ease;
            background-color: #fff;
            border: 1px solid #e9ebec;
        }

        .repeater-row:hover {
            border-color: #405189 !important;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .btn-icon {
            width: 32px;
            height: 32px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card-order-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background-color: #f3f6f9;
            color: #405189;
            font-weight: 700;
            font-size: 13px;
        }
    </style>
@endsection

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                <h4 class="mb-sm-0">Home Services Section</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.service.index') }}">Services Management</a></li>
                        <li class="breadcrumb-item active">Home Services Section</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Pills Between Dedicated Services & Home Services -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.service.index') }}" class="btn btn-outline-primary">
                    <i class="ri-list-settings-line align-bottom me-1"></i> Dedicated Services Page (/services)
                </a>
                <a href="{{ route('admin.service.home') }}" class="btn btn-primary">
                    <i class="ri-home-4-line align-bottom me-1"></i> Home Services Section
                </a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Home Page Services Section Content</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border mb-4 fs-13">
                        <i class="ri-information-line me-1"></i>
                        The items configured here are displayed exclusively in the <strong>Our Services</strong> section on the homepage. They are separate from the detailed procurement services on the <code>/services</code> page.
                    </div>

                    <form id="homeServicesForm">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="services_title" class="form-label fw-bold">Section Heading</label>
                                <input type="text" class="form-control form-control-lg" id="services_title"
                                    name="services_title" value="{{ $homeSection->services_title ?? 'Our Services' }}"
                                    placeholder="e.g. Our Services" maxlength="255" required>
                                <label id="services_title-error" class="text-danger error" style="display:none"></label>
                            </div>
                        </div>

                        <div class="border-top pt-4">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <h5 class="fs-15 mb-1">Service Cards</h5>
                                    <p class="text-muted fs-13 mb-0">Manage the 4 key service feature tiles shown on the homepage grid.</p>
                                </div>
                                <button type="button" class="btn btn-sm btn-outline-primary add-row"
                                    data-container="#servicesContainer" data-template="#serviceItemTemplate">
                                    <i class="ri-add-line align-bottom me-1"></i> Add Service Card
                                </button>
                            </div>

                            <div id="servicesContainer">
                                @php
                                    $items = $homeSection->services_items ?? [];
                                @endphp
                                @forelse($items as $index => $item)
                                    <div class="repeater-row mb-3 p-3 rounded border">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="card-order-badge mt-1">{{ $loop->iteration }}</div>
                                            <div class="flex-grow-1">
                                                <div class="row g-2">
                                                    <div class="col-md-4 repeater-field">
                                                        <label class="form-label fs-12 text-muted mb-1">Icon</label>
                                                        @include('admin.partials.icon-picker', [
                                                            'name' => "services_items[$index][icon]",
                                                            'value' => $item['icon'] ?? 'bx bx-check-circle'
                                                        ])
                                                        <label class="text-danger error field-error fs-12" style="display:none"></label>
                                                    </div>
                                                    <div class="col-md-8 repeater-field">
                                                        <label class="form-label fs-12 text-muted mb-1">Card Title</label>
                                                        <input type="text" class="form-control"
                                                            name="services_items[{{ $index }}][title]"
                                                            value="{{ $item['title'] ?? '' }}"
                                                            placeholder="Card title (e.g. Documentation for export)"
                                                            maxlength="150" required>
                                                        <label class="text-danger error field-error fs-12" style="display:none"></label>
                                                    </div>
                                                    <div class="col-12 repeater-field">
                                                        <label class="form-label fs-12 text-muted mb-1">Card Description</label>
                                                        <textarea class="form-control"
                                                            name="services_items[{{ $index }}][description]"
                                                            rows="2" maxlength="500"
                                                            placeholder="Short description text">{{ $item['description'] ?? '' }}</textarea>
                                                        <label class="text-danger error field-error fs-12" style="display:none"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-soft-danger btn-icon waves-effect waves-light remove-row mt-1" title="Remove Card">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted text-center py-4 no-services-msg">No service cards added yet. Click "Add Service Card" above.</div>
                                @endforelse
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12 text-end">
                                <button type="submit" class="btn btn-primary" id="btnUpdate">
                                    <span class="spinner-border spinner-border-sm d-none" id="btnUpdateSpinner"
                                        role="status" aria-hidden="true"></span>
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Template for adding new service cards -->
    <template id="serviceItemTemplate">
        <div class="repeater-row mb-3 p-3 rounded border">
            <div class="d-flex align-items-start gap-3">
                <div class="card-order-badge mt-1"><i class="ri-grid-line"></i></div>
                <div class="flex-grow-1">
                    <div class="row g-2">
                        <div class="col-md-4 repeater-field">
                            <label class="form-label fs-12 text-muted mb-1">Icon</label>
                            @include('admin.partials.icon-picker', [
                                'name' => 'services_items[__INDEX__][icon]',
                                'value' => 'bx bx-check-circle'
                            ])
                            <label class="text-danger error field-error fs-12" style="display:none"></label>
                        </div>
                        <div class="col-md-8 repeater-field">
                            <label class="form-label fs-12 text-muted mb-1">Card Title</label>
                            <input type="text" class="form-control"
                                name="services_items[__INDEX__][title]"
                                value=""
                                placeholder="Card title"
                                maxlength="150" required>
                            <label class="text-danger error field-error fs-12" style="display:none"></label>
                        </div>
                        <div class="col-12 repeater-field">
                            <label class="form-label fs-12 text-muted mb-1">Card Description</label>
                            <textarea class="form-control"
                                name="services_items[__INDEX__][description]"
                                rows="2" maxlength="500"
                                placeholder="Short description text"></textarea>
                            <label class="text-danger error field-error fs-12" style="display:none"></label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-soft-danger btn-icon waves-effect waves-light remove-row mt-1" title="Remove Card">
                    <i class="ri-delete-bin-line"></i>
                </button>
            </div>
        </div>
    </template>
@endsection

@section('script')
    <script>
        $(document).ready(function () {
            let rowIndex = Date.now();

            $(document).on('click', '.add-row', function () {
                $('.no-services-msg').hide();
                const html = $($(this).data('template')).html().replace(/__INDEX__/g, rowIndex++);
                $($(this).data('container')).append(html);
            });

            $(document).on('click', '.remove-row', function () {
                $(this).closest('.repeater-row').remove();
                if ($('#servicesContainer .repeater-row').length === 0) {
                    $('.no-services-msg').show();
                }
            });

            $("#homeServicesForm").on("submit", function (e) {
                e.preventDefault();

                $.ajax({
                    url: "{{ route('admin.service.home.update') }}",
                    method: "POST",
                    dataType: "json",
                    data: $(this).serialize(),
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
                        if (data && data.hasOwnProperty('error')) {
                            $.each(data.error, function (key, value) {
                                let $target;
                                if (key.includes('.')) {
                                    // e.g. services_items.0.title -> services_items[0][title]
                                    let parts = key.split('.');
                                    let name = parts[0] + parts.slice(1).map(function (p) { return '[' + p + ']'; }).join('');
                                    $target = $('[name="' + name + '"]').closest('.repeater-field').find('.field-error');
                                } else {
                                    $target = $("#" + key + "-error");
                                }
                                if ($target.length) {
                                    $target.html(Array.isArray(value) ? value[0] : value).show();
                                }
                            });
                        } else if (data && data.hasOwnProperty('message')) {
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
            });
        });
    </script>
    @include('admin.partials.icon-picker-modal')
@endsection
