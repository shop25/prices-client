<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface PricesRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Перед выполнением perform* методов необходимо указать хотя бы один продукт и хотя бы одну валюту:
 *   addProduct
 *   addCurrencyCode
 * Если ни один поставщик не указан, цены рассчитаются по всем доступным поставщикам.
 * Если указан один и более поставщик, то цены будут рассчитаны только для этих поставщиков.
 *
 * Возвращает:
 *   [][
 *     [string $brandSlug, string $rawNumber] | string $guid,
 *     [string $supplierSlug][string(3) $currencyCode][float $price, int $piecesPerPack],
 *   ]
 */
interface PricesRequestContract extends BaseRequestContract
{
    public function addProduct(string|array $product): self;

    public function addCurrencyCode(string $currencyCode): self;

    public function addSupplierSlug(string $supplierSlug): self;
}
