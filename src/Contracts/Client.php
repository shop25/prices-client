<?php

namespace S25\PricesApiClient\Contracts;

interface Client
{
    public function requestSuppliers(): Request\SuppliersRequestContract;

    public function requestSupplierBrands(): Request\SupplierBrandsRequestContract;

    public function requestProductPrices(): Request\ProductPricesRequestContract;

    public function requestBunchPrices(): Request\BunchPricesRequestContract;

    public function requestBunchPricesInCurrency(): Request\BunchPricesInCurrencyRequestContract;

    public function requestBunchBestPrices(): Request\BunchBestPricesRequestContract;

    public function requestCart(): Request\CartRequestContract;
}