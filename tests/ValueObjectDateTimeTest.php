<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\exceptions\InvalidValueObjectException;
use voku\value_objects\ValueObjectDateTime;

final class ValueObjectDateTimeTest extends TestCase
{
    public function testSimple(): void
    {
        $date = ValueObjectDateTime::create('2020-01-01 01:01:01');
        self::assertSame('2020-01-01 01:01:01', $date->value());

        // --

        $date = ValueObjectDateTime::create('2020-01-01 01:01:01');
        self::assertSame('2020', $date->format('Y'));
    }

    public function testSimpleFail(): void
    {
        $this->expectException(InvalidValueObjectException::class);
        $this->expectExceptionMessage('The value "20-20-2021 01:01:01" is not correct for: voku\value_objects\ValueObjectDateTime');
        @ValueObjectDateTime::create('20-20-2021 01:01:01');
    }

}
