@extends('landing.master')
@section('title', 'News & Articles')

@push('style')
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .line-clamp-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endpush

@section('main')
<div class="bg-gray-50 min-h-screen py-8">
    <div class="max-w-4xl mx-auto px-4">

        <!-- Header: Search Bar + Dynamic View Navigation Button -->
        <div class="mb-6 flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-gray-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input 
                    type="search" 
                    id="searchInput" 
                    placeholder="Search {{ $posts->total() }} auction articles & guides..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm text-slate-800 placeholder-gray-400 focus:outline-none focus:border-[#0B437C] focus:ring-1 focus:ring-[#0B437C] shadow-sm"
                >
            </div>

            <!-- Switch Page Navigation Link -->
            <button 
                id="viewNavBtn" 
                type="button"
                class="inline-flex items-center justify-center gap-2 bg-white border border-[#0B437C] text-[#0B437C] hover:bg-[#0B437C] hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl transition-all duration-200 shadow-sm whitespace-nowrap cursor-default"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 002 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 002 2h1.5a2.5 2.5 0 002.5-2.5V11a2 2 0 002-2v-.055M11 20.055V18a2 2 0 002-2h1a2 2 0 002-2v-1a2 2 0 012-2h2.945M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Import regulation for your country.
            </button>
        </div>

        <!-- Page Info & Items Per Page Dropdown -->
        <div class="flex items-center justify-between mb-4 px-1">
            <p id="pageCounter" class="text-xs font-semibold text-gray-500">
                Showing {{ $posts->firstItem() ?? 0 }} to {{ $posts->lastItem() ?? 0 }} of {{ $posts->total() }} articles on Page {{ $posts->currentPage() }} ({{ $posts->total() }} total)
            </p>
            
            <div class="flex items-center gap-2">
                <label for="itemsPerPageSelect" class="text-xs font-semibold text-gray-500 whitespace-nowrap">Initial Display:</label>
                <select 
                    id="itemsPerPageSelect" 
                    onchange="fetchPage(1, this.value);"
                    class="text-xs font-bold text-[#0B437C] bg-white border border-gray-200 rounded-xl px-3 py-1.5 pr-8 focus:outline-none focus:border-[#0B437C] focus:ring-1 focus:ring-[#0B437C] shadow-sm cursor-pointer"
                >
                    <option value="2" {{ ($perPage ?? 2) == 2 ? 'selected' : '' }}>2 per page</option>
                    <option value="5" {{ ($perPage ?? 2) == 5 ? 'selected' : '' }}>5 per page</option>
                    <option value="10" {{ ($perPage ?? 2) == 10 ? 'selected' : '' }}>10 per page</option>
                    <option value="20" {{ ($perPage ?? 2) == 20 ? 'selected' : '' }}>20 per page</option>
                    <option value="30" {{ ($perPage ?? 2) == 30 ? 'selected' : '' }}>30 per page</option>
                </select>
            </div>
        </div>

        <!-- Dynamic Tiles Container -->
        <div id="postsContainer" class="flex flex-col space-y-4">
            @forelse($posts as $post)
                @include('landing.pages.partials.blog-card', ['post' => $post])
            @empty
                <p class="text-center text-gray-500 py-8">No articles found.</p>
            @endforelse
        </div>

        <!-- Bottom Page Number Navigation (AJAX Pagination) -->
        <div id="pagination" class="flex items-center justify-center gap-2 mt-8 pt-4 border-t border-gray-200/60">
            <!-- Prev Button -->
            @if ($posts->onFirstPage())
                <button disabled class="opacity-40 cursor-not-allowed px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-400">Prev</button>
            @else
                <button onclick="fetchPage({{ $posts->currentPage() - 1 }})" class="px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50">Prev</button>
            @endif

            <!-- Page Number Links -->
            @php
                $lastPage = max(1, $posts->lastPage());
            @endphp
            @for ($page = 1; $page <= $lastPage; $page++)
                @if ($page == $posts->currentPage())
                    <span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#0B437C] text-white shadow-sm">{{ $page }}</span>
                @else
                    <button onclick="fetchPage({{ $page }})" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">{{ $page }}</button>
                @endif
            @endfor

            <!-- Next Button -->
            @if ($posts->hasMorePages())
                <button onclick="fetchPage({{ $posts->currentPage() + 1 }})" class="px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50">Next</button>
            @else
                <button disabled class="opacity-40 cursor-not-allowed px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-400">Next</button>
            @endif
        </div>

    </div>
</div>
@endsection

@section('script')
<script>
    let currentPage = {{ $posts->currentPage() }};
    let perPage = {{ $perPage ?? 2 }};

    function fetchPage(page, perPageVal) {
        if (page < 1) return;
        if (perPageVal) perPage = perPageVal;

        $.ajax({
            url: "{{ route('blog') }}",
            type: "GET",
            data: {
                page: page,
                per_page: perPage
            },
            success: function(response) {
                if (response.success) {
                    $('#postsContainer').html(response.html);
                    currentPage = response.currentPage;

                    // Update page counter text
                    $('#pageCounter').html(`Showing ${response.firstItem} to ${response.lastItem} of ${response.total} articles on Page ${response.currentPage} (${response.total} total)`);

                    // Re-render pagination buttons
                    renderPaginationButtons(response.currentPage, response.lastPage);
                }
            },
            error: function(xhr) {
                console.error("Failed to fetch page data", xhr);
            }
        });
    }

    function renderPaginationButtons(current, last) {
        let html = '';

        // Prev Button
        if (current <= 1) {
            html += `<button disabled class="opacity-40 cursor-not-allowed px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-400">Prev</button>`;
        } else {
            html += `<button onclick="fetchPage(${current - 1})" class="px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50">Prev</button>`;
        }

        // Page Numbers
        for (let i = 1; i <= last; i++) {
            if (i === current) {
                html += `<span class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-[#0B437C] text-white shadow-sm">${i}</span>`;
            } else {
                html += `<button onclick="fetchPage(${i})" class="px-3.5 py-1.5 rounded-lg text-xs font-bold bg-white border border-gray-200 text-gray-700 hover:bg-gray-50">${i}</button>`;
            }
        }

        // Next Button
        if (current >= last) {
            html += `<button disabled class="opacity-40 cursor-not-allowed px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-400">Next</button>`;
        } else {
            html += `<button onclick="fetchPage(${current + 1})" class="px-3 py-1.5 rounded-lg border bg-white text-xs font-semibold text-gray-700 hover:bg-gray-50">Next</button>`;
        }

        $('#pagination').html(html);
    }
</script>
@endsection
