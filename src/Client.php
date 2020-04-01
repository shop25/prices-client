<?php

namespace S25\PricesApiClient;

class Client implements Contracts\Client
{
    private string $serviceUrl;

    private string $apiKey;

    private \Closure $performCallback;

    public function __construct(string $serviceUrl, string $apiKey)
    {
        $this->serviceUrl = rtrim($serviceUrl, '/');

        $this->apiKey = $apiKey;

        $guzzle = new \GuzzleHttp\Client();

        $this->performCallback = function (string $method, string $endpoint, $data) use ($guzzle) {
            $url = $this->generateUrl($endpoint);

            $options = $this->generateGuzzleOptions($data);

            return $guzzle->requestAsync($method, $url, $options);
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

    private function generateUrl($endpoint): string
    {
        return "{$this->serviceUrl}/api/v1.0{$endpoint}";
    }

    private function generateGuzzleOptions($data): array
    {
        return [
            'headers' => ['Authorization' => "Bearer {$this->apiKey}"],
            'json' => $data,
        ];
    }
}