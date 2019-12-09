<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\ServerAnalytics;

class MethodExceptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_method_if_not_in_excludes_array()
    {
        $spy = Mockery::spy(function () {
            // no op
        });
        ServerAnalytics::addPostHook($spy);

        // Given
        ServerAnalytics::addMethodExceptions(['POST']);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }

    /** @test */
    public function it_excludes_specific_methods_from_tracking()
    {
        // Given
        ServerAnalytics::addMethodExceptions(['GET']);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseMissing(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }
}
