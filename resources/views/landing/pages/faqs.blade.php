@extends('landing.master')
@section('title', "FAQ's")

@push('style')
    {{-- <style>
        /* FAQ Card */

        .faq-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.06);
            overflow: hidden;
            transition: all .3s ease;
        }

        .faq-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        /* Header */

        .faq-header {
            padding: 18px 22px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f7f9fb;
        }

        /* Body */

        .faq-body {
            padding: 20px;
            line-height: 1.7;
            color: #555;
        }

        /* Fix the ugly <pre> blocks */

        .faq-body pre {
            white-space: pre-wrap;
            word-break: break-word;
            font-family: inherit;
            background: transparent;
            border: none;
            padding: 0;
            margin: 0;
        }

        /* Icon */

        .faq-icon {
            font-size: 22px;
            font-weight: 700;
            transition: 0.3s;
            color: darkred;
        }

        .faq-icon i {
            transition: transform 0.3s ease;
        }
    </style> --}}
    <style>
        /* FAQ Container Variables - Direct Imported Branding */
        :root {
            --primary-color: #2c3e50;
            --accent-color: #3498db;
            --bg-color: #f9f9f9;
            --text-color: #333333;
            --border-color: #e0e0e0;
        }

        .faq-container {
            max-width: 960px;
            margin: 40px auto;
            padding: 0 20px;
            color: var(--text-color);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        .faq-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .faq-header h1 {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 10px;
            font-weight: 700;
        }

        .faq-header p {
            font-size: 1.1rem;
            color: #666;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
        }

        .faq-category-title {
            font-size: 1.5rem;
            color: var(--primary-color);
            margin-top: 50px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--accent-color);
            font-weight: 600;
        }

        .faq-item {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.2s ease-in-out;
        }

        .faq-item summary {
            padding: 18px 24px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            list-style: none;
            position: relative;
            background-color: var(--bg-color);
            color: var(--primary-color);
        }

        .faq-item summary::after {
            content: '+';
            position: absolute;
            right: 24px;
            font-size: 1.5rem;
            color: var(--accent-color);
            transition: transform 0.3s ease;
        }

        .faq-item[open] summary::after {
            content: '−';
        }

        .faq-item summary::-webkit-details-marker {
            display: none;
        }

        .faq-item summary:hover {
            background-color: #f1f1f1;
        }

        .faq-content {
            padding: 0 24px 24px 24px;
            line-height: 1.7;
        }

        .faq-content p {
            margin-top: 15px;
            margin-bottom: 10px;
        }

        .faq-content ul {
            margin-top: 10px;
            margin-bottom: 10px;
            padding-left: 20px;
        }

        .faq-content li {
            margin-bottom: 8px;
        }

        .faq-footer {
            margin-top: 60px;
            text-align: center;
            padding: 30px;
            background-color: var(--bg-color);
            border-radius: 8px;
            border: 1px solid var(--border-color);
        }

        .faq-footer a {
            color: var(--accent-color);
            text-decoration: none;
            font-weight: bold;
        }

        .faq-footer a:hover {
            text-decoration: underline;
        }

        @media (max-width: 600px) {
            .faq-header h1 {
                font-size: 2rem;
            }

            .faq-item summary {
                font-size: 1rem;
                padding-right: 40px;
            }
        }
    </style>
@endpush

@section('main')
    <!-- FAQ Section Start -->
    <section class="mt-5">
        <div class="container">
            <!-- FAQ Title -->
            {{-- <h2 class="text-center mb-5">Frequently Asked Questions</h2> --}}
            <!-- FAQ  -->
            {{-- <div class="row" id="faqContainer">
                @foreach($faqs as $index => $faq)

                <div class="col-lg-6 mb-4">

                    <div class="faq-card">

                        <div class="faq-header" role="button" data-bs-toggle="collapse" data-bs-target="#faq{{$index}}">

                            {{ $faq->key }}

                            <span class="faq-icon">
                                <i class="bx bx-plus" style="color:#8f0000;"></i>
                            </span>

                        </div>

                        <div id="faq{{$index}}" class="collapse" data-bs-parent="#faqContainer">

                            <div class="faq-body">
                                {!! $faq->value !!}
                            </div>

                        </div>

                    </div>

                </div>

                @endforeach

            </div> --}}

            <div class="faq-container">

                <div class="faq-header">
                    <h1>Direct Imported Japan’s Guide</h1>
                    <p>Everything you need to know about sourcing, evaluating, bidding on, and shipping cars directly from
                        Japanese wholesale markets.</p>
                </div>

                @if(isset($categories) && $categories->count() > 0)
                    @foreach($categories as $category)
                        @if($category->faqs->count() > 0)
                            <h3 class="faq-category-title">{{ $category->name }}</h3>
                            @foreach($category->faqs as $faq)
                                <details class="faq-item">
                                    <summary>{{ $faq->key }}</summary>
                                    <div class="faq-content">
                                        {!! $faq->value !!}
                                    </div>
                                </details>
                            @endforeach
                        @endif
                    @endforeach
                @endif

                @if(isset($uncategorizedFaqs) && $uncategorizedFaqs->count() > 0)
                    @if(isset($categories) && $categories->count() > 0)
                        <h3 class="faq-category-title">General FAQs</h3>
                    @endif
                    @foreach($uncategorizedFaqs as $faq)
                        <details class="faq-item">
                            <summary>{{ $faq->key }}</summary>
                            <div class="faq-content">
                                {!! $faq->value !!}
                            </div>
                        </details>
                    @endforeach
                @endif

                @if((!isset($categories) || $categories->count() == 0) && (!isset($uncategorizedFaqs) || $uncategorizedFaqs->count() == 0))
                    @if(isset($faqs) && $faqs->count() > 0)
                        @foreach($faqs as $faq)
                            <details class="faq-item">
                                <summary>{{ $faq->key }}</summary>
                                <div class="faq-content">
                                    {!! $faq->value !!}
                                </div>
                            </details>
                        @endforeach
                    @else
                        <p class="text-center text-muted">No FAQs available at the moment.</p>
                    @endif
                @endif

                <div class="faq-footer">
                    <p><strong>Have a question not listed here?</strong></p>
                    <p>Our team is ready to assist you. <a href="{{ url('/contact') }}">Contact Direct Imported today.</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ Section End -->
@endsection
@section('script')
    <script>

        document.querySelectorAll('.collapse').forEach(function (el) {

            el.addEventListener('show.bs.collapse', function () {

                let icon = this.previousElementSibling.querySelector('.faq-icon i');

                icon.classList.remove('bx-plus');
                icon.classList.add('bx-minus');

            });

            el.addEventListener('hide.bs.collapse', function () {

                let icon = this.previousElementSibling.querySelector('.faq-icon i');

                icon.classList.remove('bx-minus');
                icon.classList.add('bx-plus');

            });

        });

    </script>
@endsection