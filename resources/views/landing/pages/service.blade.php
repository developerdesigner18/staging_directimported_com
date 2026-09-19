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
        <div class="services-header">
            <div class="services-pill-badge">
                <i class="bx bx-shield-alt-2"></i>
                Licensed Motor Vehicle Trader in Japan
            </div>
            <h1 class="services-main-title">
                End-to-End Procurement & Export
            </h1>
            <p class="services-main-desc">
                Independent technical evaluations, zero sales-target bias, and flat-fee handling for buyers worldwide.
            </p>
        </div>

        <!-- Alternating Split Showcase -->
        <div class="services-list">

            <!-- Service 01: Auction Vehicle Bidding & Inspection -->
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="service-img-frame">
                        <img src="{{ asset('uploads/service_images/photo-1503376780353-7e6692767b70.avif') }}" alt="Auction Vehicle Inspection" loading="lazy">
                        <div class="service-img-badge">
                            Hands-On Assessment
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-number-row">
                            <span class="service-number">01</span>
                            <span class="service-divider"></span>
                        </div>
                        <h2 class="service-title">
                            Auction Vehicle Bidding & Inspection
                        </h2>
                        <p class="service-text">
                            Trade-qualified Automotive Technician Phil Cathcart personally conducts physical inspections at Japanese wholesale auctions. With 30 years of vehicle assessment experience, he detects hidden rust, panel gaps, concealed repairs, and mechanical issues before you bid—delivering high-resolution photos and fully translated condition reports.
                        </p>
                        <div class="service-tags">
                            <span class="feature-tag">
                                <i class="bx bx-user-check"></i> Physical Checks
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-file"></i> Translated Sheets
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-camera"></i> High-Res Photos
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 02: Dealer Yard Direct Sourcing (Reversed Direction) -->
            <div class="row align-items-center g-4 g-lg-5 flex-column-reverse flex-lg-row-reverse">
                <div class="col-lg-6">
                    <div class="service-img-frame">
                        <img src="{{ asset('uploads/service_images/photo-1563720223185-11003d516935.avif') }}" alt="Dealer Yard Sourcing" loading="lazy">
                        <div class="service-img-badge">
                            Direct Purchase Storefront
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-number-row">
                            <span class="service-number">02</span>
                            <span class="service-divider"></span>
                        </div>
                        <h2 class="service-title">
                            Dealer Yard Direct Sourcing
                        </h2>
                        <p class="service-text">
                            Direct purchasing of hand-picked stock from dealership yards across Japan. Operating under International Auto Select Japan LLC as a licensed motor vehicle dealer, we charge a transparent flat service fee with zero price markups—displaying actual wholesale prices in Japanese Yen (JPY).
                        </p>
                        <div class="service-tags">
                            <span class="feature-tag">
                                <i class="bx bx-tag"></i> Flat Service Fee
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-yen"></i> Direct JPY Cost
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-block"></i> Zero Sales Quotas
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 03: Inspection & ODO Certification -->
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="service-img-frame">
                        <img src="{{ asset('uploads/service_images/photo-1508974239320-0a029497e820.avif') }}" alt="Vehicle Digital Odometer Dashboard" loading="lazy">
                        <div class="service-img-badge">
                            Verified Integrity
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-number-row">
                            <span class="service-number">03</span>
                            <span class="service-divider"></span>
                        </div>
                        <h2 class="service-title">
                            Inspection & ODO Certification
                        </h2>
                        <p class="service-text">
                            Not all auction grades are equal, and high-volume inspectors miss critical details. We provide thorough independent inspections along with official ODO certification (including JEVIC) to verify genuine kilometer readings and protect buyers from altered odometers.
                        </p>
                        <div class="service-tags">
                            <span class="feature-tag">
                                <i class="bx bx-badge-check"></i> JEVIC Certified
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-shield-quarter"></i> Mileage Audit
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 04: Documentation & Export Clearance (Reversed Direction) -->
            <div class="row align-items-center g-4 g-lg-5 flex-column-reverse flex-lg-row-reverse">
                <div class="col-lg-6">
                    <div class="service-img-frame">
                        <img src="{{ asset('uploads/service_images/photo-1450133064473-71024230f91b.avif') }}" alt="Export Documentation" loading="lazy">
                        <div class="service-img-badge">
                            Full Customs Support
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-number-row">
                            <span class="service-number">04</span>
                            <span class="service-divider"></span>
                        </div>
                        <h2 class="service-title">
                            Documentation & Export Clearance
                        </h2>
                        <p class="service-text">
                            Complete support with Japanese export deregistrations, translated certificates, and official customs export clearance. We manage all administrative compliance so your vehicle exports smoothly without customs holds or import delays.
                        </p>
                        <div class="service-tags">
                            <span class="feature-tag">
                                <i class="bx bx-receipt"></i> Export Certificate
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-id-card"></i> English Translation
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 05: Global Shipping & Port Logistics -->
            <div class="row align-items-center g-4 g-lg-5">
                <div class="col-lg-6">
                    <div class="service-img-frame">
                        <img src="{{ asset('uploads/service_images/photo-1578575437130-527eed3abbec.avif') }}" alt="Worldwide Export Logistics" loading="lazy">
                        <div class="service-img-badge">
                            Worldwide Freight
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-number-row">
                            <span class="service-number">05</span>
                            <span class="service-divider"></span>
                        </div>
                        <h2 class="service-title">
                            Global Shipping & Port Logistics
                        </h2>
                        <p class="service-text">
                            Complete handling of internal Japanese port transport and international freight booking. We specialize in vehicle shipping routes to Australia, the USA, UK, Ireland, Canada, and the Caribbean Islands via secure RORO or container vessels.
                        </p>
                        <div class="service-tags">
                            <span class="feature-tag">
                                <i class="bx bx-globe"></i> AU, USA, UK, IE, CA & Caribbean
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-ship"></i> RORO & Container
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Service 06: High-Performance JDM & European Classics (Reversed Direction) -->
            <div class="row align-items-center g-4 g-lg-5 flex-column-reverse flex-lg-row-reverse">
                <div class="col-lg-6">
                    <div class="service-img-frame">
                        <img src="{{ asset('uploads/service_images/photo-1617814076367-b759c7d7e738.avif') }}" alt="JDM & Euro Performance Cars" loading="lazy">
                        <div class="service-img-badge">
                            Specialist Passion
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-content">
                        <div class="service-number-row">
                            <span class="service-number">06</span>
                            <span class="service-divider"></span>
                        </div>
                        <h2 class="service-title">
                            High-Performance JDM & European Classics
                        </h2>
                        <p class="service-text">
                            Our specialty lies in high-performance and modified vehicles. We hunt down factory JDM classics like Skyline GT-Rs, Supras, and Silvias, alongside garaged European machinery like BMW M-cars. Phil's mechanical background guarantees expert inspection of tuning, aftermarket parts, and turbos.
                        </p>
                        <div class="service-tags">
                            <span class="feature-tag">
                                <i class="bx bx-car"></i> Skylines, Supras, Silvias
                            </span>
                            <span class="feature-tag">
                                <i class="bx bx-tachometer"></i> BMW M Cars & Euro Classics
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
