<?php

use PHPUnit\Framework\TestCase;
use voku\ValueObjects\ValueObjectBool;

/**
 * @internal
 */
final class ValueObjectBoolTest extends TestCase
{
    public function testSimple(): void
    {
        $bool = ValueObjectBool::create(1);
        ValueObjectBoolTest::assertTrue($bool->value());

        // --

        $bool = ValueObjectBool::createTrue();
        ValueObjectBoolTest::assertTrue($bool->value());

        // --

        $bool = ValueObjectBool::create('0');
        ValueObjectBoolTest::assertFalse($bool->value());

        // --

        $bool = ValueObjectBool::createFalse();
        ValueObjectBoolTest::assertFalse($bool->value());

        // --

        $bool = ValueObjectBool::create(false);
        ValueObjectBoolTest::assertTrue($bool->isFalse());

        // --

        $bool = ValueObjectBool::create(true);
        ValueObjectBoolTest::assertFalse($bool->isFalse());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\ValueObjects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "1.3" is not correct for: voku\ValueObjects\ValueObjectBool');
        /* @phpstan-ignore-next-line | failing test */
        ValueObjectBool::create(1.3);
    }
}
