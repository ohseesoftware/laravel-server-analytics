<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsFacade;

class MethodExceptionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_method_if_not_in_excludes_array()
    {
        $spy = Mockery::spy(function () {
            // no op
        });
        LaravelServerAnalyticsFacade::addPostHook($spy);

        // Given
        LaravelServerAnalyticsFacade::addMethodExceptions(['POST']);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(LaravelServerAnalyticsFacade::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }

    /** @test */
    public function it_excludes_specific_methods_from_tracking()
    {
        // Given
        LaravelServerAnalyticsFacade::addMethodExceptions(['GET']);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseMissing(LaravelServerAnalyticsFacade::getAnalyticsDataTable(), [
            'id' => 1
        ]);
    }
}
