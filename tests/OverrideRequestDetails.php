<?php

namespace OhSeeSoftware\LaravelServerAnalytics\Tests;

use OhSeeSoftware\LaravelServerAnalytics\RequestDetails;

class OverrideRequestDetails extends RequestDetails
{
    public function getMethod(): string
    {
        return 'Test';
    }
}
