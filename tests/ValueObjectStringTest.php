<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectString;

/**
 * @internal
 */
final class ValueObjectStringTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectString::create('öäüß');

        static::assertSame('öäüß', $str . '');
        static::assertSame('öäüß', $str->__toString());
        static::assertSame('!ÖÄÜSS...', $str->toUpperCase()->prepend('!')->append('...')->base64Encode()->base64Decode()->removeXss()->escape()->__toString());
        static::assertSame($str->value(), $str->valueOrThrowException());
    }

    public function testEncrypt(): void
    {
        $str = ValueObjectString::create('öäüß');

        static::assertSame('öäüß', $str::decryptFromString('myPassword1234', $str->encrypt('myPassword1234'))->__toString());
    }
}
