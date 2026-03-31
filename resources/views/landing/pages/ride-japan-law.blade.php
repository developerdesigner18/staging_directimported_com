@extends('landing.master')
@section('title','Ride Japan Law')

@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
        .ride-container {
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
            background: linear-gradient(90deg, #F3364F, #d12a3f);
            border-radius: 2px;
        }

        .ride-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(243, 54, 79, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .ride-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(243, 54, 79, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #F3364F 0%, #d12a3f 100%);
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

        .intro-paragraph {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
            margin-bottom: 1.5rem;
            text-align: justify;
        }

        .video-section {
            background: #fff5f6;
            border-radius: 15px;
            padding: 2rem;
            margin: 2rem 0;
            border-left: 4px solid #F3364F;
            text-align: center;
        }

        .video-section h5 {
            color: #F3364F;
            font-weight: 600;
            margin-bottom: 1.5rem;
            font-size: 1.2rem;
        }

        .video-wrapper {
            position: relative;
            width: 100%;
            padding-bottom: 56.25%; /* 16:9 Aspect Ratio */
            height: 0;
            overflow: hidden;
            margin-bottom: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        .video-wrapper iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border: 0;
        }

        .happy-viewing {
            font-size: 1.1rem;
            font-weight: 600;
            color: #F3364F;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .ride-container {
                padding: 30px 0;
            }

            .main-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            .header-title {
                font-size: 1.1rem;
            }

            .intro-paragraph {
                font-size: 1rem;
            }

            .video-section {
                padding: 1.5rem;
            }
        }
    </style>
@endpush

@section('main')
    <div class="ride-container">
        <div class="container">
            <h2 class="main-title text-center">How to Ride Safely in Japan</h2>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">

                    <!-- Main Content Card -->
                    <div class="ride-card">
                        <div class="card-header-custom">
                            <h4 class="header-title">
                                Essential Information for a Safe Journey
                            </h4>
                        </div>
                        <div class="card-body-custom">

                            <p class="intro-paragraph">
                                Driving in Japan is fairly straightforward and very similar to other countries that drive on the left. The roads are all in good condition and the driving standard is generally high. Of course like any country there will always be the rare exception of an aggressive driver or damaged road, and winter can be treacherous with ice on higher roads (please see terms and conditions).
                            </p>

                            <p class="intro-paragraph">
                                There are some road signs and markings that may be unfamiliar that understanding is a must so watching the following two videos will familiarize you as much as possible. There is a checkbox in the contract to say you have watched and understood the content of the two videos, and questions may be asked to confirm understanding before the contract is finalized.
                            </p>

                            <p class="intro-paragraph">
                                We will be happy to answer any further questions you may have about driving in Japan.
                            </p>

                            <div class="video-section">
                                <h5>Watch These Videos to Prepare for Your Ride!</h5>
                                <p class="text-muted mb-4">Familiarize yourself with Japanese road signs and driving etiquette.</p>

                                <div class="video-wrapper">
                                    <iframe src="https://www.youtube.com/embed/-5J-oEJBrWY"
                                            title="YouTube video player 1"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                </div>

                                <div class="video-wrapper">
                                    <iframe src="https://www.youtube.com/embed/5mYmPa2nkpY"
                                            title="YouTube video player 2"
                                            frameborder="0"
                                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen>
                                    </iframe>
                                </div>

                                <p class="happy-viewing">Happy viewing!</p>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection