@extends('landing.master')
@section('title', 'Blog')

@push('style')
    <style>
        .blog-container {
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            min-height: 80vh;
            padding: 60px 0;
        }

        .main-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .main-title::after {
            content: '';
            position: absolute;
            bottom: -12px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(90deg, #053C7C, #141733);
            border-radius: 2px;
        }

        .blog-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(20, 23, 51, 0.08);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .blog-card:hover {
            transform: translateY(-7px);
            box-shadow: 0 18px 35px rgba(20, 23, 51, 0.15);
        }

        .blog-img-wrapper {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            background-color: #f0f2f5;
        }

        .blog-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }

        .blog-card:hover .blog-img {
            transform: scale(1.05);
        }

        .blog-date-badge {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(5, 60, 124, 0.9);
            color: #ffffff;
            font-size: 0.82rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 20px;
            backdrop-filter: blur(4px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .blog-card-body {
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .blog-card-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #141733;
            margin-bottom: 0.75rem;
            line-height: 1.4;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .blog-card-title a {
            color: #141733;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .blog-card-title a:hover {
            color: #053C7C;
        }

        .blog-card-description {
            color: #6c757d;
            font-size: 0.92rem;
            line-height: 1.6;
            margin-bottom: 1.25rem;
            flex-grow: 1;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .blog-card-footer {
            margin-top: auto;
            padding-top: 0.75rem;
            border-top: 1px solid #f0f0f0;
        }

        .btn-read-more {
            display: inline-flex;
            align-items: center;
            color: white;
            font-weight: 600;
            font-size: 0.92rem;
            text-decoration: none;
            transition: color 0.2s ease, transform 0.2s ease;
        }

        .btn-read-more i {
            margin-left: 6px;
            transition: transform 0.2s ease;
        }

        .btn-read-more:hover {
            color: #141733;
            text-decoration: none;
        }

        .btn-read-more:hover i {
            transform: translateX(4px);
        }

        .pagination-wrapper {
            margin-top: 2rem;
        }

        .pagination-wrapper .pagination {
            justify-content: center;
        }

        @media (max-width: 768px) {
            .blog-container {
                padding: 35px 0;
            }

            .main-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .blog-img-wrapper {
                height: 190px;
            }
        }
    </style>
@endpush

@section('main')
    <div class="blog-container">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="main-title text-center">Latest News & Articles</h2>
            </div>

            @if($posts->isNotEmpty())
                <div class="row">
                    @foreach($posts as $post)
                        <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                            <div class="blog-card">
                                <div class="blog-img-wrapper">
                                    @php
                                        $image = !empty($post->featured_image)
                                            ? $post->featured_image
                                            : asset('assets/landing/images/blog/blog-no-sidebar.jpg');
                                    @endphp
                                    <a href="{{ route('blog.detail', $post->slug) }}">
                                        <img src="{{ $image }}" alt="{{ $post->title }}" class="blog-img" loading="lazy"
                                             onerror="this.onerror=null;this.src='{{ asset('assets/landing/images/blog/blog-no-sidebar.jpg') }}';">
                                    </a>
                                    @if($post->published_at)
                                        <span class="blog-date-badge">
                                            <i class="far fa-calendar-alt mr-1"></i> {{ $post->published_at->format('d M, Y') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="blog-card-body">
                                    <h5 class="blog-card-title">
                                        <a href="{{ route('blog.detail', $post->slug) }}">
                                            {{ $post->title }}
                                        </a>
                                    </h5>

                                    <p class="blog-card-description">
                                        {{ Str::limit(strip_tags($post->description ?? $post->content), 130) }}
                                    </p>

                                    <div class="blog-card-footer">
                                        <a href="#" class="btn-read-more">
                                            Read More <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($posts->hasPages())
                    <div class="row">
                        <div class="col-12 pagination-wrapper">
                            {{ $posts->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            @else
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center py-5">
                        <div class="alert alert-info shadow-sm p-4 rounded-lg">
                            <i class="fas fa-newspaper fa-3x mb-3 text-muted"></i>
                            <h4 class="font-weight-bold">No Blog Posts Available</h4>
                            <p class="text-muted mb-0">Check back soon for latest news, updates, and articles.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('script')
@endsection
