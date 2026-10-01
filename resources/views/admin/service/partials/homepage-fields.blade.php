<!-- Short Description (homepage tile) -->
<div class="mb-3">
    <label for="short_description"
        class="form-label">{{ admin_label('service_form', 'short_description', 'Short Description') }}</label>
    <textarea class="form-control" id="short_description" name="short_description" rows="3" maxlength="500"
        placeholder="Short text shown on the homepage service card">{{ $service->short_description ?? '' }}</textarea>
    <small class="text-muted">Shown on the homepage "Our Services" cards (the first 4 services by sort order). If left empty, the start of the description is used.</small>
    <label id="short_description-error" class="text-danger error" style="display:none"></label>
</div>

<!-- Icon (homepage tile) -->
<div class="mb-3">
    <label for="icon" class="form-label">{{ admin_label('service_form', 'homepage_icon', 'Homepage Icon') }}</label>
    @if(isset($service) && $service->icon_url)
        <div class="d-flex align-items-center gap-3 mb-2" id="currentIconWrapper">
            <img src="{{ $service->icon_url }}" alt="{{ $service->title }}" class="img-thumbnail"
                style="width:64px;height:64px;object-fit:contain;">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="remove_icon" name="remove_icon">
                <label class="form-check-label" for="remove_icon">Remove current icon</label>
            </div>
        </div>
    @endif
    <input type="file" class="filepond-icon" id="icon" name="icon" accept="image/png,image/jpeg,image/webp,image/gif">
    <small class="text-muted">Optional. Square PNG/WEBP with a transparent background works best (max 2 MB). Uploading a new icon replaces the current one.</small>
    <label id="icon-error" class="text-danger error" style="display:none"></label>
</div>
