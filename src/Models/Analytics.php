<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalytics;

class Analytics extends Model
{
    public function getTable()
    {
        return LaravelServerAnalytics::getAnalyticsDataTable();
    }

    protected $fillable = ['path', 'method', 'status_code', 'duration_ms', 'user_agent', 'query_params', 'ip_address'];

    /**
     * Adds a new relation to the analytics record.
     *
     * @param Model $entity
     * @return AnalyticsRelation
     */
    public function addRelation(Model $entity): AnalyticsRelation
    {
        $relation = new AnalyticsRelation;
        $relation->relation()->associate($entity);
        $this->relations()->save($relation);
        return $relation;
    }

    public function relations()
    {
        return $this->hasMany(AnalyticsRelation::class);
    }
}
