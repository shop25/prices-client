<?php

namespace S25\PricesApiClient\Request;

use GuzzleHttp\Promise\PromiseInterface;
use S25\PricesApiClient\Contracts\Request\BaseRequestContract;
use S25\PricesApiClient\Exception\RequestSetupException;

abstract class BaseRequest implements BaseRequestContract
{
    private $performCallback;
    private int $timeout = 0;
    private string $traceId = '';

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

    /** @inheritDoc */
    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function setTraceId(string $traceId): self
    {
        $this->traceId = $traceId;

        return $this;
    }

    /** @inheritDoc */
    public function performAsync(): PromiseInterface
    {
        $errors = $this->validateSetup();

        if ($errors) {
            $errorMessage = implode('; ', $errors);

            throw new RequestSetupException($errorMessage);
        }

        return ($this->performCallback)(
            $this->getMethod(),
            $this->getEndpoint(),
            $this->getData(),
            $this->timeout,
            $this->traceId,
        );
    }

    /** @inheritDoc */
    public function perform(): mixed
    {
        return $this->performAsync()->wait(true);
    }
}
