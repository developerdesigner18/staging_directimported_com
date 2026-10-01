{{--
    Icon picker field. Stores a Boxicons class (e.g. "bx bx-car") in a hidden input.
    Params: $name (input name), $value (current class, optional)
    Requires admin.partials.icon-picker-modal to be included once on the page.
--}}
<div class="icon-picker d-flex align-items-center gap-2">
    <input type="hidden" class="icon-picker-value" name="{{ $name }}" value="{{ $value ?? '' }}">
    <button type="button" class="btn btn-light border icon-picker-btn d-flex align-items-center gap-2 text-start"
        title="Choose icon">
        <span class="icon-picker-preview"><i class="site-bx {{ ($value ?? null) ?: 'bx bx-check-circle' }}"></i></span>
        <span class="fs-13">{{ filled($value ?? null) ? 'Change icon' : 'Choose icon' }}</span>
    </button>
</div>
