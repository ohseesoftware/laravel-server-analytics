<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

class AnalyticsMetaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_saves_meta_for_analytics()
    {
        // Given
        ServerAnalytics::addPostHook(
            function (Request $request, Analytics $analytics) {
                $analytics->addMeta('test', '1234');
            }
        );

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
