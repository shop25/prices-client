<?php

namespace S25\PricesApiClient\Validators;

class BaseValidator {
    public static function validate($value): ?string {
        return null;
    }

    public static function assert($value): void {
        $error = static::validate($value);

        if ($error !== null) {
            throw new \InvalidArgumentException($error);
        }
    }
}