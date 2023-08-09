<?php


use PHPUnit\Framework\TestCase;
use voku\value_objects\exceptions\InvalidValueObjectException;
use voku\value_objects\ValueObjectUrl;

final class ValueObjectUrlTest extends TestCase
{

    public function testSimple(): void
    {
        $str = ValueObjectUrl::create('https://ipayment.de/foo/test');

        self::assertSame('https://ipayment.de/foo/test', $str . '');
        self::assertSame('https://ipayment.de/foo/test', $str->__toString());
        self::assertSame($str->value(), $str->valueOrThrowException());
    }

    public function testError(): void
    {
        $this->expectException(InvalidValueObjectException::class);
        $this->expectExceptionMessage('The value "htps://foo.de" is not correct for: voku\value_objects\ValueObjectUrl');
        ValueObjectUrl::create('htps://foo.de');
    }
}
