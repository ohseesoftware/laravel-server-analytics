<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests\Fixtures;

use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

class RequestDetailsOverride extends RequestDetails
{
    public function getMethod(): string
    {
        return 'Test';
    }
}
