<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests\Fixtures;

use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use OhSeeSoftware\LaravelServerAnalytics\Traits\HasAnalytics;

class HasAnalyticsModel extends Analytics {
    use HasAnalytics;

    public $table = 'analytics';
}