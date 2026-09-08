@extends('landing.master')
@section('title', 'Rental Policies')

@push('style')
    <style>
     /* 
     * Custom CSS to exactly match the provided image references.
     * This isolates the styling so it won't conflict with your site's current theme.
     */
    .terms-page-wrapper {
        background-color: #f8f9fb; /* Light off-white background from image */
        padding: 50px 20px;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #333333;
    }
    /* .terms-container {
        max-width: 1000px;
        margin: 0 auto;
    } */
    
    /* Main Page Title */
    .terms-page-title {
        text-align: center;
        font-size: 2.75rem;
        font-weight: 700;
        color: #2b3b4e; /* Dark slate blue */
        margin-bottom: 50px;
        position: relative;
    }
    .terms-page-title::after {
        content: "";
        display: block;
        width: 80px;
        height: 4px;
        background-color: #172a53; /* Dark navy underline matching the image */
        margin: 15px auto 0;
        border-radius: 2px;
    }

    /* Card Styling */
    .term-card {
        background-color: #ffffff;
        border-radius: 8px;
        margin-bottom: 40px;
        /* Soft shadow with a very faint pink/red tint matching the reference images */
        box-shadow: 0 12px 35px rgba(220, 20, 60, 0.04), 0 4px 10px rgba(0,0,0,0.03);
        overflow: hidden;
    }

    /* Card Header */
    .term-header {
        background-color: #121933; /* Deep Navy Blue from image */
        padding: 0px 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .term-badge {
        background-color: #3460a8; /* Lighter blue circle */
        color: #ffffff;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        font-weight: 700;
        font-size: 15px;
        flex-shrink: 0;
    }
    .term-header h2 {
        color: #ffffff;
        margin: 0;
        font-size: 1.15rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Card Body */
    .term-body {
        padding: 30px 40px;
        line-height: 1.7;
        font-size: 0.95rem;
        color: #475569;
        /* Force word wrapping to prevent horizontal scrolling */
        word-wrap: break-word;
        overflow-wrap: break-word;
    }
    .term-body p {
        margin-top: 0;
        margin-bottom: 20px;
    }
    .term-body ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }
    .term-body li {
        margin-bottom: 24px;
        padding-left: 0;
    }
    .term-body li:last-child {
        margin-bottom: 0;
    }
    .term-body strong {
        color: #1e293b;
        font-weight: 600;
        display: inline-block;
        margin-bottom: 4px;
    }

    /* Table Styling for Insurace & Glossary */
    .table-wrapper {
        overflow-x: auto;
        margin-top: 15px;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
    }
    .terms-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        min-width: 600px; /* Ensures tables don't squish too much on mobile */
    }
    .terms-table th, .terms-table td {
        padding: 14px 20px;
        border-bottom: 1px solid #e2e8f0;
        vertical-align: top;
    }
    .terms-table th {
        background-color: #f8fafc;
        color: #0f172a;
        font-weight: 600;
    }
    .terms-table tr:last-child td {
        border-bottom: none;
    }

    /* Mobile Responsive Adjustments */
    @media (max-width: 768px) {
        .terms-page-title { font-size: 2rem; }
        .term-body { padding: 20px; }
        .term-header { padding: 14px 18px; }
    }
    </style>
@endpush

@section('main')
    <div class="container">
        
        <h1 class="terms-page-title">Terms and Conditions</h1>

        @if(isset($policies) && $policies->count() > 0)
            @foreach($policies as $index => $policy)
                <div class="term-card">
                    <div class="term-header">
                        <span class="term-badge">{{ $index + 1 }}</span>
                        <h2>{{ $policy->key }}</h2>
                    </div>
                    <div class="term-body">
                        {!! $policy->value !!}
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-5">
                <p class="text-muted">No Terms and Conditions available at the moment.</p>
            </div>
        @endif

    </div>
@endsection