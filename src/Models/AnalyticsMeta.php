<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Models;

use Illuminate\Database\Eloquent\Model;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

class AnalyticsMeta extends Model
{
    public $timestamps = false;

    protected $fillable = ['key', 'value'];

    public function getTable()
    {
        return ServerAnalytics::getAnalyticsMetaTable();
    }

    public function analytics()
    {
        return $this->belongsTo(Analytics::class);
    }
}
