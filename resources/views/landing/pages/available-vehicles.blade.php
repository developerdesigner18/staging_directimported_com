@extends('landing.master')
@section('title', 'Available Vehicles')
@push('style')
    <style>
        :root {
            --bg-color: #F4F6F9;
            --card-bg: #FFFFFF;
            --text-heading: #0F2C59;
            --text-main: #4A5568;
            --text-muted: #718096;
            --primary-red: #CC1921;
            --border-color: #CBD5E0;
            --blue-link: #4299E1;
            --font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        }

        body {
            background-color: var(--bg-color);
            font-family: var(--font-family);
            color: var(--text-main);
            margin: 0;
            align-items: center;
        }

        .container {
            max-width: 1000px;
            width: 100%;
        }

        .search-card {
            margin-top: 20px;
        }

        /* --- STICKY BAR (Compacting Search Feature) --- */
        .sticky-wrapper {
            position: fixed;
            top: -100px;
            left: 0;
            width: 100%;
            z-index: 1000;
            transition: top 0.3s ease-in-out;
            display: flex;
            justify-content: center;
            padding-top: 10px;
        }

        .sticky-wrapper.visible {
            top: 0;
        }

        .sticky-bar-inner {
            width: 100%;
            max-width: 1000px;
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 12px 20px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .sticky-title {
            font-weight: 700;
            color: var(--text-heading);
            font-size: 16px;
        }

        .open-search-tab {
            position: absolute;
            bottom: -28px;
            left: 50%;
            transform: translateX(-50%);
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-top: none;
            padding: 4px 16px;
            border-radius: 0 0 6px 6px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
            display: flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 13px;
            color: var(--text-main);
            font-weight: 600;
            z-index: 1001;
        }

        .open-search-tab:hover {
            background: #F9FAFB;
        }

        .chevron-icon {
            width: 14px;
            height: 14px;
            transition: transform 0.2s;
        }

        .dropdown-search {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            z-index: 999;
            background: #fff;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--border-color);
            border-top: none;
            border-radius: 0 0 6px 6px;
        }

        .dropdown-search.active {
            display: block;
        }

        .close-dropdown-tab {
            background: #F8FAFC;
            border-top: 1px solid var(--border-color);
            padding: 10px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            font-size: 13px;
            color: var(--text-main);
            font-weight: 600;
            border-radius: 0 0 6px 6px;
            transition: background 0.2s;
        }

        .close-dropdown-tab:hover {
            background: #E2E8F0;
        }

        /* --- SEARCH PANEL --- */
        .search-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 6px;
            margin-bottom: 25px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        }

        .search-header {
            background-color: #E2E8F0;
            padding: 12px 20px;
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--text-heading);
            border-bottom: 1px solid var(--border-color);
        }

        .search-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px 30px;
            align-items: center;
        }

        .form-group {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .form-group label {
            width: 110px;
            font-weight: 700;
            color: var(--text-heading);
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .form-group input[type="text"],
        .form-group select {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 0.9rem;
            color: var(--text-main);
            outline: none;
            background-color: #FFF;
        }

        .range-group {
            display: flex;
            align-items: center;
            gap: 6px;
            flex: 1;
        }

        .range-group select {
            width: 100%;
        }

        /* --- OVERLAPPING DUAL PRICE SLIDER SECTION --- */
        .price-row {
            display: flex;
            align-items: center;
            gap: 15px;
            padding-top: 10px;
        }

        .price-label-title {
            width: 110px;
            font-weight: 700;
            color: var(--text-heading);
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .price-slider-container {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .price-value-box {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-heading);
            white-space: nowrap;
            min-width: 95px;
            text-align: center;
        }

        .dual-range-wrapper {
            position: relative;
            flex: 1;
            height: 30px;
            display: flex;
            align-items: center;
        }

        .slider-track {
            position: absolute;
            width: 100%;
            height: 6px;
            background-color: #CBD5E0;
            border-radius: 3px;
            z-index: 1;
        }

        .range-input {
            position: absolute;
            width: 100%;
            height: 6px;
            background: none;
            pointer-events: none;
            -webkit-appearance: none;
            appearance: none;
            margin: 0;
            z-index: 2;
            outline: none;
        }

        .range-input::-webkit-slider-thumb {
            height: 20px;
            width: 20px;
            border-radius: 50%;
            background: #4299E1;
            pointer-events: auto;
            -webkit-appearance: none;
            cursor: pointer;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.3);
            border: 2px solid white;
            transition: transform 0.1s ease;
        }

        .range-input::-webkit-slider-thumb:hover {
            transform: scale(1.15);
            background: #3182CE;
        }

        /* --- FOOTER BUTTONS --- */
        .search-footer {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 15px;
            padding-top: 18px;
            border-top: 1px dashed var(--border-color);
            margin-top: 10px;
        }

        .search-footer button {
            height: 44px;
            padding: 0 22px;
            font-family: inherit;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease-in-out;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .btn-reset {
            justify-self: start;
            background-color: #F7FAFC;
            border: 1px solid #CBD5E0;
            color: #4A5568;
        }

        .btn-reset:hover {
            background-color: #EDF2F7;
            color: #2D3748;
            border-color: #A0AEC0;
        }

        .btn-submit-search {
            justify-self: center;
            background-color: #1A365D;
            border: 1px solid #1A365D;
            color: #FFFFFF;
            font-size: 0.95rem !important;
            padding: 0 32px !important;
            box-shadow: 0 2px 4px rgba(26, 54, 93, 0.15);
        }

        .btn-submit-search:hover {
            background-color: #2B6CB0;
            border-color: #2B6CB0;
            box-shadow: 0 4px 10px rgba(43, 108, 176, 0.25);
            transform: translateY(-1px);
        }

        .btn-25yr {
            justify-self: end;
            background-color: #2F855A;
            border: 1px solid #2F855A;
            color: #FFFFFF;
            box-shadow: 0 2px 4px rgba(47, 133, 90, 0.15);
        }

        .btn-25yr:hover {
            background-color: #276749;
            border-color: #276749;
            box-shadow: 0 4px 10px rgba(39, 103, 73, 0.25);
            transform: translateY(-1px);
        }

        /* --- DISPLAY CONTROLS & PAGINATION BAR --- */
        .controls-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: var(--card-bg);
            padding: 10px 15px;
            border-radius: 6px;
            border: 1px solid var(--border-color);
        }

        .top-bar {
            margin-bottom: 20px;
        }

        .bottom-bar {
            margin-top: 25px;
            margin-bottom: 30px;
        }

        .display-controls {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .display-controls label {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-heading);
        }

        .display-select {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            background-color: var(--card-bg);
            color: var(--text-heading);
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
        }

        /* --- PAGINATION STYLES --- */
        .pagination {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .pagination button {
            padding: 6px 12px;
            border: 1px solid var(--border-color);
            background-color: var(--card-bg);
            color: var(--text-heading);
            font-size: 0.85rem;
            font-weight: 600;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            min-width: 36px;
        }

        .pagination button:hover:not(:disabled) {
            background-color: #EDF2F7;
            border-color: #A0AEC0;
        }

        .pagination button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .pagination button.active {
            background-color: var(--text-heading);
            color: #FFFFFF;
            border-color: var(--text-heading);
        }

        .pagination button.skip-btn {
            background-color: #F8FAFC;
            color: var(--primary-red);
        }

        .pagination button.skip-btn:hover:not(:disabled) {
            background-color: #FEE2E2;
            border-color: var(--primary-red);
        }

        /* Listing Cards */
        .listing-card {
            background: var(--card-bg);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(15, 44, 89, 0.05);
            display: flex;
            flex-direction: row;
            padding: 20px;
            margin-bottom: 20px;
            gap: 25px;
            border: 1px solid var(--border-color);
        }

        .gallery {
            flex: 0 0 300px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .main-img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 6px;
            background-color: #E6EAEF;
            border: 1px solid #edf2f7;
            transition: transform 0.3s ease, filter 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .main-img:hover {
            transform: scale(1.02);
            filter: brightness(1.04);
            box-shadow: 0 4px 12px rgba(15, 44, 89, 0.15);
        }

        .thumbnail-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 6px;
        }

        .thumb-img {
            width: 100%;
            height: 55px;
            object-fit: cover;
            border-radius: 4px;
            background-color: #E6EAEF;
            border: 1px solid #edf2f7;
            transition: transform 0.2s ease, opacity 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
            opacity: 0.85;
        }

        .thumb-img:hover {
            transform: scale(1.06);
            opacity: 1;
            border-color: var(--blue-link);
            box-shadow: 0 2px 6px rgba(66, 153, 225, 0.3);
        }

        .details {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 15px;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
        }

        .vehicle-title {
            color: var(--text-heading);
            font-size: 1.3rem;
            font-weight: 700;
            margin: 0;
        }

        .vehicle-title span.make {
            font-weight: 400;
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-right: 5px;
        }

        .status-badge {
            background-color: #2F855A;
            color: white;
            padding: 6px 14px;
            border-radius: 4px;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            cursor: default;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
            text-align: center;
            margin-bottom: 25px;
        }

        .spec-item {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .spec-label {
            font-size: 0.75rem;
            color: var(--text-muted);
            height: 28px;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            margin-bottom: 6px;
        }

        .spec-value {
            color: var(--text-heading);
            font-size: 0.9rem;
            font-weight: 600;
        }

        /* ACTION & PRICE AREA */
        .action-area {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            min-height: 52px;
        }

        .price-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .price-label {
            color: var(--primary-red);
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 2px;
            line-height: 1;
        }

        .price-value {
            color: var(--primary-red);
            font-size: 2.2rem;
            font-weight: 700;
            line-height: 1.1;
            margin: 0;
        }

        .buttons-section {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .btn-fav {
            background: #F8FAFC;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            width: 45px;
            height: 48px;
            font-size: 1.2rem;
            color: #CBD5E0;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s ease, border-color 0.2s ease;
        }

        .btn-fav:hover {
            color: #ECC94B;
            border-color: #ECC94B;
        }

        .btn-quote {
            position: relative;
            overflow: hidden;
            background-color: var(--primary-red);
            border: none;
            border-radius: 6px;
            color: white;
            padding: 8px 25px;
            height: 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-sizing: border-box;
            transition: background-color 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
        }

        .btn-quote::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(120deg,
                    transparent,
                    rgba(255, 255, 255, 0.35),
                    transparent);
            transition: all 0.6s ease;
        }

        .btn-quote.wave-active::after,
        .btn-quote:hover::after {
            left: 100%;
        }

        .btn-quote.wave-active,
        .btn-quote:hover {
            background-color: #A91218;
            transform: scale(1.03) translateY(-1px);
            box-shadow: 0 4px 12px rgba(204, 25, 33, 0.3);
        }

        .btn-quote .btn-text-main {
            font-size: 1rem;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .btn-quote .btn-text-sub {
            font-size: 0.85rem;
            font-weight: 500;
            line-height: 1.2;
        }

        .fine-print {
            text-align: center;
            color: var(--blue-link);
            font-size: 0.85rem;
            margin-bottom: 15px;
        }

        .evaluation-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15px;
            background-color: #FFFFFF;
            padding: 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
        }

        .eval-grade {
            font-weight: 700;
            color: var(--primary-red);
        }

        .stars {
            color: #ffb800;
        }

        .no-results {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 8px;
            color: var(--text-muted);
            font-size: 1.1rem;
            border: 1px solid var(--border-color);
        }

        @media (max-width: 800px) {
            .search-grid {
                grid-template-columns: 1fr;
            }

            .listing-card {
                flex-direction: column;
            }

            .gallery {
                flex: none;
                width: 100%;
            }

            .price-row {
                flex-direction: column;
                align-items: flex-start;
            }

            .price-slider-container {
                width: 100%;
            }

            .search-footer {
                grid-template-columns: 1fr;
            }

            .btn-reset,
            .btn-submit-search,
            .btn-25yr {
                justify-self: stretch;
                width: 100%;
            }

            .controls-bar {
                flex-direction: column;
                gap: 10px;
                align-items: stretch;
            }
        }

        #listings-container.is-loading {
            opacity: 0.5;
            pointer-events: none;
            transition: opacity 0.2s ease;
        }
    </style>
@endpush

@section('main')
    <!-- COMPACT STICKY NAVIGATION -->
    <div class="sticky-wrapper" id="stickyBar">
        <div class="sticky-bar-inner">
            <div class="sticky-title">Search Conditions</div>

            <button class="open-search-tab" id="tabBtn" onclick="toggleDropdown()">
                <span id="tabText">Open Search</span>
                <svg class="chevron-icon" id="tabIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 7 12 13 18 7"></polyline>
                    <polyline points="6 13 12 19 18 13"></polyline>
                </svg>
            </button>

            <!-- Dropdown Panel -->
            <div class="dropdown-search" id="dropdownPanel">
                <!-- JavaScript injects form here -->
            </div>
        </div>
    </div>

    <div class="container">

        <!-- SEARCH CONDITIONS PANEL -->
        <div class="search-card" id="mainSearchForm">
            <div class="search-header">Search Conditions</div>
            <div class="search-body">

                <div class="search-grid">
                    <!-- Row 1 -->
                    <div class="form-group">
                        <label for="filter-maker">Maker</label>
                        <select id="filter-maker">
                            <option value="">All Makers</option>
                            @foreach($manufacturers as $manufacturer)
                                <option value="{{ $manufacturer->name }}">{{ $manufacturer->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="filter-carname">Car Name</label>
                        <input type="text" id="filter-carname" placeholder="e.g. Skyline, Supra">
                    </div>

                    <!-- Row 2 -->
                    <div class="form-group">
                        <label for="filter-type">Type</label>
                        <select id="filter-type">
                            <option value="">All Types</option>
                            <option value="Coupe">Coupe</option>
                            <option value="Sedan">Sedan</option>
                            <option value="Hatchback">Hatchback</option>
                            <option value="Convertible">Convertible</option>
                        </select>
                    </div>
                    <div></div>

                    <!-- Row 3 -->
                    <div class="form-group">
                        <label>Model Year</label>
                        <div class="range-group">
                            <select id="filter-year-min">
                                <option value="">Select</option>
                            </select>
                            <span>~</span>
                            <select id="filter-year-max">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Mileage</label>
                        <div class="range-group">
                            <select id="filter-mileage-min">
                                <option value="">Select</option>
                            </select>
                            <span>~</span>
                            <select id="filter-mileage-max">
                                <option value="">Select</option>
                            </select>
                        </div>
                    </div>

                    <!-- Row 4 -->
                    <div class="form-group">
                        <label for="filter-location">Location</label>
                        <select id="filter-location">
                            <option value="">Select Location</option>
                            @foreach($locations as $loc)
                                <option value="{{ $loc }}">
                                    {{ $loc }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Overlapping Continuous Dual Price Slider -->
                <div class="price-row">
                    <span class="price-label-title">Price</span>
                    <div class="price-slider-container">
                        <div class="price-value-box" id="price-min-display">0 yen</div>

                        <div class="dual-range-wrapper">
                            <div class="slider-track" id="slider-track"></div>
                            <input type="range" id="filter-price-min" min="0" max="30000000" step="100000" value="0"
                                class="range-input">
                            <input type="range" id="filter-price-max" min="0" max="30000000" step="100000" value="30000000"
                                class="range-input">
                        </div>

                        <div class="price-value-box" id="price-max-display">30 M yen</div>
                    </div>
                </div>

                <!-- Footer Buttons Layout -->
                <div class="search-footer">
                    <button type="button" class="btn-reset" id="btn-reset-filters">Reset Conditions</button>
                    <button type="button" class="btn-submit-search" id="btn-submit-search">Search (<span
                            id="search-count">{{ $cars->total() }}</span> Items)</button>
                    <button type="button" class="btn-25yr" id="btn-25yr">Show Vehicles 25 Years or Older</button>
                </div>

            </div>
        </div>

        <!-- Vehicle Listings -->
        <div id="listings-container">
            @include('landing.pages.partials.vehicle-list')
        </div>

        <!-- Bottom Display Controls & Bottom Pagination Bar -->
        <div class="controls-bar bottom-bar">
            <div class="display-controls">
                <label for="display-count-bottom">Display</label>
                <select id="display-count-bottom" class="display-select">
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
            <div id="pagination-bottom" class="pagination">
                {!! (string) $cars->links() !!}
            </div>
        </div>
    </div>

    </div>
@endsection

@section('script')
    <script>
        // --- STATE ---
        let currentPage = 1;
        let currentFilterXHR = null;
        let carNameDebounceTimer = null;
        let priceDebounceTimer = null;
        let isSyncingFilters = false;

        // --- POPULATE YEAR DROPDOWNS ---
        function populateYearDropdowns() {
            const minEls = document.querySelectorAll('#filter-year-min');
            const maxEls = document.querySelectorAll('#filter-year-max');

            const currentDate = new Date();
            const systemYear = currentDate.getFullYear();
            const endYear = Math.min(systemYear, 2040);
            const startYear = 1969;

            minEls.forEach(el => {
                const currentVal = el.value;
                el.innerHTML = '<option value="">Select</option>';
                for (let y = startYear; y <= endYear; y++) {
                    el.innerHTML += `<option value="${y}">${y}</option>`;
                }
                el.value = currentVal;
            });

            maxEls.forEach(el => {
                const currentVal = el.value;
                el.innerHTML = '<option value="">Select</option>';
                for (let y = startYear; y <= endYear; y++) {
                    el.innerHTML += `<option value="${y}">${y}</option>`;
                }
                el.value = currentVal;
            });
        }

        // --- POPULATE MILEAGE DROPDOWNS ---
        function populateMileageDropdowns() {
            const minEls = document.querySelectorAll('#filter-mileage-min');
            const maxEls = document.querySelectorAll('#filter-mileage-max');

            minEls.forEach(el => {
                const currentVal = el.value;
                el.innerHTML = '<option value="">Select</option>';
                for (let m = 10000; m <= 200000; m += 10000) {
                    const label = m.toLocaleString() + ' km';
                    el.innerHTML += `<option value="${m}">${label}</option>`;
                }
                el.value = currentVal;
            });

            maxEls.forEach(el => {
                const currentVal = el.value;
                el.innerHTML = '<option value="">Select</option>';
                for (let m = 10000; m <= 200000; m += 10000) {
                    const label = m.toLocaleString() + ' km';
                    el.innerHTML += `<option value="${m}">${label}</option>`;
                }
                el.value = currentVal;
            });
        }

        function getFilterVal(id) {
            const els = document.querySelectorAll('[id="' + id + '"]');
            for (let el of els) {
                if (el.value !== '' && el.value !== null && el.value !== undefined) {
                    return el.value;
                }
            }
            return els.length > 0 ? els[0].value : '';
        }

        function setFilterVal(id, val) {
            const els = document.querySelectorAll('[id="' + id + '"]');
            els.forEach(el => {
                if (el.value !== String(val)) {
                    el.value = val;
                }
                if (window.jQuery && jQuery(el).data('select2')) {
                    jQuery(el).val(val);
                }
            });
        }

        // --- DYNAMIC RANGE VALIDATION ---
        function updateDropdownConstraints() {
            const yearMin = parseInt(getFilterVal('filter-year-min')) || 0;
            const yearMax = parseInt(getFilterVal('filter-year-max')) || Infinity;

            document.querySelectorAll('#filter-year-min option').forEach(opt => {
                if (opt.value !== '') opt.disabled = parseInt(opt.value) > yearMax;
            });
            document.querySelectorAll('#filter-year-max option').forEach(opt => {
                if (opt.value !== '') opt.disabled = parseInt(opt.value) < yearMin;
            });

            const mileageMin = parseInt(getFilterVal('filter-mileage-min')) || 0;
            const mileageMax = parseInt(getFilterVal('filter-mileage-max')) || Infinity;

            document.querySelectorAll('#filter-mileage-min option').forEach(opt => {
                if (opt.value !== '') opt.disabled = parseInt(opt.value) > mileageMax;
            });
            document.querySelectorAll('#filter-mileage-max option').forEach(opt => {
                if (opt.value !== '') opt.disabled = parseInt(opt.value) < mileageMin;
            });
        }

        // --- PRICE SLIDER FORMATTING ---
        function formatPriceText(val) {
            if (val >= 10000000) {
                const millions = Math.round(val / 1000000);
                return `${millions} M yen`;
            } else if (val >= 1000000) {
                if (val % 1000000 === 0) {
                    return `${val / 1000000} M yen`;
                }
                return `${(val / 1000000).toFixed(1)} M yen`;
            }
            return val.toLocaleString() + " yen";
        }

        function updateTrackHighlight() {
            const slidersMin = document.querySelectorAll('#filter-price-min');
            const slidersMax = document.querySelectorAll('#filter-price-max');
            const tracks = document.querySelectorAll('#slider-track');

            if (slidersMin.length > 0 && slidersMax.length > 0) {
                const min = parseInt(slidersMin[0].min);
                const max = parseInt(slidersMax[0].max);
                const valMin = parseInt(slidersMin[0].value);
                const valMax = parseInt(slidersMax[0].value);

                const percent1 = ((valMin - min) / (max - min)) * 100;
                const percent2 = ((valMax - min) / (max - min)) * 100;

                tracks.forEach(tr => {
                    tr.style.background = `linear-gradient(to right, #CBD5E0 ${percent1}%, #4299E1 ${percent1}%, #4299E1 ${percent2}%, #CBD5E0 ${percent2}%)`;
                });
            }
        }

        function syncPriceSliders(event) {
            if (isSyncingFilters) return;
            isSyncingFilters = true;

            const minVal = parseInt(getFilterVal('filter-price-min')) || 0;
            const maxVal = parseInt(getFilterVal('filter-price-max')) || 30000000;

            let newMin = minVal;
            let newMax = maxVal;

            if (event && event.target.id === 'filter-price-min') {
                newMin = parseInt(event.target.value);
                if (newMin > newMax) newMax = newMin;
            } else if (event && event.target.id === 'filter-price-max') {
                newMax = parseInt(event.target.value);
                if (newMax < newMin) newMin = newMax;
            }

            setFilterVal('filter-price-min', newMin);
            setFilterVal('filter-price-max', newMax);

            document.querySelectorAll('#price-min-display').forEach(el => el.textContent = formatPriceText(newMin));
            document.querySelectorAll('#price-max-display').forEach(el => el.textContent = formatPriceText(newMax));

            updateTrackHighlight();

            if (priceDebounceTimer) clearTimeout(priceDebounceTimer);
            priceDebounceTimer = setTimeout(() => {
                applyFilters();
            }, 400);

            isSyncingFilters = false;
        }

        // --- CENTRAL AJAX FETCH FUNCTION ---
        function fetchVehicles(page = 1) {
            currentPage = page;

            const data = {
                maker: getFilterVal('filter-maker'),
                car_name: getFilterVal('filter-carname'),
                type: getFilterVal('filter-type'),
                year_min: getFilterVal('filter-year-min'),
                year_max: getFilterVal('filter-year-max'),
                mileage_min: getFilterVal('filter-mileage-min'),
                mileage_max: getFilterVal('filter-mileage-max'),
                location: getFilterVal('filter-location'),
                price_min: getFilterVal('filter-price-min'),
                price_max: getFilterVal('filter-price-max'),
                page: page,
                per_page: $('#display-count-bottom').val() || 10
            };

            if (currentFilterXHR) {
                currentFilterXHR.abort();
            }

            $('#listings-container').addClass('is-loading');

            currentFilterXHR = $.ajax({
                url: "{{ route('available-vehicles.filter') }}",
                type: "GET",
                data: data,
                success: function (response) {
                    if (response.success) {
                        $('#listings-container').html(response.html);
                        $('[id="search-count"]').text(response.count);
                        $('#pagination-bottom').html(response.pagination);
                    }
                },
                error: function (xhr, status, error) {
                    if (status !== 'abort') {
                        console.error('AJAX Error:', error);
                    }
                },
                complete: function () {
                    $('#listings-container').removeClass('is-loading');
                }
            });
        }

        function applyFilters() {
            currentPage = 1;
            fetchVehicles(1);
        }

        // --- INITIALIZE STICKY BAR & CLONING ---
        const mainFormBody = document.querySelector('#mainSearchForm .search-body');
        const dropdownPanel = document.getElementById('dropdownPanel');

        if (mainFormBody && dropdownPanel) {
            dropdownPanel.appendChild(mainFormBody.cloneNode(true));

            const closeTabBtn = document.createElement('div');
            closeTabBtn.className = 'close-dropdown-tab';
            closeTabBtn.innerHTML = `
                                                                <span>Close Search</span>
                                                                <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="transform: rotate(180deg);">
                                                                    <polyline points="6 7 12 13 18 7"></polyline>
                                                                    <polyline points="6 13 12 19 18 13"></polyline>
                                                                </svg>
                                                            `;
            closeTabBtn.onclick = closeDropdown;
            dropdownPanel.appendChild(closeTabBtn);
        }

        const filterIds = [
            'filter-maker', 'filter-carname', 'filter-type',
            'filter-year-min', 'filter-year-max',
            'filter-mileage-min', 'filter-mileage-max',
            'filter-location'
        ];

        filterIds.forEach(id => {
            if (id === 'filter-carname') {
                const handleCarNameInput = function (e) {
                    if (isSyncingFilters) return;
                    isSyncingFilters = true;
                    setFilterVal(id, $(this).val());
                    isSyncingFilters = false;

                    if (carNameDebounceTimer) clearTimeout(carNameDebounceTimer);
                    carNameDebounceTimer = setTimeout(() => {
                        applyFilters();
                    }, 400);
                };
                $(document).off('input keyup search', '[id="' + id + '"]').on('input keyup search', '[id="' + id + '"]', handleCarNameInput);
            } else {
                const handleFilterChange = function (e) {
                    if (isSyncingFilters) return;
                    isSyncingFilters = true;
                    setFilterVal(id, $(this).val());
                    updateDropdownConstraints();
                    applyFilters();
                    isSyncingFilters = false;
                };
                $(document).off('change select2:select select2:clear', '[id="' + id + '"]').on('change select2:select select2:clear', '[id="' + id + '"]', handleFilterChange);
            }
        });

        $(document).off('input change', '[id="filter-price-min"], [id="filter-price-max"]').on('input change', '[id="filter-price-min"], [id="filter-price-max"]', syncPriceSliders);

        $(document).on('click', '[id="btn-submit-search"]', function (e) {
            e.preventDefault();
            applyFilters();
            closeDropdown();
            const container = document.getElementById('listings-container');
            if (container) {
                const yOffset = -80;
                const y = container.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        });

        $(document).on('click', '[id="btn-25yr"]', function (e) {
            e.preventDefault();
            isSyncingFilters = true;

            const currentYear = new Date().getFullYear();
            const maxYear25Cutoff = currentYear - 25;

            filterIds.forEach(id => setFilterVal(id, ''));
            setFilterVal('filter-price-min', 0);
            setFilterVal('filter-price-max', 30000000);

            document.querySelectorAll('#price-min-display').forEach(el => el.textContent = formatPriceText(0));
            document.querySelectorAll('#price-max-display').forEach(el => el.textContent = formatPriceText(30000000));

            setFilterVal('filter-year-max', maxYear25Cutoff);

            updateTrackHighlight();
            updateDropdownConstraints();
            isSyncingFilters = false;

            applyFilters();
            closeDropdown();

            const container = document.getElementById('listings-container');
            if (container) {
                const yOffset = -80;
                const y = container.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        });

        $(document).on('click', '[id="btn-reset-filters"]', function (e) {
            e.preventDefault();
            isSyncingFilters = true;

            filterIds.forEach(id => setFilterVal(id, ''));
            setFilterVal('filter-price-min', 0);
            setFilterVal('filter-price-max', 30000000);

            document.querySelectorAll('#price-min-display').forEach(el => el.textContent = formatPriceText(0));
            document.querySelectorAll('#price-max-display').forEach(el => el.textContent = formatPriceText(30000000));

            updateTrackHighlight();
            updateDropdownConstraints();
            isSyncingFilters = false;

            applyFilters();
        });

        // --- AJAX PAGINATION EVENT HANDLER ---
        $(document).on('click', '#pagination-bottom a', function (e) {
            e.preventDefault();
            const href = $(this).attr('href');
            if (href) {
                const urlParams = new URLSearchParams(href.split('?')[1]);
                const page = urlParams.get('page') || 1;
                fetchVehicles(page);
                const container = document.getElementById('listings-container');
                if (container) {
                    const yOffset = -80;
                    const y = container.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            }
        });

        $(document).on('change', '#display-count-bottom', function () {
            applyFilters();
            const container = document.getElementById('listings-container');
            if (container) {
                const yOffset = -80;
                const y = container.getBoundingClientRect().top + window.pageYOffset + yOffset;
                window.scrollTo({ top: y, behavior: 'smooth' });
            }
        });

        const stickyBar = document.getElementById('stickyBar');
        const mainSearchForm = document.getElementById('mainSearchForm');
        const tabText = document.getElementById('tabText');
        const tabIcon = document.getElementById('tabIcon');

        let lastScrollY = window.scrollY;

        window.addEventListener('scroll', () => {
            if (!mainSearchForm || !stickyBar) return;
            const currentScrollY = window.scrollY;
            const triggerPoint = mainSearchForm.offsetTop + mainSearchForm.offsetHeight;

            if (currentScrollY > triggerPoint) {
                stickyBar.classList.add('visible');
                if (dropdownPanel && dropdownPanel.classList.contains('active') && Math.abs(currentScrollY - lastScrollY) > 15) {
                    closeDropdown();
                }
            } else {
                stickyBar.classList.remove('visible');
                closeDropdown();
            }

            lastScrollY = currentScrollY;
        });

        document.addEventListener('click', (e) => {
            if (dropdownPanel && dropdownPanel.classList.contains('active')) {
                if (stickyBar && !stickyBar.contains(e.target)) {
                    closeDropdown();
                }
            }
        });

        function toggleDropdown() {
            if (dropdownPanel && dropdownPanel.classList.contains('active')) {
                closeDropdown();
            } else {
                openDropdown();
            }
        }

        function openDropdown() {
            if (dropdownPanel) dropdownPanel.classList.add('active');
            if (tabText) tabText.innerText = "Close Search";
            if (tabIcon) tabIcon.style.transform = "rotate(180deg)";
        }

        function closeDropdown() {
            if (dropdownPanel) dropdownPanel.classList.remove('active');
            if (tabText) tabText.innerText = "Open Search";
            if (tabIcon) tabIcon.style.transform = "0deg";
        }

        // --- INIT ---
        populateYearDropdowns();
        populateMileageDropdowns();
        updateTrackHighlight();
    </script>
@endsection