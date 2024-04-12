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
            ->addBrandSlug('yamaha')
            ->setPageSize(1)
            ->setTraceId('abcdefg');

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
            ->addBrandSlug('yamaha')
            ->setPageSize(1)
            ->setTraceId('abcdefg');

        $count = 0;
        foreach ($paginateAll->iterate() as $result) {
            $count++;

            if ($count > 36) {
                $this->assertIsArray($result);
                $this->assertEqualsCanonicalizing(
                    [
                        'brandSlug',
                        'rawNumber',
                        'number',
                        'name',
                        'prices',
                    ],
                    array_keys($result)
                );

                $prices = $result['prices'];

                $this->assertIsArray($prices);
                $this->assertNotEmpty($prices);

                $supplierPrices = reset($prices);

                $this->assertIsArray($supplierPrices);
                $this->assertNotEmpty($supplierPrices);

                $currencyPrices = reset($supplierPrices);

                $this->assertIsArray($currencyPrices);
                $this->assertCount(2, $currencyPrices);

                return;
            }
        }

        $this->expectNotToPerformAssertions();
    }
}
