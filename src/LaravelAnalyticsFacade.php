<?php

namespace Ohseesoftware\LaravelAnalytics;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ohseesoftware\LaravelAnalytics\Skeleton\SkeletonClass
 */
class LaravelAnalyticsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-analytics';
    }
}
