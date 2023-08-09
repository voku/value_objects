<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\exceptions\InvalidValueObjectException;
use voku\value_objects\ValueObjectBool;

final class ValueObjectBoolTest extends TestCase
{
    public function testSimple(): void
    {
        $bool = ValueObjectBool::create(1);
        self::assertTrue($bool->getValue());

        // --

        $bool = ValueObjectBool::create('0');
        self::assertFalse($bool->getValue());

        // --

        $bool = ValueObjectBool::create(false);
        self::assertTrue($bool->isFalse());

        // --

        $bool = ValueObjectBool::create(true);
        self::assertFalse($bool->isFalse());
    }

    public function testSimpleFail(): void
    {
        $this->expectException(InvalidValueObjectException::class);
        $this->expectExceptionMessage('The value "1.3" is not correct for: voku\value_objects\ValueObjectBool');
        ValueObjectBool::create(1.3);
    }

}
