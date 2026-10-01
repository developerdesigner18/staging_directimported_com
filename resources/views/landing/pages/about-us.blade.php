@extends('landing.master')
@section('title', 'About Us')

@push('style')
<style>
    /* Direct Imported Exact Site Styles */
    :root {
        --di-navy-blue: #053C7C;
        --di-navy-hover: #de2b43;
        --di-dark-footer: #081726;
        --di-heading-color: #1e293b;
        --di-body-text: #475569;
        --di-border-light: #e2e8f0;
        --di-card-radius: 12px;
    }

    .text-dk {
        color: #050B20;
    }

    /* Primary Navy Button Matching Site Screenshot */
    .btn-di-navy {
        background-color: var(--di-navy-blue);
        color: #ffffff !important;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        padding: 12px 32px;
        border-radius: 4px;
        border: none;
        text-decoration: none;
        display: inline-block;
        transition: background-color 0.2s ease;
    }

    .btn-di-navy:hover {
        background-color: var(--di-navy-hover);
        color: #ffffff !important;
    }

    /* Icon Pill Badge */
    .pill-badge {
        display: inline-flex;
        align-items: center;
        background-color: #f8fafc;
        padding: 8px 16px;
        border-radius: 50px;
        border: 1px solid var(--di-border-light);
        color: #64748b;
        font-size: 0.9rem;
        font-weight: 500;
    }

    .pill-badge i {
        color: #94a3b8;
        font-size: 1.25rem;
        margin-right: 8px;
    }

    /* Image Cards with Soft Rounded Corners */
    .rounded-image-card {
        border-radius: var(--di-card-radius);
        overflow: hidden;
        border: 1px solid var(--di-border-light);
        background-color: #f8fafc;
    }

    /* Content Cards */
    .di-card {
        background: #ffffff;
        border: 1px solid var(--di-border-light);
        border-radius: var(--di-card-radius);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .di-card:hover {
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.07);
    }

    /* Table Header Matching Dark Navy */
    .table-header-navy {
        background-color: var(--di-dark-footer);
        color: #ffffff;
    }

    /* Custom Image and Layout Utility Classes */
    .about-hero-img {
        height: 340px;
        object-fit: cover;
    }

    .about-inspection-img {
        height: 190px;
        object-fit: cover;
    }

    .about-card-img {
        min-height: 290px;
        object-fit: cover;
    }

    .icon-navy {
        color: var(--di-navy-blue);
    }

    .col-w-30 {
        width: 30%;
    }

    .col-w-70 {
        width: 70%;
    }

    .about-passion-header {
        max-width: 750px;
    }

    /* CMS rich-text blocks: match the original paragraph styling */
    .about-intro p {
        margin-bottom: 1rem;
    }

    .about-intro p:first-child {
        font-weight: 300;
    }

    .about-intro p:last-child {
        margin-bottom: 1.5rem;
    }
</style>
@endpush

@section('main')
@php
    $about = $homeSection ?? new \App\Models\HomeSection();
    $heroImage = \App\Models\HomeSection::imageUrl($about->about_hero_image);
    $storyImages = collect($about->story_images ?? [])->map(fn($image) => \App\Models\HomeSection::imageUrl($image))->filter();
    $operations = collect($about->operations ?? [])->filter(fn($item) => filled($item['title'] ?? null));
    $facts = collect($about->facts ?? [])->filter(fn($item) => filled($item['feature'] ?? null));
    $passionCards = collect($about->passion_cards ?? [])->filter(fn($item) => filled($item['title'] ?? null));
    $hasStory = filled($about->founded_title) || filled($about->founded_content) || filled($about->advantage_title) || filled($about->advantage_content);
@endphp

<!-- Main Header / Hero Intro -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="row align-items-center">
            <div class="{{ $heroImage ? 'col-lg-7 mb-4 mb-lg-0' : 'col-lg-12' }}">
                @if(filled($about->about_badge))
                <div class="pill-badge mb-3">
                    <i class="bx bx-check-circle"></i> {{ $about->about_badge }}
                </div>
                @endif
                <h1 class="fw-bold display-5 text-dk mb-3">{{ $about->about_title ?: 'About Us' }}</h1>
                @if(filled($about->about_intro))
                <div class="about-intro text-secondary">
                    {!! $about->about_intro !!}
                </div>
                @endif
                @if(filled($about->about_button_text))
                <a href="#about-details" class="btn-di-navy">{{ $about->about_button_text }}</a>
                @endif
            </div>
            @if($heroImage)
            <div class="col-lg-5">
                <div class="rounded-image-card shadow-sm">
                    <img src="{{ $heroImage }}" alt="{{ $about->about_title }}" class="img-fluid w-100 about-hero-img">
                </div>
            </div>
            @endif
        </div>
    </div>
