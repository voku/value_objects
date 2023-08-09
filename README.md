[//]: # (AUTO-GENERATED BY "PHP README Helper": base file -> docs/base.md)
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

<p id="voku-php-readme-class-methods"></p><table><tr><td><a href="#createnulltcreatevalue-value">create</a>
</td><td><a href="#createempty">createEmpty</a>
</td><td><a href="#decryptfromstringstring-password-string-data">decryptFromString</a>
</td><td><a href="#encryptstring-password">encrypt</a>
</td></tr><tr><td><a href="#jsonserialize-string">jsonSerialize</a>
</td><td><a href="#value-nulltvalue">value</a>
</td><td><a href="#valueorfallbacktvaluefallback-fallback-tvaluetvaluefallback">valueOrFallback</a>
</td><td><a href="#valueorthrowexception-tvalue">valueOrThrowException</a>
</td></tr></table>

## create(null|\TCreateValue $value): 
<a href="#voku-php-readme-class-methods">↑</a>


**Parameters:**
- `null|\TCreateValue $value`

**Return:**
- `static`

--------

## createEmpty(): 
<a href="#voku-php-readme-class-methods">↑</a>


**Parameters:**
__nothing__

**Return:**
- `static`

--------

## decryptFromString(string $password, string $data): 
<a href="#voku-php-readme-class-methods">↑</a>


**Parameters:**
- `string $password`
- `string $data`

**Return:**
- `static`

--------

## encrypt(string $password): 
<a href="#voku-php-readme-class-methods">↑</a>


**Parameters:**
- `string $password`

**Return:**
- `string`

--------

## jsonSerialize(): string
<a href="#voku-php-readme-class-methods">↑</a>


**Parameters:**
__nothing__

**Return:**
- `string`

--------

## value(): null|\TValue
<a href="#voku-php-readme-class-methods">↑</a>
Get the value that are used for the database.

**Parameters:**
__nothing__

**Return:**
- `null|\TValue`

--------

## valueOrFallback(\TValueFallback $fallback): TValue|\TValueFallback
<a href="#voku-php-readme-class-methods">↑</a>


**Parameters:**
- `\TValueFallback $fallback`

**Return:**
- `\TValue|\TValueFallback`

--------

## valueOrThrowException(): TValue
<a href="#voku-php-readme-class-methods">↑</a>


**Parameters:**
__nothing__

**Return:**
- `\TValue`

--------


### Thanks

- Thanks to [GitHub](https://github.com) (Microsoft) for hosting the code and a good infrastructure including Issues-Management, etc.
- Thanks to [IntelliJ](https://www.jetbrains.com) as they make the best IDEs for PHP and they gave me an open source license for PhpStorm!
- Thanks to [StyleCI](https://styleci.io/) for the simple but powerful code style check.
- Thanks to [PHPStan](https://github.com/phpstan/phpstan) && [Psalm](https://github.com/vimeo/psalm) for really great Static analysis tools and for discover bugs in the code!