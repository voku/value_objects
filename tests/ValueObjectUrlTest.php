<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectUrl;

/**
 * @internal
 */
final class ValueObjectUrlTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectUrl::create('https://moelleken.org/foo/test');

        static::assertSame('https://moelleken.org/foo/test', $str . '');
        static::assertSame('https://moelleken.org/foo/test', $str->__toString());
        static::assertSame('https://moelleken.org/foo/test', $str->value());
    }

    public function testError(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "htps://foo.de" is not correct for: voku\ValueObjects\ValueObjectUrl');
        ValueObjectUrl::create('htps://foo.de');
    }
}
