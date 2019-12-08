<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Mockery;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsFacade;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

class PostHooksTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_runs_custom_post_hooks()
    {
        // Given
        $relatedAnalytics = factory(Analytics::class)->create();
        LaravelServerAnalyticsFacade::addPostHook(function (Request $request, Analytics $analytics) use ($relatedAnalytics) {
            $analytics->addRelation($relatedAnalytics);
        });

        $spy = Mockery::spy(function () {
            // no op
        });
        LaravelServerAnalyticsFacade::addPostHook($spy);

        // When
        $this->get('/analytics');

        // Then
        // $spy->shouldHaveBeenCalled();
        $this->assertDatabaseHas(LaravelServerAnalyticsFacade::getAnalyticsDataTable(), [
            'id' => 2
        ]);
        $this->assertDatabaseHas(LaravelServerAnalyticsFacade::getAnalyticsRelationTable(), [
            'analytics_id'  => 2,
            'relation_id'   => $relatedAnalytics->id,
            'relation_type' => Analytics::class
        ]);
    }
}
