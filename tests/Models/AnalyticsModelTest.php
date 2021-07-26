<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests\Models;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use OhSeeSoftware\LaravelServerAnalytics\Tests\TestCase;

class AnalyticsModelTest extends TestCase
{
    use RefreshDatabase;

    /** @var Analytics */
    public $analytics;

    public function setUp(): void
    {
        parent::setUp();

        $this->analytics = factory(Analytics::class)->create();
    }

    /** @test */
    public function it_scopes_related_entities()
    {
        $relatedAnalytics = factory(Analytics::class)->create();

        $this->assertCount(0, Analytics::relatedTo($relatedAnalytics)->get());

        $this->analytics->addRelation($relatedAnalytics);
        $this->assertCount(1, Analytics::relatedTo($relatedAnalytics)->get());
    }

    /** @test */
    public function it_scopes_analytics_that_have_meta_with_key()
    {
        $this->analytics->addMeta('foo', 'bar');

        $this->assertCount(0, Analytics::hasMeta('bar')->get());
        $this->assertCount(1, Analytics::hasMeta('foo')->get());
    }

    /** @test */
    public function it_scopes_analytics_that_have_specific_meta_value()
    {
        $this->analytics->addMeta('foo', 'bar');

        $this->assertCount(0, Analytics::withMetaValue('foo', 'invalid')->get());
        $this->assertCount(1, Analytics::withMetaValue('foo', 'bar')->get());
    }
}
