<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\SuppliersRequestContract;

class SuppliersRequest extends BaseRequest implements SuppliersRequestContract
{
    protected function getEndpoint(): string
    {
        return 'suppliers';
    }
}