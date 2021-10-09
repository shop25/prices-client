<?php

namespace S25\PricesApiClient\Contracts\Request;

/**
 * Interface PaginateAllRequestContract
 * @package S25\PricesApiClient\Contracts\Request
 *
 * Обязательные перед выполнением perform* методы:
 *   setCurrencyCodes или addCurrencyCode
 *
 * Установка размера страницы через setPageSize не гарантирует,
 * что в полученном результате будет точно указанное кол-во товаров, или они будут вообще.
 * НО(!) пустой результат НЕ означает завершения итерации, для этого есть признак "done"!
 *
 * perform* возвращают:
 *   [
 *      "result" => [$brandSlug => [$rawNumber => [$supplierSlug => [$currencyCode => [
 *          float $price,
 *          int $piecesPerPack
 *      ]]]]],
 *      "done" => bool, // Признак завершения пагинации,
 *                      // false - еще остались товары, true - все товары получены, указатель вернулся в начало
 *   ]
 */
interface PaginateAllRequestContract extends BaseRequestContract
{
    public function setCurrencyCodes(array $currencyCodes): self;

    public function addCurrencyCode(string $currencyCode): self;

    public function setPageSize(int $pageSize): self;
}
