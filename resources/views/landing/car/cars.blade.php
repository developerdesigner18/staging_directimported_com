@extends('landing.master')
@section('title', 'Cars - Coming Soon')

@push('style')
<style>
    .coming-soon-wrapper {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 80px 20px;
        background-color: #f8f9fa;
    }
    .coming-soon-card {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 50px 40px;
        max-width: 600px;
        width: 100%;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.06);
    }
    .coming-soon-icon {
        width: 70px;
        height: 70px;
        background: rgba(5, 60, 124, 0.08);
        color: #053C7C;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 20px;
    }
    .coming-soon-card h1 {
        font-size: 36px;
        font-weight: 700;
        color: #050B20;
        margin-bottom: 12px;
        line-height: 40px;
    }
    .coming-soon-card p {
        font-size: 16px;
        color: #64748b;
        line-height: 1.6;
        margin-bottom: 24px;
    }
    .btn-back-home {
        display: inline-block;
        background-color: #053C7C;
        color: #ffffff;
        font-weight: 600;
        padding: 12px 28px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    .btn-back-home:hover {
        background-color: #de2b43;
        color: #ffffff;
    }
</style>
@endpush

@section('main')
<div class="coming-soon-wrapper">
    <div class="coming-soon-card">

        <h1>Coming Soon...</h1>
        <p>This page is currently under construction. Please check back later for updates!</p>
        <a href="{{ route('landing') }}" class="btn-back-home">Back to Home</a>
    </div>
</div>
@endsection