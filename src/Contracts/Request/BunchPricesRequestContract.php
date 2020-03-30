<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface BunchPricesRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setBrandSlug
 *   setRawNumbers
 *   setCurrencyCodes
 *
 * Возвращает:
 *   $result[$supplierSlug][$rawNumber][$currencyCode] = $price
 *
 */
interface BunchPricesRequestContract extends BaseRequestContract
{
    public function setBrandSlug(string $brandSlug): self;

    public function setRawNumbers(array $rawNumbers): self;

    public function setCurrencyCodes(array $currencyCodes): self;

    public function setSupplierSlug(string $supplierSlug): self;
}