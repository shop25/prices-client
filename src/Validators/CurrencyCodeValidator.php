<?php

namespace S25\PricesApiClient\Validators;

class CurrencyCodeValidator extends BaseValidator {
    public static function validate($value): ?string {
        return preg_match('/^[a-z]{3}$/i', $value)
            ? null
            : "Код валюты {$value} не соответствует ISO 4217";
    }
}
