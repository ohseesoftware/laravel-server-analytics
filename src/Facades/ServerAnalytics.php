<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Facades;

use Illuminate\Support\Facades\Facade;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

/**
 * @method void setRequestDetails(RequestDetails $requestDetails)
 * @method void addRouteExclusions(array $routes)
 * @method void addMethodExclusions(array $methods)
 * @method bool shouldTrackRequest(Request $request)
 * @method void addPostHook($callback)
 * @method void clearPostHooks()
 * @method void addRelation(Model $model, ?string $reason = null)
 * @method void addMeta(string $key, $value)
 * @method bool inExcludeRoutesArray(Request $request)
 * @method bool inExcludeMethodsArray(Request $request)
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
