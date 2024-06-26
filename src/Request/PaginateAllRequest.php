<?php

namespace S25\PricesApiClient\Request;

use GuzzleHttp\Promise\PromiseInterface;
use S25\PricesApiClient\Contracts\Request\PaginateAllRequestContract;
use S25\PricesApiClient\Validators\CurrencyCodeValidator;

class PaginateAllRequest extends BaseRequest implements PaginateAllRequestContract
{
    private array $currencyCodes = [];
    private ?int $pageSize = null;
    private ?int $startFromId = null;
    private ?array $supplierSlugs = null;
    private ?array $brandSlugs = null;

    protected function getMethod(): string
    {
        return 'POST';
    }

    protected function getEndpoint(): string
    {
        return "paginate-all";
    }

    protected function getData(): ?array
    {
        return [
            'currencies' => $this->currencyCodes,
            'from_id'    => $this->startFromId,
            'count'      => $this->pageSize,
            'suppliers'  => $this->supplierSlugs,
            'brands'     => $this->brandSlugs,
        ];
    }

    protected function validateSetup(): array
    {
        return array_filter(
            [
                $this->currencyCodes ? null : 'Не указаны коды валют',
            ]
        );
    }

    public function setCurrencyCodes(array $currencyCodes): self
    {
        array_walk($currencyCodes, [CurrencyCodeValidator::class, 'assert']);

        $this->currencyCodes = array_values($currencyCodes);

        return $this;
    }

    public function addCurrencyCode(string $currencyCode): self
    {
        CurrencyCodeValidator::assert($currencyCode);

        $this->currencyCodes[] = $currencyCode;

        return $this;
    }

    public function setPageSize(int $pageSize): self
    {
        $this->pageSize = $pageSize;

        return $this;
    }

    public function addSupplierSlug(string $supplierSlug): self
    {
        $this->supplierSlugs ??= [];
        $this->supplierSlugs[] = $supplierSlug;

        return $this;
    }

    public function addBrandSlug(string $brandSlug): self
    {
        $this->brandSlugs ??= [];
        $this->brandSlugs[] = $brandSlug;

        return $this;
    }

    public function addNoBrand(): PaginateAllRequestContract
    {
        return $this->addBrandSlug('');
    }

    public function performAsync(): PromiseInterface
    {
        return parent::performAsync()->then(function ($response) {
            $this->startFromId = $response['last_id'];
            return [
                'result' => $response['result'],
                'done'   => $this->startFromId === null
            ];
        });
    }

    /** @inheritDoc */
    public function iterate(): \Generator
    {
        do {
            ['result' => $result, 'done' => $done] = $this->perform();

            yield from $result;
        } while (!$done);
    }
}
