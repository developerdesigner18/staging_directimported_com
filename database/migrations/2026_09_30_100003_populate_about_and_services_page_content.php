<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Moves the content that was previously hardcoded in the About Us and Services
 * Blade templates into the existing CMS tables, so the pages keep rendering the
 * same content after becoming dynamic. Only empty fields are filled and only
 * services whose title does not exist yet are inserted, so it never overwrites
 * content that was already managed through the admin panel.
 */
return new class extends Migration {
    public function up(): void
    {
        $this->populateAboutPage();
        $this->populateServicesPageHeader();
        $this->populateServices();
    }

    public function down(): void
    {
        // Content-only migration: the columns are dropped by their own migrations.
    }

    private function populateAboutPage(): void
    {
        $now = now();
        $homeSection = DB::table('home_sections')->whereNull('deleted_at')->orderBy('id')->first();

        if (!$homeSection) {
            $id = DB::table('home_sections')->insertGetId([
                'title' => 'About Us',
                'short_description' => 'Welcome to our website. We are dedicated to providing the best service possible.',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $homeSection = DB::table('home_sections')->find($id);
        }

        $defaults = [
            'about_badge' => 'Licensed Automobile Dealer in Japan',
            'about_title' => 'About Direct Imported Japan',
            'about_intro' => '<p>Direct Imported Japan operates under <strong>International Auto Select Japan LLC</strong> (short name <strong>IAS Japan</strong>), a fully licensed automobile dealer based in Japan. While operating under our independent LLC structure, we retain the Direct Imported website as our dedicated purchasing storefront, as we have for over 25 years.</p>'
                . "\n" . '<p>Our operations are managed by Phil Cathcart, a trade-qualified Automotive Technician who completed his technical schooling in 1992 and has been a resident of Japan for over 25 years.</p>',
            'about_button_text' => 'Read More',
            'about_hero_image' => 'pexels-photo-4489749.avif',
            'story_images' => json_encode(['pexels-photo-2244746.avif', 'pexels-photo-3807277.avif']),
            'founded_title' => 'Why We Were Founded',
            'founded_content' => '<p>Direct Imported Japan was established to fix a major flaw in the vehicle export industry: reliance on commission-driven salespeople. Most export agents act purely as sales staff chasing monthly quotas, creating a hit-or-miss, 50/50 chance of buyers receiving a rusty, misrepresented car just so an agent can hit a target. As a licensed Japanese motor vehicle dealer, we operate without sales targets, providing accurate vehicle descriptions, honest condition reports, and true technical assessments.</p>',
            'advantage_title' => 'Our Automotive Inspection Advantage',
            'advantage_content' => '<p>Auction inspectors in Japan process hundreds of cars daily and frequently miss critical details, factory options, or aftermarket modifications—sometimes failing to even note a turbo attached to an engine block.</p>'
                . "\n" . '<p>Combining our export operations established in 1997 with formal automotive trade qualifications, Phil personally conducts physical inspections at the auctions we attend. Having worked as a vehicle assessor for nearly 30 years, his strict attention to detail ensures he spots misaligned panel gaps, concealed body repairs, rust, and mechanical issues before you place a bid or commit funds.</p>',
            'operations_title' => 'Core Operations',
            'operations_subtitle' => 'Direct purchasing access across Japan with full logistics support',
            'operations' => json_encode([
                ['icon' => 'bx bx-gavel', 'title' => 'Auction Vehicle Bidding', 'description' => 'Direct access to bid on thousands of vehicles passing through Japanese wholesale auctions daily.'],
                ['icon' => 'bx bx-store-alt', 'title' => 'Dealer Yard Sourcing', 'description' => 'Direct purchasing of hand-picked stock from dealership yards across Japan.'],
                ['icon' => 'bx bx-ship', 'title' => 'Export & Logistics Management', 'description' => 'Complete handling of Japanese export documentation, international shipping, and port logistics.'],
            ]),
            'facts_title' => 'Operational Facts',
            'facts' => json_encode([
                ['icon' => 'bx bx-check-circle', 'feature' => 'Licensed Dealer Status', 'details' => 'Fully registered and licensed motor vehicle trader in Japan.'],
                ['icon' => 'bx bx-dollar-circle', 'feature' => 'Direct JPY Pricing', 'details' => 'Vehicle listings display actual auction or dealer prices in Japanese Yen (JPY).'],
                ['icon' => 'bx bx-search-alt', 'feature' => 'Expertly Assessed Listings', 'details' => '25 years of experience, along with his technical qualifications, means Phil has a keen eye for good cars. He will tell you to think twice about buying certain vehicles—an insight that only comes from many years of hands-on experience.'],
                ['icon' => 'bx bx-receipt', 'feature' => 'Flat Service Fees', 'details' => 'We charge a clear handling fee to manage the purchase, paperwork, and transport, with zero vehicle price markups.'],
                ['icon' => 'bx bx-globe', 'feature' => 'Global Export Specialists', 'details' => 'We specialize in exporting vehicles to Australia, USA, UK, Ireland, Canada, and the Caribbean Islands.'],
            ]),
            'passion_title' => 'Our Passion: The Vehicles We Source',
            'passion_intro' => '<p>While we happily source commercial vehicles, SUVs, and reliable daily drivers for our clients, our <strong>true passion and absolute specialty lies in high-performance and modified vehicles</strong>. We don\'t just export these cars; we live and breathe JDM and Euro performance culture.</p>',
            'passion_cards' => json_encode([
                [
                    'image' => 'car_1785251750_6a68c7a61a8c1.webp',
                    'badge' => 'JDM Icons',
                    'title' => 'JDM Legends & Classics',
                    'description' => 'Japan is the birthplace of some of the most iconic sports cars in automotive history. We have a deep-rooted love for the golden era of Japanese performance. Our true specialty is hunting down the best Nissan Skylines, Toyota Supras, and Nissan Silvias. Whether it’s a pristine, low-kilometer factory classic like a 1994 GT-R V-Spec II or a heavily modified track weapon, our technical expertise means we know exactly what to look for—and what to avoid—when evaluating these legendary machines.',
                ],
                [
                    'image' => 'pexels-photo-170811.avif',
                    'badge' => 'European Performance',
                    'title' => 'European Precision & BMW M Cars',
                    'description' => 'The Japanese market is a hidden goldmine for impeccably maintained European luxury and high-performance vehicles. We have a massive appreciation for the precision engineering of BMW M Cars and European classics. Because high-end Euro models are status symbols in Japan, they are often garaged, meticulously serviced, and driven sparingly. We leverage our years of experience on the ground to source the absolute best examples of European performance available.',
                ],
            ]),
            'passion_button_text' => 'Search Live Auctions Now',
        ];

        $updates = [];
        foreach ($defaults as $column => $value) {
            if (is_null($homeSection->{$column})) {
                $updates[$column] = $value;
            }
        }

        if ($updates) {
            $updates['updated_at'] = $now;
            DB::table('home_sections')->where('id', $homeSection->id)->update($updates);
        }
    }

    private function populateServicesPageHeader(): void
    {
        $now = now();
        $settings = DB::table('site_settings')->orderBy('id')->first();

        $defaults = [
            'services_page_badge' => 'Licensed Motor Vehicle Trader in Japan',
            'services_page_title' => 'End-to-End Procurement & Export',
            'services_page_description' => 'Independent technical evaluations, zero sales-target bias, and flat-fee handling for buyers worldwide.',
        ];

        if (!$settings) {
            DB::table('site_settings')->insert($defaults + ['created_at' => $now, 'updated_at' => $now]);
            return;
        }

        $updates = array_filter($defaults, fn($value, $column) => is_null($settings->{$column}), ARRAY_FILTER_USE_BOTH);
        if ($updates) {
            DB::table('site_settings')->where('id', $settings->id)->update($updates + ['updated_at' => $now]);
        }
    }

    private function populateServices(): void
    {
        $now = now();
        $sortOrder = (int) DB::table('services')->max('sort_order');

        $services = [
            [
                'title' => 'Auction Vehicle Bidding & Inspection',
                'short_description' => 'Do we sit behind a computer NO!!! We provide detailed information, high-resolution photos, and condition reports for each vehicle at the auctions we attend.',
                'icon' => 'service_icon_auction.svg',
                'description' => '<p>Trade-qualified Automotive Technician Phil Cathcart personally conducts physical inspections at Japanese wholesale auctions. With 30 years of vehicle assessment experience, he detects hidden rust, panel gaps, concealed repairs, and mechanical issues before you bid—delivering high-resolution photos and fully translated condition reports.</p>',
                'images' => ['photo-1503376780353-7e6692767b70.avif'],
                'image_badge' => 'Hands-On Assessment',
                'features' => [
                    ['icon' => 'bx bx-user-check', 'text' => 'Physical Checks'],
                    ['icon' => 'bx bx-file', 'text' => 'Translated Sheets'],
                    ['icon' => 'bx bx-camera', 'text' => 'High-Res Photos'],
                ],
            ],
            [
                'title' => 'Dealer Yard Direct Sourcing',
                'short_description' => 'Direct purchasing of hand-picked stock from dealership yards across Japan, with a transparent flat service fee and zero price markups.',
                'icon' => 'service_icon_dealer.svg',
                'description' => '<p>Direct purchasing of hand-picked stock from dealership yards across Japan. Operating under International Auto Select Japan LLC as a licensed motor vehicle dealer, we charge a transparent flat service fee with zero price markups—displaying actual wholesale prices in Japanese Yen (JPY).</p>',
                'images' => ['photo-1563720223185-11003d516935.avif'],
                'image_badge' => 'Direct Purchase Storefront',
                'features' => [
                    ['icon' => 'bx bx-tag', 'text' => 'Flat Service Fee'],
                    ['icon' => 'bx bx-yen', 'text' => 'Direct JPY Cost'],
                    ['icon' => 'bx bx-block', 'text' => 'Zero Sales Quotas'],
                ],
            ],
            [
                'title' => 'Inspection & ODO Certification',
                'short_description' => 'Thorough inspection reports translated checked based on auction location and grading. Not all auctions are equal. ODO certification services from JEVIC to ensure genuine KLM',
                'icon' => 'service_icon_certificate.svg',
                'description' => '<p>Not all auction grades are equal, and high-volume inspectors miss critical details. We provide thorough independent inspections along with official ODO certification (including JEVIC) to verify genuine kilometer readings and protect buyers from altered odometers.</p>',
                'images' => ['photo-1508974239320-0a029497e820.avif'],
                'image_badge' => 'Verified Integrity',
                'features' => [
                    ['icon' => 'bx bx-badge-check', 'text' => 'JEVIC Certified'],
                    ['icon' => 'bx bx-shield-quarter', 'text' => 'Mileage Audit'],
                ],
            ],
            [
                'title' => 'Documentation & Export Clearance',
                'short_description' => 'Complete support with export and import documentation, customs export clearance, to successfully export your vehicles without delay.',
                'icon' => 'service_icon_documentation.svg',
                'description' => '<p>Complete support with Japanese export deregistrations, translated certificates, and official customs export clearance. We manage all administrative compliance so your vehicle exports smoothly without customs holds or import delays.</p>',
                'images' => ['photo-1450133064473-71024230f91b.avif'],
                'image_badge' => 'Full Customs Support',
                'features' => [
                    ['icon' => 'bx bx-receipt', 'text' => 'Export Certificate'],
                    ['icon' => 'bx bx-id-card', 'text' => 'English Translation'],
                ],
            ],
            [
                'title' => 'Global Shipping & Port Logistics',
                'short_description' => 'Complete handling of Japanese port transport and international freight booking via secure RORO or container vessels.',
                'icon' => 'service_icon_shipping.svg',
                'description' => '<p>Complete handling of internal Japanese port transport and international freight booking. We specialize in vehicle shipping routes to Australia, the USA, UK, Ireland, Canada, and the Caribbean Islands via secure RORO or container vessels.</p>',
                'images' => ['photo-1578575437130-527eed3abbec.avif'],
                'image_badge' => 'Worldwide Freight',
                'features' => [
                    ['icon' => 'bx bx-globe', 'text' => 'AU, USA, UK, IE, CA & Caribbean'],
                    ['icon' => 'bx bx-ship', 'text' => 'RORO & Container'],
                ],
            ],
            [
                'title' => 'High-Performance JDM & European Classics',
                'short_description' => 'Specialists in factory JDM classics and garaged European performance cars, with expert inspection of tuning and aftermarket parts.',
                'icon' => 'service_icon_performance.svg',
                'description' => '<p>Our specialty lies in high-performance and modified vehicles. We hunt down factory JDM classics like Skyline GT-Rs, Supras, and Silvias, alongside garaged European machinery like BMW M-cars. Phil\'s mechanical background guarantees expert inspection of tuning, aftermarket parts, and turbos.</p>',
                'images' => ['photo-1617814076367-b759c7d7e738.avif'],
                'image_badge' => 'Specialist Passion',
                'features' => [
                    ['icon' => 'bx bx-car', 'text' => 'Skylines, Supras, Silvias'],
                    ['icon' => 'bx bx-tachometer', 'text' => 'BMW M Cars & Euro Classics'],
                ],
            ],
        ];

        foreach ($services as $service) {
            if (DB::table('services')->where('title', $service['title'])->exists()) {
                continue;
            }

            DB::table('services')->insert([
                'title' => $service['title'],
                'short_description' => $service['short_description'],
                'icon' => $service['icon'],
                'description' => $service['description'],
                'images' => json_encode($service['images']),
                'image_badge' => $service['image_badge'],
                'features' => json_encode($service['features']),
                'sort_order' => ++$sortOrder,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
};
