<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface BunchPricesRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setBrandSlug
 *   setRawNumbers или addRawNumber
 *   setCurrencyCodes или addCurrencyCode
 *
 * Возвращает:
 *   $result[$supplierSlug][$rawNumber][$currencyCode] = [$price, $piecesPerPack]
 *
 */
interface BunchPricesRequestContract extends BaseRequestContract
{
    public function setBrandSlug(string $brandSlug): self;

    public function setRawNumbers(array $rawNumbers): self;

    public function addRawNumber(string $rawNumber): self;

    public function setCurrencyCodes(array $currencyCodes): self;

    public function addCurrencyCode(string $currencyCode): self;

    public function setSupplierSlug(string $supplierSlug): self;
}