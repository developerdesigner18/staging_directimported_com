<?php

namespace Tests\Feature;

use App\Enum\CarStatus;
use App\Models\BlogPost;
use App\Models\Car;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SitemapTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'database.default' => 'mysql',
            'database.connections.mysql.host' => '127.0.0.1',
            'database.connections.mysql.port' => '3306',
            'database.connections.mysql.database' => 'admin_staging_directimported_db',
            'database.connections.mysql.username' => 'root',
            'database.connections.mysql.password' => '',
        ]);
        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    /**
     * Test sitemap endpoint returns valid XML with 200 status code.
     */
    public function test_sitemap_returns_valid_xml_response(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('application/xml', $response->headers->get('Content-Type'));

        $content = $response->getContent();
        $this->assertNotEmpty($content);

        libxml_use_internal_errors(true);
        $xml = simplexml_load_string($content);

        $this->assertNotFalse($xml, 'Sitemap XML is invalid: ' . json_encode(libxml_get_errors()));
        $this->assertEquals('urlset', $xml->getName());
        $this->assertGreaterThan(0, count($xml->url));
    }

    /**
     * Test static indexable pages are included and non-indexable pages are excluded.
     */
    public function test_sitemap_includes_static_pages_and_excludes_auth_pages(): void
    {
        $response = $this->get('/sitemap.xml');
        $content = $response->getContent();

        // Included static pages
        $this->assertStringContainsString('<loc>https://www.directimported.com/</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/available-vehicles</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/about-us</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/services</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/blog</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/contact</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/faqs</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/terms-and-conditions</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/shipping-and-logistics</loc>', $content);
        $this->assertStringContainsString('<loc>https://www.directimported.com/vehicle-inspection-info</loc>', $content);

        // Excluded pages
        $this->assertStringNotContainsString('<loc>https://www.directimported.com/login</loc>', $content);
        $this->assertStringNotContainsString('<loc>https://www.directimported.com/register</loc>', $content);
        $this->assertStringNotContainsString('<loc>https://www.directimported.com/forgot-password</loc>', $content);
        $this->assertStringNotContainsString('<loc>https://www.directimported.com/car</loc>', $content); // redirect
        $this->assertStringNotContainsString('<loc>https://www.directimported.com/admin</loc>', $content);
    }

    /**
     * Test no duplicate URLs exist in sitemap.
     */
    public function test_sitemap_contains_no_duplicate_urls(): void
    {
        $response = $this->get('/sitemap.xml');
        $xml = simplexml_load_string($response->getContent());

        $seen = [];
        $duplicates = [];

        foreach ($xml->url as $url) {
            $loc = (string) $url->loc;
            if (isset($seen[$loc])) {
                $duplicates[] = $loc;
            }
            $seen[$loc] = true;
        }

        $this->assertEmpty($duplicates, 'Duplicate URLs found in sitemap: ' . implode(', ', $duplicates));
    }

    /**
     * Test draft records are excluded and active records are included.
     */
    public function test_sitemap_excludes_draft_records(): void
    {
        $response = $this->get('/sitemap.xml');
        $content = $response->getContent();

        // Draft car VH000051 must not be in sitemap
        $this->assertStringNotContainsString('/car/VH000051', $content);

        // Active car VH000002 must be in sitemap
        $this->assertStringContainsString('/car/VH000002', $content);
    }

    /**
     * Test dynamic updates on car and blog changes.
     */
    public function test_sitemap_dynamically_updates_on_record_changes(): void
    {
        DB::beginTransaction();

        try {
            $initialResponse = $this->get('/sitemap.xml');
            $initialXml = simplexml_load_string($initialResponse->getContent());
            $initialCount = count($initialXml->url);

            // Create new active car
            $car = new Car();
            $car->name = 'Test Dynamic Skyline';
            $car->slug = 'test-dynamic-skyline';
            $car->vehicle_id = 'VH_TEST_DYNAMIC_1';
            $car->model = 'Skyline';
            $car->status = CarStatus::AVAILABLE;
            $car->save();

            $newResponse = $this->get('/sitemap.xml');
            $newXml = simplexml_load_string($newResponse->getContent());
            $this->assertEquals($initialCount + 1, count($newXml->url));
            $this->assertStringContainsString('/car/VH_TEST_DYNAMIC_1', $newResponse->getContent());

            // Set car to draft (unpublish)
            $car->status = CarStatus::DRAFT;
            $car->save();

            $draftResponse = $this->get('/sitemap.xml');
            $draftXml = simplexml_load_string($draftResponse->getContent());
            $this->assertEquals($initialCount, count($draftXml->url));
            $this->assertStringNotContainsString('/car/VH_TEST_DYNAMIC_1', $draftResponse->getContent());
        } finally {
            DB::rollBack();
        }
    }
}
