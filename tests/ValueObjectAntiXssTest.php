<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectAntiXss;

final class ValueObjectAntiXssTest extends TestCase
{

    public function testSimple(): void
    {
        $str = ValueObjectAntiXss::create('hello <script>alert("xss");</script>');

        self::assertSame('hello ', $str . '');
        self::assertSame('hello ', $str->__toString());
        self::assertSame($str->value(), $str->valueOrThrowException());
    }

}
