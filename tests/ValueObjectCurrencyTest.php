<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectCurrency;

/**
 * @internal
 */
final class ValueObjectCurrencyTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectCurrency::create('EUR');

        static::assertSame('EUR', $str . '');
        static::assertSame('EUR', $str->__toString());
        static::assertSame($str->value(), $str->valueOrThrowException());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "FOOBAR" is not correct for: voku\value_objects\ValueObjectCurrency');
        @ValueObjectCurrency::create('FOOBAR');
    }
}
