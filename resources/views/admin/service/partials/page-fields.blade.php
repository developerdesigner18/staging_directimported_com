<!-- Image Badge (Services page) -->
<div class="mb-3">
    <label for="image_badge"
        class="form-label">{{ admin_label('service_form', 'image_badge', 'Image Badge') }}</label>
    <input type="text" class="form-control" id="image_badge" name="image_badge" maxlength="100"
        value="{{ $service->image_badge ?? '' }}" placeholder="e.g. Hands-On Assessment">
    <small class="text-muted">Optional label shown over the first service image on the Services page.</small>
    <label id="image_badge-error" class="text-danger error" style="display:none"></label>
</div>

<!-- Feature Tags (Services page) -->
<div class="mb-3">
    <label class="form-label">{{ admin_label('service_form', 'feature_tags', 'Feature Tags') }}</label>
    <p class="text-muted fs-12 mb-2">
        Small tags shown under the description on the Services page. Click <strong>Choose icon</strong> to pick an icon for each tag.
    </p>
    <div id="featuresContainer">
        @foreach(($service->features ?? []) as $index => $feature)
            @include('admin.service.partials.feature-row', ['index' => $index, 'feature' => $feature])
        @endforeach
    </div>
    <button type="button" class="btn btn-soft-success btn-sm" id="addFeature">
        <i class="ri-add-line align-bottom me-1"></i> Add Tag
    </button>
    <label id="features-error" class="text-danger error" style="display:none"></label>
</div>

<template id="featureRowTemplate">
    @include('admin.service.partials.feature-row', ['index' => '__INDEX__', 'feature' => []])
</template>
