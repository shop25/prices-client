<?php

namespace S25\PricesApiClient\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Large;
use S25\PricesApiClient\Tests\BaseClientTestCase;

#[CoversMethod(\S25\PricesApiClient\Client::class, 'requestPaginateAll')]
#[CoversMethod(\S25\PricesApiClient\Request\PaginateAllRequest::class, 'iterate')]
#[Large]
class LargeClientTest extends BaseClientTestCase
{
    public function testFetchAll(): void
    {
        $paginateAll = $this->client->requestPaginateAll()
            ->addCurrencyCode('JPY')
            ->addBrandSlug('yamaha')
            ->addNoBrand()
            ->setPageSize(2)
            ->setTraceId('abcdefg')
            ->setForwardedFor('127.0.0.1');

        $count = 0;
        foreach ($paginateAll->iterate() as $result) {
            $count++;

            if ($count > 1) {
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
                $this->assertCount(4, $currencyPrices);

                return;
            }
        }

        $this->expectNotToPerformAssertions();
    }
}
