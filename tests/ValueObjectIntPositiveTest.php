<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectIntPositive;

/**
 * @internal
 */
final class ValueObjectIntPositiveTest extends TestCase
{
    public function testSimple(): void
    {
        $numeric = ValueObjectIntPositive::create(1);
        static::assertSame(1, $numeric->value());

        // --

        $numeric = ValueObjectIntPositive::create('13');
        static::assertSame(13, $numeric->value());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "0" is not correct for: voku\ValueObjects\ValueObjectIntPositive');
        @ValueObjectIntPositive::create('0');
    }
}
