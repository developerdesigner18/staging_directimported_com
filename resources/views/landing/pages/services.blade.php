@extends('landing.master')
@section('title', 'Services')
@push('style')
    <style>
        .terms-container {
            background: linear-gradient(135deg, #fafafa 0%, #f5f5f5 100%);
            min-height: 100vh;
            padding: 60px 0;
        }

        .main-title {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 3rem;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

        .terms-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(20, 23, 51, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            background: white;
        }

        .terms-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(20, 23, 51, 0.15);
        }

        .card-header-custom {
            background: linear-gradient(135deg, #053C7C 0%, #141733 100%);
            color: white !important;
            padding: 1rem 1.5rem;
            border-bottom: none;
        }

        .card-header-title {
            margin: 0;
            font-size: 1.1rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            color: white !important;
        }

        .section-number {
            background: rgba(255, 255, 255, 0.2);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            color: white !important;
        }

        .card-body-custom {
            padding: 2rem;
            background: white;
            color: #444;
        }

        .page-header-title {
            margin-bottom: 3rem;
        }

        .services-sub-title {
            font-weight: 600;
        }

        .services-color-text {
            color: #053C7C;
        }

        .services-img {
            object-fit: cover;
            max-height: 250px;
        }

        @media (max-width: 768px) {
            .terms-container {
                padding: 30px 0;
            }

            .main-title {
                font-size: 2rem;
                margin-bottom: 2rem;
            }

            .card-body-custom {
                padding: 1.5rem;
            }

            .section-number {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }

            .card-header-title {
                font-size: 1rem;
            }
        }
    </style>
@endpush
@section('main')
    <div class="terms-container">
        <div class="container">
            <div class="text-center page-header-title mb-4">
                <h2 class="main-title text-center">Our Service</span></h2>
            </div>

            <div class="row justify-content-center">
                <div class="col-12">
                    <?php
    $cnt = 1;
                                                                ?>
                    @if($service->isNotEmpty())
                        @foreach($service as $item)
                            <div class="terms-card">
                                <div class="card-header-custom">
                                    <h4 class="card-header-title">
                                        <span class="section-number"><?= $cnt ?></span>
                                        {{$item->title ?? '-'}}
                                    </h4>
                                </div>
                                <div class="card-body-custom">
                                    <div class="row align-items-center">

                                        @php
                                            $image = (isset($item->images) && isset($item->images[0]))
                                                ? SERVICE_PATH . $item->images[0]
                                                : 'uploads/user_documents/default.jpg';
                                        @endphp

                                        <div class="col-md-4 mb-3 mb-md-0">
                                            <img src="{{ asset($image) }}" alt="{{ $item->title }}"
                                                class="img-fluid rounded w-100 services-img">
                                        </div>

                                        <div class="col-md-8">
                                            {!! $item->description !!}
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php        $cnt++; ?>
                        @endforeach
                    @else
                        <div class="text-center py-5">
                            <p class="text-muted">No services available right now.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection