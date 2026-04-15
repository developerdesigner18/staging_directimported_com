{{--<div class="sk-ww-google-reviews" data-embed-id="25653006"></div>--}}
{{--
<script src="https://widgets.sociablekit.com/google-reviews/widget.js" defer></script>--}}
<!-- Google Reviews Section Starts-->

<!-- Google Reviews Section Ends-->
<footer class="rid-footer-1">
    <section class="rid-filter-1">
        <div class="container">
            <h2 class="visually-hidden">Rental Bike Search</h2>
            <div class="filter-box cta-box top-0 mb-4">
                <h3 class="cta-title text-dark">Check our Google reviews</h3>
                <a href="{{ route('reviews') }}" class="cta-btn fs-5">
                    Google Reviews
                </a>
            </div>
        </div>
    </section>
    <div class="footer-top">
        <div class="container">
            <div class="row justify-content-between">
                <!-- Company Info & Social -->
                <div class="col-lg-3 col-md-6 mb-4 mb-lg-0">
                    <a href="{{route('landing')}}" class="d-inline-block mb-3">
                        <img src="{{asset('assets/logo/' . getSetting()->footer_logo)}}" alt="IAS JAPAN Footer Logo"
                            style="max-height: 60px;">
                    </a>
                    <p class="rid-footer-info mb-4">
                        By 2003, we identified a critical need for accessible data and Auction Services and shifted our
                        focus to provide professional English-language auction and export services for JDM Cars. Today,
                        we proudly serve all English-speaking nations and specialize in Australia and USA, by
                        streamlining the sourcing of high-quality vehicles directly from Japan.
                    </p>
                    <p class="rid-footer-info mb-4">
                        With over 25 years of specialized expertise And Mechanical Qualifications, we remain dedicated
                        to helping our clients secure premium inventory with absolute transparency and professional
                        integrity.
                    </p>

                    <div class="social-icon-box">
                        <a href="https://wa.me/818033441177" target="_blank" aria-label="WhatsApp">
                            <i class='bx bxl-whatsapp'></i>
                        </a>
                        <a href="#" target="_blank" aria-label="Facebook">
                            <i class='bx bxl-facebook'></i>
                        </a>
                        <a href="#" target="_blank" aria-label="Instagram">
                            <i class='bx bxl-instagram'></i>
                        </a>
                        <a href="#" target="_blank" aria-label="YouTube">
                            <i class='bx bxl-youtube'></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="col-lg-2 col-md-6 mb-4 mb-md-0 px-lg-4">
                    <h4 class="footer-title">Quick Links</h4>
                    <ul class="footer-list">
                        <li><a href="{{ route('landing') }}#auction">Auction</a></li>
                        <li><a href="{{ route('motorcycle') }}">Example Cars</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                        <li><a href="{{ route('faqs') }}">FAQs</a></li>
                        <li><a href="{{ route('landing') }}#about">About us</a></li>
                        <li><a href="{{ route('rental.policies') }}">Terms & Conditions</a></li>
                    </ul>
                </div>

                <!-- Business Hours -->
                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h4 class="footer-title">Business Hours</h4>
                    <div class="bh-table">
                        <div class="bh-row-item">
                            <span class="day">Monday</span>
                            <span class="status">OPEN</span>
                        </div>
                        <div class="bh-row-item">
                            <span class="day">Tuesday</span>
                            <span class="status">OPEN</span>
                        </div>
                        <div class="bh-row-item">
                            <span class="day">Wednesday</span>
                            <span class="status">OPEN</span>
                        </div>
                        <div class="bh-row-item">
                            <span class="day">Thursday</span>
                            <span class="status">OPEN</span>
                        </div>
                        <div class="bh-row-item">
                            <span class="day">Friday</span>
                            <span class="status">OPEN</span>
                        </div>
                        <div class="bh-row-item">
                            <span class="day">Saturday</span>
                            <span class="status">OPEN</span>
                        </div>
                        <div class="bh-row-item">
                            <span class="day">Sunday</span>
                            <span class="status text-danger">CLOSED</span>
                        </div>
                    </div>
                </div>

                <!-- Contact Us -->
                <div class="col-lg-3 col-md-6">
                    <h4 class="footer-title">Contact us</h4>
                    <div class="contact-list">
                        <div class="contact-item mb-3">
                            <i class='bx bxs-envelope'></i>
                            <a href="mailto:IAS@directimported.com">IAS@directimported.com</a>
                        </div>
                        <div class="contact-item mb-3">
                            <i class='bx bxl-whatsapp'></i>
                            <a href="https://wa.me/818033441177" target="_blank">Whatsapp +81 8033441177</a>
                        </div>
                        <div class="contact-item mb-3">
                            <i class='bx bx-globe'></i>
                            <a href="https://www.directimported.com" target="_blank">www.directimported.com</a>
                        </div>
                        <div class="contact-item">
                            <i class='bx bxs-location-plus'></i>
                            <span>Osaka, Japan</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container footer-bottom">
        <hr class="footer-divider">
        <div class="row">
            <div class="col-12 text-center copyright-text py-4">
                <p class="mb-0">Copyright © International Auto Select Japan 2026.</p>
            </div>
        </div>
    </div>
</footer>

<style>
    .rid-footer-1 {
        background: radial-gradient(circle at center, #0a3366 0%, #000b21 100%);
        color: #ffffff;
        padding-top: 80px;
        font-family: 'Poppins', sans-serif;
    }

    .footer-top {
        padding-bottom: 60px;
    }

    .rid-footer-info {
        font-size: 14px;
        line-height: 1.7;
        color: #f1f1f1;
        font-weight: 300;
        margin-top: 20px;
        max-width: 330px;
        /* Matching the narrow column in image */
        text-align: left;
    }

    .footer-title {
        font-size: 19px;
        font-weight: 700;
        color: #ffffff;
        margin-bottom: 40px;
        /* Increased space below headings */
        letter-spacing: 0.5px;
    }

    .footer-list {
        list-style: none;
        padding: 0;
    }

    .footer-list li {
        margin-bottom: 15px;
    }

    .footer-list li a {
        color: #f1f1f1;
        text-decoration: none;
        font-size: 15px;
        transition: 0.3s;
        font-weight: 400;
        opacity: 0.85;
    }

    .footer-list li a:hover {
        color: #ffffff;
        opacity: 1;
        padding-left: 5px;
    }

    .bh-table {
        min-width: 200px;
        max-width: 220px;
    }

    .bh-row-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 7px;
        font-size: 15px;
    }

    .bh-row-item .day {
        color: #f1f1f1;
        opacity: 0.85;
    }

    .bh-row-item .status {
        color: #ffffff;
        font-weight: 500;
        text-align: right;
    }

    .contact-list .contact-item {
        display: flex;
        align-items: center;
        font-size: 15px;
    }

    .contact-list .contact-item i {
        margin-right: 15px;
        font-size: 18px;
        color: #ffffff;
    }

    .contact-list .contact-item a,
    .contact-list .contact-item span {
        color: #f1f1f1;
        text-decoration: none;
        opacity: 0.85;
    }

    .contact-list .contact-item a:hover {
        opacity: 1;
        color: #ffffff;
    }

    .social-icon-box {
        display: flex;
        gap: 25px;
        margin-top: 30px;
    }

    .social-icon-box a {
        color: #ffffff;
        font-size: 24px;
        transition: 0.3s;
        opacity: 0.9;
    }

    .social-icon-box a:hover {
        transform: translateY(-5px);
        opacity: 1;
    }

    .footer-divider {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-bottom: 20px;
    }

    .copyright-text p {
        font-size: 14px;
        color: #f1f1f1;
        opacity: 0.8;
        font-weight: 300;
    }

    .text-danger {
        color: #ff5252 !important;
        opacity: 1 !important;
    }
</style>