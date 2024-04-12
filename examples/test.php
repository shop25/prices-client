<?php

require 'vendor/autoload.php';

use S25\PricesApiClient\Client;

$serviceUrl = $_SERVER['PRICES_SERVICE_URL'] ?? 'https://service.url/';
$apiKey = $_SERVER['PRICES_SERVICE_APIKEY'] ?? 'shop-api.key';

$client = new Client(
    $serviceUrl,
    $apiKey,
);

$paginateAll = $client->requestPaginateAll()
    ->addCurrencyCode('USD')
    ->addBrandSlug('suzuki')
    ->setPageSize(1);

$count = 0;
foreach ($paginateAll->iterate() as $result) {
    $now = date('Y-m-d H:i:s');
    $count++;
    fwrite(STDOUT, "[$now] #{$count}: {$result['brandSlug']} {$result['rawNumber']}\n");
}

fwrite(STDOUT, "Total count: {$count}\n");

return 0;

