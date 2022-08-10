<?php

namespace OhSeeSoftware\LaravelServerAnalytics;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Jaybizzle\CrawlerDetect\CrawlerDetect;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use Symfony\Component\HttpFoundation\Response;

class LaravelServerAnalytics
{
    /** @var array */
    public $excludeRoutes = [];

    /** @var array */
    public $excludeMethods = [];

    /** @var array */
    public $metaHooks = [];

    /** @var array */
    public $relationHooks = [];

    /**
     * Returns the class which represents users.
     */
    public static function getUserModel(): string
    {
        return config('laravel-server-analytics.user_model');
    }

    /**
     * Indicates if we should ignore requests from bots.
     */
    public static function shouldIgnoreBotRequests(): bool
    {
        return config('laravel-server-analytics.ignore_bot_requests');
    }


    /**
     * Returns the name of the analytics data table.
     */
    public static function getAnalyticsDataTable(): string
    {
        return config('laravel-server-analytics.analytics_data_table');
    }

    /**
     * Returns the name of the analytics relation table.
     */
    public static function getAnalyticsRelationTable(): string
    {
        return config('laravel-server-analytics.analytics_relation_table');
    }

    /**
     * Returns the name of the analytics meta table.
     */
    public static function getAnalyticsMetaTable(): string
    {
        return config('laravel-server-analytics.analytics_meta_table');
    }

    /**
     * Returns the FQN of the RequestDetails class to user.
     */
    public static function getRequestDetailsClass(): string
    {
        return config('laravel-server-analytics.request_details_class');
    }

    public static function getQueueConnection(): string|null
    {
        return config('laravel-server-analytics.queue_connection', null);
    }

    /**
     * Add routes to exclude from tracking.
     *
     * Routes can use wildcard matching.
     */
    public function addRouteExclusions(array $routes): void
    {
        $this->excludeRoutes = array_merge($this->excludeRoutes, $routes);
    }

    /**
     * Add methods to exclude from tracking.
     */
    public function addMethodExclusions(array $methods): void
    {
        $methods = array_map(function ($method) {
            return strtoupper($method);
        }, $methods);

        $this->excludeMethods = array_merge($this->excludeMethods, $methods);
    }

    /**
     * Determine if the request should be tracked.
     */
    public function shouldTrackRequest(Request $request): bool
    {
        if ($this->inExcludeRoutesArray($request) || $this->inExcludeMethodsArray($request)) {
            return false;
        }

        if (static::shouldIgnoreBotRequests() && (new CrawlerDetect())->isCrawler($request->userAgent())) {
            return false;
        }

        return true;
    }

    /**
     * Add a hook for storing meta data with the Analytics record.
     *
     * The hook should return an array with `key` and `value` keys.
     */
    public function addMetaHook($callback): void
    {
        $this->metaHooks[] = $callback;
    }

    public function getMetaHooks(): array
    {
        return $this->metaHooks;
    }

    public function runMetaHooks(RequestDetails $requestDetails): array
    {
        return collect($this->metaHooks)
            ->map(function ($callback) use ($requestDetails) {
                return $callback($requestDetails);
            })
            ->toArray();
    }

    /**
     * Add a hook for storing meta data with the Analytics record.
     *
     * The hook should return an array with `model` and `reason` keys.
     */
    public function addRelationHook($callback): void
    {
        $this->relationHooks[] = $callback;
    }

    public function getRelationHooks(): array
    {
        return $this->relationHooks;
    }

    public function runRelationHooks(RequestDetails $requestDetails): array
    {
        return collect($this->relationHooks)
            ->map(function ($callback) use ($requestDetails) {
                return $callback($requestDetails);
            })
            ->toArray();
    }

    public function addRelation(Model $model, ?string $reason = null): void
    {
        $this->addRelationHook(function (RequestDetails $requestDetails) use ($model, $reason) {
            return [
                'model' => $model,
                'reason' => $reason,
            ];
        });
    }

    /**
     * Attaches the given meta to the current analytics record.
     */
    public function addMeta(string $key, $value): void
    {
        $this->addMetaHook(function (RequestDetails $requestDetails) use ($key, $value) {
            return [
                'key' => $key,
                'value' => $value,
            ];
        });
    }

    /**
     * Determine if the request should be excluded.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function inExcludeRoutesArray(Request $request): bool
    {
        foreach ($this->excludeRoutes as $route) {
            if ($route !== '/') {
                $route = trim($route, '/');
            }

            if ($request->fullUrlIs($route) || $request->is($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the request should be excluded.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function inExcludeMethodsArray(Request $request): bool
    {
        $method = strtoupper($request->method());
        return in_array($method, $this->excludeMethods);
    }
}
