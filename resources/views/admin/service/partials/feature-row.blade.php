<div class="row g-2 mb-2 feature-row align-items-center">
    <div class="col-md-4">
        @include('admin.partials.icon-picker', ['name' => "features[$index][icon]", 'value' => $feature['icon'] ?? ''])
    </div>
    <div class="col-md-7">
        <input type="text" class="form-control" name="features[{{ $index }}][text]" value="{{ $feature['text'] ?? '' }}"
            placeholder="Tag text, e.g. High-Res Photos" maxlength="100">
    </div>
    <div class="col-md-1">
        <button type="button" class="btn btn-soft-danger w-100 remove-feature" title="Remove">
            <i class="ri-close-line"></i>
        </button>
    </div>
</div>
