<?php

namespace S25\PricesApiClient\Validators;

class ProductRawNumberValidator extends BaseValidator
{
    public static function validate($number): ?string
    {
        return preg_match('/^[a-z0-9]+$/ui', $number)
            ? null
            : "Номер \"{$number}\" должен содержать хотя бы один символ из диапазона \"A-Z0-9\"";
    }
}