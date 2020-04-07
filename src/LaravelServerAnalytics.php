<?php

namespace OhSeeSoftware\LaravelServerAnalytics;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;
use Symfony\Component\HttpFoundation\Response;

class LaravelServerAnalytics
{
    /** @var RequestDetails */
    public $requestDetails;

    /** @var array */
    public $excludeRoutes = [];

    /** @var array */
    public $excludeMethods = [];

    /** @var array */
    public $postHooks = [];

    public function __construct()
    {
        $this->setRequestDetails(resolve(RequestDetails::class));
    }

    /**
     * Returns the class which represents users.
     *
     * @return string
     */
    public static function getUserModel(): string
    {
        return config('laravel-server-analytics.user_model');
    }

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

    /**
     * Returns the name of the analytics meta table.
     *
     * @return string
     */
    public static function getAnalyticsMetaTable(): string
    {
        return config('laravel-server-analytics.analytics_meta_table');
    }

    /**
     * Sets the request details class.
     *
     * @param RequestDetails
     * @return void
     */
    public function setRequestDetails(RequestDetails $requestDetails): void
    {
        $this->requestDetails = $requestDetails;
    }

    /**
     * Add routes to exclude from tracking.
     *
     * Routes can use wildcard matching.
     *
     * @param array $routes
     * @return void
     */
    public function addRouteExclusions(array $routes): void
    {
        $this->excludeRoutes = array_merge($this->excludeRoutes, $routes);
    }

    /**
     * Add methods to exclude from tracking.
     *
     * @param array $methods
     * @return void
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
     *
     * @param Request $request
     * @return boolean
     */
    public function shouldTrackRequest(Request $request): bool
    {
        if ($this->inExcludeRoutesArray($request) || $this->inExcludeMethodsArray($request)) {
            return false;
        }

        return true;
    }

    /**
     * Adds a new post hook.
     *
     * @param Closure
     * @return void
     */
    public function addPostHook($callback): void
    {
        $this->postHooks[] = $callback;
    }

    /**
     * Relates the given Model to the current analytics record.
     *
     * @param Model $model
     * @param string|null $reason
     * @return void
     */
    public function addRelation(Model $model, ?string $reason = null): void
    {
        $this->addPostHook(function (RequestDetails $requestDetails, Analytics $analytics) use ($model, $reason) {
            $analytics->addRelation($model, $reason);
        });
    }

    /**
     * Attaches the given meta to the current analytics record.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function addMeta(string $key, $value): void
    {
        $this->addPostHook(function (RequestDetails $requestDetails, Analytics $analytics) use ($key, $value) {
            $analytics->addMeta($key, $value);
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

    /**
     * Logs the request.
     *
     * @param Request $request
     * @param Response $response
     * @return Analytics
     */
    public function logRequest(Request $request, Response $response): Analytics
    {
        $this->requestDetails->setRequest($request);
        $this->requestDetails->setResponse($response);

        $duration = 0;
        if ($request->analyticsRequestStartTime) {
            $duration = round(microtime(true) * 1000) - $request->analyticsRequestStartTime;
        }

        $userId = null;
        if ($user = $request->user()) {
            $userId = $user->id;
        }

        $analytics = Analytics::create([
            'user_id'      => $userId,
            'method'       => $this->requestDetails->getMethod(),
            'path'         => $this->requestDetails->getPath(),
            'status_code'  => $this->requestDetails->getStatusCode(),
            'user_agent'   => $this->requestDetails->getUserAgent(),
            'ip_address'   => $this->requestDetails->getIpAddress(),
            'referrer'     => $this->requestDetails->getReferrer(),
            'query_params' => $this->requestDetails->getQueryParams(),
            'duration_ms'  => $duration
        ]);

        foreach ($this->postHooks as $hook) {
            call_user_func($hook, $this->requestDetails, $analytics);
        }

        return $analytics;
    }
}
