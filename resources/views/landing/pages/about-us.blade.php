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
</style>
@endpush

@section('main')
<!-- Main Header / Hero Intro -->
<section class="py-5 bg-white">
    <div class="container py-2">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-4 mb-lg-0">
                <div class="pill-badge mb-3">
                    <i class="bx bx-check-circle"></i> Licensed Automobile Dealer in Japan
                </div>
                <h1 class="fw-bold display-5 text-dk mb-3">About Direct Imported Japan</h1>
                <p class="text-secondary lead fs-6 mb-3">
                    Direct Imported Japan operates under <strong>International Auto Select Japan LLC</strong> (short name <strong>IAS Japan</strong>), a fully licensed automobile dealer based in Japan. While operating under our independent LLC structure, we retain the Direct Imported website as our dedicated purchasing storefront, as we have for over 25 years.
                </p>
                <p class="text-secondary mb-4">
                    Our operations are managed by Phil Cathcart, a trade-qualified Automotive Technician who completed his technical schooling in 1992 and has been a resident of Japan for over 25 years.
                </p>
                <a href="#about-details" class="btn-di-navy">Read More</a>
            </div>
            <div class="col-lg-5">
                <div class="rounded-image-card shadow-sm">
                    <img src="{{ asset('uploads/about_us/pexels-photo-4489749.avif') }}" alt="Phil Cathcart - Vehicle Inspection" class="img-fluid w-100 about-hero-img">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Main Content Area -->
