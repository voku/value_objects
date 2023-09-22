<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectStringNonXss;

/**
 * @internal
 */
final class ValueObjectStringNonXssTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectStringNonXss::createAndRemoveXss('hello <script>alert("xss");</script>');

        static::assertSame('hello ', $str . '');
        static::assertSame('hello ', $str->__toString());
        static::assertSame('hello ', $str->value());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "hello <script>alert("xss");</script>" is not correct for: voku\ValueObjects\ValueObjectStringNonXss');
        ValueObjectStringNonXss::create('hello <script>alert("xss");</script>');
    }
}
