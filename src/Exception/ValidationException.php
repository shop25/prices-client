<?php

namespace S25\PricesApiClient\Exception;

class ValidationException extends \RuntimeException {
    private array $errors;

    public function __construct(string $message, array $errors)
    {
        parent::__construct($message);

        $this->errors = $errors;
    }

    /**
     * @return string[][] - [...'fieldName' => [...'errors']]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}