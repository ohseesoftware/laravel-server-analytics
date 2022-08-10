<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;
use OhSeeSoftware\LaravelServerAnalytics\Tests\Fixtures\RequestDetailsOverride;

class RequestDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_overriding_request_details_class()
    {
        // Given
        Config::set('laravel-server-analytics.request_details_class', RequestDetailsOverride::class);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id'           => 1,
            'method'       => 'Test',
        ]);
    }
}
