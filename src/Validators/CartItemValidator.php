<?php

namespace S25\PricesApiClient\Validators;

class CartItemValidator extends BaseValidator
{
    public static function validate($item): ?string
    {
        if (!is_array($item)) {
            return 'Пункт не является массивом';
        }

        [$brandSlug, $rawNumber] = $item + [null, null];

        if (!$brandSlug) {
            return 'В пункте не указан брэнд';
        }

        if (!$rawNumber) {
            return 'В пункте не указан номер';
        }

        return ProductRawNumberValidator::validate($rawNumber);
    }
}