@extends('landing.master')
@section('title','Japan Law')

@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
        .laws-container {
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

        .laws-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(243, 54, 79, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .laws-card:hover {
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

        .law-image {
            max-width: 100%;
            height: auto;
            display: block; /* Ensures images stack vertically */
            margin: 1.5rem auto; /* Adds vertical spacing and centers images */
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 1px solid #eee; /* Subtle border for definition */
        }

        @media (max-width: 768px) {
            .laws-container {
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

            .law-image {
                margin: 1rem auto;
            }
        }
    </style>
@endpush

@section('main')
    <div class="laws-container">
        <div class="container">
            <h2 class="main-title text-center">BIKE LAWS IN JAPAN</h2>

            <div class="row justify-content-center">
                <div class="col-12 col-lg-10">

                    <!-- Main Content Card -->
                    <div class="laws-card">
                        <div class="card-header-custom">
                            <h4 class="header-title">
                                Important Regulations for Motorcycling in Japan
                            </h4>
                        </div>
                        <div class="card-body-custom">
                            <div class="setPage">
                                <img src="{{asset('assets/laws/0.jpg')}}" alt="Japanese Bike Law 1" class="law-image">
                                <img src="{{asset('assets/laws/1.jpg')}}" alt="Japanese Bike Law 2" class="law-image">
                                <img src="{{asset('assets/laws/2.jpg')}}" alt="Japanese Bike Law 3" class="law-image">
                                <img src="{{asset('assets/laws/3.jpg')}}" alt="Japanese Bike Law 4" class="law-image">
                                <img src="{{asset('assets/laws/4.jpg')}}" alt="Japanese Bike Law 5" class="law-image">
                                <img src="{{asset('assets/laws/5.jpg')}}" alt="Japanese Bike Law 6" class="law-image">
                                <img src="{{asset('assets/laws/6.jpg')}}" alt="Japanese Bike Law 7" class="law-image">
                                <img src="{{asset('assets/laws/7.jpg')}}" alt="Japanese Bike Law 8" class="law-image">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

