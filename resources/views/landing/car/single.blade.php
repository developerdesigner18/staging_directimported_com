@extends('landing.master')
@section('title', $car->name)

@push('style')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0a2a5e;
            --primary-red: #ee1c25;
            --primary-red-hover: #b91c1c;
            --border-color: #cbd5e1;
            --bg-color: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--bg-color);
            color: var(--text-dark);
        }

        .rid-menubar ul li a {
            font-size: 15px !important;
            margin-right: 20px !important;
        }

        .car-detail-wrapper {
            padding: 15px 0 10px;
            background-color: var(--bg-color);
            position: relative;
        }

        /* Form Inputs from reference style */
        .form-input,
        #contactRequestForm input.form-input,
        #contactRequestForm select.form-input {
            width: 100% !important;
            height: 48px !important;
            min-height: 48px !important;
            max-height: 48px !important;
            padding: 0 14px !important;
            line-height: 44px !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 8px !important;
            margin-bottom: 14px !important;
            box-sizing: border-box !important;
            font-family: inherit !important;
            font-size: 14px !important;
            outline: none !important;
            transition: all 0.2s ease-in-out !important;
            background-color: #ffffff !important;
            color: var(--text-dark) !important;
            display: block !important;
        }

        #contactRequestForm select.form-input {
            -webkit-appearance: none !important;
            -moz-appearance: none !important;
            appearance: none !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23334155' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e") !important;
            background-repeat: no-repeat !important;
            background-position: right 14px center !important;
            background-size: 12px 12px !important;
            padding: 0 36px 0 14px !important;
            cursor: pointer !important;
            box-shadow: none !important;
        }

        #contactRequestForm select.form-input::-ms-expand {
            display: none !important;
        }

        #contactRequestForm textarea.form-input {
            height: auto !important;
            min-height: 80px !important;
            max-height: none !important;
            padding: 12px 14px !important;
            line-height: 1.5 !important;
        }

        .form-input:focus {
            border-color: var(--primary-blue) !important;
        }

        /* Select2 Container Overrides for Contact Form */
        #contactRequestForm .select2-container {
            width: 100% !important;
            display: block !important;
            margin-bottom: 14px !important;
        }

        #contactRequestForm .select2-container .select2-selection--single {
            height: 48px !important;
            min-height: 48px !important;
            max-height: 48px !important;
            border: 2px solid var(--border-color) !important;
            border-radius: 8px !important;
            background-color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            box-sizing: border-box !important;
            outline: none !important;
            transition: all 0.2s ease-in-out !important;
        }

        #contactRequestForm .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 44px !important;
            color: var(--text-dark) !important;
            font-size: 14px !important;
            padding-left: 14px !important;
            padding-right: 36px !important;
        }

        #contactRequestForm .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 44px !important;
            position: absolute !important;
            top: 0 !important;
            right: 10px !important;
            width: 20px !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        #contactRequestForm .select2-container--focus .select2-selection--single,
        #contactRequestForm .select2-container--open .select2-selection--single {
            border-color: var(--primary-blue) !important;
        }

        #contactRequestForm label:not(.error) {
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #334155 !important;
            display: block !important;
            margin-bottom: 6px !important;
            text-transform: none !important;
            letter-spacing: normal !important;
            white-space: nowrap !important;
        }

        /* Sweet validation animations */
        @keyframes flashRed {

            0%,
            100% {
                border-color: var(--border-color);
                box-shadow: none;
            }

            50% {
                border-color: var(--primary-red);
                box-shadow: 0 0 8px rgba(238, 28, 37, 0.4);
            }
        }

        .flash-error {
            animation: flashRed 0.4s ease-in-out 3;
            border-color: var(--primary-red) !important;
        }

        /* Lightbox photos viewer style */
        #modalThumbStrip::-webkit-scrollbar {
            height: 6px;
        }

        #modalThumbStrip::-webkit-scrollbar-track {
            background: transparent;
        }

        #modalThumbStrip::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }

        #modalThumbStrip::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.4);
        }

        /* General error label styles for jQuery validation */
        label.error {
            display: block;
            margin-top: -8px;
            margin-bottom: 10px;
            font-size: 11px;
            color: var(--primary-red);
            font-weight: 700;
        }

        /* FAQ Accordion Styling overrides */
        #carFaqMain {
            margin-top: 20px;
        }

        #carFaqMain .accordion-item {
            border: 1px solid var(--border-color);
            background: #ffffff;
            margin-bottom: 8px;
            border-radius: 8px !important;
            overflow: hidden;
            box-shadow: 0 4px 10px -3px rgba(10, 42, 94, 0.05);
            transition: all 0.3s ease;
        }

        #carFaqMain .accordion-item:hover {
            box-shadow: 0 8px 15px -3px rgba(10, 42, 94, 0.08);
        }

        #carFaqMain .accordion-button {
            background-color: #ffffff !important;
            color: var(--primary-blue) !important;
            font-weight: 700;
            font-size: 14px;
            padding: 0px 16px;
            border: none;
            box-shadow: none !important;
        }

        #carFaqMain .accordion-button:not(.collapsed) {
            border-bottom: 1px solid #f1f5f9;
        }

        #carFaqMain .accordion-button::after {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%230a2a5e'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
            background-size: 12px;
        }

        #carFaqMain .accordion-body {
            padding: 16px 20px;
            color: #4b5563;
            font-size: 14.5px;
            line-height: 1.6;
        }

        /* Related Cars Styling overrides */
        .related-cars-slider .card {
            border: 1px solid var(--border-color) !important;
            border-radius: 12px !important;
            overflow: hidden;
            background-color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(10, 42, 94, 0.08) !important;
            transition: all 0.3s ease;
        }

        .related-cars-slider .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 35px -10px rgba(10, 42, 94, 0.15) !important;
        }

        .related-cars-slider .card-title-link {
            color: var(--primary-blue);
            text-decoration: none;
            font-size: 20px;
            font-weight: 800;
            transition: color 0.2s;
        }

        .related-cars-slider .card-title-link:hover {
            color: var(--primary-red);
        }

        .related-cars-slider .price-label {
            color: var(--primary-red);
            font-size: 18px;
            font-weight: 800;
        }

        .related-cars-slider .view-btn {
            color: var(--primary-blue);
            font-weight: 800;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: gap 0.2s;
        }

        .related-cars-slider .view-btn:hover {
            gap: 10px;
            color: var(--primary-red);
        }

        /* Slick Slider Dots */
        .slick-dots {
            display: flex !important;
            justify-content: center;
            list-style: none;
            padding: 0;
            margin: 30px 0 0;
            gap: 8px;
        }

        .slick-dots li {
            margin: 0;
            display: flex;
            align-items: center;
        }

        .slick-dots li button {
            font-size: 0;
            line-height: 0;
            display: block;
            width: 10px;
            height: 10px;
            padding: 0;
            cursor: pointer;
            border: 0;
            outline: none;
            background: #CBD5E1;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .slick-dots li button:before {
            display: none !important;
        }

        .slick-dots li.slick-active button {
            background: var(--primary-blue);
            width: 25px;
            border-radius: 10px;
        }

        /* Related Cars Slider Arrows */
        .related-cars-slider .slick-prev,
        .related-cars-slider .slick-next {
            width: 40px;
            height: 40px;
            background: #fff;
            border-radius: 50%;
            z-index: 10;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s;
            display: flex !important;
            align-items: center;
            justify-content: center;
        }

        .related-cars-slider .slick-prev:hover,
        .related-cars-slider .slick-next:hover {
            background: var(--primary-blue);
        }

        .related-cars-slider .slick-prev:before,
        .related-cars-slider .slick-next:before {
            color: #111;
            font-family: 'boxicons';
            font-size: 24px;
            opacity: 1;
        }

        .related-cars-slider .slick-prev:hover:before,
        .related-cars-slider .slick-next:hover:before {
            color: #fff;
        }

        .related-cars-slider .slick-prev {
            left: -20px;
        }

        .related-cars-slider .slick-next {
            right: -20px;
        }

        .related-cars-slider .slick-prev:before {
            content: "\ea41";
        }

        .related-cars-slider .slick-next:before {
            content: "\ea42";
        }

        /* Dynamic Flex layout container matching reference */
        .details-flex-container {
            padding: 0 15px;
            color: var(--text-dark);
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            gap: 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .details-left-column {
            flex: 1 1 600px;
            min-width: 320px;
        }

        .details-right-column {
            flex: 1 1 350px;
            max-width: 450px;
            position: -webkit-sticky;
            position: sticky;
            top: 75px;
            z-index: 99;
        }

        @media (max-width: 991px) {
            .details-right-column {
                position: static;
                max-width: 100%;
            }
        }

        /* Reset margins on sticky column and title */
        .details-right-column {
            margin-top: 0 !important;
            padding-top: 0 !important;
        }

        .details-right-column h1 {
            margin-top: 0 !important;
        }

        /* -------------------------------------------------------------
                                                                                                                                                                           MIGRATED INLINE CLASSES FOR CLEAN HTML
                                                                                                                                                                           ------------------------------------------------------------- */

        /* Main Image Container & Watermark Display */
        .open-gallery-btn {
            position: relative;
            background-color: #e2e8f0;
            border-radius: 12px;
            aspect-ratio: 4 / 3;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 16px;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(10, 42, 94, 0.1);
            cursor: pointer;
            transition: transform 0.2s;
        }

        .open-gallery-btn:hover {
            transform: scale(1.01);
        }

        .main-display-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hq-watermark {
            position: absolute;
            bottom: 16px;
            right: 16px;
            width: 72px;
            height: 72px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(3px);
            border: 1.5px solid rgba(255, 255, 255, 0.6);
            border-radius: 6px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #ffffff;
            text-shadow: 0px 1px 4px rgba(0, 0, 0, 0.9), 0px 0px 2px rgba(0, 0, 0, 0.8);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
            z-index: 10;
            font-family: Arial, sans-serif;
        }

        .hq-watermark svg {
            position: absolute;
            top: 4px;
            left: 4px;
            width: calc(100% - 8px);
            height: calc(100% - 8px);
            pointer-events: none;
            filter: drop-shadow(0px 1px 2px rgba(0, 0, 0, 0.8));
            z-index: 1;
        }

        .hq-watermark .hq-text {
            font-size: 28px;
            font-weight: 900;
            line-height: 1;
            margin-top: 2px;
            position: relative;
            z-index: 2;
        }

        .hq-watermark .image-text {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-top: 4px;
            position: relative;
            z-index: 2;
        }

        .main-thumb-container {
            margin-bottom: 32px;
        }

        .main-thumb-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 8px;
            margin-bottom: 12px;
        }

        .view-all-btn {
            width: 100%;
            background-color: #ffffff;
            color: var(--primary-blue);
            border: 2px solid #cbd5e1;
            padding: 12px;
            font-size: 15px;
            font-weight: 800;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s;
            box-shadow: 0 4px 6px rgba(10, 42, 94, 0.05);
        }

        .tabs-header-container {
            display: flex;
            border-bottom: 2px solid #cbd5e1;
            margin-bottom: 24px;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }

        .tab-overview {
            padding: 12px 24px;
            font-weight: 900;
            color: var(--primary-blue);
            border-bottom: 4px solid var(--primary-red);
            margin-bottom: -2px;
            cursor: pointer;
            letter-spacing: 0.5px;
        }

        .tab-grade {
            padding: 12px 16px;
            font-weight: 700;
            color: var(--text-muted);
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .grade-badge {
            background-color: #fef2f2;
            color: var(--primary-red);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 15px;
            font-weight: 900;
            border: 1px solid #fca5a5;
            box-shadow: 0 2px 6px rgba(238, 28, 37, 0.15);
        }

        .tab-stock {
            margin-left: auto;
            padding: 12px 0;
            font-weight: 700;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
            letter-spacing: 0.5px;
            font-size: 14px;
            white-space: nowrap;
        }

        .stock-badge {
            color: var(--primary-blue);
            font-size: 16px;
            font-weight: 900;
            background-color: #e2e8f0;
            padding: 4px 12px;
            border-radius: 6px;
        }

        /* Specifications (Vehicle Details) style elements */
        .specs-header {
            font-size: 22px;
            font-weight: 900;
            margin-bottom: 20px;
            color: var(--primary-blue);
        }

        .specs-container {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 10px 25px -5px rgba(10, 42, 94, 0.08);
            margin-bottom: 30px;
        }

        .specs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px 32px;
        }

        .specs-item {
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 12px;
            align-items: center;
        }

        .specs-label {
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
        }

        .specs-value {
            font-weight: 800;
            color: var(--primary-blue);
        }

        .faq-header {
            font-size: 22px;
            font-weight: 900;
            margin-top: 40px;
            color: var(--primary-blue);
        }

        /* Sidebar Title, Status & Pricing section style elements */
        /* .sidebar-header {
                                                                            display: flex;
                                                                            align-items: center;
                                                                            gap: 12px;
                                                                            margin-bottom: 8px;
                                                                            flex-wrap: wrap;
                                                                        } */

        .sidebar-title {
            font-size: 24px;
            font-weight: 900;
            margin: 0;
            letter-spacing: -0.5px;
            color: var(--primary-blue);
            text-transform: uppercase;
        }

        .details-right-column h1 {
            line-height: 34px;
        }

        .status-badge {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff;
            font-weight: 800;
            font-size: 12px;
            padding: 4px 12px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.25);
            white-space: nowrap;
        }

        .pricing-section {
            margin-bottom: 12px;
            background-color: #ffffff;
            padding: 14px 18px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(10, 42, 94, 0.05);
            border: 1px solid #cbd5e1;
            margin-top: 12px;
        }

        .pricing-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 4px;
            margin-bottom: 4px;
        }

        .pricing-label {
            color: #64748b;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 0;
            text-transform: none;
            letter-spacing: normal;
        }

        .pricing-subtext {
            color: var(--primary-red);
            font-size: 13px;
            font-weight: 600;
        }

        .pricing-value {
            display: block;
            font-size: 32px;
            font-weight: 900;
            color: var(--primary-blue);
            line-height: 1.1;
        }

        /* Inquiry Form Wrapper & Inline style element mappings */
        .contact-form-container {
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 15px 30px -10px rgba(10, 42, 94, 0.12);
            padding: 20px;
            border: 1px solid #cbd5e1;
            border-top: 4px solid var(--primary-blue);
        }

        .contact-form-title {
            font-size: 18px;
            font-weight: 900;
            margin: 0 0 16px 0;
            border-bottom: 2px solid #f1f5f9;
            padding-bottom: 10px;
            color: var(--primary-blue);
            text-transform: uppercase;
        }

        .form-readonly-input {
            background-color: #f8fafc;
            color: var(--text-muted);
            font-weight: 700;
            cursor: not-allowed;
        }

        .form-row-2 {
            display: flex;
            gap: 12px;
            margin-bottom: 0px;
        }

        .form-col {
            flex: 1;
        }

        .form-select-white {
            background-color: #fff;
        }

        .textarea-message {
            resize: vertical;
            height: 80px;
        }

        .btn-submit-quote {
            width: 100%;
            background: linear-gradient(135deg, #ee1c25 0%, #b91c1c 100%);
            color: #ffffff;
            border: none;
            padding: 12px;
            font-size: 14px;
            font-weight: 900;
            border-radius: 8px;
            cursor: pointer;
            box-shadow: 0 5px 12px rgba(238, 28, 37, 0.25);
            transition: transform 0.2s, box-shadow 0.2s;
            letter-spacing: 0.5px;
        }

        .red-separator-line {
            width: 100%;
            height: 4px;
            background-color: var(--primary-red);
        }

        /* Photo Modal lightbox styles */
        .photo-modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(10, 42, 94, 0.96);
            z-index: 99999;
            flex-direction: column;
            backdrop-filter: blur(8px);
            font-family: 'Inter', sans-serif;
        }

        .photo-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            color: white;
        }

        .photo-modal-counter {
            font-weight: 800;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .photo-modal-close-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px;
            border-radius: 50%;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
        }

        .photo-modal-close-btn:hover {
            background-color: #ee1c25;
            border-color: #ee1c25;
        }

        .photo-modal-content-area {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
            padding: 0 10px;
        }

        .photo-modal-arrow-btn {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            backdrop-filter: blur(4px);
            transition: all 0.2s;
            z-index: 10;
        }

        .photo-modal-arrow-btn:hover {
            background-color: #ee1c25;
            border-color: #ee1c25;
        }

        .photo-modal-arrow-left {
            left: 20px;
        }

        .photo-modal-arrow-right {
            right: 20px;
        }

        .photo-modal-main-img {
            max-width: 90%;
            max-height: 90vh;
            object-fit: contain;
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
            user-select: none;
        }

        .photo-modal-thumb-strip {
            padding: 20px;
            display: flex;
            gap: 12px;
            overflow-x: auto;
            white-space: nowrap;
            scroll-behavior: smooth;
            min-height: 90px;
            background: linear-gradient(0deg, rgba(3, 18, 46, 1) 0%, rgba(10, 42, 94, 0) 100%);
        }

        .related-section-title {
            color: var(--primary-blue);
            font-size: 32px;
            font-weight: 900;
            text-transform: uppercase;
            margin-bottom: 24px;
        }

        .related-card-img {
            height: 240px;
            object-fit: cover;
        }

        /* Dynamic script thumbnails */
        .thumb-box {
            aspect-ratio: 4 / 3;
            background-color: #f1f5f9;
            background-size: cover;
            background-position: center;
            border-radius: 6px;
            cursor: pointer;
            box-sizing: border-box;
            border: 1px solid var(--border-color);
            transition: all 0.2s ease-in-out;
        }

        .thumb-box.active {
            border: 3px solid var(--primary-red) !important;
            box-shadow: 0 0 12px rgba(238, 28, 37, 0.4) !important;
        }

        .thumb-box.hidden-thumb {
            display: none;
        }

        .modal-strip-thumb {
            height: 70px;
            min-width: 90px;
            background-size: cover;
            background-position: center;
            border-radius: 6px;
            cursor: pointer;
            opacity: 0.4;
            transition: all 0.3s ease;
            border: 3px solid transparent;

            .modal-strip-thumb.active {
                opacity: 1 !important;
                border-color: var(--primary-red) !important;
            }

        }

        .modal-strip-thumb.active {
            opacity: 1 !important;
            border-color: var(--primary-red) !important;
        }

        .price-unit {
            font-size: 13px;
        }

        .related-card-title {
            font-size: 20px;
        }

        .required-asterisk {
            color: var(--primary-red);
        }
    </style>
@endpush

@section('main')
    <div class="car-detail-wrapper">
        <div class="details-flex-container">

            <!-- LEFT COLUMN: Images & Details -->
            <div class="details-left-column">

                <!-- Main Image Container matching reference structure -->
                <div id="openGalleryBtn" class="open-gallery-btn" onmouseover="this.style.transform='scale(1.01)'"
                    onmouseout="this.style.transform='scale(1)'">

                    <img id="mainDisplayImg" class="main-display-img" src="" alt="{{ $car->name }}">

                    <!-- Transparent HQ Photo Watermark -->
                    <div class="hq-watermark">
                        <svg viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="1" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="7 2 2 2 2 7"></polyline>
                            <line x1="2" y1="2" x2="8" y2="8"></line>
                            <polyline points="17 2 22 2 22 7"></polyline>
                            <line x1="22" y1="2" x2="16" y2="8"></line>
                            <polyline points="2 17 2 22 7 22"></polyline>
                            <line x1="2" y1="22" x2="8" y2="16"></line>
                            <polyline points="22 17 22 22 17 22"></polyline>
                            <line x1="22" y1="22" x2="16" y2="16"></line>
                        </svg>
                        <span class="hq-text">HQ</span>
                        <span class="image-text">IMAGE</span>
                    </div>
                </div>

                <!-- Thumbnails Gallery -->
                <div class="main-thumb-container">
                    <div id="mainThumbGrid" class="main-thumb-grid"></div>

                    <button id="viewAllBtn" class="view-all-btn">
                        View All {{ count($car->images) }} Photos
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                            stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>
                </div>

                <!-- Tabs Area, Grade & Stock # matching reference style -->
                <div class="tabs-header-container">
                    <div class="tab-overview">OVERVIEW</div>

                    <div class="tab-grade">
                        Grade <span
                            class="grade-badge">{{ (!empty($car->auctionGrade->grade) && strtoupper(trim($car->auctionGrade->grade)) !== 'N/A') ? $car->auctionGrade->grade : '-' }}</span>
                    </div>

                    <div class="tab-stock">
                        STOCK # <span class="stock-badge">{{ $car->vehicle_id ?? $car->name ?? '-' }}</span>
                    </div>
                </div>

                <!-- Specifications (Vehicle Details) matching reference table style -->
                <h3 class="specs-header">Vehicle Details</h3>
                <div class="specs-container">
                    <div class="specs-grid">

                        <div class="specs-item">
                            <span class="specs-label">🚘 Make</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->make) && strtoupper(trim($car->spec->make)) !== 'N/A') ? $car->spec->make : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">⏱️ Odometer</span>
                            <span
                                class="specs-value">{{ !empty($car->spec->odometer) ? number_format($car->spec->odometer) . ' km' : '0 km' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">🎨 Exterior Color</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->exterior_color) && strtoupper(trim($car->spec->exterior_color)) !== 'N/A') ? $car->spec->exterior_color : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">📅 Model Year</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->model_year) && strtoupper(trim($car->spec->model_year)) !== 'N/A') ? $car->spec->model_year : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">🚗 Body Type</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->body_type) && strtoupper(trim($car->spec->body_type)) !== 'N/A') ? $car->spec->body_type : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">⚙️ Engine</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->engine) && strtoupper(trim($car->spec->engine)) !== 'N/A') ? $car->spec->engine : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">🔌 Fuel Type</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->fuel_type) && strtoupper(trim($car->spec->fuel_type)) !== 'N/A') ? $car->spec->fuel_type : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">🎛️ Transmission</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->transmission) && strtoupper(trim($car->spec->transmission)) !== 'N/A') ? $car->spec->transmission : '-' }}</span>
                        </div>

                        <div class="specs-item">
                            <span class="specs-label">💺 Interior Color</span>
                            <span
                                class="specs-value">{{ (!empty($car->spec->interior_color) && strtoupper(trim($car->spec->interior_color)) !== 'N/A') ? $car->spec->interior_color : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- FAQ Section matching premium styles -->
                <h3 class="faq-header">Frequently Asked Questions</h3>
                <div class="car-faq-container accordion" id="carFaqMain">
                    @foreach($carConf as $index => $conf)
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="faqHeading{{ $index }}">
                                <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" type="button"
                                    data-bs-toggle="collapse" data-bs-target="#faqCollapse{{ $index }}"
                                    aria-expanded="{{ $index === 0 ? 'true' : 'false' }}">
                                    {{ $conf->title }}
                                </button>
                            </h2>
                            <div id="faqCollapse{{ $index }}"
                                class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}"
                                data-bs-parent="#carFaqMain">
                                <div class="accordion-body">
                                    {!! $conf->description !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- RIGHT COLUMN: Pricing & Form (STICKY) -->
            <div class="details-right-column">

                <div class="sidebar-header">
                    <h1 class="sidebar-title">
                        {{ $car->name }}
                    </h1>

                </div>
                <span class="status-badge">
                    {{ strtoupper($car->status->value) }}
                </span>
                <!-- Pricing Section matching reference -->
                <div class="pricing-section">
                    <div class="pricing-header">
                        <span class="pricing-label">Vehicle Base Price</span>
                        <span class="pricing-subtext">(Excludes any FOB/CiF Fees)</span>
                    </div>
                    @php
                        $cleanPrice = preg_replace('/[^\d.]/', '', $car->vehicle_price ?? 0);
                        $formattedPrice = is_numeric($cleanPrice) && $cleanPrice > 0 ? number_format((float) $cleanPrice) : ($car->vehicle_price ?? '0');
                    @endphp
                    <span class="pricing-value">¥{{ $formattedPrice }}</span>
                </div>

                <!-- Inquiry/Booking Redesigned Form -->
                <form id="contactRequestForm" novalidate method="POST" class="contact-form-container">
                    @csrf
                    <h2 class="contact-form-title">Contact Us</h2>

                    <input type="hidden" name="vehicle_id" value="{{ $car->name }}">

                    <label>Vehicle Stock #</label>
                    <input type="text" value="{{ $car->vehicle_id ?? $car->name ?? '' }}" readonly
                        class="form-input form-readonly-input">

                    <label>Full Name <span class="required-asterisk">*</span></label>
                    <input type="text" name="full_name" id="full_name" required placeholder="Full Name" class="form-input">

                    <label>Email Address <span class="required-asterisk">*</span></label>
                    <input type="email" name="email" id="email" required placeholder="Email Address" class="form-input">

                    <div class="form-row-2 row-2">
                        <div class="form-col">
                            <label>Phone <span class="required-asterisk">*</span></label>
                            <input type="tel" name="phone_number" id="phone_number" required placeholder="Phone Number"
                                class="form-input">
                        </div>
                        <div class="form-col">
                            <label>Contact Method <span class="required-asterisk">*</span></label>
                            <select name="preferred_contact_method" id="preferred_contact_method" required
                                class="form-input form-select-white">
                                <option value="">Select...</option>
                                <option value="Email">Email</option>
                                <option value="Phone">Phone</option>
                                <option value="WhatsApp">WhatsApp</option>
                            </select>
                        </div>
                    </div>

                    <!-- Searchable Destination Country Datalist & Nearest Port -->
                    <div class="form-row-2 row-2">
                        <div class="form-col">
                            <label>Country <span class="required-asterisk">*</span></label>
                            <input list="countryList" name="destination_country" id="destination_country" required
                                placeholder="Select Country..." class="form-input">
                            <datalist id="countryList">
                                <option value="USA"></option>
                                <option value="UK"></option>
                                <option value="Australia"></option>
                                <option value="Bahamas"></option>
                                <option value="Canada"></option>
                                <option value="New Zealand"></option>
                                <option value="Ireland"></option>
                                <option value="Pakistan"></option>
                                <option value="India"></option>
                                <option value="Japan"></option>
                                <option value="Singapore"></option>
                                <option value="South Africa"></option>
                            </datalist>
                        </div>

                        <div class="form-col">
                            <label>Nearest Port <span class="required-asterisk">*</span></label>
                            <input type="text" name="nearest_port_or_postal_code" id="nearest_port_or_postal_code" required
                                placeholder="Port / Post Code" class="form-input">
                        </div>
                    </div>

                    <label>Message <span class="required-asterisk">*</span></label>
                    <textarea name="message" id="message" required rows="3" placeholder="Your Message"
                        class="form-input textarea-message"></textarea>

                    <!-- Popping Red Gradient Button -->
                    <button type="submit" id="submitRequestBtn" class="btn-submit-quote">
                        <i class="bx bx-loader spinner me-2" style="display: none" id="submitRequestBtnSpinner"></i>
                        Request Details & Quote
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- FULL WIDTH RED SEPARATOR LINE matching reference -->
    <div class="red-separator-line"></div>

    <!-- LIGHTBOX / PHOTO VIEWER MODAL matching reference style -->
    <div id="photoModal" class="photo-modal-overlay">
        <div class="photo-modal-header">
            <div class="photo-modal-counter"><span id="currentPhotoNum">1</span> / <span
                    id="totalPhotoNum">{{ count($car->images) }}</span></div>
            <button onclick="closeGallery()" class="photo-modal-close-btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>

        <div class="photo-modal-content-area">
            <button onclick="prevPhoto()" class="photo-modal-arrow-btn photo-modal-arrow-left">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>

            <img id="modalMainImg" src="" alt="High Quality Vehicle Image" class="photo-modal-main-img">

            <button onclick="nextPhoto()" class="photo-modal-arrow-btn photo-modal-arrow-right">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
        </div>

        <div id="modalThumbStrip" class="photo-modal-thumb-strip"></div>
    </div>

    <!-- RELATED SECTION -->
    <section class="py-3 bg-white border-top">
        <div class="container gallery-inner-container">
            <h2 class="fw-bold mb-4 related-section-title">You Might Also Like</h2>
            <div class="related-cars-slider">
                @foreach($relatedCars as $relatedCar)
                    <div class="px-2">
                        <div class="card border-0 shadow-sm h-100 rounded-3 overflow-hidden">
                            <a href="{{ route('car.single', ['slug' => $relatedCar->slug]) }}">
                                @php
                                    $relImg = (!empty($relatedCar->images) && is_array($relatedCar->images) && isset($relatedCar->images[0]) && !empty($relatedCar->images[0])) ? $relatedCar->images[0] : null;
                                @endphp
                                <img src="{{ $relImg ? asset(CAR_PATH . $relImg) : asset('uploads/user_documents/default.jpg') }}"
                                    class="card-img-top related-card-img">
                            </a>
                            <div class="card-body p-4">
                                <h5 class="fw-bold mb-3related-card-title">
                                    <a href="{{ route('car.single', ['slug' => $relatedCar->slug]) }}" class="card-title-link">
                                        {{ $relatedCar->name }}
                                    </a>
                                </h5>
                                <div class="d-flex justify-content-between align-items-center">
                                    <!-- Hidden based on client request. -->
                                    {{--
                                    <div class="price-label">
                                        From ¥{{ number_format($relatedCar->max_price ?? $relatedCar->less_four_days_price) }}
                                        <span class="text-muted fw-normal price-unit">{{ isset($relatedCar->max_price) ? 'FOB' :
                                            '/ day' }}</span>
                                    </div>
                                    --}}
                                    <a href="{{ route('car.single', ['slug' => $relatedCar->slug]) }}" class="view-btn">
                                        VIEW <i class="bx bx-right-arrow-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        const photoUrls = [
            @if(!empty($car->images))
                @foreach($car->images as $image)
                    "{{ asset(CAR_PATH . $image) }}",
                @endforeach
            @endif
                                                                                                                                                                    ];

        let currentIndex = 0;
        let gridExpanded = false;

        const modal = document.getElementById('photoModal');
        const modalMainImg = document.getElementById('modalMainImg');
        const modalThumbStrip = document.getElementById('modalThumbStrip');
        const counter = document.getElementById('currentPhotoNum');

        const mainDisplayImg = document.getElementById('mainDisplayImg');
        const mainThumbGrid = document.getElementById('mainThumbGrid');
        const viewAllBtn = document.getElementById('viewAllBtn');

        function initGalleries() {
            if (!mainThumbGrid || !modalThumbStrip) return;
            mainThumbGrid.innerHTML = '';
            modalThumbStrip.innerHTML = '';

            if (photoUrls.length > 0) {
                mainDisplayImg.src = photoUrls[0];
            }

            photoUrls.forEach((src, index) => {
                // Left column page thumbnails
                const mainThumb = document.createElement('div');
                mainThumb.className = 'thumb-box';
                mainThumb.style.backgroundImage = `url(${src})`;

                if (index === 0) {
                    mainThumb.classList.add('active');
                }

                if (index > 15) {
                    mainThumb.classList.add('hidden-thumb');
                }

                mainThumb.addEventListener('mouseover', () => {
                    mainDisplayImg.src = src;
                    Array.from(mainThumbGrid.children).forEach((child, idx) => {
                        if (idx === index) {
                            child.classList.add('active');
                        } else {
                            child.classList.remove('active');
                        }
                    });
                });

                mainThumb.addEventListener('click', () => goToPhoto(index));
                mainThumbGrid.appendChild(mainThumb);

                // Fullscreen modal strip thumbnails
                const modalThumb = document.createElement('div');
                modalThumb.className = 'modal-strip-thumb';
                modalThumb.style.backgroundImage = `url(${src})`;
                modalThumb.onclick = () => goToPhoto(index);
                modalThumb.id = `modal-thumb-${index}`;
                modalThumbStrip.appendChild(modalThumb);
            });
        }

        if (viewAllBtn) {
            viewAllBtn.addEventListener('click', () => {
                gridExpanded = !gridExpanded;
                Array.from(mainThumbGrid.children).forEach((child, idx) => {
                    if (idx > 15) {
                        if (gridExpanded) {
                            child.classList.remove('hidden-thumb');
                        } else {
                            child.classList.add('hidden-thumb');
                        }
                    }
                });

                if (gridExpanded) {
                    viewAllBtn.innerHTML = `Hide Photos <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>`;
                } else {
                    viewAllBtn.innerHTML = `View All ${photoUrls.length} Photos <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"></polyline></svg>`;
                }
            });
        }

        function openGallery(startIndex = 0) {
            currentIndex = startIndex;
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            updateModalGallery();
        }

        function closeGallery() {
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function nextPhoto() {
            if (photoUrls.length === 0) return;
            currentIndex = (currentIndex === photoUrls.length - 1) ? 0 : currentIndex + 1;
            updateModalGallery();
        }

        // Exposed global functions for the HTML inline click events
        window.closeGallery = closeGallery;
        window.nextPhoto = nextPhoto;
        window.prevPhoto = prevPhoto;

        function prevPhoto() {
            if (photoUrls.length === 0) return;
            currentIndex = (currentIndex === 0) ? photoUrls.length - 1 : currentIndex - 1;
            updateModalGallery();
        }

        function goToPhoto(index) {
            currentIndex = index;
            if (modal.style.display !== 'flex') {
                openGallery(index);
            } else {
                updateModalGallery();
            }
        }

        function updateModalGallery() {
            if (photoUrls.length === 0) return;
            modalMainImg.src = photoUrls[currentIndex];
            counter.textContent = currentIndex + 1;

            for (let i = 0; i < photoUrls.length; i++) {
                const thumb = document.getElementById(`modal-thumb-${i}`);
                if (thumb) {
                    if (i === currentIndex) {
                        thumb.classList.add('active');
                        thumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                    } else {
                        thumb.classList.remove('active');
                    }
                }
            }
        }

        $(document).ready(function () {
            initGalleries();

            const openGalleryEl = document.getElementById('openGalleryBtn');
            if (openGalleryEl) {
                openGalleryEl.addEventListener('click', () => {
                    let currentSrc = mainDisplayImg.src;
                    let targetIndex = photoUrls.findIndex(url => currentSrc.includes(url));
                    openGallery(targetIndex !== -1 ? targetIndex : 0);
                });
            }

            document.addEventListener('keydown', (e) => {
                if (modal && modal.style.display === 'flex') {
                    if (e.key === 'Escape') closeGallery();
                    if (e.key === 'ArrowRight') nextPhoto();
                    if (e.key === 'ArrowLeft') prevPhoto();
                }
            });

            // Initialize Related Cars Slider
            $('.related-cars-slider').slick({
                slidesToShow: 3,
                slidesToScroll: 1,
                arrows: true,
                dots: true,
                autoplay: true,
                autoplaySpeed: 3000,
                responsive: [
                    {
                        breakpoint: 992,
                        settings: {
                            slidesToShow: 2
                        }
                    },
                    {
                        breakpoint: 576,
                        settings: {
                            slidesToShow: 1
                        }
                    }
                ]
            });

            // Initialize Form Validator using reference layout rules
            $("#contactRequestForm").validate({
                rules: {
                    full_name: { required: true, minlength: 2 },
                    email: { required: true, email: true },
                    phone_number: { required: true },
                    preferred_contact_method: { required: true },
                    destination_country: { required: true },
                    nearest_port_or_postal_code: { required: true },
                    message: { required: true }
                },
                messages: {
                    full_name: { required: "Please enter your full name", minlength: "Name must be at least 2 characters" },
                    email: { required: "Please enter your email", email: "Please enter a valid email address" },
                    phone_number: { required: "Please enter your phone number" },
                    preferred_contact_method: { required: "Please select preferred contact method" },
                    destination_country: { required: "Please select destination country" },
                    nearest_port_or_postal_code: { required: "Please fill this field" },
                    message: { required: "Please leave us a short message" }
                },
                errorClass: 'text-danger error',
                errorPlacement: function (error, element) {
                    element.after(error);
                },
                highlight: function (element) {
                    $(element).addClass('flash-error');
                },
                unhighlight: function (element) {
                    $(element).removeClass('flash-error');
                },
                submitHandler: function (form, e) {
                    e.preventDefault();
                    $.ajax({
                        url: "{{ route('contact.request.store') }}",
                        method: "POST",
                        dataType: "json",
                        data: new FormData(form),
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function () {
                            $('#submitRequestBtn').attr('disabled', true);
                            $("#submitRequestBtnSpinner").show();
                            $('.text-danger.error').hide().text('');
                        },
                        success: function (result) {
                            sendSuccess(result.message);
                            form.reset();
                            // Reset select2 / input validation states
                            $('.flash-error').removeClass('flash-error');
                        },
                        error: function (xhr) {
                            let data = xhr.responseJSON;
                            if (data && data.hasOwnProperty('error')) {
                                $.each(data.error, function (key, value) {
                                    $("#" + key + "-error").html(value[0]).show();
                                    $("#" + key).addClass('flash-error');
                                });
                            } else if (data && data.hasOwnProperty('message')) {
                                actionError(xhr, data.message);
                            } else {
                                actionError(xhr);
                            }
                        },
                        complete: function () {
                            $('#submitRequestBtn').attr('disabled', false);
                            $("#submitRequestBtnSpinner").hide();
                        },
                    });
                }
            });
        });
    </script>
@endsection