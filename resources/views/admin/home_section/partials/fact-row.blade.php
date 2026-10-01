<div class="repeater-row mb-3 p-3 bg-light rounded border border-dashed">
    <div class="d-flex align-items-start gap-3">
        <div class="flex-grow-1">
            <div class="row g-2">
                <div class="col-md-4 repeater-field">
                    @include('admin.partials.icon-picker', ['name' => "facts[$index][icon]", 'value' => $item['icon'] ?? ''])
                    <label class="text-danger error field-error fs-12" style="display:none"></label>
                </div>
                <div class="col-md-8 repeater-field">
                    <input type="text" class="form-control" name="facts[{{ $index }}][feature]"
                        value="{{ $item['feature'] ?? '' }}" placeholder="Feature, e.g. Licensed Dealer Status" maxlength="150">
                    <label class="text-danger error field-error fs-12" style="display:none"></label>
                </div>
                <div class="col-12 repeater-field">
                    <textarea class="form-control" name="facts[{{ $index }}][details]" rows="2" maxlength="1000"
                        placeholder="Details">{{ $item['details'] ?? '' }}</textarea>
                    <label class="text-danger error field-error fs-12" style="display:none"></label>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-soft-danger btn-icon waves-effect waves-light remove-row mt-1" title="Remove">
            <i class="ri-close-line"></i>
        </button>
    </div>
</div>
