# au.ru-api

Unofficial PHP library for [au.ru auction API](https://api.au.ru/docs/).

## Installation

composer require insafonov/au.ru-api

## Basic usage

```php
<?php
$auRuApi = new INSafonov\AuRuApi(['token' => 'your_secret_token']);

$auRuApi->lots->find([
	'Id' => 10033369,
	'Name' => 'Водонагреватель Oasis 15 Gn Над Раковиной',
	'ExtId' => '92429',
]);

$auRuApi->lots->update(10033369, ['Price' => 20035]);

$auRuApi->lots->close(10033369);

$auRuApi->lots->repeat(10033369);
```
