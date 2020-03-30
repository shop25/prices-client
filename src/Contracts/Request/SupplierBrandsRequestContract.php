<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface SupplierBrandsRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setSlug
 *
 * Возвращает
 *   $result[] = ['name' => $brandName, 'slug' => $brandSlug]
 */
interface SupplierBrandsRequestContract extends BaseRequestContract
{
    public function setSlug(string $slug): self;
}