@php
    $image = !empty($post->featured_image)
        ? $post->featured_image
        : asset('assets/landing/images/blog/blog-no-sidebar.jpg');
    $badge = $post->published_at ? $post->published_at->format('d M, Y') : 'Blog';
@endphp
<article class="flex flex-col sm:flex-row bg-white rounded-2xl border border-gray-200/70 shadow-sm overflow-hidden hover:shadow-md transition-shadow duration-300 sm:h-44">
    <!-- Uniform Image Box (w-60, h-44) -->
    <div class="relative w-full sm:w-60 h-44 shrink-0 overflow-hidden bg-gray-100">
        <img src="{{ $image }}" alt="{{ $post->title }}" class="w-full h-full object-cover" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/landing/images/blog/blog-no-sidebar.jpg') }}';">
        <span class="absolute bottom-3 left-3 bg-[#0B437C] text-white text-[11px] font-semibold px-3 py-1 rounded-full shadow-sm">
            {{ $badge }}
        </span>
    </div>

    <!-- Content Side with Bottom-Right READ MORE -->
    <div class="p-4 sm:p-5 flex-1 flex flex-col justify-between overflow-hidden">
        <div>
            <h3 class="text-base sm:text-lg font-bold text-slate-900 hover:text-[#0B437C] transition-colors leading-snug mb-1.5">
                <a href="{{ route('blog.detail', $post->slug) }}">{{ $post->title }}</a>
            </h3>
            <p class="text-gray-500 text-xs leading-relaxed line-clamp-3">
                {{ Str::limit(strip_tags($post->description ?? $post->content), 180) }}
            </p>
        </div>

        <div class="mt-2 flex justify-end">
            <a href="{{ route('blog.detail', $post->slug) }}" class="bg-[#0B437C] hover:bg-[#08325d] text-white text-[11px] font-bold uppercase tracking-wider px-4 py-2 rounded-md transition-colors shadow-sm">
                READ MORE
            </a>
        </div>
    </div>
</article>
