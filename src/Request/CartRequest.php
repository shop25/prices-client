<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\CartRequestContract;
use S25\PricesApiClient\Validators\CartItemValidator;
use S25\PricesApiClient\Validators\CurrencyCodeValidator;
use S25\PricesApiClient\Validators\ProductRawNumberValidator;

class CartRequest extends BaseRequest implements CartRequestContract
{
    private ?string $currencyCode  = null;
    private array   $supplierSlugs = [];
    private array   $items         = [];

    protected function getEndpoint(): string
    {
        return "/cart-in-{$this->currencyCode}";
    }

    protected function getData(): ?array
    {
        return [
            'suppliers' => $this->supplierSlugs,
            'items' => $this->items,
        ];
    }

    protected function validateSetup(): array
    {
        return array_filter([
            $this->currencyCode === null ? 'Не указан код валюты' : null,
            empty($this->supplierSlugs) ? 'Не указаны слаги поставщиков' : null,
            empty($this->items) ? 'Не указан ни один пункт корзины' : null,
        ]);
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        CurrencyCodeValidator::assert($currencyCode);

        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function setSupplierSlugs(array $supplierSlugs): self
    {
        $this->supplierSlugs = $supplierSlugs;

        return $this;
    }

    public function addSupplierSlug(string $supplierSlug): self
    {
        $this->supplierSlugs[] = $supplierSlug;

        return $this;
    }

    /** @inheritDoc */
    public function setItems(array $items): self
    {
        array_walk($items, [CartItemValidator::class, 'assert']);

        $this->items = array_values($items);

        return $this;
    }

    public function addItem(string $brandSlug, string $rawNumber): self
    {
        ProductRawNumberValidator::assert($rawNumber);

        $this->items[] = [$brandSlug, $rawNumber];

        return $this;
    }
}