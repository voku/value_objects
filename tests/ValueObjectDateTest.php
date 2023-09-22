<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectDate;

/**
 * @internal
 */
final class ValueObjectDateTest extends TestCase
{
    public function testSimple(): void
    {
        $date = ValueObjectDate::create('2020-01-01');
        static::assertSame('2020-01-01', $date->value());

        // --

        $date = ValueObjectDate::create('2020-01-01');
        static::assertSame('2020', $date->format('Y'));
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "20-20-2021" is not correct for: voku\ValueObjects\ValueObjectDate');
        @ValueObjectDate::create('20-20-2021');
    }
}
