<?php

namespace S25\PricesApiClient\Request;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\PromiseInterface;
use S25\PricesApiClient\Contracts\Request\BaseRequestContract;
use S25\PricesApiClient\Exception\RequestSetupException;

abstract class BaseRequest implements BaseRequestContract
{
    private $performCallback;

    public function __construct(callable $performCallback)
    {
        $this->performCallback = $performCallback;
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    abstract protected function getEndpoint(): string;

    protected function getData(): ?array
    {
        return null;
    }

    protected function validateSetup(): array
    {
        return [];
    }

    /**
     * @return PromiseInterface
     *
     * @throws ConnectException
     * @throws RequestSetupException
     */
    public function performAsync(): PromiseInterface
    {
        $errors = $this->validateSetup();

        if ($errors) {
            $errorMessage = implode('; ', $errors);

            throw new RequestSetupException($errorMessage);
        }

        return ($this->performCallback)($this->getMethod(), $this->getEndpoint(), $this->getData());
    }

    /**
     * @return mixed
     *
     * @throws ConnectException
     * @throws RequestSetupException
     */
    public function perform()
    {
        $promise = $this->performAsync();

        return $promise->wait(true);
    }
}