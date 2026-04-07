@extends('landing.master')
@section('title','Useful Links')

@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
        .links-container {
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
            background: linear-gradient(90deg, #053C7C, #141733);
            border-radius: 2px;
        }

        .links-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(243, 54, 79, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .links-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(243, 54, 79, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #053C7C 0%, #141733 100%);
            color: white;
            padding: 1.5rem;
            border-bottom: none;
            text-align: center;
        }

        .header-title {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
        }

        .card-body-custom {
            padding: 2.5rem;
            background: white;
        }

        .link-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid #eee;
        }

        .link-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .link-icon {
            flex-shrink: 0;
            width: 50px;
            height: 50px;
            background: #053C7C;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 20px;
            box-shadow: 0 4px 10px rgba(243, 54, 79, 0.2);
        }

        .link-content {
            flex-grow: 1;
        }

        .link-content h4 {
            margin-top: 0;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .link-content h4 a {
            color: #053C7C;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .link-content h4 a:hover {
            color: #8A1821;
            text-decoration: underline;
        }

        .link-content p {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #555;
            margin-bottom: 0;
        }

        @media (max-width: 768px) {
            .links-container {
                padding: 30px 0;
            }

            .main-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            .link-item {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            .link-icon {
                margin-right: 0;
                margin-bottom: 15px;
            }

            .link-content h4 {
                font-size: 1.1rem;
            }

            .link-content p {
                font-size: 0.9rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="links-container">
        <div class="container">
            <h2 class="main-title text-center">Useful Links</h2>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">

                    <!-- Main Content Card -->
                    <div class="links-card">
                        <div class="card-header-custom">
                            <h4 class="header-title">
                                Resources to help you plan your trip to Japan
                            </h4>
                        </div>
                        <div class="card-body-custom">

                            <!-- Link Item: Road Use in Japan -->
                            <div class="link-item">
                                <div class="link-icon">🛣️</div>
                                <div class="link-content">
                                    <h4><a href="https://origami-book.com/column/course-en/7384" target="_blank">Road Use in Japan</a></h4>
                                    <p>This site has an amazing amount of information about all aspects of driving in Japan, a must read if this is your first time motoring here.</p>
                                </div>
                            </div>

                            <!-- Link Item: Currency Conversion -->
                            <div class="link-item">
                                <div class="link-icon">💰</div>
                                <div class="link-content">
                                    <h4><a href="https://www.xe.com/" target="_blank">Currency Conversion</a></h4>
                                    <p>All rates found on our website are in Japanese Yen (JPY). XE currency conversion is an easy to use website that can be used to convert our rates to your local currency.</p>
                                </div>
                            </div>

                            <!-- Link Item: Motorcycle Trip Packing List -->
                            <div class="link-item">
                                <div class="link-icon">🎒</div>
                                <div class="link-content">
                                    <h4><a href="https://drive.google.com/open?id=1MnuZll_ke0IUo_gQwtCHWwIlifB9vaBT" target="_blank">Motorcycle Trip Packing List</a></h4>
                                    <p>We expect you’ve been planning this for a long time and have it covered, but please do take a look at this list just to make sure nothing has slipped your mind.</p>
                                </div>
                            </div>

                            <!-- Link Item: JNTO-Destination Kansai -->
                            <div class="link-item">
                                <div class="link-icon">📍</div>
                                <div class="link-content">
                                    <h4><a href="https://www.japan.travel/en/destinations/kansai/" target="_blank">JNTO - Destination Kansai</a></h4>
                                    <p>Kansai and the surrounding areas are an amazing, beautiful and interesting place. We have made up some great tours however there is always much more to see that have nothing to do with bikes so please have a look and ride there anyway!</p>
                                </div>
                            </div>

                            <!-- Link Item: Japan Guide-Osaka -->
                            <div class="link-item">
                                <div class="link-icon">🏙️</div>
                                <div class="link-content">
                                    <h4><a href="https://www.japan-guide.com/e/e2157.html" target="_blank">Japan Guide - Osaka</a></h4>
                                    <p>Osaka is a unique, lively and interesting city with plenty to offer, more than we can tell anyone so please check here for yourself.</p>
                                </div>
                            </div>

                            <!-- Link Item: Osaka Weather -->
                            <div class="link-item">
                                <div class="link-icon">☀️</div>
                                <div class="link-content">
                                    <h4><a href="https://www.holiday-weather.com/osaka/" target="_blank">Osaka Weather</a></h4>
                                    <p>Japan has one of the most variable climates in the world with a possible 50°c difference between summer and winter, with a rainy and typhoon season. This site can help you plan your trip for your preferred weather.</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

