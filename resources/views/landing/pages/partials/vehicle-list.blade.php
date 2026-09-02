@forelse($cars as $car)
    @include('landing.pages.partials.vehicle-card', ['car' => $car])
@empty
    <div class="no-results" id="no-results-message"
        style="text-align: center; padding: 60px 20px; background: #ffffff; border-radius: 12px; border: 1px dashed #cbd5e1; margin: 20px 0; width: 100%;">
        <div style="font-size: 48px; color: #94a3b8; margin-bottom: 16px;">
            <i class="bx bx-search-alt"></i>
        </div>
        <h3 style="font-size: 20px; font-weight: 700; color: #1e293b; margin-bottom: 8px;">No Match Found</h3>
        <p
            style="font-size: 14px; color: #64748b; margin-bottom: 20px; max-width: 400px; margin-left: auto; margin-right: auto;">
            We couldn't find any vehicles matching your search criteria. Try adjusting your filters or resetting
            your conditions.
        </p>
        <button type="button" onclick="document.querySelectorAll('[id=\'btn-reset-filters\']')[0]?.click()"
            style="background: #1e293b; color: white; border: none; padding: 10px 24px; border-radius: 6px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
            Reset Conditions
        </button>
    </div>
@endforelse