</section>

<!-- Main Content Area -->
<div class="py-4 bg-light" id="about-details">
    <div class="container py-4">

        <!-- Why Founded & Automotive Inspection Advantage -->
        @if($hasStory)
        <div class="row align-items-center mb-5">
            @if($storyImages->isNotEmpty())
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="row g-3">
                    @foreach($storyImages as $storyImage)
                    <div class="col-12">
                        <div class="rounded-image-card">
                            <img src="{{ $storyImage }}" alt="{{ $about->founded_title }}" class="img-fluid w-100 about-inspection-img">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            <div class="{{ $storyImages->isNotEmpty() ? 'col-lg-7 ps-lg-5' : 'col-lg-12' }}">
                @if(filled($about->founded_title))
                <h2 class="h3 fw-bold text-dk mb-3">{{ $about->founded_title }}</h2>
                @endif
                @if(filled($about->founded_content))
                <div class="text-secondary">
                    {!! $about->founded_content !!}
                </div>
                @endif

                @if(filled($about->advantage_title))
                <h3 class="h4 fw-bold mt-4 mb-2 text-dk">{{ $about->advantage_title }}</h3>
                @endif
                @if(filled($about->advantage_content))
                <div class="text-secondary">
                    {!! $about->advantage_content !!}
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Core Operations -->
        @if($operations->isNotEmpty())
        <div class="my-5 py-3">
            <div class="text-center mb-4">
                @if(filled($about->operations_title))
                <h2 class="fw-bold text-dk">{{ $about->operations_title }}</h2>
                @endif
                @if(filled($about->operations_subtitle))
                <p class="text-muted">{{ $about->operations_subtitle }}</p>
                @endif
            </div>
            <div class="row g-4">
                @foreach($operations as $operation)
                <div class="col-md-4">
                    <div class="p-4 di-card h-100">
                        <i class="{{ ($operation['icon'] ?? null) ?: 'bx bx-check-circle' }} fs-1 mb-3 icon-navy"></i>
                        <h4 class="h5 fw-bold text-dk">{{ $operation['title'] }}</h4>
                        @if(filled($operation['description'] ?? null))
                        <p class="text-secondary small mb-0">
                            {{ $operation['description'] }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Operational Facts Table -->
        @if($facts->isNotEmpty())
        <div class="mb-5">
            @if(filled($about->facts_title))
            <h2 class="h3 fw-bold text-dk mb-4">{{ $about->facts_title }}</h2>
            @endif
            <div class="table-responsive rounded border bg-white">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-header-navy">
                        <tr>
                            <th scope="col" class="col-w-30">Feature</th>
                            <th scope="col" class="col-w-70">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facts as $fact)
                        <tr>
                            <td class="fw-bold"><i class="{{ ($fact['icon'] ?? null) ?: 'bx bx-check-circle' }} me-2 icon-navy"></i>{{ $fact['feature'] }}</td>
                            <td>{{ $fact['details'] ?? '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Blog-Style Vehicles Sourced & Passion Section -->
        <div class="my-5 pt-3">
            @if(filled($about->passion_title) || filled($about->passion_intro))
            <div class="text-center mx-auto mb-5 about-passion-header">
                @if(filled($about->passion_title))
                <h2 class="fw-bold text-dk">{{ $about->passion_title }}</h2>
                @endif
                @if(filled($about->passion_intro))
                <div class="text-secondary">
                    {!! $about->passion_intro !!}
                </div>
                @endif
            </div>
            @endif

            @foreach($passionCards as $card)
            @php($cardImage = \App\Models\HomeSection::imageUrl($card['image'] ?? null))
            <div class="di-card mb-4 overflow-hidden">
                <div class="row g-0 align-items-center @if($loop->even) flex-row-reverse @endif">
                    @if($cardImage)
                    <div class="col-md-6">
                        <img src="{{ $cardImage }}" alt="{{ $card['title'] }}" class="img-fluid h-100 w-100 about-card-img">
                    </div>
                    @endif
                    <div class="{{ $cardImage ? 'col-md-6' : 'col-12' }}">
                        <div class="card-body p-4 p-lg-5">
                            @if(filled($card['badge'] ?? null))
                            <span class="badge bg-light text-dk border mb-2">{{ $card['badge'] }}</span>
                            @endif
                            <h3 class="h4 fw-bold text-dk mb-3">{{ $card['title'] }}</h3>
                            @if(filled($card['description'] ?? null))
                            <p class="card-text text-secondary">
                                {!! nl2br(e($card['description'])) !!}
                            </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if(filled($about->passion_button_text))
            <div class="text-center mt-5">
                <a href="{{ route('car') }}" class="btn-di-navy">{{ $about->passion_button_text }}</a>
            </div>
            @endif
        </div>

    </div>
</div>
@endsection
