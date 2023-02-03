<?php

require 'vendor/autoload.php';

use S25\PricesApiClient\Client;

$client = new Client(
    'https://service.url/',
    'shop-api.key',
);

$paginateAll = $client->requestPaginateAll()
    ->addCurrencyCode('USD')
    ->addBrandSlug('yamaha')
    ->setPageSize(8000)
    ->setTimeout(5 * 60);

$count = 0;
foreach ($paginateAll->iterate() as $result) {
    $now = date('Y-m-d H:i:s');
    $count++;
    fwrite(STDOUT, "[$now] #{$count}: {$result['brandSlug']} {$result['rawNumber']}\n");
}
