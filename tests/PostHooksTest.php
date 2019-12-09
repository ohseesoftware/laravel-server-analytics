<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

class PostHooksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_runs_custom_post_hooks()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        ServerAnalytics::addPostHook(
            function (Request $request, Response $response, Analytics $analytics) use ($relatedAnalytics) {
                $analytics->addRelation($relatedAnalytics);
            }
        );

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
