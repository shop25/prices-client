<?php

namespace S25\PricesApiClient\Contracts;

interface Client
{
    public function requestSuppliers(): Request\SuppliersRequestContract;

    public function requestPrices(): Request\PricesRequestContract;

    public function requestPaginateAll(): Request\PaginateAllRequestContract;
}
