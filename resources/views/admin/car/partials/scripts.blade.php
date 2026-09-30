<script>
    // =========================================================================
    // Shared Car Form AI & Import Workflow (Used by Create and Edit pages)
    // =========================================================================

    /**
     * Collect current/latest values from the car form DOM elements.
     * Ensures unsaved modifications in the browser are sent to AI.
     */
    function collectCarFormData() {
        var makeText = $('#manufacturer_id option:selected').text();
        var make = (makeText && makeText !== 'Select Make') ? makeText.trim() : '';

        var categoryText = $('#category_id option:selected').text();
        var category = (categoryText && categoryText !== 'Select Category') ? categoryText.trim() : '';

        var statusText = $('#status option:selected').text();
        var status = (statusText && statusText !== 'Select Status') ? statusText.trim() : '';

        var gradeText = $('#auction_grade_id option:selected').text();
        var auctionGrade = (gradeText && gradeText !== 'Select Auction Grade') ? gradeText.trim() : '';

        var fuelType = $('#fuel_type').val() || '';
        if ($('input[name="fuel_custom_option"]:checked').val() === '1') {
            var customFuel = ($('#fuel_type_custom').val() || '').trim();
            if (customFuel) {
                fuelType = fuelType ? (fuelType + ' (' + customFuel + ')') : customFuel;
            }
        }

        var transmission = $('#transmission').val() || '';
        if ($('input[name="transmission_custom_option"]:checked').val() === '1') {
            var customTrans = ($('#transmission_custom').val() || '').trim();
            if (customTrans) {
                transmission = transmission ? (transmission + ' (' + customTrans + ')') : customTrans;
            }
        }

        var extColor = $('input[name="exterior_color"]:checked').siblings('.color-name').text().trim();
        var intColor = $('input[name="interior_color"]:checked').siblings('.color-name').text().trim();

        return {
            make: make,
            model: ($('#model').val() || '').trim(),
            year: ($('#year').val() || '').trim(),
            category: category,
            status: status,
            auction_grade: auctionGrade,
            location: ($('#location').val() || '').trim(),
            price: ($('#vehicle_price').val() || '').trim(),
            stock_id: ($('#vehicle_id').val() || '').trim(),
            vin: ($('#vin').val() || '').trim(),
            body_type: ($('#body_type').val() || '').trim(),
            type: ($('#type').val() || '').trim(),
            steering: ($('#steering').val() || '').trim(),
            interior_grade: ($('#interior_grade').val() || '').trim(),
            exterior_grade: ($('#exterior_grade').val() || '').trim(),
            drive_type: ($('#drive_type').val() || '').trim(),
            engine: ($('#engine').val() || '').trim(),
            fuel_type: fuelType,
            transmission: transmission,
            odometer: ($('#odometer').val() || '').trim(),
            exterior_color: extColor,
            interior_color: intColor,
            card_header: ($('#card_header').val() || '').trim(),
            card_subtitle: ($('#card_subtitle').val() || '').trim(),
        };
    }

    $(document).ready(function () {
        // --- AI Content Generation Handler ---
        $('#btn-generate-ai').on('click', function (e) {
            e.preventDefault();

            var currentCarData = collectCarFormData();
            var promptText = ($('#ai_prompt').val() || '').trim();

            // Verify that at least some vehicle information or custom prompt is present
            var hasVehicleInfo = false;
            for (var k in currentCarData) {
                if (currentCarData[k]) {
                    hasVehicleInfo = true;
                    break;
                }
            }

            if (!hasVehicleInfo && !promptText) {
                var errorMsg = 'Please enter vehicle details (such as Make, Model, or Year) or provide a prompt before generating AI description.';
                $('#ai_prompt-error').show().text(errorMsg);
                if (typeof sendError === 'function') {
                    sendError(errorMsg);
                }
                return false;
            }

            $('#ai_prompt-error').hide().text('');
            const $btn = $(this);

            $.ajax({
                url: "{{ route('admin.car.generate-ai-content') }}",
                type: "POST",
                dataType: "json",
                data: {
                    _token: "{{ csrf_token() }}",
                    prompt: promptText,
                    vehicle_data: currentCarData
                },
                beforeSend: function () {
                    $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...');
                },
                success: function (result) {
                    if (result.status && result.content) {
                        $('#description_editor').val(result.content);
                        if (typeof tinymce !== 'undefined' && tinymce.get('description_editor')) {
                            tinymce.get('description_editor').setContent(result.content);
                            tinymce.get('description_editor').fire('change');
                        }
                        $('#description').val(result.content);
                        $('#description-error').hide();

                        if (typeof sendSuccess === 'function') {
                            sendSuccess(result.message || 'AI Content generated successfully!');
                        }
                    } else {
                        if (typeof sendError === 'function') {
                            sendError(result.message || 'Failed to generate content.');
                        } else {
                            alert(result.message || 'Failed to generate content.');
                        }
                    }
                },
                error: function (xhr) {
                    let data = xhr.responseJSON;
                    let errorMsg = 'An error occurred while generating AI content.';
                    if (data && data.hasOwnProperty('error')) {
                        if (typeof data.error === 'string') {
                            errorMsg = data.error;
                        } else if (typeof data.error === 'object') {
                            let firstKey = Object.keys(data.error)[0];
                            errorMsg = Array.isArray(data.error[firstKey]) ? data.error[firstKey][0] : data.error[firstKey];
                        }
                    } else if (data && data.hasOwnProperty('errors')) {
                        let firstKey = Object.keys(data.errors)[0];
                        errorMsg = Array.isArray(data.errors[firstKey]) ? data.errors[firstKey][0] : data.errors[firstKey];
                    } else if (data && data.hasOwnProperty('message')) {
                        errorMsg = data.message;
                    }

                    $('#ai_prompt-error').show().text(errorMsg);
                    if (typeof sendError === 'function') {
                        sendError(errorMsg);
                    } else {
                        alert(errorMsg);
                    }
                },
                complete: function () {
                    $btn.prop('disabled', false).html('<i class="ri-magic-line me-1"></i> Generate AI Content');
                }
            });
        });

        // =====================================================================
        // CarSensor Import Workflow
        // =====================================================================

        // --- Modal control ---
        $('#btn-open-carsensor-modal').on('click', function () {
            $('#carsensor_url').val('');
            $('#carsensor-url-error').addClass('d-none').text('');
            $('#carsensor-progress').addClass('d-none');
            $('#carsensor-progress-bar').css('width', '0%');
            $('#btn-start-import').prop('disabled', false).html('<i class="ri-download-2-line me-1"></i> Import &amp; Fill Form');
            var modal = new bootstrap.Modal(document.getElementById('carSensorImportModal'));
            modal.show();
        });

        $('#btn-dismiss-summary').on('click', function () {
            $('#carsensor-import-summary').addClass('d-none');
        });

        // --- Import trigger ---
        $('#btn-start-import').on('click', function () {
            var url = $('#carsensor_url').val().trim();
            var $btn = $(this);

            $('#carsensor-url-error').addClass('d-none').text('');

            if (!url) {
                $('#carsensor-url-error').removeClass('d-none').text('Please enter a listing URL.');
                return;
            }

            if (!url.match(/^https?:\/\//i)) {
                $('#carsensor-url-error').removeClass('d-none').text('Please enter a valid URL (must start with http:// or https://).');
                return;
            }

            $('#carsensor-progress').removeClass('d-none');
            $('#btn-cancel-import').prop('disabled', true);
            $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span> Importing...');
            csAnimateProgress(10, 60, 45000);

            var postData = {
                _token: "{{ csrf_token() }}",
                url: url
            };

            var currentCarId = @json($carId ?? null);
            if (currentCarId) {
                postData.car_id = currentCarId;
            }

            $.ajax({
                url: "{{ route('admin.car.import-carsensor') }}",
                type: 'POST',
                dataType: 'json',
                data: postData,
                timeout: 180000,
                success: function (result) {
                    csAnimateProgress(100, 100, 500);

                    setTimeout(function () {
                        bootstrap.Modal.getInstance(document.getElementById('carSensorImportModal')).hide();
                        $('#btn-cancel-import').prop('disabled', false);

                        if (result.success) {
                            csPopulateForm(result);
                            csShowSummary(result);
                            if (typeof sendSuccess === 'function') {
                                sendSuccess('Listing import complete! Please review the form before {{ !empty($isEdit) ? "updating" : "creating" }} the car.');
                            }
                        } else if (result.duplicate) {
                            var dupMsg = result.message || 'This listing has already been imported.';
                            if (result.edit_url) {
                                dupMsg += ' <a href="' + result.edit_url + '" target="_blank" class="alert-link">View existing vehicle &rarr;</a>';
                            }
                            if (typeof sendError === 'function') {
                                sendError('Duplicate: ' + result.message);
                            }
                            alert('⚠️ Duplicate Detected\n\n' + result.message);
                        } else {
                            if (typeof sendError === 'function') {
                                sendError(result.message || 'Import failed. Please try again.');
                            } else {
                                alert('Import failed: ' + (result.message || 'Unknown error'));
                            }
                        }
                    }, 600);
                },
                error: function (xhr) {
                    $('#btn-cancel-import').prop('disabled', false);
                    $btn.prop('disabled', false).html('<i class="ri-download-2-line me-1"></i> Import &amp; Fill Form');
                    $('#carsensor-progress').addClass('d-none');

                    var data = xhr.responseJSON;
                    var msg = 'An error occurred during import.';

                    if (xhr.status === 409 && data && data.duplicate) {
                        bootstrap.Modal.getInstance(document.getElementById('carSensorImportModal')).hide();
                        alert('⚠️ Duplicate Detected\n\n' + (data.message || 'This listing has already been imported.'));
                        return;
                    }

                    if (data && data.message) {
                        msg = data.message;
                    } else if (data && data.error) {
                        if (typeof data.error === 'object') {
                            var firstKey = Object.keys(data.error)[0];
                            msg = Array.isArray(data.error[firstKey]) ? data.error[firstKey][0] : data.error[firstKey];
                        } else {
                            msg = data.error;
                        }
                    } else if (xhr.status === 0) {
                        msg = 'Request timed out or network error. Listing scraping can take up to 2 minutes — please try again.';
                    }

                    $('#carsensor-url-error').removeClass('d-none').text(msg);
                    if (typeof sendError === 'function') {
                        sendError(msg);
                    }
                }
            });
        });

        // --- Animate progress bar ---
        function csAnimateProgress(from, to, durationMs) {
            var current = from;
            var steps = 30;
            var stepValue = (to - from) / steps;
            var stepDuration = durationMs / steps;

            var interval = setInterval(function () {
                current += stepValue;
                if (current >= to) {
                    current = to;
                    clearInterval(interval);
                }
                $('#carsensor-progress-bar').css('width', current + '%');
            }, stepDuration);
        }

        // --- Populate form with imported data ---
        function csPopulateForm(result) {
            var data = result.data || {};

            if (data.manufacturer_id) {
                $('#manufacturer_id').val(data.manufacturer_id).trigger('change');
            }

            if (data.model) {
                $('#model').val(data.model).trigger('input');
            }

            if (data.year) {
                $('#year').val(data.year).trigger('change');
            }

            if (data.category_id) {
                $('#category_id').val(data.category_id).trigger('change');
            }

            if (data.status) {
                $('#status').val(data.status).trigger('change');
            }

            if (data.auction_grade_id) {
                $('#auction_grade_id').val(data.auction_grade_id).trigger('change');
            }

            if (data.location) {
                $('#location').val(data.location).trigger('input');
            }

            if (data.vehicle_price) {
                $('#vehicle_price').val(data.vehicle_price).trigger('input');
            }

            if (data.card_header) {
                setTimeout(function () {
                    var currentHeader = $('#card_header').val();
                    if (!currentHeader || currentHeader.trim() === '') {
                        $('#card_header').val(data.card_header).trigger('input');
                    }
                }, 500);
            }

            if (data.card_subtitle) {
                setTimeout(function () {
                    var currentSubtitle = $('#card_subtitle').val();
                    if (!currentSubtitle || currentSubtitle.trim() === '') {
                        $('#card_subtitle').val(data.card_subtitle).trigger('input');
                    }
                }, 500);
            }

            if (data.vehicle_id) {
                $('#vehicle_id_type_manual').prop('checked', true).trigger('change');
                $('input[name="vehicle_id_type"]').trigger('change');
                setTimeout(function () {
                    $('#vehicle_id_container').show();
                    $('#vehicle_id').val(data.vehicle_id).trigger('input');
                }, 100);
            }

            if (data.is_recommended === 0 || data.is_recommended === false) {
                $('#is_recommended').prop('checked', false);
            }

            if (data.private_notes) {
                $('#private_notes').val(data.private_notes).trigger('input');
            }

            if (data.vin) {
                $('#vin').val(data.vin).trigger('input');
            }

            if (data.body_type) {
                $('#body_type').val(data.body_type).trigger('change');
            }

            if (data.drive_type) {
                $('#drive_type').val(data.drive_type).trigger('change');
            }

            if (data.steering) {
                $('#steering').val(data.steering).trigger('change');
            }

            if (data.interior_grade) {
                $('#interior_grade').val(data.interior_grade).trigger('change');
            }

            if (data.exterior_grade) {
                $('#exterior_grade').val(data.exterior_grade).trigger('change');
            }

            if (data.engine) {
                $('#engine').val(data.engine).trigger('input');
            }

            if (data.odometer) {
                $('#odometer').val(data.odometer).trigger('input');
            }

            if (data.fuel_type) {
                $('#fuel_type').val(data.fuel_type).trigger('change');
            }
            if (data.fuel_custom_option === '1') {
                $('#fuel_custom_on').prop('checked', true).trigger('change');
                if (data.fuel_type_custom) {
                    $('#fuel_type_custom').val(data.fuel_type_custom).trigger('input');
                }
            }

            if (data.transmission) {
                $('#transmission').val(data.transmission).trigger('change');
            }
            if (data.trans_custom_option === '1') {
                $('#trans_custom_on').prop('checked', true).trigger('change');
                if (data.transmission_custom) {
                    $('#transmission_custom').val(data.transmission_custom).trigger('input');
                }
            }

            if (data.exterior_color) {
                $('input[name="exterior_color"][value="' + data.exterior_color + '"]').prop('checked', true).trigger('change');
            }
            if (data.interior_color) {
                $('input[name="interior_color"][value="' + data.interior_color + '"]').prop('checked', true).trigger('change');
            }

            if (data.description) {
                setTimeout(function () {
                    $('#description').val(data.description);
                    if (typeof tinymce !== 'undefined' && tinymce.get('description_editor')) {
                        tinymce.get('description_editor').setContent(data.description);
                        tinymce.get('description_editor').fire('change');
                    } else {
                        $('#description_editor').val(data.description);
                    }
                    $('#description-error').hide();
                }, 800);
            }

            if (result.filepond_images && result.filepond_images.length > 0) {
                csLoadFilePondImages(result.filepond_images);
            }

            if (result.banner_data) {
                csLoadBannerImage(result.banner_data);
            }
        }

        // --- Load images into FilePond ---
        function csLoadFilePondImages(filepondJsonArray) {
            if (!filepondJsonArray || filepondJsonArray.length === 0) return;

            var inputEl = document.querySelector('input.filepond');
            if (!inputEl) {
                console.warn('Listing Import: FilePond input element not found.');
                return;
            }

            var pond = FilePond.find(inputEl);
            if (!pond) {
                console.warn('Listing Import: FilePond instance not found.');
                return;
            }

            var filesToLoad = [];

            filepondJsonArray.forEach(function (jsonStr, index) {
                try {
                    var parsed = JSON.parse(jsonStr);
                    var mimeType = parsed.type || 'image/jpeg';
                    var base64Data = parsed.data;
                    var byteChars = atob(base64Data);
                    var byteArray = new Uint8Array(byteChars.length);
                    for (var i = 0; i < byteChars.length; i++) {
                        byteArray[i] = byteChars.charCodeAt(i);
                    }
                    var blob = new Blob([byteArray], { type: mimeType });
                    var ext = mimeType.split('/')[1] || 'jpg';
                    var file = new File([blob], 'imported_image_' + (index + 1) + '.' + ext, { type: mimeType });
                    filesToLoad.push(file);
                } catch (e) {
                    console.warn('Listing Import: Could not parse image ' + (index + 1), e);
                }
            });

            if (filesToLoad.length > 0) {
                pond.addFiles(filesToLoad);
            }
        }

        // --- Load banner image into the banner file input ---
        function csLoadBannerImage(filepondJsonStr) {
            if (!filepondJsonStr) return;

            try {
                var parsed = JSON.parse(filepondJsonStr);
                var mimeType = parsed.type || 'image/jpeg';
                var base64Data = parsed.data;

                var byteChars = atob(base64Data);
                var byteArray = new Uint8Array(byteChars.length);
                for (var i = 0; i < byteChars.length; i++) {
                    byteArray[i] = byteChars.charCodeAt(i);
                }
                var blob = new Blob([byteArray], { type: mimeType });
                var ext = mimeType.split('/')[1] || 'jpg';
                var file = new File([blob], 'imported_banner.' + ext, { type: mimeType });

                var bannerInput = document.getElementById('banner');
                if (bannerInput) {
                    var dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    bannerInput.files = dataTransfer.files;

                    bannerInput.dispatchEvent(new Event('change', { bubbles: true }));

                    var previewContainer = bannerInput.closest('label')?.querySelector('.uploaded-preview');
                    if (previewContainer) {
                        var reader = new FileReader();
                        reader.onload = function (e) {
                            previewContainer.innerHTML = '<img src="' + e.target.result + '" class="img-thumbnail" style="max-height:120px;" alt="Banner preview">';
                        };
                        reader.readAsDataURL(file);
                    }
                }
            } catch (e) {
                console.warn('Listing Import: Could not load banner image', e);
            }
        }

        // --- Render the import summary panel ---
        function csShowSummary(result) {
            var data = result.data || {};
            var raw = result.scraped_raw || {};

            var rows = [
                ['Source URL', '<a href="' + (result.source_url || '') + '" target="_blank" class="text-truncate d-inline-block" style="max-width:200px;">' + (result.source_id || '') + '</a>'],
                ['Listing ID', result.source_id || '-'],
                ['Manufacturer', raw.manufacturer || '-'],
                ['Model', data.model || '-'],
                ['Year', raw.year || '-'],
                ['Price (JPY)', raw.vehicle_price ? '¥' + parseInt(raw.vehicle_price).toLocaleString() : '-'],
                ['Odometer', raw.odometer ? parseInt(raw.odometer).toLocaleString() + ' km' : '-'],
                ['Body Type', raw.body_type || '-'],
                ['Location', raw.location || '-'],
                ['Images Downloaded', (result.images_downloaded || 0) + ' of ' + (result.images_total || 0)],
            ];

            var tableHtml = '';
            rows.forEach(function (row) {
                tableHtml += '<tr><td class="text-muted small pe-2 text-nowrap">' + row[0] + '</td><td class="fw-medium small">' + row[1] + '</td></tr>';
            });
            $('#carsensor-summary-table').html(tableHtml);

            var reviewItems = result.needs_manual_review || [];
            var $reviewCol = $('#carsensor-review-col');
            var $reviewList = $('#carsensor-review-list');

            if (reviewItems.length > 0) {
                var reviewHtml = '';
                reviewItems.forEach(function (item) {
                    reviewHtml += '<li class="d-flex gap-2 mb-2 text-danger small"><i class="ri-error-warning-line flex-shrink-0 mt-1"></i><span>' + item + '</span></li>';
                });
                $reviewList.html(reviewHtml);
                $reviewCol.show();
            } else {
                $reviewCol.html('<p class="text-success small"><i class="ri-checkbox-circle-line me-1"></i>All fields were mapped with high confidence.</p>');
            }

            $('#carsensor-import-summary').removeClass('d-none');
            $('html, body').animate({ scrollTop: $('#carsensor-import-summary').offset().top - 20 }, 500);
        }
    });
</script>
