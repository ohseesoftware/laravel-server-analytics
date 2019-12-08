<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalytics;

class AnalyticsRelation extends Model
{
    public $timestamps = false;

    public function getTable()
    {
        return LaravelServerAnalytics::getAnalyticsRelationTable();
    }

    public function relation()
    {
        return $this->morphTo('relation');
    }
}
