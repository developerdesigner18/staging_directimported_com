<div class="repeater-row mb-3 p-3 bg-light rounded border border-dashed">
    <div class="d-flex align-items-start gap-3">
        <div class="flex-grow-1">
            <div class="row g-3">
                <div class="col-md-5">
                    @include('admin.home_section.partials.image-field', [
                        'name' => "passion_cards[$index][image]",
                        'label' => 'Card Image',
                        'url' => \App\Models\HomeSection::imageUrl($item['image'] ?? null),
                        'removeName' => "passion_cards[$index][remove_image]",
                        'existingName' => "passion_cards[$index][existing_image]",
                        'existingValue' => $item['image'] ?? '',
                    ])
                </div>
                <div class="col-md-7">
                    <div class="row g-2">
                        <div class="col-md-5 repeater-field">
                            <label class="form-label fw-bold">Badge</label>
                            <input type="text" class="form-control" name="passion_cards[{{ $index }}][badge]"
                                value="{{ $item['badge'] ?? '' }}" placeholder="e.g. JDM Icons" maxlength="100">
                            <label class="text-danger error field-error fs-12" style="display:none"></label>
                        </div>
                        <div class="col-md-7 repeater-field">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" class="form-control" name="passion_cards[{{ $index }}][title]"
                                value="{{ $item['title'] ?? '' }}" placeholder="Card title" maxlength="150">
                            <label class="text-danger error field-error fs-12" style="display:none"></label>
                        </div>
                        <div class="col-12 repeater-field">
                            <label class="form-label fw-bold">Description</label>
                            <textarea class="form-control" name="passion_cards[{{ $index }}][description]" rows="5"
                                maxlength="3000" placeholder="Card text">{{ $item['description'] ?? '' }}</textarea>
                            <label class="text-danger error field-error fs-12" style="display:none"></label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <button type="button" class="btn btn-soft-danger btn-icon waves-effect waves-light remove-row mt-1" title="Remove">
            <i class="ri-close-line"></i>
        </button>
    </div>
</div>
