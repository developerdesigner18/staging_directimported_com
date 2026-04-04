@extends('landing.master')
@section('title','About Our Bikes')

@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
        .bikes-container {
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            min-height: 100vh;
            padding: 60px 0;
        }

        .main-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: relative;
        }

        .main-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 4px;
            background: linear-gradient(90deg, #053C7C, #8A1821);
            border-radius: 2px;
        }

        .bikes-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(243, 54, 79, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .bikes-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(243, 54, 79, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #053C7C 0%, #8A1821 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
            text-align: center;
        }

        .header-title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            font-style: italic;
        }

        .card-body-custom {
            padding: 2.5rem;
            background: white;
        }

        .intro-text {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
            margin-bottom: 2rem;
            text-align: justify;
        }

        .features-section {
            background: #fff5f6;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            border-left: 4px solid #053C7C;
        }

        .features-title {
            color: #053C7C;
            font-weight: 600;
            font-size: 1.2rem;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .bike-categories {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin: 2rem 0;
        }

        .category-card {
            background: white;
            border-radius: 10px;
            padding: 1.5rem;
            box-shadow: 0 5px 15px rgba(243, 54, 79, 0.1);
            border: 2px solid #f8f9fa;
            transition: all 0.3s ease;
        }

        .category-card:hover {
            border-color: #053C7C;
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(243, 54, 79, 0.15);
        }

        .category-title {
            color: #053C7C;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            text-align: center;
        }

        .category-description {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            text-align: center;
        }

        .included-features {
            background: #053C7C;
            color: white;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
        }

        .included-features h5 {
            color: white;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
            font-size: 1.2rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .feature-item {
            background: rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 1rem;
            text-align: center;
            transition: background 0.3s ease;
        }

        .feature-item:hover {
            background: rgba(255,255,255,0.2);
        }

        .feature-icon {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            display: block;
        }

        .maintenance-highlight {
            background: linear-gradient(135deg, #fff5f6 0%, #ffeef0 100%);
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            border: 2px solid #053C7C;
            text-align: center;
        }

        .maintenance-highlight h5 {
            color: #053C7C;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.2rem;
        }

        .maintenance-highlight p {
            color: #444;
            font-size: 1rem;
            line-height: 1.7;
            margin: 0;
        }

        .bike-image-placeholder {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 15px;
            height: 300px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 2rem 0;
            border: 2px dashed #053C7C;
            color: #053C7C;
            font-size: 1.1rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .bikes-container {
                padding: 30px 0;
            }

            .main-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            .bike-categories {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }

            .intro-text {
                font-size: 1rem;
            }

            .header-title {
                font-size: 1.1rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="bikes-container">
        <div class="container">
            <h2 class="main-title text-center">About Our Bikes</h2>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">

                    <!-- Main Content Card -->
                    <div class="bikes-card">
                        <div class="card-header-custom">
                            <h4 class="header-title">
                                Bike Rental Japan is proud to offer Kansai's best selection of rental motorcycles.
                            </h4>
                        </div>
                        <div class="card-body-custom">

                            <!-- Introduction Text -->
                            <p class="intro-text">
                                We offer a wide range of popular motorcycles including small 250s for shorter rides and offroad, 400s for longer, 750 or near for adventure touring and larger capacity sports touring bikes. For whatever you require, we have what you need, the best professionally maintained bikes, good quality gear, so that you can get on the road hassle free.
                            </p>

                            <!-- Bike Categories -->
                            <div class="features-section">
                                <h5 class="features-title">Our Motorcycle Categories</h5>
                                <div class="bike-categories">
                                    <div class="category-card">
                                        <h6 class="category-title">250cc Bikes</h6>
                                        <p class="category-description">Perfect for shorter rides and off-road adventures. Lightweight and easy to handle for city exploration.</p>
                                    </div>
                                    <div class="category-card">
                                        <h6 class="category-title">400cc Bikes</h6>
                                        <p class="category-description">Ideal for longer journeys with more power and comfort for extended touring.</p>
                                    </div>
                                    <div class="category-card">
                                        <h6 class="category-title">750cc+ Bikes</h6>
                                        <p class="category-description">Adventure touring and larger capacity sports touring bikes for serious riders.</p>
                                    </div>
                                    <div class="category-card">
                                        <h6 class="category-title">Classic Models</h6>
                                        <p class="category-description">Popular classic bikes alongside our modern fleet for a unique riding experience.</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Bike Image Placeholder -->
                            <div class="bike-image-placeholder">
                                🏍️ Professional Quality Motorcycles
                            </div>

                            <!-- Included Features -->
                            <div class="included-features">
                                <h5>What's Included with Every Bike</h5>
                                <p style="text-align: center; margin-bottom: 1.5rem;">
                                    Bikes come with panniers, ETC readers (you need them for highway entry, please see our further information on the requirements) USB power outlets and smart phone holders.
                                </p>
                                <div class="features-grid">
                                    <div class="feature-item">
                                        <span class="feature-icon">🎒</span>
                                        <strong>Panniers</strong><br>
                                        <small>Storage bags for your belongings</small>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-icon">📡</span>
                                        <strong>ETC Readers</strong><br>
                                        <small>Required for highway entry</small>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-icon">🔌</span>
                                        <strong>USB Power Outlets</strong><br>
                                        <small>Keep your devices charged</small>
                                    </div>
                                    <div class="feature-item">
                                        <span class="feature-icon">📱</span>
                                        <strong>Phone Holders</strong><br>
                                        <small>Secure mounting for navigation</small>
                                    </div>
                                </div>
                            </div>

                            <!-- Maintenance Highlight -->
                            <div class="maintenance-highlight">
                                <h5>Professional Maintenance Standards</h5>
                                <p>
                                    We pride ourselves on the high level maintenance of our bikes. Most of our bikes are late up to date models, however we do rent some more popular classic bikes. Every motorcycle in our fleet undergoes rigorous maintenance checks to ensure your safety and riding pleasure.
                                </p>
                            </div>

                            <!-- Additional Information -->
                            <div class="features-section">
                                <h5 class="features-title">Why Choose Our Bikes?</h5>
                                <div class="bike-categories">
                                    <div class="category-card">
                                        <h6 class="category-title">✅ Quality Assurance</h6>
                                        <p class="category-description">Every bike is professionally maintained and regularly serviced to the highest standards.</p>
                                    </div>
                                    <div class="category-card">
                                        <h6 class="category-title">🛡️ Safety First</h6>
                                        <p class="category-description">All motorcycles come with quality safety gear and are thoroughly inspected before each rental.</p>
                                    </div>
                                    <div class="category-card">
                                        <h6 class="category-title">🚀 Hassle-Free Experience</h6>
                                        <p class="category-description">Get on the road quickly with our streamlined rental process and well-equipped bikes.</p>
                                    </div>
                                    <div class="category-card">
                                        <h6 class="category-title">🏆 Best Selection</h6>
                                        <p class="category-description">Kansai's finest selection of rental motorcycles for every type of rider and journey.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
