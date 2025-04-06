<?php

namespace S25\PricesApiClient\Tests\Unit;

use PHPUnit\Framework\Attributes\CoversMethod;
use PHPUnit\Framework\Attributes\Medium;
use S25\PricesApiClient\Tests\BaseClientTestCase;

#[CoversMethod(\S25\PricesApiClient\Client::class, 'requestPaginateAll')]
#[CoversMethod(\S25\PricesApiClient\Request\PaginateAllRequest::class, 'perform')]
#[Medium]
class MediumClientTest extends BaseClientTestCase
{
    public function testRequestPaginateAll(): void
    {
        $paginateAll = $this->client->requestPaginateAll()
            ->addCurrencyCode('JPY')
            ->addBrandSlug('yamaha')
            ->addNoBrand()
            ->setPageSize(1)
            ->setTraceId('abcdefg')
            ->setForwardedFor('127.0.0.1');

        $response = $paginateAll->perform();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('done', $response);
        $this->assertArrayHasKey('result', $response);
        $this->assertIsBool($response['done'] ?? null);
        $this->assertIsArray($response['result'] ?? null);
    }
}