<div class="py-4 bg-light" id="about-details">
    <div class="container py-4">

        <!-- Why Founded & Automotive Inspection Advantage -->
        <div class="row align-items-center mb-5">
            <div class="col-lg-5 mb-4 mb-lg-0">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="rounded-image-card">
                            <img src="{{ asset('uploads/about_us/pexels-photo-2244746.avif') }}" alt="Engine Inspection" class="img-fluid w-100 about-inspection-img">
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="rounded-image-card">
                            <img src="{{ asset('uploads/about_us/pexels-photo-3807277.avif') }}" alt="Vehicle Panel Assessment" class="img-fluid w-100 about-inspection-img">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7 ps-lg-5">
                <h2 class="h3 fw-bold text-dk mb-3">Why We Were Founded</h2>
                <p class="text-secondary">
                    Direct Imported Japan was established to fix a major flaw in the vehicle export industry: reliance on commission-driven salespeople. Most export agents act purely as sales staff chasing monthly quotas, creating a hit-or-miss, 50/50 chance of buyers receiving a rusty, misrepresented car just so an agent can hit a target. As a licensed Japanese motor vehicle dealer, we operate without sales targets, providing accurate vehicle descriptions, honest condition reports, and true technical assessments.
                </p>

                <h3 class="h4 fw-bold mt-4 mb-2 text-dk">Our Automotive Inspection Advantage</h3>
                <p class="text-secondary">
                    Auction inspectors in Japan process hundreds of cars daily and frequently miss critical details, factory options, or aftermarket modifications—sometimes failing to even note a turbo attached to an engine block.
                </p>
                <p class="text-secondary">
                    Combining our export operations established in 1997 with formal automotive trade qualifications, Phil personally conducts physical inspections at the auctions we attend. Having worked as a vehicle assessor for nearly 30 years, his strict attention to detail ensures he spots misaligned panel gaps, concealed body repairs, rust, and mechanical issues before you place a bid or commit funds.
                </p>
            </div>
        </div>

        <!-- Core Operations -->
        <div class="my-5 py-3">
            <div class="text-center mb-4">
                <h2 class="fw-bold text-dk">Core Operations</h2>
                <p class="text-muted">Direct purchasing access across Japan with full logistics support</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="p-4 di-card h-100">
                        <i class="bx bx-gavel fs-1 mb-3 icon-navy"></i>
                        <h4 class="h5 fw-bold text-dk">Auction Vehicle Bidding</h4>
                        <p class="text-secondary small mb-0">
                            Direct access to bid on thousands of vehicles passing through Japanese wholesale auctions daily.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 di-card h-100">
                        <i class="bx bx-store-alt fs-1 mb-3 icon-navy"></i>
                        <h4 class="h5 fw-bold text-dk">Dealer Yard Sourcing</h4>
                        <p class="text-secondary small mb-0">
                            Direct purchasing of hand-picked stock from dealership yards across Japan.
                        </p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 di-card h-100">
                        <i class="bx bx-ship fs-1 mb-3 icon-navy"></i>
                        <h4 class="h5 fw-bold text-dk">Export & Logistics Management</h4>
                        <p class="text-secondary small mb-0">
                            Complete handling of Japanese export documentation, international shipping, and port logistics.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Operational Facts Table -->
        <div class="mb-5">
            <h2 class="h3 fw-bold text-dk mb-4">Operational Facts</h2>
            <div class="table-responsive rounded border bg-white">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-header-navy">
                        <tr>
                            <th scope="col" class="col-w-30">Feature</th>
                            <th scope="col" class="col-w-70">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold"><i class="bx bx-check-circle me-2 icon-navy"></i>Licensed Dealer Status</td>
                            <td>Fully registered and licensed motor vehicle trader in Japan.</td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bx bx-dollar-circle me-2 icon-navy"></i>Direct JPY Pricing</td>
                            <td>Vehicle listings display actual auction or dealer prices in Japanese Yen (JPY).</td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bx bx-search-alt me-2 icon-navy"></i>Expertly Assessed Listings</td>
                            <td>25 years of experience, along with his technical qualifications, means Phil has a keen eye for good cars. He will tell you to think twice about buying certain vehicles—an insight that only comes from many years of hands-on experience.</td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bx bx-receipt me-2 icon-navy"></i>Flat Service Fees</td>
                            <td>We charge a clear handling fee to manage the purchase, paperwork, and transport, with zero vehicle price markups.</td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="bx bx-globe me-2 icon-navy"></i>Global Export Specialists</td>
                            <td>We specialize in exporting vehicles to Australia, USA, UK, Ireland, Canada, and the Caribbean Islands.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Blog-Style Vehicles Sourced & Passion Section -->
        <div class="my-5 pt-3">
            <div class="text-center mx-auto mb-5 about-passion-header">
                <h2 class="fw-bold text-dk">Our Passion: The Vehicles We Source</h2>
                <p class="text-secondary">
                    While we happily source commercial vehicles, SUVs, and reliable daily drivers for our clients, our <strong>true passion and absolute specialty lies in high-performance and modified vehicles</strong>. We don't just export these cars; we live and breathe JDM and Euro performance culture.
                </p>
            </div>

            <!-- JDM Card with GT-R 32 V-Spec II -->
            <div class="di-card mb-4 overflow-hidden">
                <div class="row g-0 align-items-center">
                    <div class="col-md-6">
                        <img src="{{ asset('uploads/about_us/car_1785251750_6a68c7a61a8c1.webp') }}" alt="1994 Nissan Skyline GT-R V-Spec II" class="img-fluid h-100 w-100 about-card-img">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-light text-dk border mb-2">JDM Icons</span>
                            <h3 class="h4 fw-bold text-dk mb-3">JDM Legends & Classics</h3>
                            <p class="card-text text-secondary">
                                Japan is the birthplace of some of the most iconic sports cars in automotive history. We have a deep-rooted love for the golden era of Japanese performance. Our true specialty is hunting down the best Nissan Skylines, Toyota Supras, and Nissan Silvias. Whether it’s a pristine, low-kilometer factory classic like a 1994 GT-R V-Spec II or a heavily modified track weapon, our technical expertise means we know exactly what to look for—and what to avoid—when evaluating these legendary machines.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Euro / BMW M Card -->
            <div class="di-card mb-4 overflow-hidden">
                <div class="row g-0 align-items-center flex-row-reverse">
                    <div class="col-md-6">
                        <img src="{{ asset('uploads/about_us/pexels-photo-170811.avif') }}" alt="BMW M Performance European Sports Car" class="img-fluid h-100 w-100 about-card-img">
                    </div>
                    <div class="col-md-6">
                        <div class="card-body p-4 p-lg-5">
                            <span class="badge bg-light text-dk border mb-2">European Performance</span>
                            <h3 class="h4 fw-bold text-dk mb-3">European Precision & BMW M Cars</h3>
                            <p class="card-text text-secondary">
                                The Japanese market is a hidden goldmine for impeccably maintained European luxury and high-performance vehicles. We have a massive appreciation for the precision engineering of BMW M Cars and European classics. Because high-end Euro models are status symbols in Japan, they are often garaged, meticulously serviced, and driven sparingly. We leverage our years of experience on the ground to source the absolute best examples of European performance available.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="{{ route('car') }}" class="btn-di-navy">Search Live Auctions Now</a>
            </div>
        </div>

    </div>
</div>
@endsection
