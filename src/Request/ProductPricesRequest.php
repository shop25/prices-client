<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\ProductPricesRequestContract;
use S25\PricesApiClient\Validators\CurrencyCodeValidator;
use S25\PricesApiClient\Validators\ProductRawNumberValidator;

class ProductPricesRequest extends BaseRequest implements ProductPricesRequestContract
{
    private ?string $brandSlug     = null;
    private ?string $rawNumber     = null;
    private ?string $supplierSlug  = null;
    private array   $currencyCodes = [];

    protected function getEndpoint(): string
    {
        $supplierSlugPart = $this->supplierSlug ? "/{$this->supplierSlug}" : '';

        return "product/{$this->brandSlug}/{$this->rawNumber}/prices{$supplierSlugPart}";
    }

    protected function getData(): ?array
    {
        return [
            'currencies' => $this->currencyCodes,
        ];
    }

    protected function validateSetup(): array
    {
        return array_filter([
            $this->brandSlug ? null : 'Не указан слаг брэнда',
            $this->rawNumber ? null : 'Не указан номер детали',
            $this->currencyCodes ? null : 'Не указаны коды валют',
        ]);
    }

    public function setBrandSlug(string $brandSlug): self
    {
        $this->brandSlug = $brandSlug;

        return $this;
    }

    public function setRawNumber(string $rawNumber): self
    {
        ProductRawNumberValidator::assert($rawNumber);

        $this->rawNumber = $rawNumber;

        return $this;
    }

    public function setSupplierSlug(string $supplierSlug): self
    {
        $this->supplierSlug = $supplierSlug;

        return $this;
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
}