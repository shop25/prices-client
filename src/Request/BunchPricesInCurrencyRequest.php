<?php

namespace S25\PricesApiClient\Request;

use GuzzleHttp\Promise\PromiseInterface;
use S25\PricesApiClient\Contracts\Request\BunchPricesInCurrencyRequestContract;
use S25\PricesApiClient\Validator\CurrencyCodeValidator;
use S25\PricesApiClient\Validator\ProductRawNumberValidator;

class BunchPricesInCurrencyRequest extends BaseRequest implements BunchPricesInCurrencyRequestContract
{
    use CurrencyCodeValidator;
    use ProductRawNumberValidator;

    private ?string $brandSlug = null;

    private ?string $currencyCode = null;

    private ?array $rawNumbers = null;

    private ?string $supplierSlug = null;

    private bool $indexByNumber = false;

    protected function getMethod(): string
    {
        return 'POST';
    }

    protected function getEndpoint(): string
    {
        $supplierSlugPart = $this->supplierSlug ? "/{$this->supplierSlug}" : '';

        return "/bunch/{$this->brandSlug}/prices-in-{$this->currencyCode}{$supplierSlugPart}";
    }

    protected function getData(): ?array
    {
        return ['numbers' => $this->rawNumbers];
    }

    public function performAsync(): PromiseInterface
    {
        $promise = parent::performAsync();

        if ($this->indexByNumber) {
            $promise = $promise->then(static function ($response) {
                $result = [];

                foreach ($response as $supplier => $numberPrices) {
                    foreach ($numberPrices as $number => $price) {
                        $result[$number][$supplier] = $price;
                    }
                }

                return $result;
            });
        }

        return $promise;
    }

    protected function validate(): array
    {
        return array_filter(array_merge(
            [
                $this->brandSlug ? null : 'Не указан слаг брэнда',
                $this->validateCurrencyCode($this->currencyCode)
            ],
            $this->rawNumbers
                ? array_map([$this, 'validateProductRawNumber'], $this->rawNumbers)
                : ['Не указан ни один номер детали']
        ));
    }

    public function setBrandSlug(string $brandSlug): self
    {
        $this->brandSlug = $brandSlug;

        return $this;
    }

    public function setCurrencyCode(string $currencyCode): self
    {
        $this->currencyCode = $currencyCode;

        return $this;
    }

    public function setRawNumbers(array $rawNumbers): self
    {
        $this->rawNumbers = $rawNumbers;

        return $this;
    }

    public function setSupplierSlug(string $supplierSlug): self
    {
        $this->supplierSlug = $supplierSlug;

        return $this;
    }

    public function setIndexByNumber(bool $flag): self
    {
        $this->indexByNumber = $flag;

        return $this;
    }
}