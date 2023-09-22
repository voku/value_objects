<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectStringClass;

/**
 * @internal
 */
final class ValueObjectStringClassTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectStringClass::create(\voku\ValueObjects\ValueObjectBool::class);

        static::assertSame(\voku\ValueObjects\ValueObjectBool::class, $str . '');
        static::assertSame(\voku\ValueObjects\ValueObjectBool::class, $str->__toString());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "öäü" is not correct for: voku\ValueObjects\ValueObjectStringClass');
        ValueObjectStringClass::create('öäü');
    }
}
