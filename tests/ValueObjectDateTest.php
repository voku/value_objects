<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectDate;

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
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "20-20-2021" is not correct for: voku\value_objects\ValueObjectDate');
        @ValueObjectDate::create('20-20-2021');
    }
}