<?php

use S25\PricesApiClient\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    protected function setUp(): void
    {
        $this->client = new Client(
            $_ENV['PRICES_SERVICE_URL'],
            $_ENV['PRICES_SERVICE_APIKEY'],
        );
    }

    /**
     * @covers \S25\PricesApiClient\Client::requestPaginateAll
     */
    public function testRequestPaginateAll()
    {
        $paginateAll = $this->client->requestPaginateAll()
            ->addCurrencyCode('JPY')
            ->setPageSize(16)
        ;

        $response = $paginateAll->perform();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('done', $response);
        $this->assertArrayHasKey('result', $response);
        $this->assertIsBool($response['done'] ?? null);
        $this->assertIsArray($response['result'] ?? null);
    }
}
