<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Http\Middleware;

use Closure;
use OhSeeSoftware\LaravelServerAnalytics\Models\Analytics;

class LogRequest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $request->analyticsRequestStartTime = round(microtime(true) * 1000);
        $request->analyticsUser = $request->user();

        return $next($request);
    }

    /**
     * Handle HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Http\Response $response
     */
    public function terminate($request, $response)
    {
        $analytics = Analytics::create([
            'method'      => ucwords($request->getMethod()),
            'path'        => $request->getPathInfo(),
            'status_code' => $response->getStatusCode(),
            'duration_ms' => round(microtime(true) * 1000) - $request->analyticsRequestStartTime,
            'user_agent'  => $request->header('User-Agent'),
            'ip_address'  => $request->ip()
        ]);

        if ($request->analyticsUser) {
            $analytics->addRelation($request->analyticsUser);
        }
    }
}
