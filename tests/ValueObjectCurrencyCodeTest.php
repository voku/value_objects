<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectCurrencyCode;

/**
 * @internal
 */
final class ValueObjectCurrencyCodeTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectCurrencyCode::create('EUR');

        static::assertTrue($str->isEquals('EUR'));
        static::assertSame('eur', $str->toLowerCase()->value());
        static::assertSame('EUR', $str . '');
        static::assertSame('EUR', $str->__toString());
        static::assertSame($str->value(), $str->valueOrThrowException());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "FOOBAR" is not correct for: voku\value_objects\ValueObjectCurrency');
        @ValueObjectCurrencyCode::create('FOOBAR');
    }
}
