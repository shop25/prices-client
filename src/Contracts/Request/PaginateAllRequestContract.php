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
 *      "result" => [
 *          'brandSlug' => "Брэнд",
 *          'rawNumber' => "Номер без форматирования",
 *          'number' => "Номер с форматированием",
 *          'name' => "Название товара",
 *          'prices' => [$supplierSlug => [$currencyCode => [float $price, int $piecesPerPack]]],
 *      ][],
 *      "done" => bool, // Признак завершения пагинации,
 *                      // false - еще остались товары, true - все товары получены, указатель вернулся в начало
 *   ]
 */
interface PaginateAllRequestContract extends BaseRequestContract
{
    public function setCurrencyCodes(array $currencyCodes): self;

    public function addCurrencyCode(string $currencyCode): self;

    public function addSupplierSlug(string $supplierSlug): self;

    public function setPageSize(int $pageSize): self;

    /**
     * @experimental
     * Возвращает генератор, отдающий элементы "result" из perform* по одному.
     *
     * @return \Generator
     */
    public function iterate(): \Generator;
}
