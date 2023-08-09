[![SWUbanner](https://raw.githubusercontent.com/vshymanskyy/StandWithUkraine/main/banner2-direct.svg)](https://github.com/vshymanskyy/StandWithUkraine/blob/main/docs/README.md)

[![Build Status](https://github.com/voku/value_objects/actions/workflows/ci.yml/badge.svg?branch=main)](https://github.com/voku/value_objects/actions)
[![codecov.io](http://codecov.io/github/voku/value_objects/coverage.svg?branch=main)](http://codecov.io/github/voku/value_objects?branch=main)

# Value Objects

A collection of value objects that can help you to write more readable, 
self-validated and immutable code.

### Install with Composer

```shell
composer require voku/value_objects
```

INFO: if you still use PHP 7.4 (you should update your version) but you still can use the value objects if you also require `
symfony/polyfill-php80`

### Usage

```php
use voku\value_objects\ValueObjectVat;

require_once __DIR__ . '/vendor/autoload.php'; // example path

  $vat = ValueObjectVat::create('16.0');

$vat->getGross(10.0)); // '11.6'
```

### Unit Tests

1) [Composer](https://getcomposer.org) is a prerequisite for running the tests.

```shell
composer install
```

2) The tests can be executed by running this command from the root directory.

```shell
./vendor/bin/phpunit
```

## AbstractHttpProvider methods

%__functions_index__voku\value_objects\AbstractValueObject__%

%__functions_list__voku\value_objects\AbstractValueObject__%

### Thanks

- Thanks to [GitHub](https://github.com) (Microsoft) for hosting the code and a good infrastructure including Issues-Management, etc.
- Thanks to [IntelliJ](https://www.jetbrains.com) as they make the best IDEs for PHP and they gave me an open source license for PhpStorm!
- Thanks to [StyleCI](https://styleci.io/) for the simple but powerful code style check.
- Thanks to [PHPStan](https://github.com/phpstan/phpstan) && [Psalm](https://github.com/vimeo/psalm) for really great Static analysis tools and for discover bugs in the code!
