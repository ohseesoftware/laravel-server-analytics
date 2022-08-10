<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;
use OhSeeSoftware\LaravelServerAnalytics\Exceptions\MissingMetaKeyException;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

class LogRequestRecord implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $tries = 5;

    public $backoff = 10;

    public function __construct(public array $requestData)
    {
    }

    public function handle()
    {
        $analytics = Analytics::create($this->requestData);

        collect($this->requestData['meta'] ?? [])
            ->each(function ($meta) use ($analytics) {
                $key = $meta['key'] ?? null;
                if (!$key) {
                    throw new MissingMetaKeyException('Missing meta `key`!');
                }
                $analytics->addMeta($key, $meta['value'] ?? null);
            });

        collect($this->requestData['relations'] ?? [])
            ->each(function ($relation) use ($analytics) {
                $model = $relation['model'] ?? null;
                if (!$model) {
                    throw new MissingMetaKeyException('Missing relation `model`!');
                }
                $analytics->addRelation($model, $relation['reason'] ?? null);
            });
    }
}
