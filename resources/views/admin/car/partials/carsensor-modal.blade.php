{{-- ===== CarSensor Import Modal ===== --}}
<div class="modal fade" id="carSensorImportModal" tabindex="-1" aria-labelledby="carSensorImportModalLabel"
    aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning bg-opacity-10 border-bottom border-warning">
                <h5 class="modal-title d-flex align-items-center gap-2" id="carSensorImportModalLabel">
                    <i class="ri-import-line text-warning fs-4"></i>
                    Import Car from Listing URL
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" id="btn-close-modal"></button>
            </div>

            <div class="modal-body">
                {{-- Copyright Warning --}}
                <div class="alert alert-warning d-flex gap-2 align-items-start mb-4" role="alert">
                    <i class="ri-alert-line fs-5 flex-shrink-0 mt-1"></i>
                    <div>
                        <strong>Image Rights Notice:</strong> Confirm you have the right to re-host images from this
                        listing. Scraping a public image does not automatically grant republication rights. Ensure your
                        use complies with the source site's terms of service and applicable copyright law.
                    </div>
                </div>

                {{-- URL Input --}}
                <div class="mb-4">
                    <label for="carsensor_url" class="form-label fw-semibold">
                        <i class="ri-links-line me-1"></i>Vehicle Listing URL
                    </label>
                    <input type="url" class="form-control form-control-lg" id="carsensor_url"
                        placeholder="https://www.carsensor.net/usedcar/detail/AU7320809064/index.html"
                        autocomplete="off">
                    <div class="form-text text-muted">Works with <strong>CarSensor.net</strong>, Goo-net, Yahoo
                        Auctions, and other public used-car listing pages.</div>
                    <div id="carsensor-url-error" class="text-danger mt-1 d-none"></div>
                </div>

                {{-- Progress Panel (hidden until import starts) --}}
                <div id="carsensor-progress" class="d-none">
                    <div class="d-flex align-items-center gap-3 p-3 bg-light rounded mb-3">
                        <div class="spinner-border text-warning" role="status" aria-hidden="true"></div>
                        <div>
                            <div class="fw-semibold" id="carsensor-progress-title">Importing...</div>
                            <div class="text-muted small" id="carsensor-progress-subtitle">Fetching and analysing
                                listing data with AI...</div>
                        </div>
                    </div>
                    <div class="progress" style="height:6px;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-warning"
                            id="carsensor-progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"
                    id="btn-cancel-import">Cancel</button>
                <button type="button" class="btn btn-warning px-4" id="btn-start-import">
                    <i class="ri-download-2-line me-1"></i> Import &amp; Fill Form
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== Import Summary Panel (shown after successful import) ===== --}}
<div id="carsensor-import-summary" class="d-none mb-4">
    <div class="card border-success">
        <div class="card-header bg-success bg-opacity-10 d-flex justify-content-between align-items-center">
            <h6 class="mb-0 text-success d-flex align-items-center gap-2">
                <i class="ri-checkbox-circle-line fs-5"></i>
                Car Listing Data Imported Successfully
            </h6>
            <button type="button" class="btn-close btn-close-sm" id="btn-dismiss-summary" aria-label="Close"></button>
        </div>
        <div class="card-body">
            <div class="alert alert-info d-flex gap-2 align-items-start mb-3">
                <i class="ri-information-line fs-5 flex-shrink-0 mt-1"></i>
                <div>
                    <strong>The {{ !empty($isEdit) ? 'Edit' : 'Create' }} Car form has been populated but has NOT been
                        submitted.</strong><br>
                    Please review all fields carefully before clicking
                    <strong>"{{ !empty($isEdit) ? 'Update Vehicle' : 'Create Vehicle' }}"</strong>.
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2">Import Summary</h6>
                    <table class="table table-sm table-borderless mb-0">
                        <tbody id="carsensor-summary-table">
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6" id="carsensor-review-col">
                    <h6 class="text-muted text-uppercase small fw-bold mb-2 text-danger" id="carsensor-review-title">
                        Needs Manual Review</h6>
                    <ul class="list-unstyled mb-0" id="carsensor-review-list">
                    </ul>
                    <p class="text-success small d-none" id="carsensor-review-none"><i
                            class="ri-checkbox-circle-line me-1"></i>All fields were mapped with high confidence.</p>
                </div>
            </div>
        </div>
    </div>
</div>