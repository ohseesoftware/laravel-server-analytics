<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsServiceProvider;

class TestServiceProvider extends LaravelServerAnalyticsServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        parent::boot();

        $this->loadRoutesFrom(__DIR__ . '/routes/test-routes.php');
    }
}
