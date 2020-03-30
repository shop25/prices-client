<?php

namespace S25\PricesApiClient\Request;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
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

    protected function validate(): array
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
        $errors = $this->validate();

        if ($errors) {
            $errorMessage = implode('; ', $errors);

            throw new RequestSetupException($errorMessage);
        }

        /** @var PromiseInterface $promise */
        $promise = ($this->performCallback)($this->getMethod(), $this->getEndpoint(), $this->getData());

        return $promise->then(static function (Response $response) {
            $contents = $response->getBody()->getContents();

            return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        });
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