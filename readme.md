## Пример инициализации клиента API

Инстанцируем клиент, запрос, указываем необходимые параметры запроса.

```php

use S25\PricesApiClient\Client;

$client = new Client('http://service.url', 'SHOP-API-KEY');

$request = $client->requestBunchBestPrice()
    ->setBrandSlug('suzuki')
    ->setNumbers([
        'RAWPARTNUMBER1',
        'RAWPARTNUMBER2',
        'RAWPARTNUMBER3',
    ])
    ->setCurrencyCodes(['CUR']);

```

Далее, получение данных может быть как синхронным:

```php

$bestPricesResponse = $request->perform();

var_dump($bestPricesResponse);

```

, так и асинхронным:

```php

$request->performAsync()
    ->then(static function ($bestPricesResponse) {
        var_dump($bestPricesResponse);
    })
    ->wait();

```