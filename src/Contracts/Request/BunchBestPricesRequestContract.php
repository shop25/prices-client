<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface BunchBestPricesRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setBrandSlug
 *   setRawNumbers
 *   setCurrencyCodes
 *
 * Возвращает:
 *   $result[$rawNumber][$currencyCode] = [$price, $piecesPerPack, $supplierSlug]
 *
 */
interface BunchBestPricesRequestContract extends BaseRequestContract
{
    public function setBrandSlug(string $brandSlug): self;

    public function setRawNumbers(array $rawNumbers): self;

    public function setCurrencyCodes(array $currencyCodes): self;
}