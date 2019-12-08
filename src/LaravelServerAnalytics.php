<?php

namespace OhSeeSoftware\LaravelServerAnalytics;

class LaravelServerAnalytics
{
    /**
     * Returns the name of the analytics data table.
     *
     * @return string
     */
    public static function getAnalyticsDataTable(): string
    {
        return config('laravel-server-analytics.analytics_data_table');
    }

    /**
     * Returns the name of the analytics relation table.
     *
     * @return string
     */
    public static function getAnalyticsRelationTable(): string
    {
        return config('laravel-server-analytics.analytics_relation_table');
    }
}
