<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Facades;

use Illuminate\Support\Facades\Facade;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

/**
 * @method static void setRequestDetails(RequestDetails $requestDetails)
 * @method static void addRouteExclusions(array $routes)
 * @method static void addMethodExclusions(array $methods)
 * @method static bool shouldTrackRequest(Request $request)
 * @method static void clearPostHooks()
 * @method static void addRelation(Model $model, ?string $reason = null)
 * @method static void addMeta(string $key, $value)
 * @method static bool inExcludeRoutesArray(Request $request)
 * @method static bool inExcludeMethodsArray(Request $request)
 */
class ServerAnalytics extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-server-analytics';
    }
}
