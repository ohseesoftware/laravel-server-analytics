<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Http\Middleware;

use Closure;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalyticsFacade;

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
        if (!LaravelServerAnalyticsFacade::shouldTrackRequest($request)) {
            return;
        }

        $analytics = LaravelServerAnalyticsFacade::logRequest($request, $response);

        LaravelServerAnalyticsFacade::runPostHooks($request, $analytics);
    }
}
