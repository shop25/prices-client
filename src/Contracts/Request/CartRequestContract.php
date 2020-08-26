<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface CartRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setCurrencyCode
 *   setSupplierSlugs
 *   setItems или addItem
 *
 * Возвращает:
 *   $result[$supplierSlug][$brandSlug][$rawNumber] = [$price, $piecesPerPack];
 *
 */
interface CartRequestContract extends BaseRequestContract
{
    public function setCurrencyCode(string $currencyCode): self;

    public function setSupplierSlugs(array $supplierSlugs): self;

    public function addSupplierSlug(string $supplierSlug): self;

    /**
     * @param array [$brandSlug, $rawNumber][] $items
     * @return $this
     */
    public function setItems(array $items): self;

    public function addItem(string $brandSlug, string $rawNumber): self;
}