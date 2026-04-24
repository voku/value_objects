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

    public function testOperations(): void
    {
        $num = ValueObjectInt::create(10);

        static::assertSame(2, $num->div(5)->value());
        static::assertSame(1000, $num->pow(3)->value());
        static::assertSame(1, $num->mod(3)->value());
        static::assertSame(3, $num->sqrt()->value());
        static::assertSame(6, $num->powmod(3, 7)->value());

        static::assertTrue($num->isEquals(10));
        static::assertFalse($num->isEquals(5));

        static::assertSame(0, $num->compare(10));
        static::assertSame(1, $num->compare(5));
        static::assertSame(-1, $num->compare(15));

        static::assertTrue($num->isGreaterThan(5));
        static::assertFalse($num->isGreaterThan(15));
        static::assertTrue($num->isLessThan(15));
        static::assertFalse($num->isLessThan(5));
        static::assertTrue($num->isGreaterOrEquals(10));
        static::assertTrue($num->isGreaterOrEquals(5));
        static::assertFalse($num->isGreaterOrEquals(15));
        static::assertTrue($num->isLessOrEquals(10));
        static::assertTrue($num->isLessOrEquals(15));
        static::assertFalse($num->isLessOrEquals(5));

        static::assertSame(10.0, $num->toFloat());
    }

    public function testOperationsWithObject(): void
    {
        $a = ValueObjectInt::create(6);
        $b = ValueObjectInt::create(3);

        static::assertSame(9, $a->add($b)->value());
        static::assertSame(3, $a->sub($b)->value());
        static::assertSame(18, $a->mul($b)->value());
        static::assertSame(2, $a->div($b)->value());
        static::assertSame(216, $a->pow($b)->value());
        static::assertSame(0, $a->mod($b)->value());
        static::assertTrue($a->isEquals(ValueObjectInt::create(6)));
        static::assertSame(1, $a->compare(ValueObjectInt::create(3)));
    }
}
