<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\SupplierBrandsRequestContract;

class SupplierBrandsRequest extends BaseRequest implements SupplierBrandsRequestContract
{
    private ?string $slug = null;

    protected function getEndpoint(): string
    {
        return "supplier/{$this->slug}/brands";
    }

    protected function validateSetup(): array
    {
        if (!$this->slug) {
            return ['Не указан слаг поставщика'];
        }

        return [];
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }
}