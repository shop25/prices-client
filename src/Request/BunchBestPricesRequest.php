<?php

namespace S25\PricesApiClient\Request;

use S25\PricesApiClient\Contracts\Request\BunchBestPricesRequestContract;
use S25\PricesApiClient\Validators\CurrencyCodeValidator;
use S25\PricesApiClient\Validators\ProductRawNumberValidator;

class BunchBestPricesRequest extends BaseRequest implements BunchBestPricesRequestContract
{
    private ?string $brandSlug     = null;
    private array   $rawNumbers    = [];
    private array   $currencyCodes = [];

    protected function getMethod(): string
    {
        return 'POST';
    }

    protected function getEndpoint(): string
    {
        return "bunch/{$this->brandSlug}/best-prices";
    }

    protected function getData(): ?array
    {
        return [
            'numbers' => $this->rawNumbers,
            'currencies' => $this->currencyCodes,
        ];
    }

    protected function validateSetup(): array
    {
        return array_filter([
            $this->brandSlug ? null : 'Не указан слаг брэнда',
            $this->rawNumbers ? null : 'Не указан ни один номер детали',
            $this->currencyCodes ? null : 'Не указаны коды валют',
        ]);
    }

    public function setBrandSlug(string $brandSlug): self
    {
        $this->brandSlug = $brandSlug;

        return $this;
    }

    public function setRawNumbers(array $rawNumbers): self
    {
        array_walk($rawNumbers, [ProductRawNumberValidator::class, 'assert']);

        $this->rawNumbers = array_values($rawNumbers);

        return $this;
    }

    public function addRawNumber(string $rawNumber): self
    {
        ProductRawNumberValidator::validate($rawNumber);

        $this->rawNumbers[] = $rawNumber;

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