<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Force MySQL with the real database name hardcoded.
        // phpunit.xml sets DB_DATABASE=:memory: as an env var, so env() would
        // return ':memory:' — we bypass that by using the literal name here.
        config([
            'database.default'                       => 'mysql',
            'database.connections.mysql.driver'      => 'mysql',
            'database.connections.mysql.host'        => '127.0.0.1',
            'database.connections.mysql.port'        => '3306',
            'database.connections.mysql.database'    => 'admin_staging_directimported_db',
            'database.connections.mysql.username'    => 'root',
            'database.connections.mysql.password'    => '',
            'database.connections.mysql.charset'     => 'utf8mb4',
            'database.connections.mysql.collation'   => 'utf8mb4_unicode_ci',
        ]);

        // Purge any cached connection so Laravel reconnects with the new config.
        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

