## Обзор

_S25\PricesApiClient\Client_ - класс клиента API, включает методы вида request<ApiMethod>,
возвращающие инстансы запросов, реализующие интерфейс _S25\PricesApiClient\Contract\ApiMethodRequestContract_.

Интерфейсы запросов наследуют из базового интерфейса методы _perform()_ и _performAsync()_
для синхронного и асинхронного выполнения соответветственно.

В комментарии интерфейса запроса есть информация об обязательных сеттерах,
которые должны быть вызваны перед выполнением запроса.

## Пример инициализации клиента API

Инстанцируем клиент, запрос, указываем необходимые параметры запроса.

```php

use S25\PricesApiClient\Client;

$client = new Client('http://service.url', 'SHOP-API-KEY');

$request = $client->requestBunchBestPrices()
    ->setBrandSlug('suzuki')
    ->setRawNumbers([
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