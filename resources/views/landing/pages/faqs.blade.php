@extends('landing.master')
@section('title',"FAQ's")

@push('style')
    <style>
        /* FAQ Card */

        .faq-card{
            background:#fff;
            border-radius:10px;
            box-shadow:0 5px 15px rgba(0,0,0,0.06);
            overflow:hidden;
            transition:all .3s ease;
        }

        .faq-card:hover{
            transform:translateY(-3px);
            box-shadow:0 10px 25px rgba(0,0,0,0.08);
        }

        /* Header */

        .faq-header{
            padding:18px 22px;
            font-weight:600;
            font-size:16px;
            cursor:pointer;
            display:flex;
            justify-content:space-between;
            align-items:center;
            background:#f7f9fb;
        }

        /* Body */

        .faq-body{
            padding:20px;
            line-height:1.7;
            color:#555;
        }

        /* Fix the ugly <pre> blocks */

        .faq-body pre{
            white-space:pre-wrap;
            word-break:break-word;
            font-family:inherit;
            background:transparent;
            border:none;
            padding:0;
            margin:0;
        }

        /* Icon */

        .faq-icon{
            font-size:22px;
            font-weight:700;
            transition:0.3s;
            color: darkred;
        }
        .faq-icon i{
            transition: transform 0.3s ease;
        }
    </style>
@endpush

@section('main')
    <!-- FAQ Section Start -->
    <section class="mt-5">
        <div class="container">
            <!-- FAQ Title -->
            <h2 class="text-center mb-5">Frequently Asked Questions</h2>
            <!-- FAQ  -->
            <div class="row" id="faqContainer">
                @foreach($faqs as $index => $faq)

                    <div class="col-lg-6 mb-4">

                        <div class="faq-card">

                            <div class="faq-header"
                                 role="button"
                                 data-bs-toggle="collapse"
                                 data-bs-target="#faq{{$index}}">

                                {{ $faq->key }}

                                <span class="faq-icon">
                                    <i class="bx bx-plus" style="color:#8f0000;" ></i>
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

            </div>
        </div>
    </section>
    <!-- FAQ Section End -->
@endsection
@section('script')
    <script>

        document.querySelectorAll('.collapse').forEach(function(el){

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

