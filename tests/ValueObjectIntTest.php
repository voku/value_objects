<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectInt;

/**
 * @internal
 */
final class ValueObjectIntTest extends TestCase
{
    public function testSimple(): void
    {
        $numeric = ValueObjectInt::create(1);
        static::assertSame(315, $numeric->add(314)->value());

        // --

        $numeric = ValueObjectInt::create(1);
        static::assertSame(12, $numeric->add(ValueObjectInt::create(3))->mul(3)->value());

        // --

        $numeric = ValueObjectInt::create('13');
        static::assertSame(10, $numeric->sub(3)->value());

        // --

        $numeric = ValueObjectInt::create('13');
        static::assertSame(10, $numeric->sub(3)->value());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "1.4" is not correct for: voku\ValueObjects\ValueObjectInt');
        @ValueObjectInt::create(1.4);
    }
}
