<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\exceptions\InvalidValueObjectException;
use voku\value_objects\ValueObjectCurrency;

final class ValueObjectCurrencyTest extends TestCase
{

    public function testSimple(): void
    {
        $str = ValueObjectCurrency::create('EUR');

        self::assertSame('EUR', $str . '');
        self::assertSame('EUR', $str->__toString());
        self::assertSame($str->value(), $str->valueOrThrowException());
    }

    public function testSimpleFail(): void
    {
        $this->expectException(InvalidValueObjectException::class);
        $this->expectExceptionMessage('The value "FOOBAR" is not correct for: voku\value_objects\ValueObjectCurrency');
        @ValueObjectCurrency::create('FOOBAR');
    }
}
