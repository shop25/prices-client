<?php

namespace S25\PricesApiClient;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Response;
use S25\PricesApiClient\Exception\ValidationException;

class Client implements Contracts\Client
{
    private \Closure $performCallback;

    public function __construct(string $serviceUrl, string $apiKey)
    {
        $serviceUrl = rtrim($serviceUrl, '/');

        $guzzle = new GuzzleClient([
            'base_uri' => "{$serviceUrl}/api/v1.1/",
            'headers' => [
                'Authorization' => "Bearer {$apiKey}",
                'X-Requested-With' => 'XMLHttpRequest',
            ],
        ]);

        $this->performCallback = function (string $method, string $endpoint, $data) use ($guzzle) {
            /** @var PromiseInterface $promise */
            $promise = $guzzle->requestAsync($method, $endpoint, ['json' => $data]);

            return $promise->then(
                \Closure::fromCallable([$this, 'getJson']),
                \Closure::fromCallable([$this, 'handleValidationError'])
            );
        };
    }

    public function requestSuppliers(): Contracts\Request\SuppliersRequestContract
    {
        return new Request\SuppliersRequest($this->performCallback);
    }

    public function requestSupplierBrands(): Contracts\Request\SupplierBrandsRequestContract
    {
        return new Request\SupplierBrandsRequest($this->performCallback);
    }

    public function requestProductPrices(): Contracts\Request\ProductPricesRequestContract
    {
        return new Request\ProductPricesRequest($this->performCallback);
    }

    public function requestBunchPrices(): Contracts\Request\BunchPricesRequestContract
    {
        return new Request\BunchPricesRequest($this->performCallback);
    }

    public function requestBunchPricesInCurrency(): Contracts\Request\BunchPricesInCurrencyRequestContract
    {
        return new Request\BunchPricesInCurrencyRequest($this->performCallback);
    }

    public function requestBunchBestPrices(): Contracts\Request\BunchBestPricesRequestContract
    {
        return new Request\BunchBestPricesRequest($this->performCallback);
    }

    public function requestCart(): Contracts\Request\CartRequestContract
    {
        return new Request\CartRequest($this->performCallback);
    }

    private function getJson(Response $response)
    {
        $contents = $response->getBody()->getContents();

        return json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
    }

    private function getErrors(Response $response)
    {
        $errors = $this->getJson($response)['errors'] ?? null;

        return is_array($errors) ? $errors : [];
    }

    private function handleValidationError($exception): void
    {
        if ($exception instanceof RequestException === false) {
            throw $exception;
        }

        /** @var RequestException $exception */
        if ($exception->getResponse()->getStatusCode() !== 422) {
            throw $exception;
        }

        $request = $exception->getRequest();

        throw new ValidationException(
            "В запросе {$request->getMethod()} {$request->getUri()} переданы некорректные данные",
            $this->getErrors($exception->getResponse())
        );
    }
}