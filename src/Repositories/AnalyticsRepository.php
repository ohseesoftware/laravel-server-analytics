<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Repositories;

use Illuminate\Database\Eloquent\Builder;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

class AnalyticsRepository
{
    /**
     * Returns a new Eloquent query builder
     * instance for Analytics records.
     *
     * @return Builder
     */
    public function query(): Builder
    {
        return Analytics::query();
    }

    /**
     * Method to find Analytics records by
     * the given column/value.
     *
     * @param string $column
     * @param mixed $value
     * @return Builder
     */
    public function findBy(string $column, $value, string $comparator = '='): Builder
    {
        return $this->query()->where($column, $comparator, $value);
    }
}
