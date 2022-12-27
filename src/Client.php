<?php

namespace S25\PricesApiClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;
use S25\PricesApiClient\Exception\ValidationException;

class Client implements Contracts\Client
{
    private \Closure $performCallback;

    public function __construct(string $serviceUrl, string $apiKey)
    {
        $serviceUrl = rtrim($serviceUrl, '/');

        $guzzle = new GuzzleClient([
            'base_uri' => "{$serviceUrl}/api/v2/",
            'headers'  => [
                'Authorization'    => "Bearer {$apiKey}",
                'X-Requested-With' => 'XMLHttpRequest',
            ],
        ]);

        $this->performCallback = function (string $method, string $endpoint, $data) use ($guzzle) {
            return $guzzle->requestAsync($method, $endpoint, ['json' => $data])
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
    private function getJson(Response $response)
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
    private function getErrors(Response $response): array
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
}
