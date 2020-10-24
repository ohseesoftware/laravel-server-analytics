<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DateInterval;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

trait HasAnalytics
{
    public function analytics()
    {
        return $this->morphToMany(Analytics::class, 'relation', 'analytics_relations');
    }

    public function analyticsInPastHour()
    {
        return $this->analyticsInPast(CarbonInterval::hour(1));
    }

    public function analyticsInPastDay()
    {
        return $this->analyticsInPast(CarbonInterval::days(1));
    }

    public function analyticsInPastMonth()
    {
        return $this->analyticsInPast(CarbonInterval::months(1));
    }

    public function analyticsInPast(DateInterval $interval)
    {
        $date = Carbon::now()->sub($interval);
        return $this->analytics->where('created_at', '>=', $date);
    }
}
