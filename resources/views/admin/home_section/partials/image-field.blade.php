{{--
    Single image input with current-image preview.
    Params: $name, $label, $url (current image url or null), $removeName,
            $existingName/$existingValue (optional hidden input), $help (optional)
--}}
<div class="repeater-field">
    <label class="form-label fw-bold">{{ $label }}</label>
    @if(!empty($existingName))
        <input type="hidden" name="{{ $existingName }}" value="{{ $existingValue ?? '' }}">
    @endif
    @if(!empty($url))
        <div class="d-flex align-items-center gap-3 mb-2">
            <img src="{{ $url }}" alt="" class="img-thumbnail" style="height:80px;max-width:140px;object-fit:cover;">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" name="{{ $removeName }}"
                    id="{{ \Illuminate\Support\Str::slug($removeName, '_') }}">
                <label class="form-check-label" for="{{ \Illuminate\Support\Str::slug($removeName, '_') }}">Remove image</label>
            </div>
        </div>
    @endif
    <input type="file" class="form-control" name="{{ $name }}" accept="image/jpeg,image/png,image/webp">
    <small class="text-muted">{{ $help ?? 'JPG, PNG or WEBP, max 5 MB. Uploading a new image replaces the current one.' }}</small>
    <label class="text-danger error field-error fs-12" style="display:none"></label>
</div>
