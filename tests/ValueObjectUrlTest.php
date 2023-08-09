<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectUrl;

/**
 * @internal
 */
final class ValueObjectUrlTest extends TestCase
{
    public function testSimple(): void
    {
        $str = ValueObjectUrl::create('https://ipayment.de/foo/test');

        static::assertSame('https://ipayment.de/foo/test', $str . '');
        static::assertSame('https://ipayment.de/foo/test', $str->__toString());
        static::assertSame($str->value(), $str->valueOrThrowException());
    }

    public function testError(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "htps://foo.de" is not correct for: voku\value_objects\ValueObjectUrl');
        ValueObjectUrl::create('htps://foo.de');
    }
}
