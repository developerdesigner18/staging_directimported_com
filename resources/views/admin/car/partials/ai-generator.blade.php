<!-- AI Content Generator Card -->
<div class="card mb-4">
    <div class="card-header bg-light d-flex align-items-center justify-content-between">
        <h5 class="mb-0">
            <i class="ri-sparkling-fill text-primary me-1"></i> AI Content Generator
        </h5>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <label for="ai_prompt" class="form-label">Additional Features / Prompt (Optional)</label>
            <textarea class="form-control" id="ai_prompt" name="ai_prompt" rows="3"
                placeholder="Optionally add special features, condition notes, or instructions to include in the generated description..."></textarea>
            <label id="ai_prompt-error" class="text-danger error" style="display: none"></label>
        </div>
        <button type="button" id="btn-generate-ai" class="btn btn-primary">
            <i class="ri-magic-line me-1"></i> Generate AI Content
        </button>
    </div>
</div>
