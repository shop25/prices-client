<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\BunchBestPricesRequestContract;
use S25\PricesApiClient\Validator\CurrencyCodeValidator;
use S25\PricesApiClient\Validator\ProductRawNumberValidator;

class BunchBestPricesRequest extends BaseRequest implements BunchBestPricesRequestContract
{
    use CurrencyCodeValidator;
    use ProductRawNumberValidator;

    private ?string $brandSlug = null;

    private ?array $rawNumbers = null;

    private ?array $currencyCodes = null;

    protected function getMethod(): string
    {
        return 'POST';
    }

    protected function getEndpoint(): string
    {
        return "/bunch/{$this->brandSlug}/best-prices";
    }

    protected function getData(): ?array
    {
        return [
            'numbers' => $this->rawNumbers,
            'currencies' => $this->currencyCodes,
        ];
    }

    protected function validate(): array
    {
        return array_filter(array_merge(
            $this->brandSlug ? [] : ['Не указан слаг брэнда'],
            $this->rawNumbers
                ? array_map([$this, 'validateProductRawNumber'], $this->rawNumbers)
                : ['Не указан ни один номер детали'],
            $this->currencyCodes
                ? array_map([$this, 'validateCurrencyCode'], $this->currencyCodes)
                : ['Не указаны коды валют'],
        ));
    }

    public function setBrandSlug(string $brandSlug): self
    {
        $this->brandSlug = $brandSlug;

        return $this;
    }

    public function setRawNumbers(array $rawNumbers): self
    {
        $this->rawNumbers = $rawNumbers;

        return $this;
    }

    public function setCurrencyCodes(array $currencyCodes): self
    {
        $this->currencyCodes = $currencyCodes;

        return $this;
    }
}