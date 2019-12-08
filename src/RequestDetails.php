<?php

namespace OhSeeSoftware\LaravelServerAnalytics;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RequestDetails
{
    /** @var Request */
    private $request;

    /** @var Response */
    private $response;

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Returns the request method.
     *
     * @return string
     */
    public function getMethod(): string
    {
        return strtoupper($this->request->getMethod());
    }

    /**
     * Returns the path of the request.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->request->getPathInfo();
    }

    /**
     * Returns the status code of the response.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->response->getStatusCode();
    }

    /**
     * Returns the user agent of the request.
     *
     * @return string
     */
    public function getUserAgent(): string
    {
        return $this->request->header('User-Agent');
    }

    /**
     * Returns the IP Address of the request.
     *
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->request->ip();
    }

    /**
     * Returns the referrer of the request.
     *
     * @return string
     */
    public function getReferrer(): ?string
    {
        return $this->request->header('referer', null);
    }
}
