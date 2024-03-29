<?php

namespace OhSeeSoftware\LaravelServerAnalytics;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestDetails
{
    /** @var Request */
    public $request;

    /** @var Response */
    public $response;

    public function setRequest(Request $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
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
     * Returns the host of the request.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->request->getHost();
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
        return $this->request->header('User-Agent', 'unknown');
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

    /**
     * Returns the query parameters for the request.
     *
     * @return array|null
     */
    public function getQueryParams(): ?array
    {
        return $this->request->query();
    }
}
