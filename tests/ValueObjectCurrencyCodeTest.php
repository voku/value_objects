<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectCurrencyCode;

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
        static::assertSame('EUR', $str->value());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "FOOBAR" is not correct for: voku\ValueObjects\ValueObjectCurrency');
        @ValueObjectCurrencyCode::create('FOOBAR');
    }
}
