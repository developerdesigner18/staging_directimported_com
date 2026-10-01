<script>
    // Homepage icon (single file, keeps transparency) and Services page feature tags
    $(document).ready(function () {
        const iconInput = document.querySelector('input.filepond-icon');
        if (iconInput && typeof FilePond !== 'undefined') {
            FilePond.create(iconInput, {
                allowMultiple: false,
                allowImageResize: false,
                allowImageTransform: false,
                acceptedFileTypes: ['image/png', 'image/jpeg', 'image/webp', 'image/gif'],
                maxFileSize: '2MB'
            });
        }

        let featureIndex = $('#featuresContainer .feature-row').length;

        $('#addFeature').on('click', function () {
            const html = $('#featureRowTemplate').html().replace(/__INDEX__/g, featureIndex++);
            $('#featuresContainer').append(html);
        });

        $(document).on('click', '.remove-feature', function () {
            $(this).closest('.feature-row').remove();
        });
    });
</script>
@include('admin.partials.icon-picker-modal')
