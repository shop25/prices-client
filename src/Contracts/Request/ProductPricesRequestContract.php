<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface ProductPricesRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setBrandSlug
 *   setRawNumber
 *   setCurrencyCodes
 *
 * Возвращает
 *   $result[$supplierSlug][$currencyCode] = [$price, $piecesPerPack]
 */
interface ProductPricesRequestContract extends BaseRequestContract
{
    public function setBrandSlug($brandSlug): self;

    public function setRawNumber($rawNumber): self;

    public function setCurrencyCodes($currencyCodes): self;

    public function setSupplierSlug($supplierSlug): self;
}