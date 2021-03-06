## Обзор

_S25\PricesApiClient\Client_ - класс клиента API, включает методы вида _requestApiMethod_,
возвращающие инстансы запросов, реализующие интерфейс _S25\PricesApiClient\Contract\ApiMethodRequestContract_.

Интерфейсы запросов наследуют из базового интерфейса методы _perform()_ и _performAsync()_
для синхронного и асинхронного выполнения соответветственно.

В комментарии интерфейса запроса есть информация об:
* обязательных сеттерах, вызываемых перед выполнением запроса,
* формате возвращаемых данных.

Сервис не проверяет наличие поставщиков, брэндов, номеров и прочих объектов и связей между ними,
 только форматы некоторых входных данных.
При запросе несуществующих или отключенных объектов,
 ответ сервера будет содержать только данные по корректным объектам.


## Входные и выходные данные

Все методы API-запросов следуют следующим соглашениям:

Методы `setRawNumber(s)?` принимают номер/массив номеров без форматирования, только [0-9A-Z].

Методы вида `set{Param}s` всегда идут в паре с `add{Param}` для попунктного заполнения параметров.

Возвращаемые цены всегда за упаковку.
Лучшие цены рассчитываются исходя из отношения `цена за уп.`/`кол-во в уп.`,
но в результат попадают только цены за упаковку.


## Пример инициализации клиента API

Инстанцируем клиент, запрос, указываем необходимые параметры запроса.

```php

use S25\PricesApiClient\Client;

$client = new Client('http://service.url', 'SHOP-API-KEY');

$request = $client->requestBunchBestPrices()
    ->setBrandSlug('suzuki')
    ->addRawNumber('RAWPARTNUMBER1')
    ->addRawNumber('RAWPARTNUMBER2')
    ->addRawNumber('RAWPARTNUMBER3')
    ->addCurrencyCode('CUR');

```

Далее, получение данных может быть как синхронным:

```php

use S25\PricesApiClient\Contracts\Request;

/** @var Request\BunchBestPricesRequestContract $request */

$bestPricesResponse = $request->perform();

var_dump($bestPricesResponse);

```

, так и асинхронным:

```php

use S25\PricesApiClient\Contracts\Request;

/** @var Request\BunchBestPricesRequestContract $request */

$request->performAsync()
    ->then(static function ($bestPricesResponse) {
        var_dump($bestPricesResponse);
    })
    ->wait();

```