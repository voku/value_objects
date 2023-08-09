<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectIdUuid;

/**
 * @internal
 */
final class ValueObjectIdUuidTest extends TestCase
{
    public function testSimple(): void
    {
        $numeric = ValueObjectIdUuid::create('550e8400-e29b-41d4-a716-446655440000');
        static::assertSame('550e8400-e29b-41d4-a716-446655440000', $numeric->value());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "foo" is not correct for: voku\value_objects\ValueObjectIdUuid');
        @ValueObjectIdUuid::create('foo');
    }
}
