<?php

namespace S25\PricesApiClient\Contracts\Request;

use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Promise\PromiseInterface;
use S25\PricesApiClient\Exception\RequestSetupException;

interface BaseRequestContract
{
    /**
     * @param int $timeout - Предельное время ответа, 0 - запрос никогда не оборвется по таймауту
     * @return self
     */
    public function setTimeout(int $timeout): self;

    /**
     * @return PromiseInterface
     *
     * @throws ConnectException
     * @throws RequestSetupException
     */
    public function performAsync(): PromiseInterface;

    /**
     * @return mixed
     *
     * @throws ConnectException
     * @throws RequestSetupException
     */
    public function perform(): mixed;
}
