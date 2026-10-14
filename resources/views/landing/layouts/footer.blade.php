<footer class="site-footer">
    <div class="container">

        <div class="footer-grid">

            <!-- Column 1: Brand & Bio -->
            <div class="footer-col--brand">
                <div class="footer-logo">
                    <a href="{{ route('landing') }}">
                        <img src="{{ getSetting()->logo ? asset('assets/logo/' . getSetting()->logo) : asset('assets/logo/direct-imported-logo-red.png') }}"
                            alt="Direct Imported Logo">
                    </a>
                </div>
                <div class="footer-bio">
                    <p class="footer-bio-text">By 2003, we identified a critical need for accessible data and auction
                        services and shifted our
                        focus to provide professional English-language auction and export services for JDM cars. Today,
                        we proudly serve all English-speaking nations and specialize in Australia and the USA by
                        streamlining the sourcing of high-quality vehicles directly from Japan.</p>
                    <p class="footer-bio-text">With over 25 years of specialized expertise and mechanical
                        qualifications, we remain dedicated to
                        helping our clients secure premium inventory with absolute transparency and professional
                        integrity.</p>
                </div>
            </div>

            <!-- Column 2: Company -->
            <div class="footer-col--company">
                <h3 class="brand-heading-uppercase footer-heading">Company</h3>
                <nav aria-label="Company links">
                    <ul class="footer-links">
                        <li><a href="{{ route('about.us') }}" class="footer-link">About us</a></li>
                        <li><a href="{{ route('services.view') }}" class="footer-link">What we offer</a></li>
                        <li><a href="{{ route('faqs') }}" class="footer-link">FAQ</a></li>
                        <li><a href="{{ route('contact') }}" class="footer-link">Contact Us</a></li>
                        <li><a href="{{ route('blog') }}" class="footer-link">Blogs</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Column 3: Services -->
            <div class="footer-col--services">
                <h3 class="brand-heading-uppercase footer-heading">Services</h3>
                <nav aria-label="Services links">
                    <ul class="footer-links">
                        <li><a href="{{ route('available.vehicles') }}" class="footer-link">Available Vehicles</a></li>
                        <li><a href="{{ route('blog') }}" class="footer-link">How to Import</a></li>
                        <li><a href="{{ route('vehicle.inspection.info') }}" class="footer-link">Vehicle Inspection
                                Info</a></li>
                        <li><a href="{{ route('shipping.and.logistics') }}" class="footer-link">Shipping and
                                Logistics</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Column 4: Policies -->
            <div class="footer-col--policies">
                <h3 class="brand-heading-uppercase footer-heading">Policies</h3>
                <nav aria-label="Policy links">
                    <ul class="footer-links">
                        <li><a href="{{ route('rental.policies') }}" class="footer-link">Terms and Conditions</a></li>
                        <li><a href="javascript:void(0);" class="footer-link">Privacy policy</a></li>
                    </ul>
                </nav>
            </div>

            <!-- Column 5: Contact Us & Socials -->
            <div class="footer-col--contact">
                <h3 class="brand-heading-uppercase footer-heading">Contact Us</h3>
                <ul class="contact-list">
                    <li class="contact-item">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:sales@directimported.com" class="contact-link break-all">sales<span
                                style="display:none;">bot-trap</span>@directimported.com</a>
                    </li>
                    <li class="contact-item">
                        <i class="fab fa-whatsapp"></i>
                        <a href="https://wa.me/818033441177" target="_blank" class="contact-link">+81 8033441177</a>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-globe"></i>
                        <a href="https://www.directimported.com" target="_blank"
                            class="contact-link">www.directimported.com</a>
                    </li>
                    <li class="contact-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span class="contact-text">Osaka, Japan</span>
                    </li>
                </ul>

                <div class="social-icons social-icons--footer mt-4">
                    <a href="https://wa.me/818033441177" target="_blank" aria-label="WhatsApp"
                        class="social-icon social-icon--md"><i class="fab fa-whatsapp"></i></a>
                    <a href="{{ getSetting()->facebook_url }}" target="_blank" aria-label="Facebook"
                        class="social-icon social-icon--md"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ getSetting()->instagram_url }}" target="_blank" aria-label="Instagram"
                        class="social-icon social-icon--md"><i class="fab fa-instagram"></i></a>
                    <a href="{{ getSetting()->youtube_url }}" target="_blank" aria-label="YouTube"
                        class="social-icon social-icon--md"><i class="fab fa-youtube"></i></a>
                </div>
            </div>

        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom__logo">
                <img src="{{ asset('assets/logo/footer_logo_1776338594_69e0c6a2f0279.webp') }}" alt="IAS Japan Logo">
            </div>

            <p class="footer-copy">
                Copyright &copy; International Auto Select Japan {{ date('Y') }}. All rights reserved.
            </p>

            <div class="hours">
                <span class="brand-heading-uppercase hours__label">HOURS:</span>
                <div class="hours__item">
                    <span>Mon - Sat</span>
                    <span class="badge badge--open">OPEN</span>
                </div>
                <span class="hours__divider"></span>
                <div class="hours__item">
                    <span>Sunday</span>
                    <span class="badge badge--closed">CLOSED</span>
                </div>
            </div>
        </div>

    </div>
</footer>