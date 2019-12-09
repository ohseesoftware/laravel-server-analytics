<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use OhSeeSoftware\LaravelServerAnalytics\ServerAnalytics;

class Analytics extends Model
{
    public function getTable()
    {
        return ServerAnalytics::getAnalyticsDataTable();
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

    /**
     * Adds new meta to the analytics record.
     *
     * @param string $key
     * @param mixed $value
     * @return AnalyticsMeta
     */
    public function addMeta(string $key, $value): AnalyticsMeta
    {
        $meta = new AnalyticsMeta([
            'key'   => $key,
            'value' => $value
        ]);
        $this->meta()->save($meta);
        return $meta;
    }

    public function relations()
    {
        return $this->hasMany(AnalyticsRelation::class);
    }

    public function meta()
    {
        return $this->hasMany(AnalyticsMeta::class);
    }
}
