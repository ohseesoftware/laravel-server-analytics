<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class RouteExclusionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_route_if_not_in_excludes_array()
    {
        // Given
        ServerAnalytics::addRouteExclusions(['/home']);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }

    /** @test */
    public function it_excludes_specific_routes_from_tracking()
    {
        // Given
        ServerAnalytics::addRouteExclusions(['/analytics']);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseMissing(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }

    /** @test */
    public function it_excludes_wildcard_routes_from_tracking()
    {
        // Given
        ServerAnalytics::addRouteExclusions(['/test/*']);

        // When
        $this->get('/test/1234');

        // Then
        $this->assertDatabaseMissing(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }

    /** @test */
    public function it_excludes_requests_from_bots_when_configuration_is_set()
    {
        // Given
        Config::set('ignore_bot_requests', true);

        // When
        $this->get('/test/1234', [
            'User-Agent' => '80legs'
        ]);

        // Then
        $this->assertDatabaseMissing(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }
}
