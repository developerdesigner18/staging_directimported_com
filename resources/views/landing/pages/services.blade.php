@extends('landing.master')
@section('title', 'Our Services')

@push('style')
<style>
    /* Services Page Section Wrapper */
    .services-wrapper {
        background-color: #ffffff;
        color: #334155;
        padding-top: 4rem;
        padding-bottom: 5rem;
    }

    /* Modern Header Block */
    .services-header {
        text-align: center;
        max-width: 48rem;
        margin-left: auto;
        margin-right: auto;
        margin-bottom: 5rem;
    }

    .services-pill-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        border-radius: 50rem;
        background-color: #f1f5f9;
        border: 1px solid #e2e8f0;
        color: #0b4c8c;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.05em;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }

    .services-pill-badge i {
        color: #0b4c8c;
        font-size: 1rem;
    }

    .services-main-title {
        font-size: 2.75rem;
        font-weight: 800;
        color: #050B20;
        letter-spacing: -0.025em;
        margin-bottom: 1rem;
    }

    .services-main-desc {
        color: #475569;
        font-size: 1.125rem;
        line-height: 1.7;
        margin: 0;
    }

    /* Alternating Services Layout */
    .services-list {
        display: flex;
        flex-direction: column;
        gap: 6rem;
    }

    /* Image Frame with Aspect Ratio 4:3 */
    .service-img-frame {
        position: relative;
        width: 100%;
        aspect-ratio: 4 / 3;
        border-radius: 1rem;
        overflow: hidden;
        border: 1px solid #f1f5f9;
        box-shadow: 0 20px 40px -15px rgba(11, 26, 48, 0.08);
    }

    .service-img-frame img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .service-img-frame:hover img {
        transform: scale(1.05);
    }

    .service-img-badge {
        position: absolute;
        top: 1rem;
        left: 1rem;
        background-color: rgba(11, 26, 48, 0.9);
        color: #ffffff;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 0.375rem 0.875rem;
        border-radius: 0.5rem;
        backdrop-filter: blur(4px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    /* Content Block */
    .service-content {
        display: flex;
        flex-direction: column;
        gap: 1.25rem;
    }

    .service-number-row {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .service-number {
        font-size: 1.875rem;
        font-weight: 900;
        color: rgba(11, 76, 140, 0.3);
        line-height: 1;
    }

    .service-divider {
        height: 1px;
        background-color: #e2e8f0;
        flex-grow: 1;
    }

    .service-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #050B20;
        line-height: 1.35;
        margin: 0;
    }

    .service-text {
        color: #475569;
        font-size: 1rem;
        line-height: 1.7;
        margin: 0;
    }

    .service-text p:last-child {
        margin-bottom: 0;
    }

    /* Feature Tags */
    .service-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
        padding-top: 0.5rem;
    }

    .feature-tag {
        background-color: #f8fafc;
        color: #0b4c8c;
        border: 1px solid #e2e8f0;
        padding: 0.375rem 0.875rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        transition: all 0.2s ease;
    }

    .feature-tag i {
        color: #0b4c8c;
        font-size: 0.95rem;
        transition: color 0.2s ease;
    }

    .feature-tag:hover {
        background-color: #0b4c8c;
        color: #ffffff;
        border-color: #0b4c8c;
    }

    .feature-tag:hover i {
        color: #ffffff;
    }

    /* Responsive adjustments */
    @media (max-width: 991.98px) {
        .services-main-title {
            font-size: 2rem;
        }

        .services-header {
            margin-bottom: 3.5rem;
        }

        .services-list {
            gap: 4rem;
        }

        .service-title {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('main')
<section class="services-wrapper">
    <div class="container">

        <!-- Modern Header Block -->
        @if(filled($settings->services_page_badge) || filled($settings->services_page_title) || filled($settings->services_page_description))
        <div class="services-header">
            @if(filled($settings->services_page_badge))
            <div class="services-pill-badge">
                <i class="bx bx-shield-alt-2"></i>
                {{ $settings->services_page_badge }}
            </div>
            @endif
            @if(filled($settings->services_page_title))
            <h1 class="services-main-title">
                {{ $settings->services_page_title }}
            </h1>
            @endif
            @if(filled($settings->services_page_description))
            <p class="services-main-desc">
                {{ $settings->services_page_description }}
            </p>
            @endif
        </div>
        @endif

        <!-- Alternating Split Showcase -->
        <div class="services-list">

            @forelse($services as $service)
            <div class="row align-items-center g-4 g-lg-5 @if($loop->even) flex-column-reverse flex-lg-row-reverse @endif">
                @if($service->image_url)
                <div class="col-lg-6">
                    <div class="service-img-frame">
                        <img src="{{ $service->image_url }}" alt="{{ $service->title }}" loading="lazy">
                        @if(filled($service->image_badge))
                        <div class="service-img-badge">
                            {{ $service->image_badge }}
                        </div>
                        @endif
                    </div>
                </div>
                @endif
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-number-row">
                            <span class="service-number">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="service-divider"></span>
                        </div>
                        <h2 class="service-title">
                            {{ $service->title }}
                        </h2>
                        @if(filled($service->description))
                        <div class="service-text">
                            {!! $service->description !!}
                        </div>
                        @endif
                        @if(!empty($service->features))
                        <div class="service-tags">
                            @foreach($service->features as $feature)
                            <span class="feature-tag">
                                <i class="{{ ($feature['icon'] ?? null) ?: 'bx bx-check-circle' }}"></i> {{ $feature['text'] ?? '' }}
                            </span>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <p class="services-main-desc text-center">No services available right now.</p>
            @endforelse

        </div>
    </div>
</section>
@endsection
