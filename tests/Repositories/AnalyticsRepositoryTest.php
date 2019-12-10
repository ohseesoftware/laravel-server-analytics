<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use OhSeeSoftware\LaravelServerAnalytics\Repositories\AnalyticsRepository;
use OhSeeSoftware\LaravelServerAnalytics\Tests\TestCase;

class AnalyticsRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** @var Analytics */
    public $analytics;

    /** @var AnalyticsRepository */
    public $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->analytics = factory(Analytics::class)->create();
        $this->repository = resolve(AnalyticsRepository::class);
    }

    /** @test */
    public function it_finds_analytics()
    {
        $this->assertCount(1, $this->repository->findBy('path', $this->analytics->path)->get());
        $this->assertCount(1, $this->repository->findBy('method', $this->analytics->method)->get());
        $this->assertCount(1, $this->repository->findBy('status_code', $this->analytics->status_code)->get());
    }

    /** @test */
    public function it_finds_analytics_with_custom_comparator()
    {
        $this->analytics->update(['duration_ms' => 500]);

        $this->assertCount(0, $this->repository->findBy('duration_ms', 250, '<=')->get());
        $this->assertCount(1, $this->repository->findBy('duration_ms', 250, '>=')->get());
    }
}
