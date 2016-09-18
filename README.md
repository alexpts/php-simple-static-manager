# php-simple-static-manager

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/a0183cff-2e8c-4c6d-8466-2756ad374002/big.png)](https://insight.sensiolabs.com/projects/a0183cff-2e8c-4c6d-8466-2756ad374002)

[![Build Status](https://travis-ci.org/alexpts/php-simple-static-manager.svg?branch=master)](https://travis-ci.org/alexpts/php-simple-static-manager)
[![Test Coverage](https://codeclimate.com/github/alexpts/php-simple-static-manager/badges/coverage.svg)](https://codeclimate.com/github/alexpts/php-simple-static-manager/coverage)
[![Code Climate](https://codeclimate.com/github/alexpts/php-simple-static-manager/badges/gpa.svg)](https://codeclimate.com/github/alexpts/php-simple-static-manager)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/alexpts/php-simple-static-manager/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/alexpts/php-simple-static-manager/?branch=master)


## Installation

```
$ composer require alexpts/php-simple-static-manager
```

## Пример

```php
$staticManager = new StaticManager(new Collection);
$css = $staticManager->getCssSet();

$package = new Package(new StaticVersionStrategy('v1'));

$css->addItem('bootstrap2', $package->getUrl('/bootstrap/3.3.6/css/bootstrap.css'));
$css->addItem('bootstrap', '/bootstrap/3.3.6/css/bootstrap.min.css', 90);

echo $staticManager->drawStyles();
```

Вы можете зарегистрировать js/css ресурс с определенным приоритетом.
Это позволяет загрузить библиотеки, вроде jquery с высшим приоритетом до вашего кода.
При этом объявить зависимость в коде можно где угодно и в любом порядке.

Коллекция ресурсов представлена объектом коллекции:
https://github.com/alexpts/php-tools/blob/master/docs/collection.md
