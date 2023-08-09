<?php

use PHPUnit\Framework\TestCase;
use voku\value_objects\ValueObjectJson;

/**
 * @internal
 */
final class ValueObjectJsonTest extends TestCase
{
    public function testSimple(): void
    {
        $json = ValueObjectJson::createWithJsonEncode(
            (object) [
                'parentList' => [
                    1,
                    2_000_012_205,
                    2_000_006_091,
                    1000,
                ],
            ]
        );
        static::assertSame('{"parentList":[1,2000012205,2000006091,1000]}', $json->value());

        // --

        $json = ValueObjectJson::create('{"parentList": [1,2000012205,2000006091,1000]}');
        static::assertSame('{"parentList": [1,2000012205,2000006091,1000]}', $json->value());
    }

    public function testSimpleFail(): void
    {
        $this->expectException('voku\value_objects\exceptions\InvalidValueObjectException');
        $this->expectExceptionMessage('The value "{"parentList": [1,2000012205,..." is not correct for: voku\value_objects\ValueObjectJson');
        ValueObjectJson::create('{"parentList": [1,2000012205,...');
    }
}
