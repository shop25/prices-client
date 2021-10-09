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

    public function performAsync(): PromiseInterface
    {
        return parent::performAsync()->then(function ($response) {
            $this->startFromId = $response['last_id'];
            return ['result' => $response['result'], 'done' => $this->startFromId === null];
        });
    }
}
