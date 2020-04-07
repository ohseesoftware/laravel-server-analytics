<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
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
            function (Request $request, Response $response, Analytics $analytics) use ($relatedAnalytics) {
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

    /** @test */
    public function it_adds_relation_via_helper_method()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        ServerAnalytics::addRelation($relatedAnalytics);

        $spy = Mockery::spy(function () {
            // no op
        });
        ServerAnalytics::addPostHook($spy);

        // When
        $this->get('/analytics');

        // Then
        $spy->shouldHaveBeenCalled();
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 2
        ]);
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsRelationTable(), [
            'analytics_id'  => 2,
            'relation_id'   => $relatedAnalytics->id,
            'relation_type' => Analytics::class
        ]);
    }
}
