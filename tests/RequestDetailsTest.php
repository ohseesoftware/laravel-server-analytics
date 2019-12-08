<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsFacade;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

class RequestDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_overriding_request_details_class()
    {
        $override = new class extends RequestDetails
        {
            public function getMethod(): string
            {
                return 'Test';
            }
        };

        // Given
        LaravelServerAnalyticsFacade::setRequestDetails($override);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(LaravelServerAnalyticsFacade::getAnalyticsDataTable(), [
            'id'           => 1,
            'method'       => 'Test',
        ]);
    }
}
