<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\PricesRequestContract;
use S25\PricesApiClient\Validators\CurrencyCodeValidator;

class PricesRequest extends BaseRequest implements PricesRequestContract
{
    private array   $products = [];
    private array   $currencyCodes = [];
    private ?array  $supplierSlugs  = null;

    protected function getMethod(): string
    {
        return 'POST';
    }

    protected function getEndpoint(): string
    {
        return "prices";
    }

    protected function getData(): ?array
    {
        return [
            'products' => $this->products,
            'currencies' => $this->currencyCodes,
            'suppliers' => $this->supplierSlugs,
        ];
    }

    protected function validateSetup(): array
    {
        return array_filter([
            $this->products ? null : 'Не указан ни один товар',
            $this->currencyCodes ? null : 'Не указан ни один код валюты',
        ]);
    }


    public function addProduct(string|array $product): self
    {
        $this->products[] = $product;

        return $this;
    }

    public function addCurrencyCode(string $currencyCode): self
    {
        CurrencyCodeValidator::assert($currencyCode);

        $this->currencyCodes[] = $currencyCode;

        return $this;
    }

    public function addSupplierSlug(string $supplierSlug): self
    {
        $this->supplierSlugs ??= [];
        $this->supplierSlugs[] = $supplierSlug;

        return $this;
    }
}
