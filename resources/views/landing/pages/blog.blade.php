@extends('landing.master')
@section('title', 'News & Articles')

@push('style')
    <style>
        .blog-page-wrapper {
            background-color: #f8fafc;
            min-height: 100vh;
            padding-top: 2rem;
            padding-bottom: 2rem;
        }
        .blog-container {
            max-width: 56rem;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        .blog-header-row {
            margin-bottom: 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            align-items: stretch;
        }
        @media (min-width: 640px) {
            .blog-header-row {
                flex-direction: row;
                align-items: center;
            }
        }
        .blog-search-wrapper {
            position: relative;
            flex: 1 1 0%;
        }
        .blog-search-icon {
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            display: flex;
            align-items: center;
            padding-left: 0.875rem;
            pointer-events: none;
            color: #94a3b8;
        }
        .blog-search-icon svg {
            width: 1rem;
            height: 1rem;
        }
        .blog-search-input {
            width: 100%;
            padding-left: 2.5rem;
            padding-right: 1rem;
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            color: #1e293b;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            outline: none;
            transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }
        .blog-search-input::placeholder {
            color: #94a3b8;
        }
        .blog-search-input:focus {
            border-color: #0B437C;
            box-shadow: 0 0 0 2px rgba(11, 67, 124, 0.15);
        }
        .blog-view-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            background-color: #ffffff;
            border: 1px solid #0B437C;
            color: #0B437C;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.625rem 1rem;
            border-radius: 0.75rem;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            white-space: nowrap;
            cursor: pointer;
        }
        .blog-view-btn:hover {
            background-color: #0B437C;
            color: #ffffff;
        }
        .blog-meta-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1rem;
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }
        .blog-page-counter {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            margin: 0;
        }
        .blog-per-page-group {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        .blog-per-page-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            white-space: nowrap;
            margin: 0;
        }
        .blog-per-page-select {
            font-size: 0.75rem;
            font-weight: 700;
            color: #0B437C;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.375rem 0.75rem;
            outline: none;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            cursor: pointer;
        }
        .blog-per-page-select:focus {
            border-color: #0B437C;
            box-shadow: 0 0 0 2px rgba(11, 67, 124, 0.15);
        }
        .blog-posts-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .blog-no-posts {
            text-align: center;
            color: #64748b;
            padding-top: 2rem;
            padding-bottom: 2rem;
            margin: 0;
        }

        /* Load 10 More Button Internal CSS */
        .blog-load-more-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
        }
        .blog-load-more-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.375rem;
            font-size: 0.75rem;
            font-weight: 700;
            color: #0B437C;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.2s ease;
            outline: none;
        }
        .blog-load-more-btn:hover {
            color: #08325d;
        }
        .blog-load-more-icon {
            padding: 0.625rem;
            border-radius: 9999px;
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .blog-load-more-btn:hover .blog-load-more-icon {
            border-color: #0B437C;
            transform: translateY(3px);
        }
        .blog-load-more-icon svg {
            width: 1rem;
            height: 1rem;
        }

        .blog-pagination {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(226, 232, 240, 0.6);
        }
        .blog-page-btn {
            padding: 0.375rem 0.875rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 700;
            border: 1px solid #e2e8f0;
            background-color: #ffffff;
            color: #334155;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .blog-page-btn:hover:not(:disabled) {
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }
        .blog-page-btn.active {
            background-color: #0B437C;
            color: #ffffff;
            border-color: #0B437C;
        }
        .blog-page-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Blog Card Internal CSS Rules */
        .blog-card {
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            border-radius: 1rem;
            border: 1px solid rgba(226, 232, 240, 0.7);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: box-shadow 0.3s ease, border-color 0.3s ease;
            cursor: pointer;
        }
        @media (min-width: 640px) {
            .blog-card {
                flex-direction: row;
                height: 11rem;
            }
        }
        .blog-card:hover {
            box-shadow: 0 6px 16px -2px rgba(11, 67, 124, 0.12), 0 2px 6px -1px rgba(0, 0, 0, 0.06);
            border-color: rgba(11, 67, 124, 0.3);
        }
        .blog-card:hover .blog-card-img {
            transform: scale(1.08);
        }
        .blog-card:hover .blog-card-title a {
            color: #0B437C;
        }
        .blog-card:hover .blog-card-readmore {
            background-color: #08325d;
        }
        .blog-card-img-wrap {
            position: relative;
            width: 100%;
            height: 11rem;
            flex-shrink: 0;
            overflow: hidden;
            background-color: #f1f5f9;
            display: block;
        }
        @media (min-width: 640px) {
            .blog-card-img-wrap {
                width: 15rem;
            }
        }
        .blog-card-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.35s ease-in-out;
        }
        .blog-card-badge {
            position: absolute;
            bottom: 0.75rem;
            left: 0.75rem;
            background-color: #0B437C;
            color: #ffffff;
            font-size: 11px;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .blog-card-content {
            padding: 1rem;
            flex: 1 1 0%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
        }
        @media (min-width: 640px) {
            .blog-card-content {
                padding: 1.25rem;
            }
        }
        .blog-card-title {
            font-size: 1rem;
            font-weight: 700;
            color: #0f172a;
            line-height: 1.375;
            margin: 0 0 0.375rem 0;
            transition: color 0.2s ease;
        }
        @media (min-width: 640px) {
            .blog-card-title {
                font-size: 1.125rem;
            }
        }
        .blog-card-title a {
            color: inherit;
            text-decoration: none;
        }
        .blog-card-title a:hover {
            color: #0B437C;
        }
        .blog-card-excerpt {
            color: #64748b;
            font-size: 0.75rem;
            line-height: 1.6;
            margin: 0;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .blog-card-action {
            margin-top: 0.5rem;
            display: flex;
            justify-content: flex-end;
        }
        .blog-card-readmore {
            background-color: #0B437C;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            text-decoration: none;
            transition: background-color 0.2s ease;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            display: inline-block;
        }
        .blog-card-readmore:hover {
            background-color: #08325d;
            color: #ffffff;
        }

        /* Dedicated Mobile Responsive Styles (< 640px) */
        @media (max-width: 639px) {
            .blog-page-wrapper {
                padding-top: 1.25rem;
                padding-bottom: 1.25rem;
            }
            .blog-header-row {
                gap: 0.625rem;
            }
            .blog-view-btn {
                width: 100%;
                padding: 0.75rem 1rem;
                font-size: 0.8125rem;
            }
            .blog-meta-bar {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
            .blog-per-page-group {
                width: 100%;
                justify-content: space-between;
            }
            .blog-card {
                height: auto !important;
            }
            .blog-card-img-wrap {
                width: 100% !important;
                height: 12.5rem;
            }
            .blog-card-content {
                padding: 1rem;
            }
            .blog-card-title {
                font-size: 1.05rem;
                margin-bottom: 0.5rem;
            }
            .blog-card-excerpt {
                font-size: 0.8125rem;
                line-height: 1.55;
            }
            .blog-card-readmore {
                padding: 0.625rem 1.25rem;
                font-size: 12px;
            }
            .blog-pagination {
                flex-wrap: wrap;
                gap: 0.375rem;
            }
            .blog-page-btn {
                padding: 0.5rem 0.75rem;
                min-width: 38px;
                min-height: 38px;
                display: inline-flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
@endpush

@section('main')
<div class="blog-page-wrapper">
    <div class="blog-container">

        <!-- Header: Search Bar + Dynamic View Navigation Button -->
        <div class="blog-header-row">
            <div class="blog-search-wrapper">
                <div class="blog-search-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="search" 
                    id="searchInput" 
                    oninput="handleSearch()"
                    placeholder="Search articles..." 
                    class="blog-search-input"
                >
            </div>

            <!-- Switch Page Navigation Link -->
            <button 
                id="viewNavBtn" 
                type="button"
                onclick="toggleView()"
                class="blog-view-btn"
            >
                <!-- Content populated dynamically via JS -->
            </button>
        </div>

        <!-- Page Info & Items Per Page Dropdown -->
        <div class="blog-meta-bar">
            <p id="pageCounter" class="blog-page-counter"></p>
            
            <div class="blog-per-page-group">
                <label for="itemsPerPageSelect" class="blog-per-page-label">Initial Display:</label>
                <select 
                    id="itemsPerPageSelect" 
                    onchange="handleItemsPerPageChange()"
                    class="blog-per-page-select"
                >
                    <option value="10" selected>10 per page</option>
                    <option value="20">20 per page</option>
                    <option value="30">30 per page</option>
                </select>
            </div>
        </div>

        <!-- Dynamic Tiles Container -->
        <div id="postsContainer" class="blog-posts-list">
            @forelse($posts->take(10) as $post)
                @include('landing.pages.partials.blog-card', ['post' => $post])
            @empty
                <p class="blog-no-posts">No articles found.</p>
            @endforelse
        </div>

        <!-- Downward Arrow: Load 10 More Articles -->
        <div id="loadMoreContainer" class="blog-load-more-wrapper" style="display: none;">
            <button type="button" onclick="loadMoreTen()" class="blog-load-more-btn">
                <span id="loadMoreText">Load 10 More Articles</span>
                <div class="blog-load-more-icon">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </div>
            </button>
        </div>

        <!-- Bottom Page Number Navigation -->
        <div id="pagination" class="blog-pagination"></div>

    </div>
</div>
@endsection

@section('script')
<script>
    let activeView = 'blog'; // 'blog' or 'regulations'
    let currentPage = 1;
    let itemsPerPage = 10;
    let currentlyVisibleCount = 10;
    const maxPageCap = 50;
    let searchQuery = '';
    let fetchedCards = [];
    let totalMatchingRecords = 0;
    let totalServerPages = 1;
    let searchTimer = null;

    function renderHeaderButton() {
        const navBtn = document.getElementById('viewNavBtn');
        const searchInput = document.getElementById('searchInput');

        if (activeView === 'blog') {
            searchInput.placeholder = "Search articles...";
            navBtn.innerHTML = `
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 002-2v-.055M11 20.055V18a2 2 0 002-2h1a2 2 0 002-2v-1a2 2 0 012-2h2.945M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Import regulation for your country.
            `;
        } else {
            searchInput.placeholder = "Search destination country regulations...";
            navBtn.innerHTML = `
                <svg style="width: 1rem; height: 1rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to News & Articles
            `;
        }
    }

    function toggleView() {
        activeView = activeView === 'blog' ? 'regulations' : 'blog';
        currentPage = 1;
        currentlyVisibleCount = itemsPerPage;
        document.getElementById('searchInput').value = '';
        searchQuery = '';
        renderApp();
    }

    function handleItemsPerPageChange() {
        itemsPerPage = parseInt(document.getElementById('itemsPerPageSelect').value);
        currentlyVisibleCount = itemsPerPage;
        currentPage = 1;
        fetchPosts();
    }

    function loadMoreTen() {
        if (currentlyVisibleCount < maxPageCap) {
            currentlyVisibleCount = Math.min(currentlyVisibleCount + 10, maxPageCap);
            renderPosts();
        }
    }

    function changePage(page) {
        if (page < 1 || page > totalServerPages) return;
        currentPage = page;
        currentlyVisibleCount = itemsPerPage;
        fetchPosts();
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function handleSearch() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            searchQuery = document.getElementById('searchInput').value.trim().toLowerCase();
            currentPage = 1;
            currentlyVisibleCount = itemsPerPage;
            fetchPosts();
        }, 300);
    }

    function fetchPosts() {
        $.ajax({
            url: "{{ route('blog') }}",
            type: "GET",
            data: {
                category: activeView,
                page: currentPage,
                per_page: maxPageCap,
                search: searchQuery
            },
            success: function(response) {
                if (response.success) {
                    fetchedCards = response.cards || [];
                    totalMatchingRecords = response.total;
                    totalServerPages = response.lastPage;

                    renderHeaderButton();
                    renderPosts();
                }
            },
            error: function(xhr) {
                console.error("Failed to fetch posts", xhr);
            }
        });
    }

    function renderPosts() {
        const container = document.getElementById('postsContainer');
        const pageCounter = document.getElementById('pageCounter');
        const loadMoreContainer = document.getElementById('loadMoreContainer');
        const loadMoreText = document.getElementById('loadMoreText');

        container.innerHTML = '';

        const availableOnThisPage = fetchedCards;
        const displayCount = Math.min(currentlyVisibleCount, availableOnThisPage.length);
        const visibleItems = availableOnThisPage.slice(0, displayCount);

        const typeLabel = activeView === 'blog' ? 'articles' : 'regulations';

        if (totalMatchingRecords > 0) {
            pageCounter.innerText = `Showing ${visibleItems.length} of ${availableOnThisPage.length} ${typeLabel} on Page ${currentPage} (${totalMatchingRecords} total)`;
        } else {
            pageCounter.innerText = `0 ${typeLabel} found`;
        }

        if (visibleItems.length === 0) {
            container.innerHTML = `<p class="blog-no-posts">No ${typeLabel} found.</p>`;
            if (loadMoreContainer) loadMoreContainer.style.display = 'none';
            renderPagination();
            return;
        }

        let cardsHtml = '';
        visibleItems.forEach(item => {
            cardsHtml += item.html;
        });
        container.innerHTML = cardsHtml;

        // Downward arrow visible if articles remain on current page & below max cap (50)
        if (displayCount < availableOnThisPage.length && displayCount < maxPageCap) {
            if (loadMoreText) {
                loadMoreText.innerText = `Load 10 More ${activeView === 'blog' ? 'Articles' : 'Regulations'}`;
            }
            if (loadMoreContainer) loadMoreContainer.style.display = 'flex';
        } else {
            if (loadMoreContainer) loadMoreContainer.style.display = 'none';
        }

        renderPagination();
    }

    function renderPagination() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        if (totalMatchingRecords === 0) return;

        const totalPages = Math.max(1, totalServerPages);

        // Prev Button
        const isPrevDisabled = currentPage <= 1;
        pagination.innerHTML += `
            <button onclick="changePage(${currentPage - 1})" ${isPrevDisabled ? 'disabled class="blog-page-btn"' : 'class="blog-page-btn"'}>
                Prev
            </button>
        `;

        // Page Numbers
        for (let i = 1; i <= totalPages; i++) {
            pagination.innerHTML += `
                <button onclick="changePage(${i})" class="blog-page-btn ${currentPage === i ? 'active' : ''}">
                    ${i}
                </button>
            `;
        }

        // Next Button
        const isNextDisabled = currentPage >= totalPages;
        pagination.innerHTML += `
            <button onclick="changePage(${currentPage + 1})" ${isNextDisabled ? 'disabled class="blog-page-btn"' : 'class="blog-page-btn"'}>
                Next
            </button>
        `;
    }

    function renderApp() {
        renderHeaderButton();
        fetchPosts();
    }

    // Initial Load
    $(document).ready(function() {
        renderApp();
    });
</script>
@endsection
