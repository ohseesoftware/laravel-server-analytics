<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\ServerAnalytics;

class RouteExceptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_route_if_not_in_excludes_array()
    {
        // Given
        ServerAnalytics::addRouteExceptions(['/home']);

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
        ServerAnalytics::addRouteExceptions(['/analytics']);

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
        ServerAnalytics::addRouteExceptions(['/test/*']);

        // When
        $this->get('/test/1234');

        // Then
        $this->assertDatabaseMissing(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }
}
