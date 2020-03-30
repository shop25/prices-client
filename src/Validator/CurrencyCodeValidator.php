<?php

namespace S25\PricesApiClient\Validator;

trait CurrencyCodeValidator {
    private function validateCurrencyCode(string $currencyCode): ?string {
        return preg_match('/^[a-z]{3}$/i', $currencyCode)
            ? null
            : "Код валюты {$currencyCode} не соответсвует ISO 4217";
    }
}