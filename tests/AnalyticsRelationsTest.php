<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

class AnalyticsRelationsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_saves_relations_for_analytics()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        ServerAnalytics::addRelationHook(
            function (RequestDetails $requestDetails) use ($relatedAnalytics) {
                return ['model' => $relatedAnalytics];
            }
        );

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsRelationTable(), [
            'analytics_id'  => 2,
            'relation_id'   => $relatedAnalytics->id,
            'relation_type' => Analytics::class,
            'reason'        => null
        ]);
    }

    /** @test */
    public function it_saves_relations_with_a_reason_for_analytics()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        ServerAnalytics::addRelationHook(
            function (RequestDetails $requestDetails) use ($relatedAnalytics) {
                return ['model' => $relatedAnalytics, 'reason' => 'some fake reason'];
            }
        );

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsRelationTable(), [
            'analytics_id'  => 2,
            'relation_id'   => $relatedAnalytics->id,
            'relation_type' => Analytics::class,
            'reason'        => 'some fake reason'
        ]);
    }

    /** @test */
    public function it_adds_relation_via_helper_method()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        ServerAnalytics::addRelation($relatedAnalytics);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 2
        ]);
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsRelationTable(), [
            'analytics_id'  => 2,
            'relation_id'   => $relatedAnalytics->id,
            'relation_type' => Analytics::class
        ]);
    }

    /** @test */
    public function it_adds_relation_with_reason_via_helper_method()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        ServerAnalytics::addRelation($relatedAnalytics, 'some fake reason');

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id' => 2
        ]);
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsRelationTable(), [
            'analytics_id'  => 2,
            'relation_id'   => $relatedAnalytics->id,
            'relation_type' => Analytics::class,
            'reason'        => 'some fake reason'
        ]);
    }
}
