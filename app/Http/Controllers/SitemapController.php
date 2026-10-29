<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Car;
use App\Models\Faq;
use App\Models\RentalPolicies;
use App\Models\Service;
use App\Models\SiteSettings;
use Carbon\Carbon;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Return dynamic XML sitemap.
     */
    public function index(): Response
    {
        $canonicalBase = rtrim(config('sitemap.canonical_url', env('CANONICAL_URL', 'https://www.directimported.com')), '/');

        // Track added URLs to ensure strict uniqueness
        $urls = [];

        // 1. Static public indexable pages
        foreach ($this->getStaticPages($canonicalBase) as $page) {
            $urls[$page['loc']] = $page;
        }

        // 2. Eligible active vehicle listings
        foreach ($this->getVehiclePages($canonicalBase) as $page) {
            if (!isset($urls[$page['loc']])) {
                $urls[$page['loc']] = $page;
            }
        }

        // 3. Published blog posts
        foreach ($this->getBlogPages($canonicalBase) as $page) {
            if (!isset($urls[$page['loc']])) {
                $urls[$page['loc']] = $page;
            }
        }

        $xml = view('sitemap.xml', [
            'urls' => array_values($urls),
        ])->render();

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=utf-8',
        ]);
    }

    /**
     * Collect all public, indexable static pages.
     */
    protected function getStaticPages(string $canonicalBase): array
    {
        $latestCarUpdated = Car::eligibleForSitemap()->max('updated_at');
        $latestBlogUpdated = BlogPost::publishedForSitemap()->max('updated_at');
        $siteSettingsUpdated = SiteSettings::max('updated_at');

        $homeLastmod = $latestCarUpdated ?: ($latestBlogUpdated ?: ($siteSettingsUpdated ?: now()));

        return [
            [
                'loc' => $canonicalBase . '/',
                'lastmod' => $this->formatDate($homeLastmod),
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => $canonicalBase . '/available-vehicles',
                'lastmod' => $this->formatDate($latestCarUpdated ?: now()),
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => $canonicalBase . '/about-us',
                'lastmod' => $this->formatDate($this->getViewFileTime('about-us', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $canonicalBase . '/services',
                'lastmod' => $this->formatDate(Service::max('updated_at') ?: now()),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => $canonicalBase . '/blog',
                'lastmod' => $this->formatDate($latestBlogUpdated ?: now()),
                'changefreq' => 'daily',
                'priority' => '0.8',
            ],
            [
                'loc' => $canonicalBase . '/contact',
                'lastmod' => $this->formatDate($this->getViewFileTime('contact', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $canonicalBase . '/faqs',
                'lastmod' => $this->formatDate(Faq::max('updated_at') ?: now()),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $canonicalBase . '/terms-and-conditions',
                'lastmod' => $this->formatDate(RentalPolicies::max('updated_at') ?: now()),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $canonicalBase . '/shipping-and-logistics',
                'lastmod' => $this->formatDate($this->getViewFileTime('shipping-and-logistics', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $canonicalBase . '/vehicle-inspection-info',
                'lastmod' => $this->formatDate($this->getViewFileTime('vehicle-inspection-info', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => $canonicalBase . '/about-our-cars',
                'lastmod' => $this->formatDate($this->getViewFileTime('about-our-cars', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $canonicalBase . '/licence-requirement',
                'lastmod' => $this->formatDate($this->getViewFileTime('licence-requirement', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $canonicalBase . '/useful-links',
                'lastmod' => $this->formatDate($this->getViewFileTime('useful-links', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $canonicalBase . '/japan-law',
                'lastmod' => $this->formatDate($this->getViewFileTime('japan-law', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
            [
                'loc' => $canonicalBase . '/ride-japan-law',
                'lastmod' => $this->formatDate($this->getViewFileTime('ride-japan-law', $siteSettingsUpdated)),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
        ];
    }

    /**
     * Collect all eligible active vehicle listings.
     */
    protected function getVehiclePages(string $canonicalBase): array
    {
        $cars = Car::eligibleForSitemap()
            ->orderBy('updated_at', 'desc')
            ->get();

        $pages = [];

        foreach ($cars as $car) {
            $stockId = $car->stock_id;
            if (empty($stockId)) {
                continue;
            }

            // Properly URL-encode stock ID path segments (e.g. spaces, special chars)
            $encodedSlug = implode('/', array_map('rawurlencode', explode('/', (string) $stockId)));
            $loc = $canonicalBase . '/car/' . $encodedSlug;

            $lastmod = $car->updated_at ?? $car->created_at ?? now();

            $pages[] = [
                'loc' => $loc,
                'lastmod' => $this->formatDate($lastmod),
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ];
        }

        return $pages;
    }

    /**
     * Collect all published blog posts across all categories.
     */
    protected function getBlogPages(string $canonicalBase): array
    {
        $posts = BlogPost::publishedForSitemap()
            ->orderBy('updated_at', 'desc')
            ->get();

        $seenSlugs = [];
        $pages = [];

        foreach ($posts as $post) {
            $slug = trim((string) $post->slug);
            if (empty($slug) || isset($seenSlugs[$slug])) {
                continue; // Prevent duplicates for posts sharing the same slug
            }
            $seenSlugs[$slug] = true;

            $encodedSlug = implode('/', array_map('rawurlencode', explode('/', $slug)));
            $loc = $canonicalBase . '/blog/' . $encodedSlug;

            $lastmod = $post->updated_at ?? $post->published_at ?? $post->created_at ?? now();

            $pages[] = [
                'loc' => $loc,
                'lastmod' => $this->formatDate($lastmod),
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ];
        }

        return $pages;
    }

    /**
     * Format a date into W3C Atom/ISO8601 string.
     */
    protected function formatDate($date): string
    {
        if (empty($date)) {
            return now()->toAtomString();
        }

        if ($date instanceof Carbon) {
            return $date->toAtomString();
        }

        return Carbon::parse($date)->toAtomString();
    }

    /**
     * Get view blade file modification time as fallback.
     */
    protected function getViewFileTime(string $viewName, $fallback = null)
    {
        $file = resource_path('views/landing/pages/' . $viewName . '.blade.php');
        if (file_exists($file)) {
            return Carbon::createFromTimestamp(filemtime($file));
        }

        return $fallback ?: now();
    }
}

