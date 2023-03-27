<?php

namespace App\Services\API;

use GuzzleHttp\Client as GuzzleHttpClient;

abstract class AbstractAPIClient
{
    protected GuzzleHttpClient $httpClient;

    public function __construct()
    {
        $this->httpClient = new GuzzleHttpClient();
    }
}
