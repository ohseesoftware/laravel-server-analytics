<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \OhSeeSoftware\LaravelServerAnalytics\Skeleton\SkeletonClass
 */
class ServerAnalytics extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-server-analytics';
    }
}
