<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

class AnalyticsMetaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_saves_meta_for_analytics()
    {
        // Given
        ServerAnalytics::addMetaHook(function (RequestDetails $requestDetails) {
            return ['key' => 'test', 'value' => 1234];
        });

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsMetaTable(), [
            'analytics_id' => 1,
            'key'          => 'test',
            'value'        => '1234'
        ]);
    }

    /** @test */
    public function it_adds_meta_via_helper_method()
    {
        // Given
        ServerAnalytics::addMeta('test', '1234');

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsMetaTable(), [
            'analytics_id' => 1,
            'key'          => 'test',
            'value'        => '1234'
        ]);
    }
}
