<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class RequestDetailsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_allows_overriding_request_details_class()
    {
        $override = new class extends RequestDetails {
            public function getMethod(): string
            {
                return 'Test';
            }
        };

        // Given
        ServerAnalytics::setRequestDetails($override);

        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id'           => 1,
            'method'       => 'Test',
        ]);
    }
}
