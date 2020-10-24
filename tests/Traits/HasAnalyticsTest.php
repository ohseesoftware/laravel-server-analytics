<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests\Traits;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use OhSeeSoftware\LaravelServerAnalytics\Models\AnalyticsRelation;
use OhSeeSoftware\LaravelServerAnalytics\Tests\Fixtures\HasAnalyticsModel;
use OhSeeSoftware\LaravelServerAnalytics\Tests\TestCase;

class AnalyticsMetaTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_adds_analytics_relation()
    {
        // Given
        $model = factory(HasAnalyticsModel::class)->create();

        $analytics = factory(Analytics::class)->create();
        $analytics->addRelation($model);

        // When
        $result = $model->analytics;

        // Then
        $this->assertCount(1, $result);
    }

    /** @test */
    public function it_pulls_attached_analytics_in_the_past_hour()
    {
        // Given
        $model = factory(HasAnalyticsModel::class)->create();
        
        // When
        $analytics = factory(Analytics::class)->create();
        $analytics->addRelation($model);

        // Then
        Carbon::setTestNow(Carbon::now()->addHours(2));
        $result = $model->analyticsInPastHour();
        $this->assertCount(0, $result);

        Carbon::setTestNow();
        $result = $model->analyticsInPastHour();
        $this->assertCount(1, $result);
    }

    /** @test */
    public function it_pulls_attached_analytics_in_the_past_day()
    {
        // Given
        $model = factory(HasAnalyticsModel::class)->create();
        
        // When
        $analytics = factory(Analytics::class)->create();
        $analytics->addRelation($model);

        // Then
        Carbon::setTestNow(Carbon::now()->addDays(2));
        $result = $model->analyticsInPastDay();
        $this->assertCount(0, $result);

        Carbon::setTestNow();
        $result = $model->analyticsInPastDay();
        $this->assertCount(1, $result);
    }

    /** @test */
    public function it_pulls_attached_analytics_in_the_past_month()
    {
        // Given
        $model = factory(HasAnalyticsModel::class)->create();
        
        // When
        $analytics = factory(Analytics::class)->create();
        $analytics->addRelation($model);

        // Then
        Carbon::setTestNow(Carbon::now()->addMonths(2));
        $result = $model->analyticsInPastMonth();
        $this->assertCount(0, $result);

        Carbon::setTestNow();
        $result = $model->analyticsInPastMonth();
        $this->assertCount(1, $result);
    }
}
