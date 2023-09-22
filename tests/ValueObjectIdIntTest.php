<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectIdInt;

/**
 * @internal
 */
final class ValueObjectIdIntTest extends TestCase
{
    public function testSimple(): void
    {
        $numeric = ValueObjectIdInt::create(1);
        static::assertSame(1, $numeric->value());

        // --

        $numeric = ValueObjectIdInt::create('13');
        static::assertSame(13, $numeric->value());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "foo" is not correct for: voku\ValueObjects\ValueObjectIdInt');
        /* @phpstan-ignore-next-line | failing test */
        @ValueObjectIdInt::create('foo');
    }
}
