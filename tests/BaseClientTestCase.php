<?php

namespace S25\PricesApiClient\Tests;

use PHPUnit\Framework\TestCase;
use S25\PricesApiClient\Client;

abstract class BaseClientTestCase extends TestCase
{
    protected Client $client;

    protected function setUp(): void
    {
        $this->client = new Client(
            $_ENV['PRICES_SERVICE_URL'],
            $_ENV['PRICES_SERVICE_APIKEY'],
        );
    }
}
