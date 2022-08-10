<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use OhSeeSoftware\LaravelServerAnalytics\Facades\ServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\Jobs\LogRequestRecord;
use OhSeeSoftware\LaravelServerAnalytics\LaravelServerAnalytics;
use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

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

        $requestData = $this->getRequestData($request, $response);

        $queueConnection = ServerAnalytics::getQueueConnection();
        if ($queueConnection) {
            LogRequestRecord::dispatch($requestData)->onQueue($queueConnection);
        } else {
            LogRequestRecord::dispatchSync($requestData);
        }
    }

    private function getRequestData($request, $response): array
    {
        /** @var RequestDetails $requestDetails */
        $requestDetails = resolve(ServerAnalytics::getRequestDetailsClass());
        $requestDetails->setRequest($request);
        $requestDetails->setResponse($response);

        $duration = 0;
        if ($request->analyticsRequestStartTime) {
            $duration = round(microtime(true) * 1000) - $request->analyticsRequestStartTime;
        }

        return [
            'user_id'      => $request->user()?->id ?? null,
            'method'       => $requestDetails->getMethod(),
            'host'         => $requestDetails->getHost(),
            'path'         => $requestDetails->getPath(),
            'status_code'  => $requestDetails->getStatusCode(),
            'user_agent'   => $requestDetails->getUserAgent(),
            'ip_address'   => $requestDetails->getIpAddress(),
            'referrer'     => $requestDetails->getReferrer(),
            'query_params' => $requestDetails->getQueryParams(),
            'duration_ms'  => $duration,
            'meta'         => ServerAnalytics::runMetaHooks($requestDetails),
            'relations'    => ServerAnalytics::runRelationHooks($requestDetails)
        ];
    }
}
