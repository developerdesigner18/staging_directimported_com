@extends('landing.master')
@section('title', 'Blog')

@push('style')
    <style>
        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }
        .blog-section {
            padding: 60px 0;
            min-height: 70vh;
            background: #ffffff;
        }
    </style>
@endpush

@section('main')
    <section class="blog-section">
        <div class="container">
            <div id="soro-blog"></div>
        </div>
    </section>
    <script src="https://app.trysoro.com/api/embed/20856bb6-3a84-4183-b5eb-36411a0301f2" defer></script>
@endsection
