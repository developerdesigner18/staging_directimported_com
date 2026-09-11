@extends('landing.master')
@section('title', $post->title)

@push('style')
    <style>
        .blog-detail-container {
            background: #f8f9fa;
            min-height: 80vh;
            padding: 50px 0;
        }

        .blog-detail-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(20, 23, 51, 0.08);
            overflow: hidden;
            padding: 2.5rem;
            margin-bottom: 2rem;
        }

        .blog-detail-title {
            color: #141733;
            font-weight: 800;
            font-size: 2.2rem;
            line-height: 1.3;
            margin-bottom: 1rem;
        }

        .blog-meta-bar {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            color: #6c757d;
            font-size: 0.95rem;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #eef0f3;
        }

        .blog-detail-img-wrapper {
            width: 100%;
            max-height: 450px;
            overflow: hidden;
            border-radius: 12px;
            margin-bottom: 2rem;
        }

        .blog-detail-img {
            width: 100%;
            height: 100%;
            max-height: 450px;
            object-fit: cover;
        }

        .blog-detail-content {
            color: #333333;
            font-size: 1.05rem;
            line-height: 1.8;
        }

        .blog-detail-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin: 1.5rem 0;
        }

        .sidebar-card {
            background: #ffffff;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(20, 23, 51, 0.06);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #141733;
            margin-bottom: 1.25rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #053C7C;
        }

        .recent-post-item {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .recent-post-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .recent-post-img {
            width: 70px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .recent-post-title {
            font-size: 0.92rem;
            font-weight: 600;
            line-height: 1.3;
            color: #2c3e50;
            text-decoration: none;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .recent-post-title:hover {
            color: #053C7C;
            text-decoration: none;
        }

        .recent-post-date {
            font-size: 0.78rem;
            color: #8c98a4;
        }

        .btn-back-blogs {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #053C7C;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 1.5rem;
            transition: transform 0.2s ease;
        }

        .btn-back-blogs:hover {
            transform: translateX(-4px);
            color: #141733;
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .blog-detail-card {
                padding: 1.5rem;
            }

            .blog-detail-title {
                font-size: 1.6rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="blog-detail-container">
        <div class="container">
            <a href="{{ route('blog') }}" class="btn-back-blogs">
                <i class="fas fa-arrow-left"></i> Back to Blogs
            </a>

            <div class="row">
                <div class="col-lg-8">
                    <article class="blog-detail-card">
                        <h1 class="blog-detail-title">{{ $post->title }}</h1>

                        <div class="blog-meta-bar">
                            @if($post->published_at)
                                <span><i class="far fa-calendar-alt mr-1"></i> {{ $post->published_at->format('d M, Y') }}</span>
                            @endif
                            @if($post->soro_url)
                                <span><i class="fas fa-external-link-alt mr-1"></i> <a href="{{ $post->soro_url }}" target="_blank" rel="noopener noreferrer" class="text-muted">Original Post</a></span>
                            @endif
                        </div>

                        @if($post->featured_image)
                            <div class="blog-detail-img-wrapper">
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" class="blog-detail-img"
                                     onerror="this.style.display='none';">
                            </div>
                        @endif

                        <div class="blog-detail-content">
                            {!! $post->content ?? $post->description !!}
                        </div>
                    </article>
                </div>

                <div class="col-lg-4">
                    @if(isset($recentPosts) && $recentPosts->isNotEmpty())
                        <div class="sidebar-card">
                            <h4 class="sidebar-title">Recent Posts</h4>
                            @foreach($recentPosts as $recent)
                                <div class="recent-post-item">
                                    @php
                                        $recentImg = !empty($recent->featured_image)
                                            ? $recent->featured_image
                                            : asset('assets/landing/images/blog/blog-no-sidebar.jpg');
                                    @endphp
                                    <a href="{{ route('blog.detail', $recent->slug) }}">
                                        <img src="{{ $recentImg }}" alt="{{ $recent->title }}" class="recent-post-img"
                                             onerror="this.onerror=null;this.src='{{ asset('assets/landing/images/blog/blog-no-sidebar.jpg') }}';">
                                    </a>
                                    <div>
                                        <a href="{{ route('blog.detail', $recent->slug) }}" class="recent-post-title">
                                            {{ $recent->title }}
                                        </a>
                                        @if($recent->published_at)
                                            <div class="recent-post-date mt-1">
                                                <i class="far fa-calendar-alt"></i> {{ $recent->published_at->format('d M, Y') }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection
