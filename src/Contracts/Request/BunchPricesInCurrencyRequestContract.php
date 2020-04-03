<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface BunchPricesInCurrencyRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setBrandSlug
 *   setCurrencyCode
 *   setRawNumbers
 *
 * При setIndexByNumber(false) (по-умолчанию)
 * возвращает:
 *   $result[$supplierSlug][$rawNumber] = [$price, $piecesPerPack]
 *
 * При setIndexByNumber(true)
 * возвращает:
 *   $result[$rawNumber][$supplierSlug] = [$price, $piecesPerPack]
 *
 */
interface BunchPricesInCurrencyRequestContract extends BaseRequestContract
{
    public function setBrandSlug(string $brandSlug): self;

    public function setCurrencyCode(string $currencyCode): self;

    public function setRawNumbers(array $rawNumbers): self;

    public function setSupplierSlug(string $supplierSlug): self;

    public function setIndexByNumber(bool $flag): self;
}