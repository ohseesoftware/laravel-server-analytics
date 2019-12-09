<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

class AnalyticsRelationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_saves_relations_for_analytics()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        ServerAnalytics::addPostHook(
            function (Request $request, Analytics $analytics) use ($relatedAnalytics) {
                $analytics->addRelation($relatedAnalytics);
            }
        );

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsRelationTable(), [
            'analytics_id'  => 2,
            'relation_id'   => $relatedAnalytics->id,
            'relation_type' => Analytics::class
        ]);
    }
}
