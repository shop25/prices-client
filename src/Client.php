<?php

namespace S25\PricesApiClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as HttpRequest;
use GuzzleHttp\Psr7\Response as HttpResponse;
use GuzzleHttp\RequestOptions;
use S25\PricesApiClient\Exception\ValidationException;

class Client implements Contracts\Client
{
    private \Closure $performCallback;

    public function __construct(
        string $serviceUrl,
        string $apiKey,
    ) {
        $serviceUrl = rtrim($serviceUrl, '/');

        $handler = HandlerStack::create();
        $handler->push(
            Middleware::retry(\Closure::fromCallable([$this, 'retryDecider']))
        );

        $guzzle = new GuzzleClient([
            'base_uri'              => "{$serviceUrl}/api/v2/",
            'handler'               => $handler,
            RequestOptions::HEADERS => [
                'Authorization'    => "Bearer {$apiKey}",
                'X-Requested-With' => 'XMLHttpRequest',
            ],
            RequestOptions::TIMEOUT => 1,
        ]);

        $this->performCallback = function (
            string $method,
            string $endpoint,
            $data,
            $timeout = 0,
            $traceId = '',
            $forwardedFor = '',
        ) use ($guzzle) {
            $options = [
                RequestOptions::JSON => $data,
                RequestOptions::TIMEOUT => $timeout,
            ];

            if ($traceId) {
                $options[RequestOptions::HEADERS]['X-Trace-Id'] = $traceId;
            }

            if ($forwardedFor) {
                $options[RequestOptions::HEADERS]['X-Forwarded-For'] = $forwardedFor;
            }

            return $guzzle->requestAsync($method, $endpoint, $options)
                ->then(
                    \Closure::fromCallable([$this, 'getJson']),
                    \Closure::fromCallable([$this, 'handleValidationError'])
                );
        };
    }

    public function requestSuppliers(): Contracts\Request\SuppliersRequestContract
    {
        return new Request\SuppliersRequest($this->performCallback);
    }

    public function requestPrices(): Contracts\Request\PricesRequestContract
    {
        return new Request\PricesRequest($this->performCallback);
    }

    public function requestPaginateAll(): Contracts\Request\PaginateAllRequestContract
    {
        return new Request\PaginateAllRequest($this->performCallback);
    }

    /**
     * @throws \JsonException
     */
    private function getJson(HttpResponse $response)
    {
        $body = $response->getBody();

        if (!$body) {
            throw new \RuntimeException("Couldn't obtain the response body");
        }

        return json_decode($body->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws \JsonException
     */
    private function getErrors(HttpResponse $response): array
    {
        $errors = $this->getJson($response)['errors'] ?? null;

        return is_array($errors) ? $errors : [];
    }

    /**
     * @throws \JsonException
     */
    private function handleValidationError($exception): void
    {
        if ($exception instanceof RequestException === false) {
            throw $exception;
        }

        $response = $exception->getResponse();

        if (!$response || $response->getStatusCode() !== 422) {
            throw $exception;
        }

        $request = $exception->getRequest();

        throw new ValidationException(
            "В запросе {$request->getMethod()} {$request->getUri()} переданы некорректные данные",
            $this->getErrors($response)
        );
    }

    private function retryDecider(
        $retries,
        HttpRequest $request,
        HttpResponse $response = null,
        TransferException $exception = null
    ): bool {
        // Limit the number of retries to 5
        if ($retries >= 5) {
            return false;
        }

        // Retry connection exceptions
        return $exception instanceof ConnectException;
    }
}
