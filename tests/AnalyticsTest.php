<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class AnalyticsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_tracks_a_request()
    {
        // When
        $this->get('/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id'           => 1,
            'path'         => '/analytics',
            'method'       => 'GET',
            'status_code'  => '200',
            'user_agent'   => 'Symfony',
            'ip_address'   => '127.0.0.1',
            'query_params' => json_encode([])
        ]);
    }

    /** @test */
    public function it_tracks_a_request_with_query_params()
    {
        // When
        $this->get('/analytics?foo=bar&test=true');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id'           => 1,
            'path'         => '/analytics',
            'method'       => 'GET',
            'status_code'  => '200',
            'user_agent'   => 'Symfony',
            'ip_address'   => '127.0.0.1',
            'query_params' => json_encode(['foo' => 'bar', 'test' => 'true'])
        ]);
    }

    /** @test */
    public function it_tracks_a_request_with_a_json_response()
    {
        // When
        $this->get('/api/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id'           => 1,
            'path'         => '/api/analytics',
            'method'       => 'GET',
            'status_code'  => '200',
            'user_agent'   => 'Symfony',
            'ip_address'   => '127.0.0.1',
            'query_params' => json_encode([])
        ]);
    }

    /** @test */
    public function it_tracks_authenticated_user_with_request()
    {
        // Given
        $user = factory(User::class)->create();
    
        // When
        $this->be($user)->get('/api/analytics');

        // Then
        $this->assertDatabaseHas(ServerAnalytics::getAnalyticsDataTable(), [
            'id'           => 1,
            'user_id'      => $user->id,
            'path'         => '/api/analytics',
            'method'       => 'GET',
            'status_code'  => '200',
            'user_agent'   => 'Symfony',
            'ip_address'   => '127.0.0.1',
            'query_params' => json_encode([])
        ]);
    }
}
