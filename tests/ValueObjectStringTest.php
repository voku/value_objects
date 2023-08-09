<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectString;

final class ValueObjectStringTest extends TestCase
{

    public function testSimple(): void
    {
        $str = ValueObjectString::create('öäüß');

        self::assertSame('öäüß', $str . '');
        self::assertSame('öäüß', $str->__toString());
        self::assertSame('ÖÄÜSS', $str->toUpperCase()->__toString());
        self::assertSame($str->value(), $str->valueOrThrowException());
    }

    public function testEncrypt(): void
    {
        $str = ValueObjectString::create('öäüß');

        self::assertSame('öäüß', $str::decryptFromString('myPassword1234', $str->encrypt('myPassword1234'))->__toString());
    }

}
