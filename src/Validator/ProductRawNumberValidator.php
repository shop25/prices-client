<?php

namespace S25\PricesApiClient\Validator;

trait ProductRawNumberValidator
{
    private function validateProductRawNumber($number): ?string
    {
        return preg_match('/^[a-z0-9]+$/ui', $number)
            ? null
            : "Номер \"{$number}\" должен содержать хотя бы один символ из диапазона \"A-Z0-9\"";
    }
}