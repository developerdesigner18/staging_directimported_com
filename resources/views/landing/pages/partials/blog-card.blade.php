@php
    $image = !empty($post->featured_image)
        ? $post->featured_image
        : asset('assets/landing/images/blog/blog-no-sidebar.jpg');
    $badge = $post->published_at ? $post->published_at->format('d M, Y') : (in_array(strtolower($post->category ?? ''), ['import_regulation', 'import regulation', 'regulations']) ? 'Regulation' : 'Blog');
    $detailUrl = route('blog.detail', $post->slug);
@endphp
<article class="blog-card" onclick="window.location.href='{{ $detailUrl }}';">
    <!-- Uniform Image Box with Hover Zoom & Link -->
    <a href="{{ $detailUrl }}" class="blog-card-img-wrap" onclick="event.stopPropagation();">
        <img src="{{ $image }}" alt="{{ $post->title }}" class="blog-card-img" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('assets/landing/images/blog/blog-no-sidebar.jpg') }}';">
        <span class="blog-card-badge">
            {{ $badge }}
        </span>
    </a>

    <!-- Content Side with Bottom-Right READ MORE -->
    <div class="blog-card-content">
        <div>
            <h3 class="blog-card-title">
                <a href="{{ $detailUrl }}">{{ $post->title }}</a>
            </h3>
            <p class="blog-card-excerpt">
                {{ Str::limit(strip_tags($post->description ?? $post->content), 180) }}
            </p>
        </div>

        <div class="blog-card-action">
            <a href="{{ $detailUrl }}" class="blog-card-readmore">
                READ MORE
            </a>
        </div>
    </div>
</article>
