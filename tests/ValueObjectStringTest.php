<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectString;

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
        static::assertSame('öäüß', $str->value());
    }

    public function testEncrypt(): void
    {
        $str = ValueObjectString::create('öäüß');

        static::assertSame('öäüß', $str::createByDecrypt('myPassword1234', $str->encrypt('myPassword1234'))->__toString());
    }
}
