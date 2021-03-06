<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Http\Middleware;

use Closure;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;

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
        if (!ServerAnalytics::shouldTrackRequest($request)) {
            return;
        }

        ServerAnalytics::logRequest($request, $response);
    }
}
