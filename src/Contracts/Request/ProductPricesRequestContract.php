<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface ProductPricesRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setBrandSlug
 *   setRawNumber
 *   setCurrencyCodes или addCurrencyCode
 *
 * Возвращает
 *   $result[$supplierSlug][$currencyCode] = [$price, $piecesPerPack]
 */
interface ProductPricesRequestContract extends BaseRequestContract
{
    public function setBrandSlug(string $brandSlug): self;

    public function setRawNumber(string $rawNumber): self;

    public function setCurrencyCodes(array $currencyCodes): self;

    public function addCurrencyCode(string $currencyCode): self;

    public function setSupplierSlug(string $supplierSlug): self;
}