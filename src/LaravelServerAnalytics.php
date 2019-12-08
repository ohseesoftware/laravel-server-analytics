<?php

namespace OhSeeSoftware\LaravelServerAnalytics;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

class LaravelServerAnalytics
{
    /** @var RequestDetails */
    public $requestDetails;

    /** @var array */
    public $exceptRoutes = [];

    /** @var array */
    public $exceptMethods = [];

    /** @var array */
    public $postHooks = [];

    public function __construct()
    {
        $this->setRequestDetails(resolve(RequestDetails::class));
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
     * Sets the request details class.
     *
     * @param RequestDetails
     * @return void
     */
    public function setRequestDetails(RequestDetails $requestDetails)
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
    public function addRouteExceptions(array $routes)
    {
        $this->exceptRoutes = array_merge($this->exceptRoutes, $routes);
    }

    /**
     * Add methods to exclude from tracking.
     *
     * @param array $methods
     * @return void
     */
    public function addMethodExceptions(array $methods)
    {
        $methods = array_map(function ($method) {
            return strtoupper($method);
        }, $methods);

        $this->exceptMethods = array_merge($this->exceptMethods, $methods);
    }

    /**
     * Determine if the request should be tracked.
     *
     * @param Request $request
     * @return boolean
     */
    public function shouldTrackRequest(Request $request)
    {
        if ($this->inExceptRoutesArray($request) || $this->inExceptMethodsArray($request)) {
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
    public function addPostHook($callback)
    {
        $this->postHooks[] = $callback;
    }

    /**
     * Determine if the request should be excluded.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function inExceptRoutesArray(Request $request)
    {
        foreach ($this->exceptRoutes as $except) {
            if ($except !== '/') {
                $except = trim($except, '/');
            }

            if ($request->fullUrlIs($except) || $request->is($except)) {
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
    public function inExceptMethodsArray(Request $request)
    {
        $method = strtoupper($request->method());
        return in_array($method, $this->exceptMethods);
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

        $analytics = Analytics::create([
            'method'      => $this->requestDetails->getMethod(),
            'path'        => $this->requestDetails->getPath(),
            'status_code' => $this->requestDetails->getStatusCode(),
            'user_agent'  => $this->requestDetails->getUserAgent(),
            'ip_address'  => $this->requestDetails->getIpAddress(),
            'referrer'    => $this->requestDetails->getReferrer(),
            'duration_ms' => round(microtime(true) * 1000) - $request->analyticsRequestStartTime,
        ]);
        return $analytics;
    }

    /**
     * Runs the post hooks.
     *
     * @param Request $request
     * @param Analytics $analytics
     * @return void
     */
    public function runPostHooks(Request $request, Analytics $analytics)
    {
        foreach ($this->postHooks as $hook) {
            call_user_func($hook, $request, $analytics);
        }
    }
}
