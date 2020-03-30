<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\ProductPricesRequestContract;
use S25\PricesApiClient\Validator\CurrencyCodeValidator;
use S25\PricesApiClient\Validator\ProductRawNumberValidator;

class ProductPricesRequest extends BaseRequest implements ProductPricesRequestContract
{
    use CurrencyCodeValidator;
    use ProductRawNumberValidator;

    private ?string $brandSlug = null;

    private ?string $rawNumber = null;

    private ?string $supplierSlug = null;

    private ?array $currencyCodes = null;

    protected function getEndpoint(): string
    {
        $supplierSlugPart = $this->supplierSlug ? "/{$this->supplierSlug}" : '';

        return "/product/{$this->brandSlug}/{$this->rawNumber}/prices{$supplierSlugPart}";
    }

    protected function getData(): ?array
    {
        return [
            'currencies' => $this->currencyCodes,
        ];
    }

    protected function validate(): array
    {
        return array_filter(array_merge(
            [
                $this->brandSlug ? null : 'Не указан слаг брэнда',
                $this->rawNumber
                    ? $this->validateProductRawNumber($this->rawNumber)
                    : 'Не указан номер детали',
            ],
            $this->currencyCodes
                ? array_map([$this, 'validateCurrencyCode'], $this->currencyCodes)
                : ['Не указаны коды валют'],
        ));
    }

    public function setBrandSlug($brandSlug): self
    {
        $this->brandSlug = $brandSlug;

        return $this;
    }

    public function setRawNumber($rawNumber): self
    {
        $this->rawNumber = $rawNumber;

        return $this;
    }

    public function setSupplierSlug($supplierSlug): self
    {
        $this->supplierSlug = $supplierSlug;

        return $this;
    }

    public function setCurrencyCodes($currencyCodes): self
    {
        $this->currencyCodes = $currencyCodes;

        return $this;
    }
}