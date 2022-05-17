<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class Analytics extends Model
{
    public function getTable()
    {
        return ServerAnalytics::getAnalyticsDataTable();
    }

    protected $fillable = [
        'user_id', 'path', 'method', 'status_code', 'duration_ms', 'user_agent', 'query_params', 'ip_address', 'host'
    ];

    protected $casts = [
        'query_params' => 'array'
    ];

    /**
     * Adds a new relation to the analytics record.
     *
     * @param Model $entity
     * @param string|null $reason
     * @return AnalyticsRelation
     */
    public function addRelation(Model $entity, ?string $reason = null): AnalyticsRelation
    {
        $relation = new AnalyticsRelation([
            'reason' => $reason
        ]);
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

    /**
     * Scopes the query to include Analytics records
     * that are related to the given Model.
     *
     * @param Builder $builder
     * @param Model $model
     * @return void
     */
    public function scopeRelatedTo(Builder $builder, Model $model)
    {
        $builder->whereHas('relations', function ($query) use ($model) {
            $query->where('relation_type', get_class($model))->where('relation_id', $model->id);
        });
    }

    /**
     * Scopes the query to include Analytics records
     * that have meta data with the given key/value.
     *
     * @param Builder $builder
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function scopeWithMetaValue(Builder $builder, string $key, $value)
    {
        $builder->whereHas('meta', function ($query) use ($key, $value) {
            $query->where('key', $key)->where('value', $value);
        });
    }

    /**
     * Scopes the query to include Analytics records
     * that have meta records with the given key.
     *
     * @param Builder $builder
     * @param string $key
     * @return void
     */
    public function scopeHasMeta(Builder $builder, string $key)
    {
        $builder->whereHas('meta', function ($query) use ($key) {
            $query->where('key', $key);
        });
    }

    public function user()
    {
        return $this->belongsTo(ServerAnalytics::getUserModel());
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
