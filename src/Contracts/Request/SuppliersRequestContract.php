<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface SuppliersRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Возвращает
 *   $result[] = ['name' => $supplierName, 'slug' => $supplierSlug, 'countryCode' => $countryCode]
 */
interface SuppliersRequestContract extends BaseRequestContract {}