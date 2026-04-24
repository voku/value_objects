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

    public function testMethods(): void
    {
        $str = ValueObjectString::create('Hello World 123!');

        static::assertFalse($str->isEmpty());
        static::assertTrue($str->isNotEmpty());
        static::assertTrue($str->isEquals('Hello World 123!'));
        static::assertTrue($str->isEquals(ValueObjectString::create('Hello World 123!')));
        static::assertFalse($str->isEquals('other'));
        static::assertSame('123', $str->extractIntegers()->value());
        static::assertSame('!', $str->extractSpecialCharacters()->value());
        static::assertSame(16, $str->length());
        static::assertSame('Hello PHP 123!', $str->replace('World', 'PHP')->value());
        static::assertTrue($str->contains('World'));
        static::assertFalse($str->contains('Missing'));
        static::assertTrue($str->containsAny(['World', 'Missing']));
        static::assertFalse($str->containsAny(['Missing1', 'Missing2']));
        static::assertTrue($str->containsAll(['Hello', 'World']));
        static::assertFalse($str->containsAll(['Hello', 'Missing']));
        static::assertSame('hello world 123!', $str->toLowerCase()->value());
        static::assertSame('Hello W', $str->truncate(7)->value());

        $empty = ValueObjectString::create('');
        static::assertTrue($empty->isEmpty());
        static::assertFalse($empty->isNotEmpty());
    }

    public function testAppendRandomString(): void
    {
        $str = ValueObjectString::create('Hello');
        $result = $str->appendRandomString(5)->value();

        static::assertSame(10, \strlen($result));
        static::assertStringStartsWith('Hello', $result);
    }

    public function testEqualsAndSame(): void
    {
        $str1 = ValueObjectString::create('hello');
        $str2 = ValueObjectString::create('hello');
        $str3 = ValueObjectString::create('world');

        static::assertTrue($str1->equals($str2));
        static::assertFalse($str1->equals($str3));
        static::assertTrue($str1->same($str2));
        static::assertFalse($str1->same($str3));
        static::assertSame('hello', $str1->valueForDatabase());
    }
}
