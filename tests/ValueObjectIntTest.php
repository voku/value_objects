<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectInt;

/**
 * @internal
 */
final class ValueObjectIntTest extends TestCase
{
    public function testSimple(): void
    {
        $numeric = ValueObjectInt::create(1);
        static::assertSame(315, $numeric->add(314)->getValue());

        // --

        $numeric = ValueObjectInt::create(1);
        static::assertSame(12, $numeric->add(ValueObjectInt::create(3))->mul(3)->getValue());

        // --

        $numeric = ValueObjectInt::create('13');
        static::assertSame(10, $numeric->sub(3)->getValue());

        // --

        $numeric = ValueObjectInt::create('13');
        static::assertSame(10, $numeric->sub(3)->getValue());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "1.4" is not correct for: voku\value_objects\ValueObjectInt');
        @ValueObjectInt::create(1.4);
    }
}
