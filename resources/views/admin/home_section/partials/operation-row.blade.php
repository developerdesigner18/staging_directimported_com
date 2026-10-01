<div class="repeater-row mb-3 p-3 bg-light rounded border border-dashed">
    <div class="d-flex align-items-start gap-3">
        <div class="flex-grow-1">
            <div class="row g-2">
                <div class="col-md-4 repeater-field">
                    @include('admin.partials.icon-picker', ['name' => "operations[$index][icon]", 'value' => $item['icon'] ?? ''])
                    <label class="text-danger error field-error fs-12" style="display:none"></label>
                </div>
                <div class="col-md-8 repeater-field">
                    <input type="text" class="form-control" name="operations[{{ $index }}][title]"
                        value="{{ $item['title'] ?? '' }}" placeholder="Card title" maxlength="150">
                    <label class="text-danger error field-error fs-12" style="display:none"></label>
                </div>
                <div class="col-12 repeater-field">
                    <textarea class="form-control" name="operations[{{ $index }}][description]" rows="2" maxlength="500"
                        placeholder="Card text">{{ $item['description'] ?? '' }}</textarea>
                    <label class="text-danger error field-error fs-12" style="display:none"></label>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-soft-danger btn-icon waves-effect waves-light remove-row mt-1" title="Remove">
            <i class="ri-close-line"></i>
        </button>
    </div>
</div>
