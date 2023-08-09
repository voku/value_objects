<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectBool;

/**
 * @internal
 */
final class ValueObjectBoolTest extends TestCase
{
    public function testSimple(): void
    {
        $bool = ValueObjectBool::create(1);
        static::assertTrue($bool->getValue());

        // --

        $bool = ValueObjectBool::createTrue();
        static::assertTrue($bool->getValue());

        // --

        $bool = ValueObjectBool::create('0');
        static::assertFalse($bool->getValue());

        // --

        $bool = ValueObjectBool::createFalse();
        static::assertFalse($bool->getValue());

        // --

        $bool = ValueObjectBool::create(false);
        static::assertTrue($bool->isFalse());

        // --

        $bool = ValueObjectBool::create(true);
        static::assertFalse($bool->isFalse());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "1.3" is not correct for: voku\value_objects\ValueObjectBool');
        ValueObjectBool::create(1.3);
    }
}
