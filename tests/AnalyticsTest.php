<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsFacade;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_a_request()
    {
        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(LaravelServerAnalyticsFacade::getAnalyticsDataTable(), [
            'id'           => 1,
            'path'         => '/analytics',
            'method'       => 'GET',
            'status_code'  => '200',
            'user_agent'   => 'Symfony',
            'ip_address'   => '127.0.0.1',
            'query_params' => null
        ]);
    }
}
