{{--
    Shared icon picker modal. Include once per page, inside @section('script') after jQuery/Bootstrap.
    The icon list and glyphs are read from the website's own Boxicons stylesheet, so the admin
    shows exactly the icons (and designs) that the public site can display.
--}}
<style>
    .icon-picker-preview {
        width: 32px;
        height: 32px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        background: #fff;
        border: 1px solid #e9ebec;
        color: #053C7C;
        font-size: 20px;
    }

    .icon-picker-preview.icon-picker-missing {
        border-color: #f06548;
        background: #fef4f2;
        color: #f06548;
    }

    #iconPickerGrid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(92px, 1fr));
        gap: 8px;
        max-height: 55vh;
        overflow-y: auto;
    }

    .icon-picker-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        padding: 10px 4px;
        border: 1px solid #e9ebec;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .icon-picker-option i {
        font-size: 26px;
        color: #053C7C;
    }

    .icon-picker-option span {
        font-size: 11px;
        color: #6b7280;
        text-align: center;
        line-height: 1.2;
        word-break: break-word;
    }

    .icon-picker-option:hover,
    .icon-picker-option.selected {
        border-color: #405189;
        background: #f3f6f9;
    }

    .icon-picker-option.selected {
        box-shadow: 0 0 0 2px rgba(64, 81, 137, .35);
    }
</style>

<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">Choose an icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="search" class="form-control mb-3" id="iconPickerSearch"
                    placeholder="Search icons, e.g. car, ship, check, money, globe..." autocomplete="off">
                <h6 class="text-muted fs-12 text-uppercase mb-2" id="iconPickerSuggestedTitle">Suggested</h6>
                <div id="iconPickerSuggested" class="mb-3" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(92px,1fr));gap:8px;"></div>
                <h6 class="text-muted fs-12 text-uppercase mb-2" id="iconPickerAllTitle">All icons</h6>
                <div id="iconPickerGrid"><div class="text-muted">Loading icons...</div></div>
                <p class="text-muted text-center my-4" id="iconPickerEmpty" style="display:none">No icons match your search.</p>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-soft-secondary" id="iconPickerClear">Use default icon</button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

