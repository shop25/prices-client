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
     * @covers \S25\PricesApiClient\Request\PaginateAllRequest::perform
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

    /**
     * @covers \S25\PricesApiClient\Client::requestPaginateAll
     * @covers \S25\PricesApiClient\Request\PaginateAllRequest::iterate
     */
    public function testFetchAll()
    {
        $paginateAll = $this->client->requestPaginateAll()
            ->addCurrencyCode('JPY')
            ->setPageSize(16)
        ;

        $count = 0;
        foreach ($paginateAll->iterate() as $result) {
            $count++;

            if ($count > 36) {
                $this->assertIsArray($result);
                $this->assertCount(3, $result);
                $this->assertEquals([0, 1, 2], array_keys($result));
                return;
            }
        }

        $this->expectNotToPerformAssertions();
    }
}
