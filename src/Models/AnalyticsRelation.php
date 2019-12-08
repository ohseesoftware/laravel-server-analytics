<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsFacade;

class AnalyticsRelation extends Model
{
    public $timestamps = false;

    public function getTable()
    {
        return LaravelServerAnalyticsFacade::getAnalyticsRelationTable();
    }

    public function relation()
    {
        return $this->morphTo('relation');
    }
}