<script>
    (function () {
        const CSS_URL = "{{ asset('assets/vendor/boxicons/latest/boxicons-basic.min.css') }}";
        const FONT_BASE = "{{ asset('assets/vendor/boxicons/latest') }}/";
        const DEFAULT_ICON = 'bx bx-check-circle';
        // Common picks for this site; only the ones present in the website's icon set are shown
        const SUGGESTED = ['car', 'car-side', 'ship', 'truck', 'globe', 'map', 'check-circle', 'badge-check',
            'shield-quarter', 'shield-alt-2', 'gavel', 'store-alt', 'dollar-circle', 'yen', 'receipt', 'tag',
            'file', 'file-detail', 'camera', 'user-check', 'search-alt', 'tachometer', 'wrench', 'cog', 'key',
            'package', 'award', 'trophy', 'star', 'heart', 'time', 'calendar', 'phone', 'envelope', 'info-circle', 'block'];

        let icons = null;
        let $target = null;

        const label = name => name.replace(/-/g, ' ');

        // Load the website's icon stylesheet once and expose it to the admin under its own font name
        const loadIcons = () => icons ? Promise.resolve(icons) : fetch(CSS_URL).then(r => r.text()).then(css => {
            const rules = [...css.matchAll(/\.bx-([a-z0-9-]+):before\{content:"([^"]+)";?\}/g)];
            icons = rules.map(m => m[1]);
            const style = document.createElement('style');
            style.textContent =
                '@font-face{font-family:"site-boxicons";src:url("' + FONT_BASE + 'boxicons.woff2") format("woff2"),url("' + FONT_BASE + 'boxicons.woff") format("woff");}' +
                'i.site-bx{font-family:"site-boxicons" !important;font-style:normal;font-weight:normal;line-height:1;display:inline-block;}' +
                'i.site-bx:before{content:"";}' +
                rules.map(m => 'i.site-bx.bx-' + m[1] + ':before{content:"' + m[2] + '";}').join('');
            document.head.appendChild(style);
            return icons;
        });

        const optionHtml = name =>
            '<button type="button" class="icon-picker-option" data-icon="' + name + '" title="' + label(name) + '">' +
            '<i class="site-bx bx-' + name + '"></i><span>' + label(name) + '</span></button>';

        const renderGrid = list => {
            const suggested = SUGGESTED.filter(name => list.includes(name));
            $('#iconPickerSuggested').html(suggested.map(optionHtml).join(''));
            $('#iconPickerGrid').html(list.map(optionHtml).join(''));
        };

        const markSelected = () => {
            const current = ($target.find('.icon-picker-value').val() || '').replace(/^bx\s+bx-/, '');
            $('#iconPickerModal .icon-picker-option').removeClass('selected')
                .filter('[data-icon="' + current + '"]').addClass('selected');
        };

        // Flag saved icons that the website cannot display, so they can be replaced
        const flagMissing = () => {
            $('.icon-picker').each(function () {
                const value = $(this).find('.icon-picker-value').val();
                const name = (value || '').replace(/^bx\s+bx-/, '');
                if (value && !icons.includes(name)) {
                    $(this).find('.icon-picker-preview').addClass('icon-picker-missing')
                        .attr('title', 'This icon is not available on the website. Please choose another.')
                        .find('i').attr('class', 'site-bx bx-error');
                    $(this).find('.icon-picker-btn span:last').text('Choose again');
                }
            });
        };

        const setValue = value => {
            $target.find('.icon-picker-value').val(value).trigger('change');
            $target.find('.icon-picker-preview').removeClass('icon-picker-missing').removeAttr('title');
            $target.find('.icon-picker-preview i').attr('class', 'site-bx ' + (value || DEFAULT_ICON));
            $target.find('.icon-picker-btn span:last').text(value ? 'Change icon' : 'Choose icon');
            bootstrap.Modal.getOrCreateInstance(document.getElementById('iconPickerModal')).hide();
        };

        // Load early so previews already on the page use the website's icon designs
        $(function () {
            loadIcons().then(list => { renderGrid(list); flagMissing(); }).catch(function () {
                $('#iconPickerGrid').html('<div class="text-danger">Could not load the icon list. Please refresh the page.</div>');
            });
        });

        $(document).on('click', '.icon-picker-btn', function () {
            $target = $(this).closest('.icon-picker');
            $('#iconPickerSearch').val('').trigger('input');
            bootstrap.Modal.getOrCreateInstance(document.getElementById('iconPickerModal')).show();
            loadIcons().then(markSelected);
        });

        $('#iconPickerModal').on('shown.bs.modal', function () {
            $('#iconPickerSearch').trigger('focus');
        });

        $(document).on('click', '#iconPickerModal .icon-picker-option', function () {
            setValue('bx bx-' + $(this).data('icon'));
        });

        $('#iconPickerClear').on('click', function () {
            setValue('');
        });

        $('#iconPickerSearch').on('input', function () {
            const terms = $(this).val().toLowerCase().trim().split(/\s+/).filter(Boolean);
            const searching = terms.length > 0;
            let visible = 0;
            $('#iconPickerGrid .icon-picker-option').each(function () {
                const name = label($(this).data('icon'));
                const match = terms.every(term => name.includes(term));
                $(this).toggle(match);
                visible += match ? 1 : 0;
            });
            $('#iconPickerSuggested, #iconPickerSuggestedTitle').toggle(!searching);
            $('#iconPickerAllTitle').text(searching ? visible + ' matching icons' : 'All icons');
            $('#iconPickerEmpty').toggle(searching && visible === 0);
        });
    })();
</script>
