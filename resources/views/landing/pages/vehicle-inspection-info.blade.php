@extends('landing.master')
@section('title', 'Vehicle Inspection Info')

@push('style')
<style>
    .coming-soon-wrapper {
        min-height: 70vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 60px 20px;
        background-color: #f8fafc;
        font-family: var(--font-body, 'Inter', sans-serif);
    }

    .coming-soon-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        padding: 60px 40px;
        text-align: center;
        max-width: 540px;
        width: 100%;
        position: relative;
        overflow: hidden;
    }

    .coming-soon-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--theme-red, #dc2626);
    }

    .coming-soon-icon {
        width: 76px;
        height: 76px;
        border-radius: 50%;
        background-color: #fee2e2;
        color: var(--theme-red, #dc2626);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        margin-bottom: 24px;
    }

    .coming-soon-badge {
        display: inline-block;
        padding: 5px 16px;
        border-radius: 9999px;
        background-color: #f1f5f9;
        color: #475569;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        margin-bottom: 16px;
    }

    .coming-soon-title {
        font-size: 28px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
        font-family: var(--font-heading, 'Poppins', sans-serif);
    }

    .coming-soon-desc {
        font-size: 15px;
        line-height: 1.6;
        color: #64748b;
        margin-bottom: 32px;
    }

    .coming-soon-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background-color: var(--theme-red, #dc2626);
        color: #ffffff !important;
        font-weight: 600;
        font-size: 14px;
        padding: 12px 28px;
        border-radius: 8px;
        text-decoration: none !important;
        transition: background-color 0.2s, transform 0.15s;
    }

    .coming-soon-btn:hover {
        background-color: #b91c1c;
        transform: translateY(-1px);
        color: #ffffff !important;
    }

    @media (max-width: 640px) {
        .coming-soon-card {
            padding: 40px 24px;
        }

        .coming-soon-title {
            font-size: 24px;
        }
    }
</style>
@endpush

@section('main')
<div class="coming-soon-wrapper">
    <div class="coming-soon-card">
        <div class="coming-soon-icon">
            <i class="fas fa-clipboard-check"></i>
        </div>
        <div>
            <span class="coming-soon-badge">Coming Soon</span>
        </div>
        <h1 class="coming-soon-title">Vehicle Inspection Info</h1>
        <p class="coming-soon-desc">
            This page is currently under development. Coming Soon!
        </p>
        <a href="{{ route('landing') }}" class="coming-soon-btn">
            <i class="fas fa-arrow-left"></i> Back to Home
        </a>
    </div>
</div>
@endsection
