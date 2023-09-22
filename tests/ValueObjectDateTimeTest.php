<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectDateTime;

/**
 * @internal
 */
final class ValueObjectDateTimeTest extends TestCase
{
    public function testSimple(): void
    {
        $date = ValueObjectDateTime::create('2020-01-01 01:01:01');
        static::assertSame('2020-01-01 01:01:01', $date->value());

        // --

        $date = ValueObjectDateTime::create('2020-01-01 01:01:01');
        static::assertSame('2020', $date->format('Y'));
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "20-20-2021 01:01:01" is not correct for: voku\ValueObjects\ValueObjectDateTime');
        @ValueObjectDateTime::create('20-20-2021 01:01:01');
    }
}
