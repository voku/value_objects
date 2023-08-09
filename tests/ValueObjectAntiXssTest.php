<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectAntiXss;

/**
 * @internal
 */
final class ValueObjectAntiXssTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectAntiXss::create('hello <script>alert("xss");</script>');

        static::assertSame('hello ', $str . '');
        static::assertSame('hello ', $str->__toString());
        static::assertSame($str->value(), $str->valueOrThrowException());
    }
}
